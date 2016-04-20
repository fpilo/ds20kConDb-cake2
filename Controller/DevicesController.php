<?php
App::uses('AppController', 'Controller');
/**
 * Devices Controller
 *
 * @property Device $Device
 */
class DevicesController extends AppController {


/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Device->recursive = 1;
		$this->Device->unbindModel(array("hasMany"=>"Measurement"));
		$this->set('devices', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Device->id = $id;
		if (!$this->Device->exists()) {
			throw new NotFoundException(__('Invalid device'));
		}
		$this->Device->Measurement->unbindModel(array("hasMany"=>array("MeasuringPoint","MeasurementQueue")));
		$measurements = $this->Device->Measurement->find("all",array(
			"fields"=>array("Measurement.id","Item.id","Item.code","History.id","User.username","User.id","MeasurementType.id","MeasurementType.name"),
			"conditions"=>array("Device.id"=>$id),
			"recursive"=>1,
		));
		$this->set("measurements",$measurements);
		$this->set('device', $this->Device->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Device->create();
			if ($this->Device->save($this->request->data)) {
				$this->Session->setFlash(__('The device has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The device could not be saved. Please, try again.'));
			}
		}
		$locations = $this->Device->Location->getUsersLocations();
		$measurementTypes = $this->Device->MeasurementType->find('list');
		$this->set(compact('locations','measurementTypes'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Device->id = $id;
		if (!$this->Device->exists()) {
			throw new NotFoundException(__('Invalid device'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Device->save($this->request->data)) {
				$this->Session->setFlash(__('The device has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The device could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Device->read(null, $id);
		}
		$locations = $this->Device->Location->find('list');
		$measurementTypes = $this->Device->MeasurementType->find('list');
		$this->set(compact('locations','measurementTypes'));
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
		$this->Device->id = $id;
		if (!$this->Device->exists()) {
			throw new NotFoundException(__('Invalid device'));
		}
		if ($this->Device->delete()) {
			$this->Session->setFlash(__('Device deleted'), 'default', array('class' => 'notification'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Device was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}
}
