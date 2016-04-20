<?php
App::uses('Manufacturer', 'Model');

/**
 * Manufacturer Test Case
 *
 */
class ManufacturerTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.manufacturer', 'app.item', 'app.item_type', 'app.location', 'app.state', 'app.project', 'app.history', 'app.event');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Manufacturer = ClassRegistry::init('Manufacturer');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Manufacturer);

		parent::tearDown();
	}

}
