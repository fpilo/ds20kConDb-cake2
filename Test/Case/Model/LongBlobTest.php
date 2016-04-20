<?php
App::uses('LongBlob', 'Model');

/**
 * LongBlob Test Case
 *
 */
class LongBlobTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.long_blob', 'app.item_type', 'app.item', 'app.location', 'app.state', 'app.manufacturer', 'app.project', 'app.history', 'app.event', 'app.medium_blob', 'app.items_medium_blob', 'app.item_types_long_blob');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->LongBlob = ClassRegistry::init('LongBlob');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->LongBlob);

		parent::tearDown();
	}

}
