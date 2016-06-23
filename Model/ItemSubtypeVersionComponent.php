<?php
App::uses('AppModel', 'Model');
/**
 * ItemSubtypeVersionComposition Model
 *
 */
class ItemSubtypeVersionComponent extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'item_subtype_versions_compositions';
	
/**
 * Primary key field
 *
 * @var string
 */
 //	public $primaryKey = 'item_type_id';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'position_name';
	
/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'ItemSubtypeVersion' => array(
			'className' => 'ItemSubtypeVersion',
			'foreignKey' => 'component_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'project_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	public function __construct($id = false, $table = null, $ds = null) {
    parent::__construct($id, $table, $ds);
    $this->virtualFields['position_numeric'] = sprintf('CAST(%s.position as UNSIGNED)', $this->alias);
	}
	
}
