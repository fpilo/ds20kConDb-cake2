<?php
/**
 * ItemSubtypeVersionViewFixture
 *
 */
class ItemSubtypeVersionViewFixture extends CakeTestFixture {
/**
 * Table name
 *
 * @var string
 */
	public $table = 'item_subtype_version_view';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'manufacturer_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'manufacturer_name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'manufacturers_projects_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'manufacturers_projects_manufacturer_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'manufacturers_projects_project_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'project_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'project_name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'item_subtype_version_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'item_subtype_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'version' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'item_subtype_name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'item_type_name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'item_type_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(),
		'tableParameters' => array()
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'manufacturer_id' => 1,
			'manufacturer_name' => 'Lorem ipsum dolor sit amet',
			'manufacturers_projects_id' => 1,
			'manufacturers_projects_manufacturer_id' => 1,
			'manufacturers_projects_project_id' => 1,
			'project_id' => 1,
			'project_name' => 'Lorem ipsum dolor sit amet',
			'item_subtype_version_id' => 1,
			'item_subtype_id' => 1,
			'version' => 1,
			'item_subtype_name' => 'Lorem ipsum dolor sit amet',
			'item_type_name' => 'Lorem ipsum dolor sit amet',
			'item_type_id' => 1
		),
	);
}
