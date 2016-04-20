<?php
/**
 * ReadingFixture
 *
 */
class ReadingFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'measuring_point_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'parameter_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'value' => array('type' => 'float', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'Readings_MeasuringPointID' => array('column' => 'measuring_point_id', 'unique' => 0), 'Readings_ParameterID' => array('column' => 'parameter_id', 'unique' => 0)),
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
			'measuring_point_id' => 1,
			'parameter_id' => 1,
			'value' => 1
		),
	);
}
