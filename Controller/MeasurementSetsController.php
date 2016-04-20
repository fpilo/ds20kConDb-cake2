<?php
App::uses('AppController', 'Controller');
/**
 * MeasurementSets Controller
 *
 * @property MeasurementSet $MeasurementSet
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class MeasurementSetsController extends AppController {

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
		$this->MeasurementSet->recursive = 0;
		$this->set('measurementSets', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->MeasurementSet->exists($id)) {
			throw new NotFoundException(__('Invalid measurement set'));
		}
		$options = array('conditions' => array('MeasurementSet.' . $this->MeasurementSet->primaryKey => $id));
		$measurementSet = $this->MeasurementSet->find('first', $options);
		$this->set('measurementSet', $measurementSet);
		$this->set('measurementType',$this->MeasurementSet->Measurement->MeasurementType->find("list"));
		$this->set('item', $this->MeasurementSet->Measurement->Item->find("list"));
		$this->set('device', $this->MeasurementSet->Measurement->Device->find("list"));
		$this->set('user', $this->MeasurementSet->Measurement->User->find("list"));
		foreach($measurementSet["Measurement"] as $measurement){
			$measurementIds[] = $measurement["id"];
		}
		$this->loadModel('MeasurementTagsMeasurement');
		$measurement_tag_ids = $this->MeasurementTagsMeasurement->getTagsForMeasurementId($measurementIds);
		$this->set('measurementTags',$measurement_tag_ids);
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->MeasurementSet->create();
#			debug($this->request->data);
			if ($this->MeasurementSet->save($this->request->data)) {
				$this->Session->setFlash(__('The measurement set has been saved.'), 'default', array('class' => 'notification'));
#				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The measurement set could not be saved. Please, try again.'));
			}
		}
#		$measurements = $this->MeasurementSet->Measurement->find("list",array("fields"=>array("Measurement.id","Device.name","Item.code"),"recursive"=>1));
		$measurements = $this->MeasurementSet->Measurement->find("all",array("fields"=>array("Measurement.id","Device.name","MeasurementType.name","Item.code"),"recursive"=>1));
		$measurements = Set::combine($measurements,'{n}.Measurement.id',array('{0} - {1}','{n}.Device.name','{n}.MeasurementType.name'),'{n}.Item.code');
		$this->set("measurements",$measurements);
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->MeasurementSet->exists($id)) {
			throw new NotFoundException(__('Invalid measurement set'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->MeasurementSet->save($this->request->data)) {
				$this->Session->setFlash(__('The measurement set has been saved.'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'view',$id));
			} else {
				$this->Session->setFlash(__('The measurement set could not be saved. Please, try again.'));
			}
		} else {
			ini_set("memory_limit", "256M");
			$options = array('conditions' => array('MeasurementSet.' . $this->MeasurementSet->primaryKey => $id));
			$this->request->data = $this->MeasurementSet->find('first', $options);
#			$this->_runtime("reset");
#			$measurements = $this->MeasurementSet->Measurement->find("list",array("fields"=>array("Measurement.id","Device.name","Item.code"),"recursive"=>1));
#			debug($measurements);
#			$this->_runtime("find list");
			$this->MeasurementSet->Measurement->unbindModel(array("hasMany"=>array("MeasuringPoint","MeasurementQueue")));
			$measurements = $this->MeasurementSet->Measurement->find("all",array("fields"=>array("Measurement.id","Device.name","MeasurementType.name","Item.code")));
			$measurements = Set::combine($measurements,'{n}.Measurement.id',array('{0} - {1}','{n}.Device.name','{n}.MeasurementType.name'),'{n}.Item.code');
#			$this->_runtime("find all");

#			$log = $this->MeasurementSet->Measurement->getDataSource()->getLog(false, false);
#			debug($log);
#			debug($measurements);
			$this->set("measurements",$measurements);  //Requires an array in the format id=>Name
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
		$this->MeasurementSet->id = $id;
		if (!$this->MeasurementSet->exists()) {
			throw new NotFoundException(__('Invalid measurement set'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->MeasurementSet->delete()) {
			$this->Session->setFlash(__('The measurement set has been deleted.'));
		} else {
			$this->Session->setFlash(__('The measurement set could not be deleted. Please, try again.'));
		}
		return $this->redirect(array("action"=>"index"));
	}
}
