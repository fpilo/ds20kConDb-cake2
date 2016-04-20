<?php
App::uses('ItemsTransfer', 'Model');

/**
 * ItemsTransfer Test Case
 *
 */
class ItemsTransferTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.items_transfer', 'app.item', 'app.item_subtype_version', 'app.manufacturer', 'app.project', 'app.user', 'app.group', 'app.history', 'app.event', 'app.location', 'app.locations_user', 'app.projects_user', 'app.manufacturers_project', 'app.item_subtype_versions_project', 'app.item_subtype', 'app.item_type', 'app.long_blob', 'app.item_subtype_versions_long_blob', 'app.item_subtype_versions_composition', 'app.state', 'app.medium_blob', 'app.items_medium_blob', 'app.item_composition', 'app.transfer', 'app.deliverer', 'app.from_location', 'app.to_location');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ItemsTransfer = ClassRegistry::init('ItemsTransfer');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ItemsTransfer);

		parent::tearDown();
	}

}
