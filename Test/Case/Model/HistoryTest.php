<?php
App::uses('History', 'Model');

/**
 * History Test Case
 *
 */
class HistoryTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.history', 'app.item', 'app.item_type', 'app.location', 'app.state', 'app.manufacturer', 'app.project', 'app.event');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->History = ClassRegistry::init('History');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->History);

		parent::tearDown();
	}

}
