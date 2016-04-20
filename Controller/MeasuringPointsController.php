<?php
App::uses('AppController', 'Controller');
/**
 * MeasuringPoints Controller
 *
 * @property MeasuringPoint $MeasuringPoint
 */
class MeasuringPointsController extends AppController {


/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->MeasuringPoint->recursive = 0;
		$this->set('measuringPoints', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->MeasuringPoint->id = $id;
		if (!$this->MeasuringPoint->exists()) {
			throw new NotFoundException(__('Invalid measuring point'));
		}
		$this->set('measuringPoint', $this->MeasuringPoint->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->MeasuringPoint->create();
			if ($this->MeasuringPoint->save($this->request->data)) {
				$this->Session->setFlash(__('The measuring point has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The measuring point could not be saved. Please, try again.'));
			}
		}
		$measurements = $this->MeasuringPoint->Measurement->find('list');
		$this->set(compact('measurements'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->MeasuringPoint->id = $id;
		if (!$this->MeasuringPoint->exists()) {
			throw new NotFoundException(__('Invalid measuring point'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->MeasuringPoint->save($this->request->data)) {
				$this->Session->setFlash(__('The measuring point has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The measuring point could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->MeasuringPoint->read(null, $id);
		}
		$measurements = $this->MeasuringPoint->Measurement->find('list');
		$this->set(compact('measurements'));
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
		$this->MeasuringPoint->id = $id;
		if (!$this->MeasuringPoint->exists()) {
			throw new NotFoundException(__('Invalid measuring point'));
		}
		if ($this->MeasuringPoint->delete()) {
			$this->Session->setFlash(__('Measuring point deleted'), 'default', array('class' => 'notification'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Measuring point was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}
}
