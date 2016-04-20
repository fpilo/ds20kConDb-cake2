<?php
/**
 * ItemSubtypeVersionsCompositionFixture
 *
 */
class ItemSubtypeVersionsCompositionFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'item_subtype_version_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'component_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'position' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'suffix' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'attached' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 4),
		'project_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
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
			'item_subtype_version_id' => 1,
			'component_id' => 1,
			'position' => 'Lorem ipsum dolor sit amet',
			'suffix' => 'Lorem ipsum dolor sit amet',
			'attached' => 1,
			'project_id' => 1
		),
	);
}
