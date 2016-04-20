<?php
App::uses('ItemTypesWaferTypesController', 'Controller');

/**
 * TestItemTypesWaferTypesController *
 */
class TestItemTypesWaferTypesController extends ItemTypesWaferTypesController {
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
 * ItemTypesWaferTypesController Test Case
 *
 */
class ItemTypesWaferTypesControllerTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.item_types_wafer_type', 'app.item_type', 'app.item', 'app.location', 'app.state', 'app.manufacturer', 'app.project', 'app.history', 'app.event', 'app.medium_blob', 'app.items_medium_blob', 'app.long_blob', 'app.item_types_long_blob', 'app.wafer_type', 'app.wafer');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ItemTypesWaferTypes = new TestItemTypesWaferTypesController();
		$this->ItemTypesWaferTypes->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ItemTypesWaferTypes);

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
