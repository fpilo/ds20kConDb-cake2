<?php
App::uses('AppModel', 'Model');
/**
 * Stock Model
 *
 * @property ItemSubtypeVersion $ItemSubtypeVersion
 * @property Location $Location
 * @property Project $Project
 */
class Stock extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'id';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'amount' => array(
			'naturalnumber' => array(
				'rule' => array('naturalnumber', true),
				'message' => 'The Amount must be a positive number or 0.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'item_subtype_version_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please select a version.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'state_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please select a state.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'stock_quality_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Quality required',
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
		'ItemSubtypeVersion' => array(
			'className' => 'ItemSubtypeVersion',
			'foreignKey' => 'item_subtype_version_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'State' => array(
			'className' => 'State',
			'foreignKey' => 'state_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
        'ParentItem' => array(
            'className' => 'Item',
            'foreignKey' => 'parent_item_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
		),
		'StockQuality' => array(
			'className' => 'ItemQuality',
			'foreignKey' => 'stock_quality_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Location' => array(
			'className' => 'Location',
			'joinTable' => 'locations_stocks',
			'foreignKey' => 'stock_id',
			'associationForeignKey' => 'location_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		),
		'Project' => array(
			'className' => 'Project',
			'joinTable' => 'projects_stocks',
			'foreignKey' => 'stock_id',
			'associationForeignKey' => 'project_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		),
		'Item' => array(
			'className' => 'Item',
			'joinTable' => 'item_compositions',
			'foreignKey' => 'stock_id',
			'associationForeignKey' => 'item_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		),
		'StockTag' => array(
			'className' => 'ItemTag',
			'joinTable' => 'item_tags_stocks',
			'foreignKey' => 'stock_id',
			'associationForeignKey' => 'item_tag_id',
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
