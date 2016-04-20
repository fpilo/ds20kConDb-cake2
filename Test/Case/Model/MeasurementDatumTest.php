<?php
App::uses('MeasurementDatum', 'Model');

/**
 * MeasurementDatum Test Case
 *
 */
class MeasurementDatumTestCase extends CakeTestCase {
/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MeasurementDatum = ClassRegistry::init('MeasurementDatum');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MeasurementDatum);

		parent::tearDown();
	}

}
