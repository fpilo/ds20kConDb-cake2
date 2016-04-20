<?php
/**
 * MeasurementOneDimValueFixture
 *
 */
class MeasurementOneDimValueFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'measurement_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'parameter_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'parameter_value' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), '1dMeasurementDatas_ParameterID' => array('column' => 'parameter_id', 'unique' => 0), '1dMeasurementDatas_MeasurementID' => array('column' => 'measurement_id', 'unique' => 0)),
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
			'parameter_id' => 1,
			'parameter_value' => 1
		),
	);
}
