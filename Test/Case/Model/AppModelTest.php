<?php
App::uses('AppModel', 'Model');

/**
 * AppModel Test Case
 *
 */
class AppModelTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.app_model');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->AppModel = ClassRegistry::init('AppModel');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->AppModel);

		parent::tearDown();
	}

/**
 * testUseModel method
 *
 * @return void
 */
	public function testUseModel() {

	}
}
