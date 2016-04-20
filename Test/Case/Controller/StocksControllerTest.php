<?php
App::uses('StocksController', 'Controller');

/**
 * StocksController Test Case
 *
 */
class StocksControllerTest extends ControllerTestCase {

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
