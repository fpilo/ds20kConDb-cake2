<?php
/**
 * MeasurementTwoDimParameterFixture
 *
 */
class MeasurementTwoDimParameterFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'measurement_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'x_parameter_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'y_parameter_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), '2dMeasurementParameters_MeasurementID' => array('column' => 'measurement_id', 'unique' => 0), '2dMeasurementParameters_XParameterID' => array('column' => 'x_parameter_id', 'unique' => 0), '2dMeasurementParameters_YParameter' => array('column' => 'y_parameter_id', 'unique' => 0)),
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
			'x_parameter_id' => 1,
			'y_parameter_id' => 1
		),
	);
}
