<?php
App::uses('AppController', 'Controller');
/**
 * ClStates Controller
 *
 * @property ClState $ClState
 */
class ClStatesController extends AppController {


/**
 * index method
 *
 * @return void
 */
	public function index() {
	
		$this->paginate = array(
									'recursive' => 0,
																					   'fields' => array(
													   '*',
													   '(SELECT COUNT(*) FROM cl_states WHERE name = ClState.name AND cl_action_id IS NULL) AS `count`'
													   ),
									'conditions' => array('ClState.cl_action_id' => null),
									'group' => array('ClState.name HAVING COUNT(*) >= 1'),
									'order' => array('id' => 'desc')
								);
		$this->set('clStates', $this->paginate('ClState'));

	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->ClState->id = $id;
		if (!$this->ClState->exists()) {
			throw new NotFoundException(__('Invalid State'));
		}
		$this->set('clState', $this->ClState->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
	
		$clStateTypes = array(0 => 'source', 1 => 'target', 2 => 'source&target');
		$defaultTypes = 2;
			
		if ($this->request->is('post')) {
		
			$this->request->data['ClState']['type'] = $clStateTypes[$this->request->data['ClState']['type']];
			$type = $this->request->data['ClState']['type'];
			if(strcmp($type,"source&target") == 0) {

				$this->request->data['ClState']['type'] = 'source';
				$this->ClState->create();
						
				if ($this->ClState->save($this->request->data)) {
					$this->Session->setFlash(__('The State has been saved'), 'default', array('class' => 'notification'));
				} else {
					$this->Session->setFlash(__('The State could not be saved. Please, try again.'));
				}
				
				$this->request->data['ClState']['type'] = 'target';
				$this->ClState->create();
						
				if ($this->ClState->save($this->request->data)) {
					$this->Session->setFlash(__('The State has been saved'), 'default', array('class' => 'notification'));
				} else {
					$this->Session->setFlash(__('The State could not be saved. Please, try again.'));
				}
				
				$this->redirect($this->request->data['ClState']['referer']);

			}
			else {
			
				$this->ClState->create();
						
				if ($this->ClState->save($this->request->data)) {
					$this->Session->setFlash(__('The State has been saved'), 'default', array('class' => 'notification'));
					$this->redirect($this->request->data['ClState']['referer']);
				} else {
					$this->Session->setFlash(__('The State could not be saved. Please, try again.'));
				}
				
			}
		}
		else $this->request->data['ClState']['referer'] = $this->referer();

		$this->set(compact('clStateTypes','defaultTypes'));

	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
	
		$this->ClState->id = $id;
		if (!$this->ClState->exists()) {
			throw new NotFoundException(__('Invalid state'));
		}
				
		if ($this->request->is('post') || $this->request->is('put')) {

			$currClStateName =  $this->ClState->read('name', $id);										
			if($this->request->data['ClState']['saveAll']){
			
				$clStateIds = $this->ClState->find("all", array(
													'fields' => array('id'),
													'conditions' => array('ClState.name' => $currClStateName['ClState']['name'])
													)
												);
			}
			else{
				
				$clStateIds = $this->ClState->find("all", array(
																'fields' => array('id'),
																'conditions' => array('ClState.cl_action_id' => null, 'ClState.name' => $currClStateName['ClState']['name'])
																)
												);
			}
			
			foreach($clStateIds as $clStateId){
				$error = true;

				$this->request->data['ClState']['id'] = $clStateId['ClState']['id'];					
				if ($this->ClState->save($this->request->data)) {
					$error = false;
					$this->Session->setFlash(__('The State has been saved'), 'default', array('class' => 'notification'));
				} else {
					$this->Session->setFlash(__('The State could not be saved. Please, try again.'));
					break;
				}
			}
			if(!$error) return $this->redirect(array('action' => 'index'));
			
		} else {
			$this->request->data = $this->ClState->read(null, $id);
		}
		
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
		$this->ClState->id = $id;
		if (!$this->ClState->exists()) {
			throw new NotFoundException(__('Invalid State'));
		}
		
		$currClStateName =  $this->ClState->read('name', $id);										
		$clStateIds = $this->ClState->find("all", array(
												'fields' => array('id'),
												'conditions' => array('ClState.cl_action_id' => null, 'ClState.name' => $currClStateName['ClState']['name'])
												)
										);
								
		foreach($clStateIds as $clStateId){

			$this->ClState->id =  $clStateId['ClState']['id'];					
			if ($this->ClState->delete()) {
				$this->Session->setFlash(__('State deleted'), 'default', array('class' => 'notification'));
			}
			else {
				$this->Session->setFlash(__('State was not deleted'));
				break;
			}
		}
		
		return $this->redirect(array('action' => 'index'));
	}
}
