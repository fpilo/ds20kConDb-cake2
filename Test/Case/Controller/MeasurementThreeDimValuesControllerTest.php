<?php
App::uses('MeasurementThreeDimValuesController', 'Controller');

/**
 * TestMeasurementThreeDimValuesController *
 */
class TestMeasurementThreeDimValuesController extends MeasurementThreeDimValuesController {
/**
 * Auto render
 *
 * @var boolean
 */
	public $autoRender = false;

/**
 * Redirect action
 *
 * @param mixed $url
 * @param mixed $status
 * @param boolean $exit
 * @return void
 */
	public function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

/**
 * MeasurementThreeDimValuesController Test Case
 *
 */
class MeasurementThreeDimValuesControllerTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.measurement_three_dim_value', 'app.measurement', 'app.history', 'app.item', 'app.item_type', 'app.location', 'app.state', 'app.manufacturer', 'app.project', 'app.event', 'app.device', 'app.user', 'app.group', 'app.measurement_one_dim_value', 'app.parameter', 'app.measurement_three_dim_parameter', 'app.x_parameter', 'app.y_parameter', 'app.z_parameter', 'app.measurement_two_dim_parameter', 'app.measurement_two_dim_value');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MeasurementThreeDimValues = new TestMeasurementThreeDimValuesController();
		$this->MeasurementThreeDimValues->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MeasurementThreeDimValues);

		parent::tearDown();
	}

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndex() {

	}
/**
 * testView method
 *
 * @return void
 */
	public function testView() {

	}
/**
 * testAdd method
 *
 * @return void
 */
	public function testAdd() {

	}
/**
 * testEdit method
 *
 * @return void
 */
	public function testEdit() {

	}
/**
 * testDelete method
 *
 * @return void
 */
	public function testDelete() {

	}
}
