<?php
App::uses('AppModel', 'Model');
/**
 * MeasuringPoint Model
 *
 * @property Measurement $Measurement
 * @property Reading $Reading
 */
class MeasuringPoint extends AppModel {

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

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Reading' => array(
			'className' => 'Reading',
			'foreignKey' => 'measuring_point_id',
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
