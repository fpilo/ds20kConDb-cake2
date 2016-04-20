<?php
App::uses('LogEvent', 'Model');

/**
 * LogEvent Test Case
 *
 */
class LogEventTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.log_event', 'app.log');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->LogEvent = ClassRegistry::init('LogEvent');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->LogEvent);

		parent::tearDown();
	}

}
