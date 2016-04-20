<?php
App::uses('AppModel', 'Model');
/**
 * DevicesMeasurementType Model
 *
 * @property Device $Device
 * @property MeasurementType $MeasurementType
 */
class DevicesMeasurementType extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Device' => array(
			'className' => 'Device',
			'foreignKey' => 'device_id',
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
		)
	);
}
