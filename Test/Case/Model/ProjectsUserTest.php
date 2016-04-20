<?php
App::uses('ProjectsUser', 'Model');

/**
 * ProjectsUser Test Case
 *
 */
class ProjectsUserTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.projects_user', 'app.project', 'app.item', 'app.item_type', 'app.long_blob', 'app.item_types_long_blob', 'app.location', 'app.state', 'app.manufacturer', 'app.history', 'app.event', 'app.medium_blob', 'app.items_medium_blob', 'app.user', 'app.group');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ProjectsUser = ClassRegistry::init('ProjectsUser');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ProjectsUser);

		parent::tearDown();
	}

}
