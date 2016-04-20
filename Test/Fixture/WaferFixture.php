<?php
/**
 * WaferFixture
 *
 */
class WaferFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'code' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'wafer_type_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'location_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'project_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'code_UNIQUE' => array('column' => 'code', 'unique' => 1), 'wafers.wafer_type_id' => array('column' => 'wafer_type_id', 'unique' => 0), 'wafers.location_id' => array('column' => 'location_id', 'unique' => 0), 'wafers.project_id' => array('column' => 'project_id', 'unique' => 0)),
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
			'code' => 'Lorem ipsum dolor sit amet',
			'wafer_type_id' => 1,
			'location_id' => 1,
			'project_id' => 1
		),
	);
}
