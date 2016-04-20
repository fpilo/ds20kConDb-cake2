<?php
App::uses('AppModel', 'Model');
/**
 * ItemTag Model
 *
 * @property Item $Item
 * @property ProjectsItemType $ProjectsItemType
 * @property Stock $Stock
 */
class ItemTag extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
	public $order = 'lower(ItemTag.name) ASC';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Item' => array(
			'className' => 'Item',
			'joinTable' => 'item_tags_items',
			'foreignKey' => 'item_tag_id',
			'associationForeignKey' => 'item_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		),
		'ProjectsItemType' => array(
			'className' => 'ProjectsItemType',
			'joinTable' => 'item_tags_projects_item_types',
			'foreignKey' => 'item_tag_id',
			'associationForeignKey' => 'projects_item_type_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		),
		'Stock' => array(
			'className' => 'Stock',
			'joinTable' => 'item_tags_stocks',
			'foreignKey' => 'item_tag_id',
			'associationForeignKey' => 'stock_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);

	/**
	 * getTagsForItemTypeAndProject method
	 *
	 * @return array of key=>value pairs of tags for the given parameters
	 */
	function getTagsForItemTypeAndProject($item_type_id,$project_id)
	{
		$tmp = $this->ProjectsItemType->find("first",array("conditions"=>array("ItemType.id"=>$item_type_id,"Project.id"=>$project_id)));
		$return = array();
		if(isset($tmp["ItemTag"]))
			foreach($tmp["ItemTag"] as $id=>$tags) $return[$tags["id"]] = $tags["name"];
		return $return;
	}
}
