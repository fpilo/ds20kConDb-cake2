<?php
App::uses('UsersController', 'Controller');

/**
 * TestUsersController *
 */
class TestUsersController extends UsersController {
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
 * UsersController Test Case
 *
 */
class UsersControllerTestCase extends ControllerTestCase  {
/**
 * Fixtures
 *
 * @var array
 */
	//public $fixtures = array('app.user', 'app.group', 'app.post');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Users = new TestUsersController();
		$this->Users->constructClasses();
		
		$this->User = new UsersController();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Users);

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
		$data = array(
            'User' => array(
                'user_id' => 1,
                'name' => 'homer'
            )
        );
        $result = $this->testAction(
            '/users/edit/1',
            array('data' => $data, 'method' => 'post')
        );
		
		debug($result);
		pr($result);
		//$this->assertContains('The user has been saved', $result);
	}
/**
 * testDelete method
 *
 * @return void
 */
	public function testDelete() {

	}
}
