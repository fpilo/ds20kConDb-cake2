<?php
App::uses('AssembledItemType', 'Model');

/**
 * AssembledItemType Test Case
 *
 */
class AssembledItemTypeTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.assembled_item_type', 'app.child');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->AssembledItemType = ClassRegistry::init('AssembledItemType');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->AssembledItemType);

		parent::tearDown();
	}

}
