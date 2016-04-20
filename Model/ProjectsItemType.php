<?php
App::uses('AppModel', 'Model');
/**
 * ProjectsItemType Model
 *
 * @property Project $Project
 * @property ItemType $ItemType
 * @property ItemTag $ItemTag
 */
class ProjectsItemType extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'project_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'item_type_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
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
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'project_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ItemType' => array(
			'className' => 'ItemType',
			'foreignKey' => 'item_type_id',
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
		'ItemTag' => array(
			'className' => 'ItemTag',
			'joinTable' => 'item_tags_projects_item_types',
			'foreignKey' => 'projects_item_type_id',
			'associationForeignKey' => 'item_tag_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);
	public function addItemTypeToProject($item_type_id,$project_id)
	{
		return;
	}

	public function removeItemTypeFromProject($item_type_id,$project_id)
	{
		return;
	}

	/**
	 * findProjects method that returns all projects for a specific item_type
	 *
	 * @return array of projects
	 */
	function getProjectsForItemType($item_type)
	{
		$tmp = $this->find("all",array("conditions"=>array("item_type_id"=>$item_type),"fields"=>array("Project.id","Project.name")));
		$return = array();
		foreach($tmp as $id=>$value){
			$return[$value["Project"]["id"]] = $value["Project"]["name"];
		}
		return $return;
	}
}
