<?php
App::uses('AppModel', 'Model');
/**
 * MeasurementFile Model
 *
 * @property Measurement $Measurement
 */
class MeasurementFile extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'convertedFilePath';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Measurement' => array(
			'className' => 'Measurement',
			'foreignKey' => 'measurement_file_id',
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

}
