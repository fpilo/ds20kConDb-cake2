<?php
App::uses('AppModel', 'Model');
/**
 * Measurement Model
 *
 * @property History $History
 * @property Item $Item
 * @property Device $Device
 * @property User $User
 * @property MeasurementType $MeasurementType
 * @property MeasuringPoint $MeasuringPoint
 */
class Measurement extends AppModel {
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'history_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'item_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'measurement_type_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
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
		'History' => array(
			'className' => 'History',
			'foreignKey' => 'history_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Item' => array(
			'className' => 'Item',
			'foreignKey' => 'item_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Device' => array(
			'className' => 'Device',
			'foreignKey' => 'device_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'MeasurementType' => array(
			'className' => 'MeasurementType',
			'foreignKey' => 'measurement_type_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'MeasurementFile' => array(
			'className' => 'MeasurementFile',
			'foreignKey' => 'measurement_file_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * virtualFields
 */
 // public $virtualFields = array(
    // 'name' => 'CONCAT(Device.name, " - ", MeasurementType.name)'
// );

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'MeasuringPoint' => array(
			'className' => 'MeasuringPoint',
			'foreignKey' => 'measurement_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'MeasurementQueue' => array(
			'className' => 'MeasurementQueue',
			'foreignKey' => 'measurement_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'MeasurementParameter' => array(
			'className' => 'MeasurementParameter',
			'foreignKey' => 'measurement_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
					)
	);

	public $hasAndBelongsToMany = array(
		'MeasurementSet' => array(
			'className'             => 'MeasurementSet',
			'foreignKey' 			=> 'measurement_id',
			'associationForeignKey' => 'measurement_set_id',
		),
		'MeasurementTag' => array(
			'className'             => 'MeasurementTag',
			'foreignKey' 			=> 'measurement_id',
			'associationForeignKey' => 'measurement_tag_id',
		)
	);

	public function getStatus()
	{
		return 1;
	}

	public function deleteWithCheck()
	{
		//Check if a MeasurementSet contains this measurement and if yes abort and echo a description
		if($this->MeasurementSet->MeasurementSetsMeasurement->find("count",array("conditions"=>array("MeasurementSetsMeasurement.measurement_id"=>$this->id)))>0){
			$this->message = 'Measurement was not deleted because it is contained in a Measurement Set';
		}else{
			//not contained, continue with deletion
			return $this->delete();
		}
	}

}
