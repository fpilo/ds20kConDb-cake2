<?php
App::uses('AppModel', 'Model');
/**
 * ItemTagsProjectsItemType Model
 *
 * @property ItemTag $ItemTag
 * @property ProjectItemType $ProjectItemType
 */
class ItemTagsProjectsItemType extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'item_tag_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'project_item_type_id' => array(
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
		'ItemTag' => array(
			'className' => 'ItemTag',
			'foreignKey' => 'item_tag_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ProjectsItemType' => array(
			'className' => 'ProjectsItemType',
			'foreignKey' => 'projects_item_type_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
