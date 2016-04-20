<?php
App::uses('MeasurementTwoDimValue', 'Model');

/**
 * MeasurementTwoDimValue Test Case
 *
 */
class MeasurementTwoDimValueTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.measurement_two_dim_value', 'app.measurement', 'app.history', 'app.item', 'app.item_type', 'app.location', 'app.state', 'app.manufacturer', 'app.project', 'app.event', 'app.device', 'app.user', 'app.group', 'app.measurement_one_dim_value', 'app.parameter', 'app.measurement_three_dim_parameter', 'app.x_parameter', 'app.y_parameter', 'app.z_parameter', 'app.measurement_three_dim_value', 'app.measurement_two_dim_parameter');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MeasurementTwoDimValue = ClassRegistry::init('MeasurementTwoDimValue');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MeasurementTwoDimValue);

		parent::tearDown();
	}

}
