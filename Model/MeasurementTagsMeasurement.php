<?php
App::uses('AppModel', 'Model');
/**
 * MeasurementTagsMeasurement Model
 *
 * @property Measurement $Measurement
 * @property MeasurementTag $MeasurementTag
 */
class MeasurementTagsMeasurement extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Measurement' => array(
			'className' => 'Measurement',
			'foreignKey' => 'measurement_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'MeasurementTag' => array(
			'className' => 'MeasurementTag',
			'foreignKey' => 'measurement_tag_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function getTagsForMeasurementId($measurementId)
	{
		$measurement_tag_ids = $this->find("list",
				array(
					"fields"=>array("MeasurementTag.id","MeasurementTag.name","measurement_id"),
					"conditions"=>array("measurement_id"=>$measurementId),
					"recursive"=>1
				)
			);
		return $measurement_tag_ids;
	}
}
