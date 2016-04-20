<?php
App::uses('Measurement', 'Model');

/**
 * Measurement Test Case
 *
 */
class MeasurementTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.measurement', 'app.history', 'app.item', 'app.item_type', 'app.long_blob', 'app.item_types_long_blob', 'app.location', 'app.state', 'app.manufacturer', 'app.project', 'app.medium_blob', 'app.items_medium_blob', 'app.event', 'app.device', 'app.user', 'app.group', 'app.measurement_type', 'app.measuring_point', 'app.reading', 'app.parameter');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Measurement = ClassRegistry::init('Measurement');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Measurement);

		parent::tearDown();
	}

}
