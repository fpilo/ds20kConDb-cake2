<?php
App::uses('ItemSubtypeVersionView', 'Model');

/**
 * ItemSubtypeVersionView Test Case
 *
 */
class ItemSubtypeVersionViewTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.item_subtype_version_view');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ItemSubtypeVersionView = ClassRegistry::init('ItemSubtypeVersionView');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ItemSubtypeVersionView);

		parent::tearDown();
	}

}
