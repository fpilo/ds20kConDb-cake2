<?php
App::uses('Deliverer', 'Model');

/**
 * Deliverer Test Case
 *
 */
class DelivererTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.deliverer', 'app.transfer', 'app.location', 'app.item', 'app.item_subtype_version', 'app.manufacturer', 'app.project', 'app.user', 'app.group', 'app.history', 'app.event', 'app.locations_user', 'app.projects_user', 'app.manufacturers_project', 'app.item_subtype_versions_project', 'app.item_subtype', 'app.item_type', 'app.long_blob', 'app.item_subtype_versions_long_blob', 'app.item_subtype_versions_composition', 'app.state', 'app.medium_blob', 'app.items_medium_blob', 'app.item_composition', 'app.items_transfer');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Deliverer = ClassRegistry::init('Deliverer');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Deliverer);

		parent::tearDown();
	}

}
