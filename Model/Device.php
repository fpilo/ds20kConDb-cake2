<?php
App::uses('AppModel', 'Model');
/**
 * Device Model
 *
 * @property Location $Location
 * @property Measurement $Measurement
 * @property MeasurementType $MeasurementType
 */
class Device extends AppModel {

	/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
	public $order = 'Device.name ASC';

	//The Associations below have been created with all possible keys, those that are not needed can be removed
/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Location' => array(
			'className' => 'Location',
			'foreignKey' => 'location_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Measurement' => array(
			'className' => 'Measurement',
			'foreignKey' => 'device_id',
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


/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'MeasurementType' => array(
			'className' => 'MeasurementType',
			'joinTable' => 'devices_measurement_types',
			'foreignKey' => 'device_id',
			'associationForeignKey' => 'measurement_type_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);

}
