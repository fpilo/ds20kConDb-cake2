<?php
App::uses('AppModel', 'Model');
/**
 * Reading Model
 *
 * @property MeasuringPoint $MeasuringPoint
 * @property Parameter $Parameter
 */
class Reading extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'value';

	public $parameterX = "";
	public $parameterY = "";

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'measuring_point_id' => array(
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
		'MeasuringPoint' => array(
			'className' => 'MeasuringPoint',
			'foreignKey' => 'measuring_point_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Parameter' => array(
			'className' => 'Parameter',
			'foreignKey' => 'parameter_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author
	 */
	function getMeasurementByMeasuringpointId($measurementId)
	{

		$filename = "measurement_".$measurementId;
		$data = Cache::read($filename, "measurement");
		if(!$data){
			$data = $this->find("all",array("conditions"=>array("MeasuringPoint.measurement_id"=>$measurementId)));
			Cache::write($filename, $data, 'measurement');
		}
		return $data;
	}

	/**
	 * method getChips
	 * @param $measurementId: integer measurement id
	 *
	 * @return array containing the integer values of the chips in this measurement
	 */

	public function getChips($measurementId)
	{
		$filename = "measurement_".$measurementId."_chips";
		$chips = Cache::read($filename, "measurement");
		if(!$chips){
			$chips = $this->find("all",array(
						"conditions"=>array("MeasuringPoint.measurement_id"=>$measurementId,"Reading.parameter_id"=>$this->getChipParameterId()),
						"fields"=>array("DISTINCT Reading.value"),
					));
			Cache::write($filename, $chips, 'measurement');
		}
		return $chips;
	}

	public function getChipRange($measurementId,$chip)
	{
		$filename = "measurement_".$measurementId."_chip_".$chip."_range";
		$range = Cache::read($filename, "measurement");
		if(!$range){
			$range = $this->find("all",array(
						"conditions"=>array("MeasuringPoint.measurement_id"=>$measurementId,"Reading.parameter_id"=>$this->getChipParameterId(),"Reading.value"=>$chip),
						"fields"=>array("DISTINCT Reading.measuring_point_id"),
					));
			Cache::write($filename,$range,"measurement");
		}
		$stripRange = array();
		foreach($range as $reading){
			$stripRange[] = $reading["Reading"]["measuring_point_id"];
		}
		return $stripRange;
	}

	public function getStrips($measurementId,$chip)
	{
		$filename = "measurement_".$measurementId."_chip_".$chip."_strips";
		$strips = Cache::read($filename, "measurement");
		if(!$strips){
			$strips = $this->find("all",array(
						"conditions"=>array("Reading.measuring_point_id"=>$this->getChipRange($measurementId, $chip),"Reading.parameter_id"=>$this->getStripParameterId()),
						"fields"=>array("DISTINCT Reading.value"),
					));
			Cache::write($filename,$strips,"measurement");
		}
		$selectStrips = array();
		foreach($strips as $strip){
			$selectStrips[$strip["Reading"]["value"]] = $strip["Reading"]["value"];
		}
		return $selectStrips;
	}


	public function getStripRange($measurementId,$chip,$strip)
	{
		$filename = "measurement_".$measurementId."_chip_".$chip."_strip_".$strip."_range";
		$range = Cache::read($filename,"measurement");
		if(!$range){
			$range = $this->find("all",array(
						"conditions"=>array(
									"Reading.measuring_point_id"=>$this->getChipRange($measurementId, $chip),
									"Reading.parameter_id"=>$this->getStripParameterId(),
									"Reading.value"=>$strip
									),
						"fields"=>array("DISTINCT Reading.measuring_point_id"),
					));
			Cache::write($filename, $range, 'measurement');
		}
		$stripRange = array();
		foreach($range as $reading){
			$stripRange[] = $reading["Reading"]["measuring_point_id"];
		}
		return $stripRange;

	}

	public function getPossibleParameters($measurementId){
		$filename = "measurement_".$measurementId."_parameters";

		$values = Cache::read($filename, "measurement");
		if(!$values){
			$values = $this->query("SELECT DISTINCT Parameter.name FROM `measurements` AS Measurement
									LEFT JOIN `measuring_points` AS MeasuringPoint ON (Measurement.id = MeasuringPoint.measurement_id)
									LEFT JOIN `readings` AS Reading ON (MeasuringPoint.id = Reading.measuring_point_id)
									LEFT JOIN `parameters` AS Parameter ON (Reading.parameter_id = Parameter.id) WHERE Measurement.id = $measurementId");
			Cache::write($filename, $values, 'measurement');
		}
		return $values;
	}

	public function getStripValues($measurementId,$chip,$strip)
	{
		$valueParameterNames = $this->Parameter->find("list",array('fields'=>array("Parameter.id","Parameter.name"),'conditions' => array('name' => array($this->parameterX,$this->parameterY))));
		$valueParameterIds = array_keys($valueParameterNames);
		$valueParameterNames = array_flip($valueParameterNames);
		$sortOrder = ($valueParameterNames[$this->parameterX]<$valueParameterNames[$this->parameterY]) ? "ASC" : "DESC" ;

		$filename = "measurement_".$measurementId."_chip_".$chip."_strip_".$strip."_values";
		if(!file_exists($filename))
			$filename .= "_".$this->parameterX."_".$this->parameterY;


		$values = Cache::read($filename, "measurement");
		if(!$values){
			$values = $this->find("all",array(
						"conditions"=>array("Reading.parameter_id"=>$valueParameterIds,
											"Reading.measuring_point_id"=>$this->getStripRange($measurementId, $chip, $strip)
											),
						"fields"=>array("Reading.measuring_point_id","Reading.parameter_id","Reading.value","MeasuringPoint.id","Parameter.name","Parameter.id"),
						"order"=> array("Reading.measuring_point_id ASC","Reading.parameter_id $sortOrder"),
					));
			Cache::write($filename, $values, 'measurement');
		}
		return $values;

	}

	public function getStripValuesFormatted($measurementId,$chip,$strip)
	{
		$values = $this->getStripValues($measurementId, $chip, $strip);
		$plotValues = array();
		foreach($values as $value){
			$plotValues[$value["Reading"]["measuring_point_id"]][] = $value["Reading"]["value"];
		}
		$label = sprintf("Calibration Curve for Strip %s on Chip %s",$strip,$chip);
		return array("label"=>$label,"data"=>array_values($plotValues));

	}

	public function getChipParameterId()
	{
		$tmp = $this->Parameter->find("list",array('fields'=>"Parameter.id",'conditions' => array('name' => "Chip")));
		sort($tmp);
		return $tmp[0];
	}
	public function getStripParameterId()
	{
		$tmp = $this->Parameter->find("list",array('fields'=>"Parameter.id",'conditions' => array('name' => "Strip")));
		sort($tmp);
		return $tmp[0];
	}
	}
