<?php
App::uses('AppModel', 'Model');
/**
 * MeasurementSetsMeasurement Model
 *
 * @property Measurement $Measurement
 * @property MeasurementSet $MeasurementSet
 */
class MeasurementSetsMeasurement extends AppModel {


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
		'MeasurementSet' => array(
			'className' => 'MeasurementSet',
			'foreignKey' => 'measurement_set_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
