<?php
App::uses('AppController', 'Controller');
/**
 * Manufacturers Controller
 *
 * @property Manufacturer $Manufacturer
 */
class ManufacturersController extends AppController {
	
	public $helpers = array('Text');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Manufacturer->recursive = 0;
		$this->paginate = array('contain' => array('Project'));
		$this->set('manufacturers', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Manufacturer->id = $id;
		if (!$this->Manufacturer->exists()) {
			throw new NotFoundException(__('Invalid manufacturer'));
		}
		
		$manufacturer = $this->Manufacturer->find('first',
												array(	'conditions' => array('Manufacturer.id' => $id),
														'contain' => array('Project', 'ItemSubtypeVersion.ItemSubtype.ItemType', 'ItemSubtypeVersion.Project')));
		
		$this->set('manufacturer', $manufacturer);
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Manufacturer->create();
			if ($this->Manufacturer->save($this->request->data)) {
				CACHE::clear(false,"default"); //Clear the default cache since it stores the prefetched array for all users
				$this->Session->setFlash(__('The manufacturer has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The manufacturer could not be saved. Please, try again.'));
			}
		}
		
		$projects 		= $this->Manufacturer->Project->find('list');	
		$this->set(compact('projects'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Manufacturer->id = $id;
		if (!$this->Manufacturer->exists()) {
			throw new NotFoundException(__('Invalid manufacturer'));
		}
		$manufacturer = $this->Manufacturer->read(null, $id);
		
		if ($this->request->is('post') || $this->request->is('put')) {
			if($manufacturer['Manufacturer']['name'] == $this->request->data['Manufacturer']['name'])
				unset($this->Manufacturer->validate['name']);
			if ($this->Manufacturer->save($this->request->data)) {
				CACHE::clear(false,"default"); //Clear the default cache since it stores the prefetched array for all users
				$this->Session->setFlash(__('The manufacturer has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The manufacturer could not be saved. Please, try again.'));
			}			 
		} else {
			$this->request->data = $manufacturer; 
		}
		
		$projects 		= $this->Manufacturer->Project->find('list');	
		$this->set(compact('projects'));
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
		$this->Manufacturer->id = $id;
		if (!$this->Manufacturer->exists()) {
			throw new NotFoundException(__('Invalid manufacturer'));
		}
		if ($this->Manufacturer->delete()) {
			CACHE::clear(false,"default"); //Clear the default cache since it stores the prefetched array for all users
			$this->Session->setFlash(__('Manufacturer deleted'), 'default', array('class' => 'notification'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Manufacturer was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}
}
