<?php
App::uses('ItemsMediumBlob', 'Model');

/**
 * ItemsMediumBlob Test Case
 *
 */
class ItemsMediumBlobTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.items_medium_blob');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ItemsMediumBlob = ClassRegistry::init('ItemsMediumBlob');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ItemsMediumBlob);

		parent::tearDown();
	}

}
