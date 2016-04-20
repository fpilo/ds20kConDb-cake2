<?php
App::uses('AppController', 'Controller');
/**
 * ItemQualities Controller
 *
 * @property ItemQuality $ItemQuality
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class ItemQualitiesController extends AppController {

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
		$this->ItemQuality->recursive = 0;
		$this->set('itemQualities', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->ItemQuality->exists($id)) {
			throw new NotFoundException(__('Invalid item quality'));
		}
		$options = array('conditions' => array('ItemQuality.' . $this->ItemQuality->primaryKey => $id));
		$this->set('itemQuality', $this->ItemQuality->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->ItemQuality->create();
			if ($this->ItemQuality->save($this->request->data)) {
				$this->Session->setFlash(__('The item quality has been saved.'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The item quality could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->ItemQuality->exists($id)) {
			throw new NotFoundException(__('Invalid item quality'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->ItemQuality->save($this->request->data)) {
				$this->Session->setFlash(__('The item quality has been saved.'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The item quality could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ItemQuality.' . $this->ItemQuality->primaryKey => $id));
			$this->request->data = $this->ItemQuality->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->ItemQuality->id = $id;
		if (!$this->ItemQuality->exists()) {
			throw new NotFoundException(__('Invalid item quality'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->ItemQuality->delete()) {
			$this->Session->setFlash(__('The item quality has been deleted.'));
		} else {
			$this->Session->setFlash(__('The item quality could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
