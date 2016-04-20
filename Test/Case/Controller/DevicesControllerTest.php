<?php
App::uses('DevicesController', 'Controller');

/**
 * TestDevicesController *
 */
class TestDevicesController extends DevicesController {
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
 * DevicesController Test Case
 *
 */
class DevicesControllerTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.device', 'app.location', 'app.item', 'app.item_type', 'app.state', 'app.manufacturer', 'app.project', 'app.history', 'app.event', 'app.measurement', 'app.user', 'app.group', 'app.measurement_one_dim_value', 'app.parameter', 'app.measurement_three_dim_parameter', 'app.measurement_three_dim_value', 'app.measurement_two_dim_parameter', 'app.measurement_two_dim_value');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Devices = new TestDevicesController();
		$this->Devices->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Devices);

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
