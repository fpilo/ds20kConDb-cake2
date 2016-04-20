<?php
App::uses('LocationsUser', 'Model');

/**
 * LocationsUser Test Case
 *
 */
class LocationsUserTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.locations_user', 'app.location', 'app.item', 'app.item_type', 'app.long_blob', 'app.item_types_long_blob', 'app.state', 'app.manufacturer', 'app.project', 'app.history', 'app.event', 'app.medium_blob', 'app.items_medium_blob', 'app.user', 'app.group');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->LocationsUser = ClassRegistry::init('LocationsUser');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->LocationsUser);

		parent::tearDown();
	}

}
