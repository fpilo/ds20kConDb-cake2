<?php
/**
 * ItemSubtypeVersionsLongBlobFixture
 *
 */
class ItemSubtypeVersionsLongBlobFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'item_subtype_version_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'long_blob_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'item_types_long_blobs.itemsID' => array('column' => 'item_subtype_version_id', 'unique' => 0), 'item_types_long_blobs.long_blobsID' => array('column' => 'long_blob_id', 'unique' => 0)),
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
			'long_blob_id' => 1
		),
	);
}
