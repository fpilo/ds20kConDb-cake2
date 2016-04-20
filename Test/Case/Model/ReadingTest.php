<?php
App::uses('Reading', 'Model');

/**
 * Reading Test Case
 *
 */
class ReadingTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.reading', 'app.measuring_point', 'app.parameter');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Reading = ClassRegistry::init('Reading');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Reading);

		parent::tearDown();
	}

}
