<?php
App::uses('AppModel', 'Model');
/**
 * ItemTagsItem Model
 *
 * @property Item $Item
 * @property ItemTag $ItemTag
 */
class ItemTagsItem extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Item' => array(
			'className' => 'Item',
			'foreignKey' => 'item_id',
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
