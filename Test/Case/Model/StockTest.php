<?php
App::uses('Stock', 'Model');

/**
 * Stock Test Case
 *
 */
class StockTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.stock',
		'app.item_subtype_version',
		'app.manufacturer',
		'app.project',
		'app.item',
		'app.location',
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
		'app.locations_user',
		'app.projects_user',
		'app.state',
		'app.item_composition',
		'app.transfer',
		'app.deliverer',
		'app.items_transfer',
		'app.from_locations_transfer',
		'app.manufacturers_project',
		'app.item_subtype_versions_project',
		'app.item_subtype_versions_composition',
		'app.locations_stock',
		'app.projects_stock'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Stock = ClassRegistry::init('Stock');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Stock);

		parent::tearDown();
	}

}
