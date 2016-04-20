<?php
/**
 * ItemsMediumBlobFixture
 *
 */
class ItemsMediumBlobFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'items_medium_blobs.id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'items_medium_blobs.item_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'items_medium_blobs.medium_blobs_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'items_medium_blobs.id', 'unique' => 1), 'items_medium_blobs.itemsID' => array('column' => 'items_medium_blobs.item_id', 'unique' => 0), 'items_medium_blobs.medium_blobsID' => array('column' => 'items_medium_blobs.medium_blobs_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'items_medium_blobs.id' => 1,
			'items_medium_blobs.item_id' => 1,
			'items_medium_blobs.medium_blobs_id' => 1
		),
	);
}
