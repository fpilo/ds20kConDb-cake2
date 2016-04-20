<?php
App::uses('MeasurementType', 'Model');

/**
 * MeasurementType Test Case
 *
 */
class MeasurementTypeTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.measurement_type', 'app.measurement');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MeasurementType = ClassRegistry::init('MeasurementType');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MeasurementType);

		parent::tearDown();
	}

}
