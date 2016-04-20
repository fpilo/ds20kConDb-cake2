<?php
require("fileContainer.php");
/**
 *
 */
class apvdaq extends fileContainer{

	public $apvData = array();
	private $stripData = array();
	private $sensorData = array();
	private $calData = array();
	private $calvsepData = array();
	private $preview = true;
	private $measurementType = null;


	public function __construct($fileType,$fileName)
	{
		parent::__construct($fileType,$fileName);
		$this->readParameterData();
		$this->setMeasurementType("strips");

	}

	public function readAllData(){
		$this->preview = false;
		$this->readParameterData();
	}

	private function removeCommentAndTrim($string,$commentsign="#"){
		$commentstart = strpos($string, $commentsign);
		if($commentstart>0){
			return trim(substr($string, 0,$commentstart));
		}else{
			return trim($string);
		}
	}
	/**
	 * Tries to find the requested section in the file sets the file pointer in the following row
	 * @return bool Success value (true/false)
	 */

	public function setFilepointerToSectionStart($section)
	{
		$this->resetFilepointerToStart();
		while($string = fgets($this->fp)){
			$sectionDescriptor = "none";
			$tmp = $this->removeCommentAndTrim($string);
			if(strpos($tmp, "[")!==false){
				$sectionDescriptor = substr($tmp,1,-1);
			}
			if($sectionDescriptor == $section) return true;

		}
		return false;
	}

	public function getCurrentDataRow()
	{
		return fgets($this->fp);
	}
	/**
	 * reads the Parameter definitions of the apvdaq file
	 */
	 private function readParameterData()
	 {
	 	/**
		 * iterate through the whole file and extract the parameters
		 */
		$this->resetFilepointerToStart();
		$data = array();
#		echo (memory_get_usage()/(pow(1024,2)))."MB \n";
#	 	for($i=0;$i<10;$i++){
		while($string = fgets($this->fp)){
			$sectionDescriptor = "none";
			$tmp = $this->removeCommentAndTrim($string);
			if($tmp == "") continue; //Skip empty rows
			if(strpos($tmp, "[")!==false){
				$sectionDescriptor = substr($tmp,1,-1);
				switch ($sectionDescriptor) {
					case 'info':
						$this->_info();
						break;
					case 'apv25':
						$this->_apv();
						break;
					case 'strips':
						$this->_strips();
						break;
					case 'sensor':
						$this->_sensor();
						break(2); //sensor is the last section in a hardware run file, break here out of the file loop (makes preview a lot faster and has no negative effect on the import)
					case 'intcal':
						$this->_intcal();
						break(2); //intcal is the last section in a cal file, break here (makes preview a lot faster)
					case 'calvsep':
						$this->_calvsep();
						break(2); //intcal is the last section in a cal file, break here (makes preview a lot faster)
					default:
						continue;
				}
			}
		}
	 }
	 /**
	  * Returns all col names to check for parameter_id and if necessary add to database
	  */
	 public function getUsedCols(){

	 	$return = array();
	 	foreach($this->stripData["colHeader"] as $colName){
	 		if(!in_array($colName, $return))
				$return[] = $colName;
	 	}
		if(isset($this->sensorData["colHeader"])){
		 	foreach($this->sensorData["colHeader"] as $colName){
		 		if(!in_array($colName, $return))
					$return[] = $colName;
		 	}
		}
		if(isset($this->calData["colHeader"])){
		 	foreach($this->calData["colHeader"] as $colName){
		 		if(!in_array($colName, $return))
					$return[] = $colName;
		 	}
		}
		return $return;
	 }

	 protected function setMeasurementPairs(){
	 	switch ($this->fileType) {
			case 'apvdaq-sw':
				return array("strips");
			case 'apvdaq-hw':
				return array("strips","sensor");
			case 'apvdaq-cal':
				return array("strips","intcal");
			case 'apvdaq-cvs':
				return array("strips","calvsep");
			default:
				return array();
		}
	}
	/**
	 * Returns a descriptive Name for the Measurement e.g. Strip Measurement, CIV Measurement, ...
	 */
	public function getMeasurementName(){
		switch($this->fileType){
			case 'apvdaq-sw':
				return "APVDAQ Software Run";
			case 'apvdaq-hw':
				return "APVDAQ Hardware Run";
			case 'apvdaq-cal':
				return "APVDAQ Calibration Run";
			case 'apvdaq-cvs':
				return "APVDAQ IntCal vs. Vsep";
			default:
				return "Not recognized Measurement";
		}
	}
	/**
	 * Overwrite implementation of this function to accept a section in the file as parameter and return the header corresponding to this.
	 * @param $pair string A string defining a measurement type that then allows to return a correct header:
	 * @return array An array containing the column names for the requested measurement
	 */
	public function getHeaderForMeasurementPair($pair)
	{
		$this->setMeasurementType($pair);
		return $this->colNames;
	}

