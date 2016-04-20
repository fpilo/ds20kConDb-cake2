<?php
App::uses('ItemType', 'Model');

/**
 * ItemType Test Case
 *
 */
class ItemTypeTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.item_type', 'app.item', 'app.location', 'app.state', 'app.manufacturer', 'app.project', 'app.history', 'app.event');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ItemType = ClassRegistry::init('ItemType');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ItemType);

		parent::tearDown();
	}

}
