<?php
App::uses('ItemTypesWaferType', 'Model');

/**
 * ItemTypesWaferType Test Case
 *
 */
class ItemTypesWaferTypeTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.item_types_wafer_type', 'app.item_type', 'app.item', 'app.location', 'app.state', 'app.manufacturer', 'app.project', 'app.history', 'app.event', 'app.medium_blob', 'app.items_medium_blob', 'app.long_blob', 'app.item_types_long_blob', 'app.wafer_type', 'app.wafer');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ItemTypesWaferType = ClassRegistry::init('ItemTypesWaferType');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ItemTypesWaferType);

		parent::tearDown();
	}

}
