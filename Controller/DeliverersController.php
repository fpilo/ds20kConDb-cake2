<?php
App::uses('AppController', 'Controller');
/**
 * Deliverers Controller
 *
 * @property Deliverer $Deliverer
 */
class DeliverersController extends AppController {


/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Deliverer->recursive = 0;
		$this->set('deliverers', $this->paginate());
	}



/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Deliverer->id = $id;
		if (!$this->Deliverer->exists()) {
			throw new NotFoundException(__('Invalid deliverer'));
		}
		$deliverer = $this->Deliverer->find('first', array(
			'conditions' 	=>	array('Deliverer.id' => $id),
			'contain' => array('Transfer.From', 'Transfer.To')
		));
		
		$this->set(compact('deliverer'));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Deliverer->create();
			if ($this->Deliverer->save($this->request->data)) {
				$this->Session->setFlash(__('The deliverer has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The deliverer could not be saved. Please, try again.'));
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
		$this->Deliverer->id = $id;
		if (!$this->Deliverer->exists()) {
			throw new NotFoundException(__('Invalid deliverer'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Deliverer->save($this->request->data)) {
				$this->Session->setFlash(__('The deliverer has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The deliverer could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Deliverer->read(null, $id);
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
		$this->Deliverer->id = $id;
		if (!$this->Deliverer->exists()) {
			throw new NotFoundException(__('Invalid deliverer'));
		}
		if ($this->Deliverer->delete()) {
			$this->Session->setFlash(__('Deliverer deleted'), 'default', array('class' => 'notification'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Deliverer was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}
}
