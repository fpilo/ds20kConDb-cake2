<?php
App::uses('AppController', 'Controller');
/**
 * Readings Controller
 *
 * @property Reading $Reading
 */
class ReadingsController extends AppController {


/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Reading->recursive = 0;
		$this->set('readings', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Reading->id = $id;
		if (!$this->Reading->exists()) {
			throw new NotFoundException(__('Invalid reading'));
		}
		$this->set('reading', $this->Reading->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Reading->create();
			if ($this->Reading->save($this->request->data)) {
				$this->Session->setFlash(__('The reading has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The reading could not be saved. Please, try again.'));
			}
		}
		$measuringPoints = $this->Reading->MeasuringPoint->find('list');
		$parameters = $this->Reading->Parameter->find('list');
		$this->set(compact('measuringPoints', 'parameters'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Reading->id = $id;
		if (!$this->Reading->exists()) {
			throw new NotFoundException(__('Invalid reading'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Reading->save($this->request->data)) {
				$this->Session->setFlash(__('The reading has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The reading could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Reading->read(null, $id);
		}
		$measuringPoints = $this->Reading->MeasuringPoint->find('list');
		$parameters = $this->Reading->Parameter->find('list');
		$this->set(compact('measuringPoints', 'parameters'));
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
		$this->Reading->id = $id;
		if (!$this->Reading->exists()) {
			throw new NotFoundException(__('Invalid reading'));
		}
		if ($this->Reading->delete()) {
			$this->Session->setFlash(__('Reading deleted'), 'default', array('class' => 'notification'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Reading was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}
}
