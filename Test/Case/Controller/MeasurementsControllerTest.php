<?php
App::uses('MeasurementsController', 'Controller');

/**
 * TestMeasurementsController *
 */
class TestMeasurementsController extends MeasurementsController {
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
 * MeasurementsController Test Case
 *
 */
class MeasurementsControllerTestCase extends CakeTestCase {
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
		$this->Measurements = new TestMeasurementsController();
		$this->Measurements->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Measurements);

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
