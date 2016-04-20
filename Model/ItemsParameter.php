<?php
App::uses('AppModel', 'Model');
/**
 * ItemsParameter Model
 *
 * @property Item $Item
 * @property Parameter $Parameter
 */
class ItemsParameter extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'value';
	public $order = 'ItemsParameters.value ASC';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'item_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'parameter_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'value' => array(
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
		'Item' => array(
			'className' => 'Item',
			'foreignKey' => 'item_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Parameter' => array(
			'className' => 'Parameter',
			'foreignKey' => 'parameter_id',
			'conditions' => '',
			'fields' => 'name',
			'order' => ''
		)
	);

	public function addParameter($parameterName)
	{
		//Adds a parameter to the table and returns its id, if the parameter already exists it just returns the id
		return ($this->Parameter->save(array("Parameter.name"=>$parameterName))) ? $this->getLastInsertID() : $this->Parameter->getByName($parameterName);
	}

	public function addParameterToItem($itemId,$parameterData){
		$this->clear();
		if(!isset($parameterData["timestamp"])){
			$parameterData["timestamp"] = null;
		}
		$saveArray = array("item_id"=>$itemId,"parameter_id"=>$parameterData["parameter"],"value"=>$parameterData["value"],"comment"=>$parameterData["comment"],"timestamp"=>$parameterData["timestamp"]);
		return ($this->save($saveArray))? $this->getLastInsertID() : false;

	}
}
