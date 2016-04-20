<?php
App::uses('AppModel', 'Model');
/**
 * MeasurementTag Model
 *
 * @property Measurement $Measurement
 */
class MeasurementTag extends AppModel {

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
		'Measurement' => array(
			'className' => 'Measurement',
			'joinTable' => 'measurement_tags_measurements',
			'foreignKey' => 'measurement_tag_id',
			'associationForeignKey' => 'measurement_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);

	public function getTags()
	{
		$measurement_tag_ids = $this->find("list",
				array(
					"fields"=>array("MeasurementTag.id","MeasurementTag.name"),
					"recursive"=>1
				)
			);
		return $measurement_tag_ids;
	}

}
