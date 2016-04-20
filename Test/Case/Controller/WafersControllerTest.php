<?php
App::uses('WafersController', 'Controller');

/**
 * TestWafersController *
 */
class TestWafersController extends WafersController {
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
 * WafersController Test Case
 *
 */
class WafersControllerTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.wafer', 'app.wafer_type', 'app.manufacturer', 'app.item', 'app.item_type', 'app.long_blob', 'app.item_types_long_blob', 'app.location', 'app.state', 'app.project', 'app.history', 'app.event', 'app.medium_blob', 'app.items_medium_blob', 'app.item_types_wafer_type');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Wafers = new TestWafersController();
		$this->Wafers->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Wafers);

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
