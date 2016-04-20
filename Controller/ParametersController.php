<?php
App::uses('AppController', 'Controller');
/**
 * Parameters Controller
 *
 * @property Parameter $Parameter
 */
class ParametersController extends AppController {


/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Parameter->recursive = 0;
		$this->set('parameters', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Parameter->id = $id;
		if (!$this->Parameter->exists()) {
			throw new NotFoundException(__('Invalid parameter'));
		}
		$this->set('parameter', $this->Parameter->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Parameter->create();
			if ($this->Parameter->save($this->request->data)) {
				$this->Session->setFlash(__('The parameter has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The parameter could not be saved. Please, try again.'));
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
		$this->Parameter->id = $id;
		if (!$this->Parameter->exists()) {
			throw new NotFoundException(__('Invalid parameter'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Parameter->save($this->request->data)) {
				$this->Session->setFlash(__('The parameter has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The parameter could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Parameter->read(null, $id);
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
		$this->Parameter->id = $id;
		if (!$this->Parameter->exists()) {
			throw new NotFoundException(__('Invalid parameter'));
		}
		if ($this->Parameter->delete()) {
			$this->Session->setFlash(__('Parameter deleted'), 'default', array('class' => 'notification'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Parameter was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}
}
