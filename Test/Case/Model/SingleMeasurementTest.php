<?php
App::uses('SingleMeasurement', 'Model');

/**
 * SingleMeasurement Test Case
 *
 */
class SingleMeasurementTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.single_measurement', 'app.history', 'app.item', 'app.item_type', 'app.location', 'app.state', 'app.manufacturer', 'app.project', 'app.event', 'app.user', 'app.group', 'app.device', 'app.measurement_series', 'app.parameter', 'app.measurement_data');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SingleMeasurement = ClassRegistry::init('SingleMeasurement');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SingleMeasurement);

		parent::tearDown();
	}

}
