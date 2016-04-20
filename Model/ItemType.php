<?php
App::uses('AppModel', 'Model');
/**
 * ItemType Model
 *
 * @property ItemSubtypeVersionView $ItemSubtypeVersionView
 * @property ItemSubtype $ItemSubtype
 * @property Item $Item
 * @property StockView $StockView
 * @property Project $Project
 */
class ItemType extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
	public $order = 'ItemType.name ASC';

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
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'ItemSubtypeVersionView' => array(
			'className' => 'ItemSubtypeVersionView',
			'foreignKey' => 'item_type_id',
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
		'ItemSubtype' => array(
			'className' => 'ItemSubtype',
			'foreignKey' => 'item_type_id',
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
			'foreignKey' => 'item_type_id',
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
		'StockView' => array(
			'className' => 'StockView',
			'foreignKey' => 'item_type_id',
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


/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Project' => array(
			'className' => 'Project',
			'joinTable' => 'projects_item_types',
			'foreignKey' => 'item_type_id',
			'associationForeignKey' => 'project_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);

	function hasSubtypes($item_type_id){
		$count = $this->ItemSubtype->find("count", array("conditions" => array("ItemSubtype.item_type_id" => $item_type_id)));
	    if ($count == 0) {
	        return false;
	    } else {
	        return true;
	    }
	}

	/**
	 * method getAvailableTagsForProject
	 * @param int $project_id Id of the project
	 * @param int $item_type_id Id of the item type
	 *
	 * @return array containing the ItemTags for the given parameters
	 */
	public function getAvailableItemTagsForProject($item_type_id,$project_id)
	{
		return;
	}

	/**
	 * method getItemTypes
	 * Returns all existing item types
	 *
	 * @return array containing all itemTypes
	 * @author
	 */
	function getItemTypes()
	{
		return $this->find("list");
	}

	/**
	 * method getProjectsForItemTypes
	 *
	 * @return array Associative array containing all Projects for all Item Types grouped by Item Type
	 * @author
	 */
	function getProjectsForItemTypes() {
		$return = array();
		foreach($this->getItemTypes() as $id=>$name){
			$return[$id] = $this->ProjectsItemType->getProjectsForItemType($id);
		}
		return $return;
	}


}
