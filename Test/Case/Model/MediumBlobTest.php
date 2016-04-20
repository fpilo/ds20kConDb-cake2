<?php
App::uses('MediumBlob', 'Model');

/**
 * MediumBlob Test Case
 *
 */
class MediumBlobTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.medium_blob', 'app.item', 'app.item_type', 'app.location', 'app.state', 'app.manufacturer', 'app.project', 'app.history', 'app.event', 'app.items_medium_blob');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MediumBlob = ClassRegistry::init('MediumBlob');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MediumBlob);

		parent::tearDown();
	}

}
