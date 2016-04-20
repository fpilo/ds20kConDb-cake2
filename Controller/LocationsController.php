<?php
App::uses('AppController', 'Controller');
/**
 * Locations Controller
 *
 * @property Location $Location
 */
class LocationsController extends AppController {
	
	public $helpers = array('Text');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Location->recursive = 0;
		$this->set('locations', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Location->id = $id;
		if (!$this->Location->exists()) {
			throw new NotFoundException(__('Invalid location'));
		}
		$location = $this->Location->find('first', array(
									'conditions' => array('Location.id' => $id),
									'contain' => array('User.Group')));
									
		$this->set('location', $location);
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			// add users with flag "add_locations"
			$users = $this->Location->User->find('list', array(
													'conditions' => array('add_locations' => 1),
													'fields' => array('username', 'id')));
			$this->request->data['User'] = $users;
			
			$this->Location->create();
			if ($this->Location->save($this->request->data)) {
				CACHE::clear(false,"default"); //Clear the default cache since it stores the prefetched array for all users
				
				$userId = $this->Session->read('Auth.User.id');
				$this->Session->write('User', $this->Location->User->find('first', array(
														'conditions' => array('User.id' => $userId),
														'contain' => array('Group', 'Location', 'Project'))));
				
				$this->Session->setFlash(__('The location has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The location could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Location->id = $id;
		if (!$this->Location->exists()) {
			throw new NotFoundException(__('Invalid location'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Location->save($this->request->data)) {
				CACHE::clear(false,"default"); //Clear the default cache since it stores the prefetched array for all users
				$this->Session->setFlash(__('The location has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The location could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Location->read(null, $id);
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
		$this->Location->id = $id;
		if (!$this->Location->exists()) {
			throw new NotFoundException(__('Invalid location'));
		}
		$count_items = $this->Location->Item->find('count', array(
								'conditions' => array('Item.location_id' => $id)));
		if($count_items <= 0) {
			if ($this->Location->delete()) {
				CACHE::clear(false,"default"); //Clear the default cache since it stores the prefetched array for all users
				$this->Session->setFlash(__('Location deleted'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('Location was not deleted'));
			return $this->redirect(array('action' => 'index'));
		} else {
			$this->Session->setFlash(__('This location cannot be deleted, because there are '.$count_items.' related items'));
			return $this->redirect($this->referer());
		}
	}
}
