<?php
/**
 * ItemSubtypeVersionFixture
 *
 */
class ItemSubtypeVersionFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'version' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'manufacturer_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'comment' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 512, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'has_components' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4),
		'item_subtype_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
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
			'version' => 1,
			'manufacturer_id' => 1,
			'comment' => 'Lorem ipsum dolor sit amet',
			'has_components' => 1,
			'item_subtype_id' => 1
		),
	);
}
