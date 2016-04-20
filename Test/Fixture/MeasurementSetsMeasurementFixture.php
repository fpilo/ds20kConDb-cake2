<?php
/**
 * MeasurementSetsMeasurementFixture
 *
 */
class MeasurementSetsMeasurementFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'measurement_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'measurement_set_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
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
			'measurement_id' => 1,
			'measurement_set_id' => 1
		),
	);

}
