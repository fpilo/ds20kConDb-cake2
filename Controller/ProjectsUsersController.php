<?php
App::uses('AppController', 'Controller');
/**
 * ProjectsUsers Controller
 *
 * @property ProjectsUser $ProjectsUser
 */
class ProjectsUsersController extends AppController {


/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->ProjectsUser->recursive = 0;
		$this->set('projectsUsers', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->ProjectsUser->id = $id;
		if (!$this->ProjectsUser->exists()) {
			throw new NotFoundException(__('Invalid projects user'));
		}
		$this->set('projectsUser', $this->ProjectsUser->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->ProjectsUser->create();
			if ($this->ProjectsUser->save($this->request->data)) {
				$this->Session->setFlash(__('The projects user has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The projects user could not be saved. Please, try again.'));
			}
		}
		$projects = $this->ProjectsUser->Project->find('list');
		$users = $this->ProjectsUser->User->find('list');
		$this->set(compact('projects', 'users'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->ProjectsUser->id = $id;
		if (!$this->ProjectsUser->exists()) {
			throw new NotFoundException(__('Invalid projects user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->ProjectsUser->save($this->request->data)) {
				$this->Session->setFlash(__('The projects user has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The projects user could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->ProjectsUser->read(null, $id);
		}
		$projects = $this->ProjectsUser->Project->find('list');
		$users = $this->ProjectsUser->User->find('list');
		$this->set(compact('projects', 'users'));
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
		$this->ProjectsUser->id = $id;
		if (!$this->ProjectsUser->exists()) {
			throw new NotFoundException(__('Invalid projects user'));
		}
		if ($this->ProjectsUser->delete()) {
			$this->Session->setFlash(__('Projects user deleted'), 'default', array('class' => 'notification'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Projects user was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}
}
