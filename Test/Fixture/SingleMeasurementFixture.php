<?php
/**
 * SingleMeasurementFixture
 *
 */
class SingleMeasurementFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'history_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'device_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'parameter_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'parameter_value' => array('type' => 'float', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'SingleMeasurements_HistoryID' => array('column' => 'history_id', 'unique' => 0), 'SingleMeasurements_ParameterID' => array('column' => 'parameter_id', 'unique' => 0), 'SingleMeasurements_UserID' => array('column' => 'user_id', 'unique' => 0), 'SingleMeasurements_DeviceID' => array('column' => 'device_id', 'unique' => 0)),
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
			'user_id' => 1,
			'device_id' => 1,
			'parameter_id' => 1,
			'parameter_value' => 1
		),
	);
}
