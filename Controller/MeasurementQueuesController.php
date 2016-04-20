<?php
App::uses('AppController', 'Controller');
/**
 * MeasurementQueues Controller
 *
 * @property MeasurementQueue $MeasurementQueue
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class MeasurementQueuesController extends AppController {

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
		$this->MeasurementQueue->recursive = 0;
		$this->set('measurementQueues', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->MeasurementQueue->exists($id)) {
			throw new NotFoundException(__('Invalid measurement queue'));
		}
		$options = array('conditions' => array('MeasurementQueue.' . $this->MeasurementQueue->primaryKey => $id));
		$this->set('measurementQueue', $this->MeasurementQueue->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->MeasurementQueue->create();
			if ($this->MeasurementQueue->save($this->request->data)) {
				$this->Session->setFlash(__('The measurement queue has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The measurement queue could not be saved. Please, try again.'));
			}
		}
		$measurements = $this->MeasurementQueue->Measurement->find('list');
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
		if (!$this->MeasurementQueue->exists($id)) {
			throw new NotFoundException(__('Invalid measurement queue'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->MeasurementQueue->save($this->request->data)) {
				$this->Session->setFlash(__('The measurement queue has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The measurement queue could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('MeasurementQueue.' . $this->MeasurementQueue->primaryKey => $id));
			$this->request->data = $this->MeasurementQueue->find('first', $options);
		}
		$measurements = $this->MeasurementQueue->Measurement->find('list');
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
		$this->MeasurementQueue->id = $id;
		if (!$this->MeasurementQueue->exists()) {
			throw new NotFoundException(__('Invalid measurement queue'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->MeasurementQueue->delete()) {
			$this->Session->setFlash(__('The measurement queue has been deleted.'));
		} else {
			$this->Session->setFlash(__('The measurement queue could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
