<?php
App::uses('AppController', 'Controller');
/**
 * MeasurementTags Controller
 *
 * @property MeasurementTag $MeasurementTag
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class MeasurementTagsController extends AppController {

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
		$this->MeasurementTag->recursive = 0;
		$this->set('measurementTags', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->MeasurementTag->exists($id)) {
			throw new NotFoundException(__('Invalid measurement tag'));
		}
		$options = array('conditions' => array('MeasurementTag.' . $this->MeasurementTag->primaryKey => $id));
		$this->set('measurementTag', $this->MeasurementTag->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->MeasurementTag->create();
			if ($this->MeasurementTag->save($this->request->data)) {
				$this->Session->setFlash(__('The measurement tag has been saved.'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The measurement tag could not be saved. Please, try again.'));
			}
		}
		$measurements = $this->MeasurementTag->Measurement->find('list');
		$this->set(compact('measurements'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->MeasurementTag->exists($id)) {
			throw new NotFoundException(__('Invalid measurement tag'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->MeasurementTag->save($this->request->data)) {
				$this->Session->setFlash(__('The measurement tag has been saved.'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The measurement tag could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('MeasurementTag.' . $this->MeasurementTag->primaryKey => $id));
			$this->request->data = $this->MeasurementTag->find('first', $options);
		}
		$measurements = $this->MeasurementTag->Measurement->find('list');
		$this->set(compact('measurements'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->MeasurementTag->id = $id;
		if (!$this->MeasurementTag->exists()) {
			throw new NotFoundException(__('Invalid measurement tag'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->MeasurementTag->delete()) {
			$this->Session->setFlash(__('The measurement tag has been deleted.'), 'default', array('class' => 'notification'));
		} else {
			$this->Session->setFlash(__('The measurement tag could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
