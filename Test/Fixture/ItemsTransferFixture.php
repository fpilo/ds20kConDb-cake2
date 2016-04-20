<?php
/**
 * ItemsTransferFixture
 *
 */
class ItemsTransferFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'item_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'transfer_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'is_part_of' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'from_location_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'to_location_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
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
			'item_id' => 1,
			'transfer_id' => 1,
			'is_part_of' => 1,
			'from_location_id' => 1,
			'to_location_id' => 1
		),
	);
}
