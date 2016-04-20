<?php
App::uses('AppModel', 'Model');
/**
 * MeasurementQueue Model
 *
 * @property Measurement $Measurement
 */
class MeasurementQueue extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'measurement_queue';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'measurement_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'file_path' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Measurement' => array(
			'className' => 'Measurement',
			'foreignKey' => 'measurement_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	public function getDataForQueueId($queueId=null)
	{
		if($queueId !== null){
			return $this->find("first",array("conditions"=>array("MeasurementQueue.id"=>$queueId)));
		}else{
			throw new Exception("Item was not found in Queue");
		}
	}

	public function getMeasurementQueueStatus(){
		$result = $this->find("first",array("fields"=>"status","conditions"=>array("MeasurementQueue.measurement_id"=>$this->Measurement->id)));
		if(isset($result["MeasurementQueue"]["status"])){
			return $result["MeasurementQueue"]["status"];
		}else{
			return 3;
		}
	}

}
