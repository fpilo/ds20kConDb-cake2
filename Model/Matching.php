<?php
App::uses('AppModel', 'Model');
/**
 * Matching Model
 *
 * @property Parameter $Parameter
 */
class Matching extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'matching';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'maxLength' => array(
				'rule' => array('maxLength',64),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
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
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Parameter' => array(
			'className' => 'Parameter',
			'foreignKey' => 'parameter_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
