<?php
App::uses('MeasurementThreeDimParametersController', 'Controller');

/**
 * TestMeasurementThreeDimParametersController *
 */
class TestMeasurementThreeDimParametersController extends MeasurementThreeDimParametersController {
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
 * MeasurementThreeDimParametersController Test Case
 *
 */
class MeasurementThreeDimParametersControllerTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.measurement_three_dim_parameter', 'app.measurement', 'app.history', 'app.item', 'app.item_type', 'app.location', 'app.state', 'app.manufacturer', 'app.project', 'app.event', 'app.device', 'app.user', 'app.group', 'app.measurement_one_dim_value', 'app.parameter', 'app.measurement_three_dim_value', 'app.measurement_two_dim_parameter', 'app.measurement_two_dim_value', 'app.x_parameter', 'app.y_parameter', 'app.z_parameter');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MeasurementThreeDimParameters = new TestMeasurementThreeDimParametersController();
		$this->MeasurementThreeDimParameters->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MeasurementThreeDimParameters);

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
