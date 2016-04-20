<?php
App::uses('ItemSubtypeVersionsController', 'Controller');

/**
 * TestItemSubtypeVersionsController *
 */
class TestItemSubtypeVersionsController extends ItemSubtypeVersionsController {
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
 * ItemSubtypeVersionsController Test Case
 *
 */
class ItemSubtypeVersionsControllerTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.item_subtype_version', 'app.manufacturer', 'app.item_subtype', 'app.item_type', 'app.project', 'app.item', 'app.location', 'app.user', 'app.group', 'app.locations_user', 'app.projects_user', 'app.state', 'app.history', 'app.event', 'app.medium_blob', 'app.items_medium_blob', 'app.item_composition', 'app.transfer', 'app.deliverer', 'app.items_transfer', 'app.manufacturers_project', 'app.item_subtype_version_composition', 'app.long_blob', 'app.item_subtype_versions_long_blob');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ItemSubtypeVersions = new TestItemSubtypeVersionsController();
		$this->ItemSubtypeVersions->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ItemSubtypeVersions);

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
