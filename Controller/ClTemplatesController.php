<?php
App::uses('AppController', 'Controller');
/**
 * ClTemplates Controller
 *
 * @property ClTemplate $ClTemplate
 */
class ClTemplatesController extends AppController {

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->ClTemplate->unbindModel(
										array('hasMany' => array('ClAction')
												)
										);
		$this->paginate = array(
					'recursive' => 0,
					'order' => array('id' => 'desc')
				);
		$this->set('clTemplates', $this->paginate('ClTemplate'));
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->ClTemplate->id = $id;
		if (!$this->ClTemplate->exists()) {
			throw new NotFoundException(__('Invalid Template'));
		}
		$this->set('clTemplate', $this->ClTemplate->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {

		if ($this->request->is('post')) {

			$this->ClTemplate->create();
			if ($this->ClTemplate->save($this->request->data)) {

				//Check list saved ok, now add action items

				$cltemplateid=$this->ClTemplate->getInsertId();
				$list_number=0;	$list_subnumber=0;
				if(!empty($this->request->data['ClAction'])){

					foreach($this->request->data['ClAction'] as $claction) {

						//loop for each claction added
						$claction['cl_template_id']=$cltemplateid;

						if($claction['hierarchy_level']==1){$list_number++;$list_subnumber=0;}
						else if($claction['hierarchy_level']==2) $list_subnumber++;
						if($list_number!=0) $claction['list_number']=$list_number;
						if($list_subnumber!=0) $claction['list_subnumber']=$list_subnumber;

						$this->ClTemplate->ClAction->create();
						$this->ClTemplate->ClAction->save($claction);

						$clactionid=$this->ClTemplate->ClAction->getInsertId();
						$clstate = $this->ClTemplate->ClAction->ClState->find('first', array('order' => array('id' => 'desc')));
						$laststateid=($clstate['ClState']['id']);

						if( isset($claction['source_state_id']) && $claction['hierarchy_level']==1){
							$sourcestateids = $claction['source_state_id'];
							if(!empty($sourcestateids)){
								foreach($sourcestateids as $sourcestateid) {
									//loop for each cl source state added
									$clstate=$this->ClTemplate->ClAction->ClState->findAllById($sourcestateid);
									$pureclstate=$clstate['0']['ClState'];

									$laststateid++;
									$this->ClTemplate->ClAction->ClState->create();
									$pureclstate['id']=$laststateid;
									$pureclstate['cl_action_id']=$clactionid;
									$pureclstate['cl_template_id']=$cltemplateid;
									$pureclstate['type']="source";

									$this->ClTemplate->ClAction->ClState->save($pureclstate);
								}//end foreach
							}
						}

						//add cl target state
						$targetstateid = $claction['target_state_id'];
						if(!empty($targetstateid) && $claction['hierarchy_level']==1){
							$clstate=$this->ClTemplate->ClAction->ClState->findAllById($targetstateid);
							$pureclstate=$clstate['0']['ClState'];

							$laststateid++;
							$this->ClTemplate->ClAction->ClState->create();
							$pureclstate['id']=$laststateid;
							$pureclstate['cl_action_id']=$clactionid;
							$pureclstate['cl_template_id']=$cltemplateid;
							$pureclstate['type']="target";

							$this->ClTemplate->ClAction->ClState->save($pureclstate);
						}

					}//end foreach
				}

				$this->Session->setFlash(__('The Template has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Template could not be saved. Please, try again.'));
			}
		}

		$itemSubtypes = $this->ClTemplate->ItemSubtype->unbindModel(
										array('hasMany' => array('Item','ItemSubtypeVersion'),
											  'belongsTo' => array('ItemType')
												)
										);
		$itemSubtypes = $this->ClTemplate->ItemSubtype->find('list');

		$clSourceStates = $this->ClTemplate->ClAction->ClState->find('list', array(
																	'fields'=>'name',
																	'order'=>'LOWER(ClState.name) ASC',
																	'conditions'=> array('ClState.cl_action_id'=>'','ClState.type'=>'source'),
																	'group' => 'name'));
		$clTargetStates = $this->ClTemplate->ClAction->ClState->find('list', array(
																	'fields'=>'name',
																	'order'=>'LOWER(ClState.name) ASC',
																	'conditions'=> array('ClState.cl_action_id'=>'','ClState.type'=>'target'),
																	'group' => 'name'));
		$this->set(compact('itemSubtypes','clSourceStates','clTargetStates'));

	}

/**
 * clone method
 *
 * @return void
 */
	public function myClone() {

		if ($this->request->is('post')) {

			$this->ClTemplate->create();

			$cltemplateid = $this->request->data['ClTemplate']['cl_template_id'];
			$clTemplate = $this->ClTemplate->findById($cltemplateid);
			$nclones = $this->ClTemplate->find('count',array('conditions' => array('SUBSTRING_INDEX(ClTemplate.name," - CLONE#",1)'=>$clTemplate['ClTemplate']['name'])));
			$nclones--;

			unset($clTemplate['ClTemplate']['id']);
			unset($clTemplate['ClTemplate']['checklist_id']);
			unset($clTemplate['ClTemplate']['cl_action_id']);
			$clTemplate['ClTemplate']['name'].=" - CLONE#".$nclones;

			$clActions = array();
			foreach($clTemplate['ClAction'] as $claction) {
				$dbclaction=$this->ClTemplate->ClAction->findById($claction['id']);

				$claction['source_state_id'] = array();
				$claction['target_state_id'] = null;
				foreach($dbclaction['ClState'] as $clstate) {
					if($clstate['type']=='source') array_push($claction['source_state_id'],$clstate['id']);
					if($clstate['type']=='target') $claction['target_state_id'] = $clstate['id'];
				}
				array_push($clActions,$claction);
			}
			unset($clTemplate['ClAction']);
			$clTemplate["ClTemplate"]["default"] = false;

			if ($this->ClTemplate->save($clTemplate)) {

				//Checklist cloned, now add action items
				$cltemplateid=$this->ClTemplate->getInsertId();
				$list_number=0;	$list_subnumber=0;
				if(!empty($clActions)){

					foreach($clActions as $claction) {

						//loop for each claction added
						unset($claction['id']);
						$claction['cl_template_id']=$cltemplateid;

						if($claction['hierarchy_level']==1){$list_number++;$list_subnumber=0;}
						else if($claction['hierarchy_level']==2) $list_subnumber++;
						if($list_number!=0) $claction['list_number']=$list_number;
						if($list_subnumber!=0) $claction['list_subnumber']=$list_subnumber;

						$this->ClTemplate->ClAction->create();
						$this->ClTemplate->ClAction->save($claction);

						$clactionid=$this->ClTemplate->ClAction->getInsertId();
						$clstate = $this->ClTemplate->ClAction->ClState->find('first', array('order' => array('id' => 'desc')));
						$laststateid=($clstate['ClState']['id']);

						if( isset($claction['source_state_id']) && $claction['hierarchy_level']==1){
							$sourcestateids = $claction['source_state_id'];
							if(!empty($sourcestateids)){
								foreach($sourcestateids as $sourcestateid) {
									//loop for each cl source state added
									$clstate=$this->ClTemplate->ClAction->ClState->findAllById($sourcestateid);
									$pureclstate=$clstate['0']['ClState'];

									$laststateid++;
									$this->ClTemplate->ClAction->ClState->create();
									$pureclstate['id']=$laststateid;
									$pureclstate['cl_action_id']=$clactionid;
									$pureclstate['cl_template_id']=$cltemplateid;
									$pureclstate['type']="source";

									$this->ClTemplate->ClAction->ClState->save($pureclstate);
								}//end foreach
							}
						}

						//add cl target state
						$targetstateid = $claction['target_state_id'];
						if(!empty($targetstateid) && $claction['hierarchy_level']==1){
							$clstate=$this->ClTemplate->ClAction->ClState->findAllById($targetstateid);
							$pureclstate=$clstate['0']['ClState'];

							$laststateid++;
							$this->ClTemplate->ClAction->ClState->create();
							$pureclstate['id']=$laststateid;
							$pureclstate['cl_action_id']=$clactionid;
							$pureclstate['cl_template_id']=$cltemplateid;
							$pureclstate['type']="target";

							$this->ClTemplate->ClAction->ClState->save($pureclstate);
						}

					}//end foreach
				}

				$this->Session->setFlash(__('A new template has been created'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'edit',$cltemplateid));

			} else {
				$this->Session->setFlash(__('The Template could not be saved. Please, try again.'));
			}

		}

		$this->ClTemplate->unbindModel(
										array('hasMany' => array('ClAction')
												)
										);
		$clTemplates = $this->ClTemplate->find('list', array(
												'fields'=>'name',
												'conditions'=>array('LOCATE("CLONE",ClTemplate.name)'=>'0')
												));
		$this->set(compact('clTemplates'));

	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {

		$this->ClTemplate->id = $id;
		if (!$this->ClTemplate->exists()) {
			throw new NotFoundException(__('Invalid Template'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {

			if ($this->ClTemplate->save($this->request->data)) {

				//remove previous clactions
				$dbcltemplate=$this->ClTemplate->findAllById($id);
				if(count($dbcltemplate['0']['ClAction'])!==0){
					foreach($dbcltemplate['0']['ClAction'] as $dbclaction) {
						$this->ClTemplate->ClAction->delete($dbclaction['id']);
					}
				}

				//now add new clactions
				$cltemplateid=$id;
				$list_number=0;	$list_subnumber=0;
				foreach($this->request->data['ClAction'] as $claction) {
					//loop for each claction added

					unset($claction['id']);
					$claction['cl_template_id']=$cltemplateid;

					if($claction['hierarchy_level']==1){$list_number++;$list_subnumber=0;}
					else if($claction['hierarchy_level']==2) $list_subnumber++;
					if($list_number!=0) $claction['list_number']=$list_number;
					if($list_subnumber!=0) $claction['list_subnumber']=$list_subnumber;

					$this->ClTemplate->ClAction->create();

					$this->ClTemplate->ClAction->save($claction);

					$clactionid=$this->ClTemplate->ClAction->getInsertId();
					$clstate = $this->ClTemplate->ClAction->ClState->find('first', array('order' => array('id' => 'desc')));
					$laststateid=($clstate['ClState']['id']);

					if( isset($claction['source_state_id']) && $claction['hierarchy_level']==1){
						$sourcestateids = $claction['source_state_id'];
						if(!empty($sourcestateids)){
							foreach($sourcestateids as $sourcestateid) {
								//loop for each cl source state added
								$clstate=$this->ClTemplate->ClAction->ClState->findAllById($sourcestateid);
								$pureclstate=$clstate['0']['ClState'];

								$laststateid++;
								$this->ClTemplate->ClAction->ClState->create();
								$pureclstate['id']=$laststateid;
								$pureclstate['cl_action_id']=$clactionid;
								$pureclstate['cl_template_id']=$cltemplateid;
								$pureclstate['type']="source";

								$this->ClTemplate->ClAction->ClState->save($pureclstate);
							}//end foreach
						}
					}

					//add cl target state
					if( isset($claction['target_state_id']) && $claction['hierarchy_level']==1){

						$targetstateid = $claction['target_state_id'];

						$clstate=$this->ClTemplate->ClAction->ClState->findAllById($targetstateid);
						$pureclstate=$clstate['0']['ClState'];

						$laststateid++;
						$this->ClTemplate->ClAction->ClState->create();
						$pureclstate['id']=$laststateid;
						$pureclstate['cl_action_id']=$clactionid;
						$pureclstate['cl_template_id']=$cltemplateid;
						$pureclstate['type']="target";

						$this->ClTemplate->ClAction->ClState->save($pureclstate);
					}

				}//end foreach
				$this->Session->setFlash(__('The Template has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));

			} else {
				$this->Session->setFlash(__('The Template could not be saved. Please, try again.'));
			}
		} else {

			$this->ClTemplate->recursive = 2;
			$this->ClTemplate->ItemSubtype->unbindModel(
								array('belongsTo' => array('ItemType'),
									  'hasMany' => array('Item','ItemSubtypeVersion'),
									  'hasAndBelongsToMany' => array('DbFile')
										)
								);
			$this->request->data = $this->ClTemplate->read(null, $id);

			//substitute selected claction states with template states (no cl_action_id specified)
			foreach($this->request->data['ClAction'] as $iaction => $claction) {
				if(!empty($claction['ClState'])){
					foreach($claction['ClState'] as $clstate){
						if($clstate['type']=="source"){
							$defclstate = $this->ClTemplate->ClAction->ClState->find('first',
															array('conditions' => array('ClState.type' => 'source',
																						'ClState.name' => $clstate['name'],
																						'ClState.cl_action_id'=>'')
																 )
														);

							$this->request->data['ClAction'][$iaction]['source_state_id'][] = $defclstate['ClState']['id'];
						}
						if($clstate['type']=="target"){
							$defclstate = $this->ClTemplate->ClAction->ClState->find('first',
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

		$itemSubtypes = $this->ClTemplate->ItemSubtype->find('list');

		$clSourceStates = $this->ClTemplate->ClAction->ClState->find('list', array(
																	'fields'=>'name',
																	'order'=>'LOWER(ClState.name) ASC',
																	'conditions'=> array('ClState.cl_action_id'=>'','ClState.type'=>'source'),
																	'group' => 'name'));
		$clTargetStates = $this->ClTemplate->ClAction->ClState->find('list', array(
																	'fields'=>'name',
																	'order'=>'LOWER(ClState.name) ASC',
																	'conditions'=> array('ClState.cl_action_id'=>'','ClState.type'=>'target'),
																	'group' => 'name'));
		$this->set(compact('itemSubtypes','clSourceStates','clTargetStates'));

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
		$this->ClTemplate->id = $id;
		if (!$this->ClTemplate->exists()) {
			throw new NotFoundException(__('Invalid Template'));
		}
		if ($this->ClTemplate->delete()) {
			$this->Session->setFlash(__('Template deleted'), 'default', array('class' => 'notification'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Template was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}
}
