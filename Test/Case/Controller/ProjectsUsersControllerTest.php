<?php
App::uses('ProjectsUsersController', 'Controller');

/**
 * TestProjectsUsersController *
 */
class TestProjectsUsersController extends ProjectsUsersController {
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
 * ProjectsUsersController Test Case
 *
 */
class ProjectsUsersControllerTestCase extends CakeTestCase {
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
		$this->ProjectsUsers = new TestProjectsUsersController();
		$this->ProjectsUsers->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ProjectsUsers);

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
