<?php
App::uses('AppModel', 'Model');
/**
 * ClTemplate Model
 *
 * @property Template $Template
 */
class ClTemplate extends AppModel {
	public $order = 'ClTemplate.name ASC';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please specify the name of the template.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'cl_action_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'default' => array(
			'checkUnique' => array(
				'rule' => array('checkUnique'),
				'message' => 'The default template already exists.',
				//'on' => null
			),
		)
	);

	public function checkUnique() {

		if($this->data["ClTemplate"]["default"] == 1){
			$condition = array(
				"ClTemplate.item_subtype_id" => $this->data["ClTemplate"]["item_subtype_id"],
				"ClTemplate.default" => $this->data["ClTemplate"]["default"]
			);
			if(isset($this->data["ClTemplate"]["id"])){
				$condition["ClTemplate.id <>"] = $this->data["ClTemplate"]["id"];
			}
			$result = $this->find("count", array("conditions" => $condition));
		}
		else $result = 0; //do not checkUnique is default is not set

		return ($result == 0);

	}

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'ItemSubtype' => array(
			'className' => 'ItemSubtype',
			'foreignKey' => 'item_subtype_id'
			// 'conditions' => '',
			// 'type' => '',
			// 'fields' => '',
			// 'order' => '',
			// 'counterCache' => '',
			// 'counterScope' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'ClAction' => array(
			'className' => 'ClAction',
			'foreignKey' => 'cl_template_id',
			// 'conditions' => '',
			// 'order' => '',
			// 'limit' => '',
			// 'offset' => '',
			'dependent' => true,
			'exclusive' => true
			// 'finderQuery' => ''
		)
	);

}
