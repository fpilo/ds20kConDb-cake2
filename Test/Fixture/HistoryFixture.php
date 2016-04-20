<?php
/**
 * HistoryFixture
 *
 */
class HistoryFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'item_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'event_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'comment' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'EventID' => array('column' => 'event_id', 'unique' => 0), 'ItemID' => array('column' => 'item_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_german1_ci', 'engine' => 'InnoDB')
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
			'event_id' => 1,
			'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created' => '2012-03-26 16:47:55',
			'modified' => '2012-03-26 16:47:55'
		),
	);
}
