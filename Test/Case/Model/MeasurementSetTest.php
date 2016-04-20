<?php
App::uses('MeasurementSet', 'Model');

/**
 * MeasurementSet Test Case
 *
 */
class MeasurementSetTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.measurement_set',
		'app.measuremement'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MeasurementSet = ClassRegistry::init('MeasurementSet');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MeasurementSet);

		parent::tearDown();
	}

}
