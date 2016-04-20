<?php
/**
 * MeasurementSeriesFixture
 *
 */
class MeasurementSeriesFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'history_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'device_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'x_parameter_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'y_parameter_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'MeasurementSeries_HistoryID' => array('column' => 'history_id', 'unique' => 0), 'MeasurementSeries_DeviceID' => array('column' => 'device_id', 'unique' => 0), 'MeasurementSeries_UserID' => array('column' => 'user_id', 'unique' => 0), 'MeasurementSeries_XParameterID' => array('column' => 'x_parameter_id', 'unique' => 0), 'MeasurementSeries_YParameterID' => array('column' => 'y_parameter_id', 'unique' => 0)),
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
			'history_id' => 1,
			'device_id' => 1,
			'user_id' => 1,
			'x_parameter_id' => 1,
			'y_parameter_id' => 1
		),
	);
}
