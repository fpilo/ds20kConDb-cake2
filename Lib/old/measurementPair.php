<?php
/**
 * describes a measurement and the data measured.
 * @param string $measurementType A string representation of the measurement type e.g. cv,iv, strip, ...
 * @opt_param array $params An optional array containing parameters to be set for the measurement
 */
class measurementPair{

	private	$measurementType = "";
	private $data = array();
	private $cols = array();
	private $emptyCols = array();
	private $parameters;
	private $parameters_ids;


	public function __construct($params=array()) {
		$this->parameters = array("timeStart"=>time(),"timeStop"=>time());
		$this->setParameters($params);
	}

	/**
	 * Uses the header to construct a String representation of the Measurement Type
	 */
	private function setMeasurementType(){
		$this->measurementType = "";
		$tmp[] = $this->cols[0];
		$tmp[] = implode(" ",array_slice($this->cols, 1));
 		$this->measurementType = implode(" vs. ", $tmp);
		if(in_array("Line", $this->cols) && in_array("Pad", $this->cols))
	 		$this->measurementType = "Strip measurement"; //If both Line and Pad are in the cols array assume it is a Strip measurement
		if(in_array("time [sec]", $this->cols) && in_array("current corr [A]", $this->cols))
	 		$this->measurementType = "It measurement"; //If both Line and Pad are in the cols array assume it is a It measurement
		if($this->cols[0]=="Chip" && $this->cols[2]=="Pedestal")
	 		$this->measurementType = "APV Strip Measurement";
		if($this->cols[0]=="Chip" && $this->cols[2]=="Time")
	 		$this->measurementType = "APV Calibration Measurement";
		if($this->cols[0]=="Hybrid" && $this->cols[1]=="Bin")
	 		$this->measurementType = "APV Sensor Histogram";
		if($this->cols[0]=="Chip" && $this->cols[2]=="Vsep")
	 		$this->measurementType = "APV IntCal vs. Vsep Measurement";
		if(isset($this->parameters["measurementName"])){
			$this->measurementType = $this->parameters["measurementName"];
		}
	}

	public function getStartTime(){
		return $this->parameters["timeStart"];
	}
	public function getStopTime(){
		return $this->parameters["timeStop"];
	}
	public function getColnames(){
		return;
	}
	/**
	 * @opt_param int $num Number of data rows to be returned
	 */

	public function getData($num = 0){
		return;
	}
	public function getMeasurementType(){
		return $this->measurementType;
	}

	/**
	 * Sets the parameter_ids array to add the parameter id to each measuring point
	 * @param array $parameter_ids Array containing all parameters stored in the database as a $parameter_name=>$parameter_id pair
	 */
	public function setParameterIds($parameter_ids)
	{
		$this->parameters_ids = array_flip($parameter_ids);
	}
	/**
	 * Sets the header for the measurements, can contain fields that won't be displayed
	 * @param array $header Array containing the header fields
	 */
	public function setColNames($header = array())
	{
		if(count($header)>1 && count($this->cols)==0){
			$this->cols = $header;
		}else{
			//Header array is empty or header is already set
			return false;
		}
		$this->setMeasurementType();
	}
	/**
	 * Adds a row of data to the measurement
	 * @param array $dataArray An array of data containing a row in the measurement
	 */
	public function addDataRow($dataArray){
		if(count($this->data) == 0){ //No data set yet, add this array
			if(count($dataArray)>1 && count($dataArray) == count($this->cols)){ //Data Array contains at least two columns and has equal number of cols as the header, store data in the data array
				$this->data[] = $dataArray;
			}
		}elseif(count($this->data[0]) == count($dataArray)){ //Number of cols equals, store data in the data array
				$this->data[] = $dataArray;
		}else{ //Number of cols doesn't match already stored data, cannot add different number of cols to already existing data
			return false;
		}
	}

	/**
	 * Sets multiple parameters for a measurement
	 * @param array $params an array of key=>value pairs to be set as parameters
	 */
	public function setParameters($params = array())
	{
		foreach($params as $param=>$value){
			$this->setParameter($param, $value);
		}
	}
	/**
	 * Sets a parameter for a measurement
	 */
	public function setParameter($param,$value)
	{
		$this->parameters[$param] = $value;
	}

	/**
	 *
	 * @param string $param The parameter to be returned
	 * @return string The value for the requested param or FALSE if not found
	 */
	public function getParameter($param)
	{
		return (isset($this->parameters[$param])) ? $this->parameters[$param] : False ;
	}
	/**
	 * Returns all parameters set for the measurement
	 * @return array Parameter=>Value Pairs set for the measurement
	 */
	 public function getAllParameters()
	 {
		return $this->parameters;
	 }
	 public function getAllMeasuringPoints(){
	 	$return = array();
		//Add timeStart and timeStop as measuring points for the measurement <-- deactivated because start and stop are already saved in the measurements object
#		$return[] = array(array('value'=>$this->parameters["timeStart"],'parameter_name'=>"timeStart",'parameter_id'=>$this->parameters_ids["timeStart"]),
#						  array('value'=>$this->parameters["timeStop"], 'parameter_name'=>"timeStop", 'parameter_id'=>$this->parameters_ids["timeStop"]));
	 	foreach($this->data as $dataPair){
	 		$return[] = $this->getMeasurementArrayForDataRow($dataPair);
	 	}
		return $return;
	 }
	public function getMeasurementArrayForDataRow($dataPair)
	{
 		$tmp = array();
 		foreach($dataPair as $id=>$data){
 			if(empty($this->parameters_ids[$this->cols[$id]])){
 				debug($this->cols);
				debug($this->parameters_ids);
				debug($id);
				throw new NotFoundException(__('There are some measuring points with new parameter names which are not defined in the parameters array.'));
 				//Could not find a parameter id for this col name in the database, error!!!!
 			}
 			$tmp[] = array('value'=>$data,'parameter_name'=>$this->cols[$id],'parameter_id'=>$this->parameters_ids[$this->cols[$id]]);
 		}
		return $tmp;
	}
}


?>