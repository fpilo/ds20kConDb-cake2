<?php
/**
 * TransferFixture
 *
 */
class TransferFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'item_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'history_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'shipping_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
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
			'history_id' => 1,
			'shipping_date' => '2012-04-12 15:46:04'
		),
	);
}
