<?php
App::uses('AppController', 'Controller');
/**
 * LocationsUsers Controller
 *
 * @property LocationsUser $LocationsUser
 */
class LocationsUsersController extends AppController {


/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->LocationsUser->recursive = 0;
		$this->set('locationsUsers', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->LocationsUser->id = $id;
		if (!$this->LocationsUser->exists()) {
			throw new NotFoundException(__('Invalid locations user'));
		}
		$this->set('locationsUser', $this->LocationsUser->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->LocationsUser->create();
			if ($this->LocationsUser->save($this->request->data)) {
				$this->Session->setFlash(__('The locations user has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The locations user could not be saved. Please, try again.'));
			}
		}
		$locations = $this->LocationsUser->Location->find('list');
		$users = $this->LocationsUser->User->find('list');
		$this->set(compact('locations', 'users'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->LocationsUser->id = $id;
		if (!$this->LocationsUser->exists()) {
			throw new NotFoundException(__('Invalid locations user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->LocationsUser->save($this->request->data)) {
				$this->Session->setFlash(__('The locations user has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The locations user could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->LocationsUser->read(null, $id);
		}
		$locations = $this->LocationsUser->Location->find('list');
		$users = $this->LocationsUser->User->find('list');
		$this->set(compact('locations', 'users'));
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
		$this->LocationsUser->id = $id;
		if (!$this->LocationsUser->exists()) {
			throw new NotFoundException(__('Invalid locations user'));
		}
		if ($this->LocationsUser->delete()) {
			$this->Session->setFlash(__('Locations user deleted'), 'default', array('class' => 'notification'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Locations user was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}
}
