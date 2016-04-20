<?php
App::uses('Location', 'Model');

/**
 * Location Test Case
 *
 */
class LocationTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.location', 'app.item', 'app.item_type', 'app.state', 'app.manufacturer', 'app.project', 'app.history', 'app.event');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Location = ClassRegistry::init('Location');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Location);

		parent::tearDown();
	}

}
