<?php
App::uses('MeasurementOneDimValue', 'Model');

/**
 * MeasurementOneDimValue Test Case
 *
 */
class MeasurementOneDimValueTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.measurement_one_dim_value', 'app.measurement', 'app.history', 'app.item', 'app.item_type', 'app.location', 'app.state', 'app.manufacturer', 'app.project', 'app.event', 'app.device', 'app.user', 'app.group', 'app.measurement_three_dim_parameter', 'app.measurement_three_dim_value', 'app.measurement_two_dim_parameter', 'app.measurement_two_dim_value', 'app.parameter');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MeasurementOneDimValue = ClassRegistry::init('MeasurementOneDimValue');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MeasurementOneDimValue);

		parent::tearDown();
	}

}
