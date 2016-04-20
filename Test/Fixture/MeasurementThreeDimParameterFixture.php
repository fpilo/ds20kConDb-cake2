<?php
/**
 * MeasurementThreeDimParameterFixture
 *
 */
class MeasurementThreeDimParameterFixture extends CakeTestFixture {

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
		'z_parameter_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), '3dMeasurementParameters_MeasurementID' => array('column' => 'measurement_id', 'unique' => 0), '3dMeasurementParameters_XParameterID' => array('column' => 'x_parameter_id', 'unique' => 0), '3dMeasurementParameters_YParameterID' => array('column' => 'y_parameter_id', 'unique' => 0), '3dMeasurementParameters_ZParameterID' => array('column' => 'z_parameter_id', 'unique' => 0)),
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
			'y_parameter_id' => 1,
			'z_parameter_id' => 1
		),
	);
}
