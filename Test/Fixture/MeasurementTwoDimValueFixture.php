<?php
/**
 * MeasurementTwoDimValueFixture
 *
 */
class MeasurementTwoDimValueFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'measurement_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'x' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'y' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), '2dMeasurementDatas_MeasurementID' => array('column' => 'measurement_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'measurement_id' => 1,
			'x' => 1,
			'y' => 1
		),
	);
}
