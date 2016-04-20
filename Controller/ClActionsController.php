<?php
App::uses('AppController', 'Controller');
/**
 * ClActions Controller
 *
 * @property ClAction $ClAction
 */
class ClActionsController extends AppController {
		
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->ClAction->recursive = 0;
		$this->set('clActions', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->ClAction->id = $id;
		if (!$this->ClAction->exists()) {
			throw new NotFoundException(__('Invalid Action'));
		}
		$this->set('clAction', $this->ClAction->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->ClAction->create();
			if ($this->ClAction->save($this->request->data)) {
			
				$clactionid=$this->ClAction->getInsertId();
				
				$clstate = $this->ClAction->ClState->find('first', array('order' => array('id' => 'desc')));
				$laststateid=($clstate['ClState']['id']);
				
				if(!empty($this->request->data['ClAction']['source_state_id'])){
					$sourcestateids = $this->request->data['ClAction']['source_state_id'];
					foreach($sourcestateids as $sourcestateid) {
						//loop for each clstate added
						$clstate=$this->ClAction->ClState->findAllById($sourcestateid);
						$pureclstate=$clstate['0']['ClState'];

						$laststateid++;
						$this->ClAction->ClState->create();
						$pureclstate['id']=$laststateid;
						$pureclstate['cl_action_id']=$clactionid;
						$pureclstate['type']="source";

						$this->ClAction->ClState->save($pureclstate);
					}//end foreach
				}
				
				if(!empty($this->request->data['ClAction']['target_state_id'])){
					$targetstateid = $this->request->data['ClAction']['target_state_id'];
					$clstate=$this->ClAction->ClState->findAllById($targetstateid);
					$pureclstate=$clstate['0']['ClState'];

					$laststateid++;
					$this->ClAction->ClState->create();
					$pureclstate['id']=$laststateid;
					$pureclstate['cl_action_id']=$clactionid;
					$pureclstate['type']="target";

					$this->ClAction->ClState->save($pureclstate);
				}
				
				$this->Session->setFlash(__('The action has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The action could not be saved. Please, try again.'));
			}
		}
		
		$clSourceStates = $this->ClAction->ClState->find('list', array(
																	'fields'=>'name',
																	'order'=>'LOWER(ClState.name) ASC',
																	'conditions'=> array('ClState.type'=>'','ClState.type'=>'source'),
																	'group' => 'name'));		
		$clTargetStates = $this->ClAction->ClState->find('list', array(
																	'fields'=>'name',
																	'order'=>'LOWER(ClState.name) ASC',
																	'conditions'=> array('ClState.type'=>'','ClState.type'=>'target'),
																	'group' => 'name'));
		$this->set(compact('clSourceStates','clTargetStates'));
	}

/**
 * edit_notes method
 * this method should be called from the form to change notes on the items/view page
 * it expects an item_id in the request data
 *
 * @param void, but needs certain post data in $this->request
 * @return a redirect to the item that the checklist belongs to if everything worked, throws an exception otherwise
 */
   public function edit_notes() {
      if($this->request->is('post')) {
         $d = $this->request->data['ClAction'];
         $this->ClAction->id = $d['id'];
		   if (!$this->ClAction->exists()) {
			   throw new NotFoundException(__('Action does not exist'));
		   }
         if($this->ClAction->saveField('notes',$d['notes'])) {
            $this->Session->setFlash(__('Changes saved'),'default',array('class'=>'notification'));
         } else {
            $this->Session->setFlash(__('Could not save changes to notes.'));
         }
         return $this->redirect('/items/view/'.$d['item_id']);
      } else {
	      throw new NotFoundException(__('Invalid request. Need Post data'));
      }
   }

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
	
