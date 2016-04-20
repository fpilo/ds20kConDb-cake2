<?php
App::uses('MeasurementSeries', 'Model');

/**
 * MeasurementSeries Test Case
 *
 */
class MeasurementSeriesTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.measurement_series', 'app.history', 'app.item', 'app.item_type', 'app.location', 'app.state', 'app.manufacturer', 'app.project', 'app.event', 'app.device', 'app.user', 'app.group', 'app.parameter', 'app.single_measurement', 'app.measurement_data');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MeasurementSeries = ClassRegistry::init('MeasurementSeries');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MeasurementSeries);

		parent::tearDown();
	}

}
