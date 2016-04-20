<?php
/**
 * ItemTypesWaferTypeFixture
 *
 */
class ItemTypesWaferTypeFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'item_type_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'wafer_type_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'number' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'item_types_wafer_types.item_type_id' => array('column' => 'item_type_id', 'unique' => 0), 'item_types_wafer_types.wafer_type_id' => array('column' => 'wafer_type_id', 'unique' => 0)),
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
			'item_type_id' => 1,
			'wafer_type_id' => 1,
			'number' => 1
		),
	);
}
