<?php
App::uses('ItemTypesLongBlob', 'Model');

/**
 * ItemTypesLongBlob Test Case
 *
 */
class ItemTypesLongBlobTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.item_types_long_blob', 'app.item_type', 'app.item', 'app.location', 'app.state', 'app.manufacturer', 'app.project', 'app.history', 'app.event', 'app.medium_blob', 'app.items_medium_blob', 'app.long_blob');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ItemTypesLongBlob = ClassRegistry::init('ItemTypesLongBlob');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ItemTypesLongBlob);

		parent::tearDown();
	}

}
