<?php
App::uses('AppModel', 'Model');
/**
 * MeasurementParameter Model
 *
 * @property Measurement $Measurement
 * @property Parameter $Parameter
 */
class MeasurementParameter extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'value';

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
		'parameter_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'value' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
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
		'Measurement' => array(
			'className' => 'Measurement',
			'foreignKey' => 'measurement_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Parameter' => array(
			'className' => 'Parameter',
			'foreignKey' => 'parameter_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);


	public function getStripErrorMeasurement($measurementId){
		return $this->find("first",
						array("conditions"=>array(
								"Parameter.name"=>"StripMeasurementId",
								"MeasurementParameter.value"=>$measurementId
							),
						)
					);
	}
}
