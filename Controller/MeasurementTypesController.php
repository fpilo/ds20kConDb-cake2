<?php
App::uses('AppController', 'Controller');
/**
 * MeasurementTypes Controller
 *
 * @property MeasurementType $MeasurementType
 */
class MeasurementTypesController extends AppController {


/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->MeasurementType->recursive = 0;
		$this->set('measurementTypes', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->MeasurementType->id = $id;
		if (!$this->MeasurementType->exists()) {
			throw new NotFoundException(__('Invalid measurement type'));
		}
		$this->MeasurementType->Measurement->unbindModel(array("hasMany"=>array("MeasuringPoint","MeasurementQueue")));
		$measurements = $this->MeasurementType->Measurement->find("all",array(
			"fields"=>array("Measurement.id","Item.id","Item.code","History.id","Device.id","Device.name","User.username","User.id","MeasurementType.id","MeasurementType.name"),
			"conditions"=>array("MeasurementType.id"=>$id),
			"recursive"=>1,
		));
		$this->set("measurements",$measurements);
		$this->set("measurementType",$this->MeasurementType->read(null,$id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->MeasurementType->create();
			if ($this->MeasurementType->save($this->request->data)) {
				$this->Session->setFlash(__('The measurement type has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The measurement type could not be saved. Please, try again.'));
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
		$this->MeasurementType->id = $id;
		if (!$this->MeasurementType->exists()) {
			throw new NotFoundException(__('Invalid measurement type'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->MeasurementType->save($this->request->data)) {
				$this->Session->setFlash(__('The measurement type has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The measurement type could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->MeasurementType->read(null, $id);
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
		$this->MeasurementType->id = $id;
		if (!$this->MeasurementType->exists()) {
			throw new NotFoundException(__('Invalid measurement type'));
		}
		if ($this->MeasurementType->delete()) {
			$this->Session->setFlash(__('Measurement type deleted'), 'default', array('class' => 'notification'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Measurement type was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}
}
