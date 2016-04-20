<?php
App::uses('ItemSubtype', 'Model');

/**
 * ItemSubtype Test Case
 *
 */
class ItemSubtypeTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.item_subtype', 'app.item_type', 'app.item_subtype_version');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ItemSubtype = ClassRegistry::init('ItemSubtype');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ItemSubtype);

		parent::tearDown();
	}

}
