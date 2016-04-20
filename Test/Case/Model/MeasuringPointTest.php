<?php
App::uses('MeasuringPoint', 'Model');

/**
 * MeasuringPoint Test Case
 *
 */
class MeasuringPointTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.measuring_point', 'app.measurement', 'app.reading', 'app.parameter');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MeasuringPoint = ClassRegistry::init('MeasuringPoint');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MeasuringPoint);

		parent::tearDown();
	}

}
