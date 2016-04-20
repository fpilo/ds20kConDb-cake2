<?php
App::uses('DeliverersController', 'Controller');

/**
 * TestDeliverersController *
 */
class TestDeliverersController extends DeliverersController {
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
 * DeliverersController Test Case
 *
 */
class DeliverersControllerTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.deliverer', 'app.transfer', 'app.location', 'app.item', 'app.item_subtype', 'app.manufacturer', 'app.item_type', 'app.long_blob', 'app.item_subtypes_long_blob', 'app.item_subtype_composition', 'app.state', 'app.project', 'app.user', 'app.group', 'app.locations_user', 'app.projects_user', 'app.history', 'app.event', 'app.medium_blob', 'app.items_medium_blob', 'app.item_composition', 'app.items_transfer');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Deliverers = new TestDeliverersController();
		$this->Deliverers->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Deliverers);

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
