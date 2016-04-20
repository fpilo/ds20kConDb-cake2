<?php
App::uses('ItemSubtypeVersion', 'Model');

/**
 * ItemSubtypeVersion Test Case
 *
 */
class ItemSubtypeVersionTestCase extends CakeTestCase {
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
		$this->ItemSubtypeVersion = ClassRegistry::init('ItemSubtypeVersion');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ItemSubtypeVersion);

		parent::tearDown();
	}

}
