<?php
App::uses('State', 'Model');

/**
 * State Test Case
 *
 */
class StateTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.state', 'app.item', 'app.item_type', 'app.location', 'app.manufacturer', 'app.project', 'app.history', 'app.event');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->State = ClassRegistry::init('State');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->State);

		parent::tearDown();
	}

}
