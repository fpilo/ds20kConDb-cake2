<?php
App::uses('AppModel', 'Model');
/**
 * ClState Model
 *
 * @property Item $Item
 */
class ClState extends AppModel {
	public $order = 'ClState.name ASC';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

/**
 * hasOne associations
 *
 * @var array
 */
 	// public $hasOne = array(
		// 'ClAction' => array(
			// 'className' => 'ClAction',
			// 'foreignKey' => 'cl_action_id',
			// 'dependent' => true,
			// 'conditions' => '',
			// 'fields' => '',
			// 'order' => ''
		// )
	// );

}
