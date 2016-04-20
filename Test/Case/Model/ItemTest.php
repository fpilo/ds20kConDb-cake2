<?php
App::uses('Item', 'Model');

/**
 * Item Test Case
 *
 */
class ItemTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.item',
		'app.item_subtype_version',
		'app.manufacturer',
		'app.project',
		'app.user',
		'app.group',
		'app.history',
		'app.event',
		'app.db_file',
		'app.db_files_item_subtype_version',
		'app.item_subtype',
		'app.item_type',
		'app.db_files_item_subtype',
		'app.db_files_item',
		'app.location',
		'app.locations_user',
		'app.stock',
		'app.state',
		'app.locations_stock',
		'app.projects_stock',
		'app.item_composition',
		'app.projects_user',
		'app.manufacturers_project',
		'app.item_subtype_versions_project',
		'app.item_subtype_versions_composition',
		'app.transfer',
		'app.deliverer',
		'app.items_transfer',
		'app.from_locations_transfer'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Item = ClassRegistry::init('Item');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Item);

		parent::tearDown();
	}

/**
 * testCheckUniqueness method
 *
 * @return void
 */
	public function testCheckUniqueness() {
	}

/**
 * testCheckCodes method
 *
 * @return void
 */
	public function testCheckCodes() {
	}

/**
 * testSeparate method
 *
 * @return void
 */
	public function testSeparate() {
	}

/**
 * testSaveComponentsRecursive method
 *
 * @return void
 */
	public function testSaveComponentsRecursive() {
	}

/**
 * testSaveAssembledItem method
 *
 * @return void
 */
	public function testSaveAssembledItem() {
	}

/**
 * testChangeLocationRecursive method
 *
 * @return void
 */
	public function testChangeLocationRecursive() {
	}

}
