<?php
App::uses('ItemsLongBlob', 'Model');

/**
 * ItemsLongBlob Test Case
 *
 */
class ItemsLongBlobTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.items_long_blob', 'app.item', 'app.item_type', 'app.location', 'app.state', 'app.manufacturer', 'app.project', 'app.history', 'app.event', 'app.medium_blob', 'app.items_medium_blob', 'app.long_blob');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ItemsLongBlob = ClassRegistry::init('ItemsLongBlob');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ItemsLongBlob);

		parent::tearDown();
	}

}
