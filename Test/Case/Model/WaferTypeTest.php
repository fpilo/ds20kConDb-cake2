<?php
App::uses('WaferType', 'Model');

/**
 * WaferType Test Case
 *
 */
class WaferTypeTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.wafer_type', 'app.manufacturer', 'app.item', 'app.item_type', 'app.long_blob', 'app.item_types_long_blob', 'app.location', 'app.state', 'app.project', 'app.history', 'app.event', 'app.medium_blob', 'app.items_medium_blob', 'app.wafer', 'app.item_types_wafer_type');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->WaferType = ClassRegistry::init('WaferType');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->WaferType);

		parent::tearDown();
	}

}
