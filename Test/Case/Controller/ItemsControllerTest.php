<?php
App::uses('ItemsController', 'Controller');

/**
 * TestItemsController *
 */
class TestItemsController extends ItemsController {
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
 * ItemsController Test Case
 *
 */
class ItemsControllerTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	//public $fixtures = array('app.item', 'app.item_subtype_version', 'app.manufacturer', 'app.project', 'app.user', 'app.group', 'app.history', 'app.event', 'app.location', 'app.locations_user', 'app.projects_user', 'app.manufacturers_project', 'app.item_subtype_versions_project', 'app.item_subtype', 'app.item_type', 'app.long_blob', 'app.item_subtype_versions_long_blob', 'app.item_subtype_versions_composition', 'app.state', 'app.medium_blob', 'app.items_medium_blob', 'app.item_composition', 'app.transfer', 'app.deliverer', 'app.items_transfer');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Items = new TestItemsController();
		$this->Items->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Items);

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
 * testDeleteSession method
 *
 * @return void
 */
	public function testDeleteSession() {

	}
/**
 * testAddItemComposition method
 *
 * @return void
 */
	public function testAddItemComposition() {

	}
/**
 * testAdd method
 *
 * @return void
 */
	public function testAdd() {

	}
/**
 * testAssemble method
 *
 * @return void
 */
	public function testAssemble() {

	}
/**
 * testCancelAssemble method
 *
 * @return void
 */
	public function testCancelAssemble() {

	}
/**
 * testRemoveFromSelection method
 *
 * @return void
 */
	public function testRemoveFromSelection() {

	}
/**
 * testAssembleItemComposition method
 *
 * @return void
 */
	public function testAssembleItemComposition() {

	}
/**
 * testSelectFromInventory method
 *
 * @return void
 */
	public function testSelectFromInventory() {

	}
/**
 * testChangeCode method
 *
 * @return void
 */
	public function testChangeCode() {

	}
/**
 * testChangeState method
 *
 * @return void
 */
	public function testChangeState() {

	}
/**
 * testDelete method
 *
 * @return void
 */
	public function testDelete() {

	}
/**
 * testDetach method
 *
 * @return void
 */
	public function testDetach() {

	}
/**
 * testAttach method
 *
 * @return void
 */
	public function testAttach() {

	}
/**
 * testGetManufacturerByProject method
 *
 * @return void
 */
	public function testGetManufacturerByProject() {

	}
/**
 * testGetItemSubtypeByManufacturer method
 *
 * @return void
 */
	public function testGetItemSubtypeByManufacturer() {

	}
/**
 * testValidateForm method
 *
 * @return void
 */
	public function testValidateForm() {

	}
/**
 * testShoppingCart method
 *
 * @return void
 */
	public function testShoppingCart() {

	}
}
