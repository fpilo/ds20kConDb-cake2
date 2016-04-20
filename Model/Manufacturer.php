<?php
App::uses('AppModel', 'Model');
/**
 * Manufacturer Model
 *
 * @property Item $Item
 */
class Manufacturer extends AppModel {

	public $actsAs = array('Containable');
	public $order = 'Manufacturer.name ASC';
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter a name',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'checkUniqueness' => array(
				'rule' => array('checkUniqueness'),
				'message' => 'Name already in use'
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			)
		),
	);

	public function checkUniqueness($check) {
		// check uniqueness of the new codes within DB
		$result = $this->find('first', array('conditions' => array('Manufacturer.name' => $check['name'])));
		if($result != false)
			return false;

		return true;
    }

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'ItemSubtypeVersion' => array(
			'className' => 'ItemSubtypeVersion',
			'foreignKey' => 'manufacturer_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	public $hasAndBelongsToMany = array(
		'Project' => array(
			'className' => 'Project',
			'joinTable' => 'manufacturers_projects',
			'foreignKey' => 'manufacturer_id',
			'associationForeignKey' => 'project_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
}
