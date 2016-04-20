<?php
App::uses('AppController', 'Controller');
/**
 * ItemTypes Controller
 *
 * @property ItemType $ItemType
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class ItemTypesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		
		$this->ItemType->recursive = 1;
		$this->ItemType->unbindModel(array('hasMany'=>array('Item')), false);
		$this->set('itemTypes', $this->Paginator->paginate());
	
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->ItemType->exists($id)) {
			throw new NotFoundException(__('Invalid item type'));
		}
		$options = array('conditions' => array('ItemType.' . $this->ItemType->primaryKey => $id));
		$this->set('itemType', $this->ItemType->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		
		if ($this->request->is('post')) {
			$this->ItemType->create();
			if ($this->ItemType->save($this->request->data)) {
				CACHE::clear(false,"default"); //Clear the default cache since it stores the prefetched array for all users
				$this->Session->setFlash(__('The item type has been saved.'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The item type could not be saved. Please, try again.'));
			}
		}
		$projects = $this->ItemType->Project->find('list');
		$this->set(compact('projects'));
		
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->ItemType->exists($id)) {
			throw new NotFoundException(__('Invalid item type'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->ItemType->save($this->request->data)) {
				CACHE::clear(false,"default"); //Clear the default cache since it stores the prefetched array for all users
				$this->Session->setFlash(__('The item type has been saved.'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The item type could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ItemType.' . $this->ItemType->primaryKey => $id));
			$this->request->data = $this->ItemType->find('first', $options);
		}
		$projects = $this->ItemType->Project->find('list');
		$this->set(compact('projects'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->ItemType->id = $id;
		if (!$this->ItemType->exists()) {
			throw new NotFoundException(__('Invalid item type'));
		}
		$this->request->allowMethod('post', 'delete');
		if($this->ItemType->hasSubtypes($id)) {
			$this->Session->setFlash(__('FAILURE: Item type is related to some ItemSubtypes. Deletion aborted.'));
			return $this->redirect(array('action' => 'index'));
		}
		else {
			if ($this->ItemType->delete()) {
				CACHE::clear(false,"default"); //Clear the default cache since it stores the prefetched array for all users
				$this->Session->setFlash(__('Item type deleted'), 'default', array('class' => 'notification'));
			}else{
				$this->Session->setFlash(__('FAILURE: Item type was not deleted. Please try again.'));
			}
			return $this->redirect(array('action' => 'index'));
		}
	}
}
