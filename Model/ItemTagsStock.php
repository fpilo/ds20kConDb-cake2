<?php
App::uses('AppModel', 'Model');
/**
 * ItemTagsStock Model
 *
 * @property Stock $Stock
 * @property ItemTag $ItemTag
 */
class ItemTagsStock extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'id';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Stock' => array(
			'className' => 'Stock',
			'foreignKey' => 'stock_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ItemTag' => array(
			'className' => 'ItemTag',
			'foreignKey' => 'item_tag_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
