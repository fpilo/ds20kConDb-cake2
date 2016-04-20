<?php
App::uses('AppModel', 'Model');
/**
 * ItemSubtype Model
 *
 * @property ItemType $ItemType
 * @property ItemSubtypeVersion $ItemSubtypeVersion
 */
class ItemSubtype extends AppModel {


	public $actsAs = array('Containable');

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
	public $order = 'ItemSubtype.name ASC';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Name is required',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'shortname' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Shortname is required',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'item_type_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please specify an item type',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'ItemType' => array(
			'className' => 'ItemType',
			'foreignKey' => 'item_type_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'ItemSubtypeVersion' => array(
			'className' => 'ItemSubtypeVersion',
			'foreignKey' => 'item_subtype_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Item' => array(
			'className' => 'Item',
			'foreignKey' => 'item_subtype_id',
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
		'DbFile' => array(
			'className' => 'DbFile',
			'joinTable' => 'db_files_item_subtypes',
			'foreignKey' => 'item_subtype_id',
			'associationForeignKey' => 'db_file_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);
}
