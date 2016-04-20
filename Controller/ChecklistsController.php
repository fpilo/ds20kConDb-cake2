<?php
App::uses('AppController', 'Controller');
/**
 * Checklist Controller
 *
 * @property Checklist $Checklist
 */
class ChecklistsController extends AppController {
		
/**
 * index method
 *
 * @return void
 */
 	public function index() {
	
		$this->Checklist->unbindModel(
										array('hasMany' => array('ClAction')
												)
										);
		$this->paginate = array(
							'recursive' => 0,
							'order' => array('id' => 'desc')
						);
		$this->set('checklists', $this->paginate('Checklist'));
	}
	
/**
 * update method
 *
 * @param string $id
 * @param string $refStr: aco name + "_" + itemId
 *
 * @return void
 */
 	public function update($itemId, $id = null) {
		
		$this->Checklist->unbindModel(
										array('hasMany' => array('ClAction')),
										array('belongsTo' => array('Item'))
									);	
									
		$this->Checklist->Item->recursive=-1;
		$item = $this->Checklist->Item->findById($itemId);
		$checklist = $this->Checklist->read(null, $id);
		$this->set(compact('item','checklist'));
		
	}

/**
 * view method
 *
 * @param string $id
 * @param string $refStr: aco name + "_" + itemId
 *
 * @return void
 */
	public function view($id = null) {
	
		$this->Checklist->id = $id;
		if (!$this->Checklist->exists()) {
			throw new NotFoundException(__('Invalid Checklist'));
		}
			
		$checklist = $this->Checklist->read(null, $id);
		$this->set(compact('checklist'));
		
	}

/**
 * add method
 *
 * @return void
 */
	public function add($itemId = null) {
	
		if ($this->request->is('post') || $this->request->is('put')) {

			$this->Checklist->Item->recursive=-1;
			$item = $this->Checklist->Item->findById($itemId);
			$checklistName = $item['Item']['code'].'_cl'; $checklistDescription = 'Created from template';
			$checklistId = $this->Checklist->createFromTemplate($this->request->data['Checklist']['cl_template_id'],
																$checklistName,$checklistDescription);
			if(!empty($checklistId)){
				$this->Checklist->id = $checklistId;
				$this->Checklist->saveField('item_id', $itemId);
					
				$firstclaction = $this->Checklist->ClAction->find('first', array(
																					'conditions' => array(
																											'ClAction.checklist_id ' => $checklistId,
																											'ClAction.list_number >' => 0
																										  ),
																					'order' => array('ClAction.list_number' => 'asc')
																				));
				foreach($firstclaction['ClState'] as $clstate){
					if($clstate['type']=='source'){
						$this->Checklist->Item->State->unbindModel(
														array(	'hasMany' => array('Item')
														));
						$state = $this->Checklist->Item->State->find('first', array('conditions'=>array('State.name'=>$clstate['name'])));
						if(empty($state)){
							$this->Checklist->Item->State->create;
							$state = array(
											'name' => $clstate['name'],
											'description' => $clstate['description']
											);
							$this->Checklist->Item->State->save($state);
							$stateId = $this->Checklist->Item->State->getInsertId();
						}
						else $stateId = $state['State']['id'];

						break;
					}
				}
				
				if(isset($stateId)){
					$this->Checklist->init();
					$this->Checklist->Item->id = $itemId;
					$this->Checklist->Item->saveField('state_id', $stateId);	
				}
				else{
					$this->Checklist->Item->id = $itemId;
					$this->Checklist->Item->saveField('state_id', '1');				
				}
				$this->Session->setFlash(__('Checklist added'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'update', $itemId, $checklistId));

			}
			else {
				$this->Session->setFlash(__('The checklist could not be created. Please, try again.'));
			}	
		}
		
		$clTemplates = $this->Checklist->ClTemplate->unbindModel(
										array('hasMany' => array('ClAction'),
											  'belongsTo' => array('ItemSubType')
												)
										);

		$this->Checklist->Item->recursive=-1;
		$item = $this->Checklist->Item->findById($itemId);
		$clTemplates = $this->Checklist->ClTemplate->find('list',array(
															'conditions'=> array('ClTemplate.item_subtype_id'=>$item['Item']['item_subtype_id'])
															)
														);
		$this->set(compact('item','clTemplates'));

		return;
	}

/**
 * edit method
 *
 * @param string $id
 * @param string $refStr: aco name + "_" + itemId
 * @return void
 */
	public function edit($id, $refStr = null) {

		$this->Checklist->id = $id;		
		if (!$this->Checklist->exists()) {
			throw new NotFoundException(__('Invalid Template'));
		}		

		if(isset($refStr)){
			$action = explode("_", $refStr,2)[0];
			$itemId = explode("_", $refStr,2)[1];
		}
		
		if ($this->request->is('post') || $this->request->is('put')) {

			if ($this->Checklist->save($this->request->data)) {

				//remove previous clactions
				$dbchecklist=$this->Checklist->findAllById($id);
				if(count($dbchecklist['0']['ClAction'])!==0){
					foreach($dbchecklist['0']['ClAction'] as $dbclaction) {					
						$this->Checklist->ClAction->delete($dbclaction['id']);
					}
				}

				//now add new clactions
				$checklistid=$id;
				$list_number=0;	$list_subnumber=0;
				// debug($this->request->data['ClAction']);
				// exit;
				if(!empty($this->request->data['ClAction'])){
					foreach($this->request->data['ClAction'] as $claction) {
						//loop for each claction added

						unset($claction['id']);
						$claction['checklist_id']=$checklistid;

						if($claction['hierarchy_level']==1){$list_number++;$list_subnumber=0;}
						else if($claction['hierarchy_level']==2) $list_subnumber++;					
						if($list_number!=0) $claction['list_number']=$list_number;
						if($list_subnumber!=0) $claction['list_subnumber']=$list_subnumber;

						$this->Checklist->ClAction->create();
						$this->Checklist->ClAction->save($claction);
						$clactionid=$this->Checklist->ClAction->getInsertId();

						if(isset($claction['status_code'])){
							$statusCode = (int)$claction['status_code'];
							$this->Checklist->ClAction->updateAll(
																	array('ClAction.status_code' => $statusCode),
																	array('ClAction.id' => $clactionid)
																);
							unset($claction['status_code']);
						}
						
						$clstate = $this->Checklist->ClAction->ClState->find('first', array('order' => array('id' => 'desc')));
						$laststateid=($clstate['ClState']['id']);

						if( isset($claction['source_state_id']) && $claction['hierarchy_level']==1){
							$sourcestateids = $claction['source_state_id'];
							if(!empty($sourcestateids)){
								foreach($sourcestateids as $sourcestateid) {
									//loop for each cl source state added
									$clstate=$this->Checklist->ClAction->ClState->findAllById($sourcestateid);
									$pureclstate=$clstate['0']['ClState'];
									
									$laststateid++;
									$this->Checklist->ClAction->ClState->create();
									$pureclstate['id']=$laststateid;
									$pureclstate['cl_action_id']=$clactionid;
									$pureclstate['checklist_id']=$checklistid;
									$pureclstate['type']="source";

									$this->Checklist->ClAction->ClState->save($pureclstate);
								}//end foreach
							}				
						}
						
						//add cl target state
						if( isset($claction['target_state_id']) && $claction['hierarchy_level']==1){

							$targetstateid = $claction['target_state_id'];

							$clstate=$this->Checklist->ClAction->ClState->findAllById($targetstateid);
							$pureclstate=$clstate['0']['ClState'];

							$laststateid++;
							$this->Checklist->ClAction->ClState->create();
							$pureclstate['id']=$laststateid;
							$pureclstate['cl_action_id']=$clactionid;
							$pureclstate['checklist_id']=$checklistid;
							$pureclstate['type']="target";

							$this->Checklist->ClAction->ClState->save($pureclstate);
						}
						
					}//end foreach	
				}
				$this->Session->setFlash(__('The checklist has been saved'), 'default', array('class' => 'notification'));
				$this->redirect(array('action' => $action,$itemId,$id));				
			} else {
				$this->Session->setFlash(__('The checklist could not be saved. Please, try again.'));
			}
		} else {
			$this->Checklist->recursive = 2;
			$this->request->data = $this->Checklist->read(null, $id);
			
			//substitute selected claction states with template states (no cl_action_id specified)
			foreach($this->request->data['ClAction'] as $iaction => $claction) {
				if(!empty($claction['ClState'])){
					foreach($claction['ClState'] as $clstate){
						if($clstate['type']=="source"){
							$defclstate = $this->Checklist->ClAction->ClState->find('first', 
															array('conditions' => array('ClState.type' => 'source',
																						'ClState.name' => $clstate['name'],
																						'ClState.cl_action_id'=>'')
																 )
														);
							
							$this->request->data['ClAction'][$iaction]['source_state_id'][] = $defclstate['ClState']['id'];
						}
						if($clstate['type']=="target"){
							$defclstate = $this->Checklist->ClAction->ClState->find('first', 
									array('conditions' => array('ClState.type' => 'target',
																'ClState.name' => $clstate['name'],
																'ClState.cl_action_id'=>'')
										 )
								);
							$this->request->data['ClAction'][$iaction]['target_state_id'] = $defclstate['ClState']['id'];
						}
							
					}		
				}	
			}
			
		}

		$this->Checklist->Item->recursive=-1;
		$item = $this->Checklist->Item->findById($itemId);
		$clSourceStates = $this->Checklist->ClAction->ClState->find('list', array(
																	'fields'=>'name',
																	'order'=>'LOWER(ClState.name) ASC',
																	'conditions'=> array('ClState.cl_action_id'=>'','ClState.type'=>'source'),
																	'group' => 'name'));		
		$clTargetStates = $this->Checklist->ClAction->ClState->find('list', array(
																	'fields'=>'name',
																	'order'=>'LOWER(ClState.name) ASC',
																	'conditions'=> array('ClState.cl_action_id'=>'','ClState.type'=>'target'),
																	'group' => 'name'));
		$this->set(compact('refStr','item','clSourceStates','clTargetStates'));
		
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Checklist->id = $id;
		if (!$this->Checklist->exists()) {
			throw new NotFoundException(__('Invalid Checklist'));
		}
		
		$checklist = $this->Checklist->findById($id);
		if ($this->Checklist->delete()) {
			
			$this->Checklist->Item->id = $checklist['Checklist']['item_id'];
			$this->Checklist->Item->saveField('state_id', '1');

			$this->Session->setFlash(__('Checklist deleted'), 'default', array('class' => 'notification'));
			$this->redirect( $this->referer() );
		}
		$this->Session->setFlash(__('Checklist was not deleted'));
		$this->redirect( $this->referer() );
		
	}
}

