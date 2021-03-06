<?php
App::uses('MeasurementTwoDimParameter', 'Model');

/**
 * MeasurementTwoDimParameter Test Case
 *
 */
class MeasurementTwoDimParameterTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.measurement_two_dim_parameter', 'app.measurement', 'app.history', 'app.item', 'app.item_type', 'app.location', 'app.state', 'app.manufacturer', 'app.project', 'app.event', 'app.device', 'app.user', 'app.group', 'app.measurement_one_dim_value', 'app.parameter', 'app.measurement_three_dim_parameter', 'app.measurement_three_dim_value', 'app.measurement_two_dim_value');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MeasurementTwoDimParameter = ClassRegistry::init('MeasurementTwoDimParameter');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MeasurementTwoDimParameter);

		parent::tearDown();
	}

}
