<?php
App::uses('Device', 'Model');

/**
 * Device Test Case
 *
 */
class DeviceTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.device', 'app.location', 'app.item', 'app.item_type', 'app.state', 'app.manufacturer', 'app.project', 'app.history', 'app.event', 'app.measurement', 'app.user', 'app.group', 'app.measurement_one_dim_value', 'app.parameter', 'app.measurement_three_dim_parameter', 'app.measurement_three_dim_value', 'app.measurement_two_dim_parameter', 'app.measurement_two_dim_value');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Device = ClassRegistry::init('Device');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Device);

		parent::tearDown();
	}

}
