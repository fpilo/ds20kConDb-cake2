<?php
App::uses('Wafer', 'Model');

/**
 * Wafer Test Case
 *
 */
class WaferTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.wafer', 'app.wafer_type', 'app.manufacturer', 'app.item', 'app.item_type', 'app.long_blob', 'app.item_types_long_blob', 'app.location', 'app.state', 'app.project', 'app.history', 'app.event', 'app.medium_blob', 'app.items_medium_blob', 'app.item_types_wafer_type');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Wafer = ClassRegistry::init('Wafer');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Wafer);

		parent::tearDown();
	}

}