	public function getDataForMeasurementPair($pair)
	{
		$this->setMeasurementType($pair);
		return $this->data;
	}


	public function getPreviewRows($num=5)
	{
		return array_slice($this->data, 0,$num);
	}

	public function setMeasurementType($measurementType)
	{
		$this->measurementType = $measurementType;
		switch ($measurementType) {
			case 'sensor':
				$this->colNames = &$this->sensorData["colHeader"];
				$this->data = &$this->sensorData["data"];
				break;
			case 'calvsep':
				$this->colNames = &$this->calvsepData["colHeader"];
				$this->data = &$this->calvsepData["data"];
				break;
			case 'intcal':
				$this->colNames = &$this->calData["colHeader"];
				$this->data = &$this->calData["data"];
				break;
			case 'strips':
				$this->colNames = &$this->stripData["colHeader"];
				$this->data = &$this->stripData["data"];
				break;

			default:
				debug("measurement type not recognized, this should not happen");
				return false;
		}
	}

	private function _info(){
		//Iterate through the lines until the newline is reached and put the data into the correct class fields
		$i=0;
		while($string = fgets($this->fp)){
			$i++;
			if(trim($string)=="")break;
			if($i==1) continue; //Only column description, not interesting for this part
			if($i==2){ //Info
				$tmp = explode(",",$string);
				$this->parameters['timeStart'] = strtotime($tmp[0]);
				$this->parameters['timeStop'] = strtotime($tmp[1]);
				$this->parameters['runType'] = $tmp[2];
				//Trying to get the itemCode out of the fileName
				$fileNameExplode = explode("_", $tmp[4]);
				$this->parameters["itemCode"] = $fileNameExplode[0];
				$eventCount = intval($tmp[3]);
				$fileName = $this->removeCommentAndTrim($tmp[4]);
				$configFile = $this->removeCommentAndTrim($tmp[5]);
				$apvdaqVersion = $this->removeCommentAndTrim($tmp[6]);
				if(isset($tmp[7])){
					$operator = $this->removeCommentAndTrim($tmp[7]);
					if($operator == "")
					    $operator = " ";
				}else{
				        $operator = " ";
				}
				if(isset($tmp[8])){
				        $sensor = $this->removeCommentAndTrim($tmp[8]);
				        if($sensor == "") #Check if sensor is empty string and if yes set it to "Unknown"
			                $sensor = " ";
				}else{
				        $sensor = " ";
				}
			}
		}
		$this->parameters["device_id"] = 3; #TODO: This is a fixed value for the APVDAQ. Needs to be set in the database and maybe be editable

		$this->parameters["MeasurementParameters"] = array(
			array("Parameter"=>array("name"=>"Event count"),"value"=>$eventCount),
			array("Parameter"=>array("name"=>"File name"),"value"=>$fileName),
			array("Parameter"=>array("name"=>"Configuration file"),"value"=>$configFile),
			array("Parameter"=>array("name"=>"APVDAQ Version"),"value"=>$apvdaqVersion),
			array("Parameter"=>array("name"=>"Operator"),"value"=>$operator),
			array("Parameter"=>array("name"=>"Sensor"),"value"=>$sensor),
			);
	}
	private function _apv(){
		#
		if($this->preview){
			$this->preview = false; //Deactivate preview shortly to allow to read all rows for adding to queue
			$this->apvData = $this->_getCsv();
			$this->preview = true;
		}else{
			$this->apvData = $this->_getCsv();
		}
	}
	private function _strips(){
		#
		$this->stripData = $this->_getCsv();
	}
	private function _intcal(){
		#
		$this->calData = $this->_getCsv();
	}
	private function _calvsep(){
		#
		$this->calvsepData = $this->_getCsv();
	}
	private function _sensor(){
		#
		$this->sensorData = $this->_getCsv();
	}

	private function _getCsv(){
		$numRows = ($this->preview) ? 5 : 0 ;# If preview is true only read 5 rows, otherwise read all
		//Get header for reference and create assoc array with content of the following rows until empty line
		$i=0;
		$colHeader = array();
		$data = array();
		while($string = fgets($this->fp)){
			$i++;
			if(trim($string)=="") break;
			if($i==1){
				$colHeader = array_map('trim',explode(",",$string));
			}else{
				$data[] = $this->interpretCsvRow($string);
			}
			if($numRows != 0 && $i>$numRows) break;
		}
		return array("colHeader"=>$colHeader,"data"=>$data);
	}

	public function interpretCsvRow($string)
	{
		$tmp = array_map('trim',explode(",",$string));
		foreach($tmp as $id=>$value){
			if(is_numeric($value)){
				$value = strval($value);
				if(ctype_digit($value)){
					$tmp[$id] = intval($value);
				}else{
					$tmp[$id] = floatval($value);
				}
			}
		}
		return $tmp;
	}
}
?>
