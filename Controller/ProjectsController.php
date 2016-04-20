<?php
App::uses('AppController', 'Controller');
/**
 * Projects Controller
 *
 * @property Project $Project
 */
class ProjectsController extends AppController {
	
	public $helpers = array('Text');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Project->recursive = 0;
		$this->set('projects', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Project->id = $id;
		if (!$this->Project->exists()) {
			throw new NotFoundException(__('Invalid project'));
		}
		$project = $this->Project->find('first', array(
									'conditions' => array('Project.id' => $id),
									'contain' => array('Manufacturer', 'User.Group', 'DbFile.User')));
									
		//debug($project);							
		$this->set('project', $project);
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			// add users with flag "add_projects"
			$users = $this->Project->User->find('list', array(
													'conditions' => array('add_projects' => 1),
													'fields' => array('username', 'id')));
			$this->request->data['User'] = $users;
			
			$this->Project->create();
			if ($this->Project->save($this->request->data)) {
				CACHE::clear(false,"default"); //Clear the default cache since it stores the prefetched array for all users
				
				$userId = $this->Session->read('Auth.User.id');
				$this->Session->write('User', $this->Project->User->find('first', array(
														'conditions' => array('User.id' => $userId),
														'contain' => array('Group', 'Location', 'Project'))));
				
				$this->Session->setFlash(__('The project has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project could not be saved. Please, try again.'));
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
		$this->Project->id = $id;
		if (!$this->Project->exists()) {
			throw new NotFoundException(__('Invalid project'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Project->save($this->request->data)) {
				CACHE::clear(false,"default"); //Clear the default cache since it stores the prefetched array for all users
				$this->Session->setFlash(__('The project has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Project->read(null, $id);
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
		$this->Project->id = $id;
		if (!$this->Project->exists()) {
			throw new NotFoundException(__('Invalid project'));
		}
		$count_items = $this->Project->Item->find('count', array(
								'conditions' => array('Item.project_id' => $id)));
		if($count_items <= 0) {
			if ($this->Project->delete()) {
				CACHE::clear(false,"default"); //Clear the default cache since it stores the prefetched array for all users
				$this->Session->setFlash(__('Project deleted'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('Project was not deleted'));
			return $this->redirect(array('action' => 'index'));
		} else {
			$this->Session->setFlash(__('This project cannot be deleted, because there are '.$count_items.' related items'));
			return $this->redirect($this->referer());
		}
	}
}
