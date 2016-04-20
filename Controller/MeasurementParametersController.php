<?php
App::uses('AppController', 'Controller');
/**
 * MeasurementParameters Controller
 *
 * @property MeasurementParameter $MeasurementParameter
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class MeasurementParametersController extends AppController {

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
		$this->MeasurementParameter->recursive = 0;
		$this->set('measurementParameters', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->MeasurementParameter->exists($id)) {
			throw new NotFoundException(__('Invalid measurement parameter'));
		}
		$options = array('conditions' => array('MeasurementParameter.' . $this->MeasurementParameter->primaryKey => $id));
		$this->set('measurementParameter', $this->MeasurementParameter->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->MeasurementParameter->create();
			if ($this->MeasurementParameter->save($this->request->data)) {
				$this->Session->setFlash(__('The measurement parameter has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The measurement parameter could not be saved. Please, try again.'));
			}
		}
		$measurements = $this->MeasurementParameter->Measurement->find('list');
		$parameters = $this->MeasurementParameter->Parameter->find('list');
		$this->set(compact('measurements', 'parameters'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->MeasurementParameter->exists($id)) {
			throw new NotFoundException(__('Invalid measurement parameter'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->MeasurementParameter->save($this->request->data)) {
				$this->Session->setFlash(__('The measurement parameter has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The measurement parameter could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('MeasurementParameter.' . $this->MeasurementParameter->primaryKey => $id));
			$this->request->data = $this->MeasurementParameter->find('first', $options);
		}
		$measurements = $this->MeasurementParameter->Measurement->find('list');
		$parameters = $this->MeasurementParameter->Parameter->find('list');
		$this->set(compact('measurements', 'parameters'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->MeasurementParameter->id = $id;
		if (!$this->MeasurementParameter->exists()) {
			throw new NotFoundException(__('Invalid measurement parameter'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->MeasurementParameter->delete()) {
			$this->Session->setFlash(__('The measurement parameter has been deleted.'));
		} else {
			$this->Session->setFlash(__('The measurement parameter could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
