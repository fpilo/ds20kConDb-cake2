<?php
App::uses('Transfer', 'Model');

/**
 * Transfer Test Case
 *
 */
class TransferTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.transfer', 'app.item', 'app.item_type', 'app.location', 'app.state', 'app.manufacturer', 'app.project', 'app.history', 'app.event');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Transfer = ClassRegistry::init('Transfer');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Transfer);

		parent::tearDown();
	}

}