		$this->ClAction->id = $id;
		if (!$this->ClAction->exists()) {
			throw new NotFoundException(__('Invalid Action'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
		
			$dbclaction = $this->ClAction->findAllById($id);
			$dataclaction = $this->request->data['ClAction'];
			if($dataclaction['hierarchy_level']==2){
				unset($dataclaction['source_state_id']);
				unset($dataclaction['target_state_id']);
			}
			
			if ($this->ClAction->save($dataclaction)) {
				
				$dbclstateids = array();
				
				foreach($dbclaction['0']['ClState'] as $dbclstate) {	
					$dbclstate;
					$dbclstateids[]=$dbclstate['id'];	
				}
						
				$dataclstateids = array();
				if(!empty($dataclaction['source_state_id'])) $dataclstateids=$dataclaction['source_state_id'];
				if(!empty($dataclaction['target_state_id'])) $dataclstateids[]=$dataclaction['target_state_id'];
												
				//remove from DB deleted clstates in clstate list
				if(empty($dataclstateids)) $dbclstatetoberemids=$dbclstateids;
				else $dbclstatetoberemids=array_diff($dbclstateids,$dataclstateids);
				
				foreach($dbclstatetoberemids as $dbclstatetoberemid) {
					$this->ClAction->ClState->delete($dbclstatetoberemid);
				}
				
				//create new clstates in DB
				if(!empty($dataclstateids)){
					$dbclstatetobeaddids=array_diff($dataclstateids,$dbclstateids);
					
					$dbclstate = $this->ClAction->ClState->find('first', array('order' => array('id' => 'desc')));
					$lastdbclstateid=($dbclstate['ClState']['id']);	
					foreach($dbclstatetobeaddids as $dbclstatetobeaddid) {
						//loop for each cl state added
						$dbclstate=$this->ClAction->ClState->findAllById($dbclstatetobeaddid);
						if(!empty($dbclstate['0']['ClState'])){
							$pureclstate=$dbclstate['0']['ClState'];
									
							$lastdbclstateid++;
							$this->ClAction->ClState->create();
							$pureclstate['id']=$lastdbclstateid;
							$pureclstate['cl_action_id']=$id;
							
							$this->ClAction->ClState->save($pureclstate);
						}
					}
				}	
				$this->Session->setFlash(__('The Action has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Action could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->ClAction->read(null, $id);
		
			$clSourceStates = $this->ClAction->ClState->find('list', array(
																		'fields'=>'name',
																		'order'=>'LOWER(ClState.name) ASC',
																		'conditions'=> array('ClState.type'=>'','ClState.type'=>'source'),
																		'group' => 'name'));		
			$clTargetStates = $this->ClAction->ClState->find('list', array(
																		'fields'=>'name',
																		'order'=>'LOWER(ClState.name) ASC',
																		'conditions'=> array('ClState.type'=>'','ClState.type'=>'target'),
																		'group' => 'name'));
			$this->set(compact('clSourceStates','clTargetStates'));
		}		
	}
	
/**
 * check method
 *
 * @param integer $id
 * @return void
 */
	public function check($id = null) {

		$this->ClAction->id = $id;

		if (!$this->ClAction->exists()) {
			throw new NotFoundException(__('Invalid Action'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
		
			$name = $this->request->data['ClAction']['name'];
			unset($this->request->data['ClAction']['name']);

			$currstatusCode = $this->request->data['ClAction']['status_code'];
			$currStatus = $this->request->data['ClAction']['status'];
			$newstatusCode = $currstatusCode | ($currStatus << 12);
			$currDateTime = date('Y-m-d H:i:s');
			$currUser = $this->request->data['ClAction']['updated_by'];
			$notes = $this->request->data['ClAction']['notes'];
			
			$this->ClAction->updateAll(				
				array(
					'ClAction.last_update' => "'$currDateTime'",
					'ClAction.updated_by' => "'$currUser'",
					'ClAction.notes' => "'$notes'",
					'ClAction.status_code' => $newstatusCode),
				array('ClAction.id' => $id)
			); 
			
			//Add new event in the item history
			$this->ClAction->createEvent($id,$currStatus,$notes);
		
			//Update parent action status
			$this->ClAction->recursive=-1;
			$hierarchyLvl = $this->ClAction->findById($id, array('hierarchy_level'));
			if($hierarchyLvl['ClAction']['hierarchy_level'] == 2){
			
				//if fail status mark the parent action as failed					
				if($this->request->data['ClAction']['status'] == 0x1) {
				
					$dbclaction = $this->ClAction->find('first', array(
															'conditions' => array('ClAction.checklist_id' => $this->ClAction->field('checklist_id'), 
																				  'ClAction.list_number' => $this->ClAction->field('list_number')),
															'order' => array('ClAction.list_subnumber ASC')
														));
				
					$currstatusCode = $dbclaction['ClAction']['status_code'];
					$newstatusCode = $currstatusCode | (1 << 12);
					$this->ClAction->updateAll(				
												array('ClAction.last_update' => "'$currDateTime'",
													  'ClAction.updated_by' => "'$currUser'",
													  'ClAction.status_code' => $newstatusCode),
												array('ClAction.id' => $dbclaction['ClAction']['id'])
											   ); 
											   
					//Add new event in the item history
					$this->ClAction->createEvent($dbclaction['ClAction']['id'],0x1);

				}
				
				//if pass status check if all subaction have been executed
				if($this->request->data['ClAction']['status'] == 0x3) {
		
					$dbclactions = $this->ClAction->find('all', array(
															'conditions' => array('ClAction.checklist_id' => $this->ClAction->field('checklist_id'), 
																				  'ClAction.list_number' => $this->ClAction->field('list_number')),
															'order' => array('ClAction.list_subnumber ASC')
														));
				
					$allpassed = true;
					foreach($dbclactions as $dbclaction) {
						if ( $dbclaction['ClAction']['list_subnumber'] !== null && (($dbclaction['ClAction']['status_code'] >> 12) & 0x3) !== 3 ) {
							$allpassed =false;
							}
					}
					
					if($allpassed){
						$currstatusCode = $dbclactions[0]['ClAction']['status_code'];
						$newstatusCode = $currstatusCode | (3 << 12);
						$this->ClAction->updateAll(				
							array('ClAction.last_update' => "'$currDateTime'",
								'ClAction.status_code' => $newstatusCode),
							array('ClAction.id' => $dbclactions[0]['ClAction']['id'])
						); 
						
						//Add new event in the item history
						$this->ClAction->createEvent($dbclactions[0]['ClAction']['id'],0x3);

					}
				}

			}
			
			$this->redirect($this->request->data['ClAction']['referer']); // All data saved successful
			
		} else {
			$this->request->data = $this->ClAction->read(null, $id);
			$user = $this->Session->read('Auth.User');
			$this->request->data['ClAction']['updated_by'] = $user['username'];
			$this->request->data['ClAction']['notes'] = null;
			$this->request->data['ClAction']['referer'] = $this->referer()."#checklist";
		}

	}
	
/**
 * skip method
 *
 * @param integer $id
 * @return void
 */
	public function skip($id = null) {
		$this->ClAction->id = $id;
		if (!$this->ClAction->exists()) {
			throw new NotFoundException(__('Invalid Action'));
		}
		
		$name = $this->ClAction->field('name');
		$currstatusCode = $this->ClAction->field('status_code');
		$newstatusCode = $currstatusCode | 0x7000;
		$currDateTime = date('Y-m-d H:i:s');
		$user = $this->Session->read('Auth.User');
		$currUser = $user['username'];
		
		$this->ClAction->updateAll(
							array('ClAction.last_update' => "'$currDateTime'",
								  'ClAction.updated_by' => "'$currUser'",
								  'ClAction.status_code' => $newstatusCode),
							array('ClAction.id' => $id)
							); 
											
		//Add new event in the item history
		$this->ClAction->createEvent($id,0x7);
		
		//Update parent action status
		$this->ClAction->recursive=-1;
		$hierarchyLvl = $this->ClAction->findById($id, array('hierarchy_level'));
		if($hierarchyLvl['ClAction']['hierarchy_level'] == 2){
			//if pass status check if all subaction have been executed
			$dbclactions = $this->ClAction->find('all', array(
															'conditions' => array('ClAction.checklist_id' => $this->ClAction->field('checklist_id'), 
																				  'ClAction.list_number' => $this->ClAction->field('list_number')),
															'order' => array('ClAction.list_subnumber ASC')
														));
			$allpassed = true;
			foreach($dbclactions as $dbclaction) {
				if ( $dbclaction['ClAction']['hierarchy_level'] == 2 && (($dbclaction['ClAction']['status_code'] >> 12) & 0x3) !== 3 ) {
						$allpassed = false;
					}
			}
			if($allpassed){
				$currstatusCode = $dbclactions[0]['ClAction']['status_code'];
				$newstatusCode = $currstatusCode | (3 << 12);
				$this->ClAction->updateAll(				
					array('ClAction.last_update' => "'$currDateTime'",
						  'ClAction.status_code' => $newstatusCode),
					array('ClAction.id' => $dbclactions[0]['ClAction']['id'])
				); 
				
				//Add new event in the item history
				$this->ClAction->createEvent($dbclactions[0]['ClAction']['id'],0x3);
				
			}
		}		
							
		$referer = $this->referer().'#checklist';
		$this->redirect($referer);
		
	}

/**
 * repeat method
 *
 * @param integer $id
 * @return void
 */
	public function repeat($id = null) {
		$this->ClAction->id = $id;
		
		if (!$this->ClAction->exists()) {
			throw new NotFoundException(__('Invalid Action'));
		}
		
		$currstatusCode = $this->ClAction->field('status_code');
		$newstatusCode = $currstatusCode & ~ 0xf000;
		$currDateTime = date('Y-m-d H:i:s');
		$user = $this->Session->read('Auth.User');
		$currUser = $user['username'];

		$this->ClAction->updateAll(
									array('ClAction.last_update' => "'$currDateTime'",
										  'ClAction.updated_by' => "'$currUser'",
										  'ClAction.notes' => null,
									      'ClAction.status_code' => ++$newstatusCode),
									array('ClAction.id' => $id)
									); 
		
		$dbclactions = $this->ClAction->find('all', array(
															'conditions' => array('ClAction.checklist_id' => $this->ClAction->field('checklist_id'), 
																				'ClAction.list_number' => $this->ClAction->field('list_number')),
															'order' => array('ClAction.list_subnumber ASC')
															));
															
		foreach($dbclactions as $dbclaction) {
			if ( $dbclaction['ClAction']['list_subnumber'] !== null ) {
				$this->ClAction->updateAll(
											array('ClAction.last_update' => null,
												  'ClAction.updated_by' => null,
												  'ClAction.notes' => null,
												  'ClAction.status_code' => $newstatusCode),
											array('ClAction.id' => $dbclaction['ClAction']['id'])
											);
			}
		}; 
		
		$referer = $this->referer().'#checklist';
		$this->redirect($referer);

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
		$this->ClAction->id = $id;
		if (!$this->ClAction->exists()) {
			throw new NotFoundException(__('Invalid Action'));
		}
		if ($this->ClAction->delete()) {
			$this->Session->setFlash(__('Action deleted'), 'default', array('class' => 'notification'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Action was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}
}
