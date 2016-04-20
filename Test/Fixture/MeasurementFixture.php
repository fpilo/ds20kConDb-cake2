<?php
/**
 * MeasurementFixture
 *
 */
class MeasurementFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'history_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'item_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'device_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'measurement_type_id' => array('type' => 'integer', 'null' => false, 'default' => '1', 'key' => 'index', 'comment' => 'To select the right table for the measurement data: dim=1 ==> 1D_measurement_data; dim=2 ==> 2D_measurement_data; ...'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'MeasurementSeries_HistoryID' => array('column' => 'history_id', 'unique' => 0), 'MeasurementSeries_DeviceID' => array('column' => 'device_id', 'unique' => 0), 'MeasurementSeries_UserID' => array('column' => 'user_id', 'unique' => 0), 'MeasurementSeries_ItemID' => array('column' => 'item_id', 'unique' => 0), 'MeasurementSeries_MeasurementTypeID' => array('column' => 'measurement_type_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
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
			'item_id' => 1,
			'device_id' => 1,
			'user_id' => 1,
			'measurement_type_id' => 1
		),
	);
}
