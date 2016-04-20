<?php
require("fileContainer.php");
/**
 *
 */
class ascii extends fileContainer{

	public function __construct($fileType,$fileName)
	{
		parent::__construct($fileType,$fileName);
		$this->readParameterData();

	}

	/**
	 * reads the Parameter definitions of the ascii file according to the different specifications and stores the values in the $parameters array
	 */
	 private function readParameterData()
	 {
		$this->resetFilepointerToStart();
	 	//Read first 10 lines of the file to get the header definitions
	 	$data = array();
		$this->parameters["MeasurementParameters"] = array();

	 	for($i=0;$i<20;$i++){
			$data[$i] = trim(fgets($this->fp));
		}

//		print_r($data);

	 	if(in_array($this->fileType, array("ascii-str","ascii-civ"))){
	 		//Strip or CVI Measurement, import header as follows
			//Do stuff that is the same for these files
			$tmp = explode("' ",trim(str_replace("*", "", $data[3])));
			foreach($tmp as $field){
				if($field[0] == "D"){
					//Fourth row check after "Detector name" for the identifier string
					//Found Detector Name, save it to array
					$this->parameters["itemCode"] = substr($field, strpos($field, "'")+1);
					//Search for detector in database and inform the user if the detector was found or not.
				}elseif($field[0] == "t"){
					//Fourth row check after "type" for the sensor type
					//Found Sensor type, save it to array
					$this->parameters["sensorType"] = substr($field, strpos($field, "'")+1);
				}
			}
			//For the start and stop times use regex
			preg_match_all("/<.*?>/", $data[4],$tmp);
			if(count($tmp[0])==2){
				//correct number of time elements, convert them to numbers and then save the lower value as starting value
				$t1 = strtotime(substr($tmp[0][0],1,-1));
				$t2 = strtotime(substr($tmp[0][1],1,-1));
				if($t1<$t2){
					$this->parameters["timeStart"] = $t1;
					$this->parameters["timeStop"] = $t2;
				}else{
					$this->parameters["timeStart"] = $t2;
					$this->parameters["timeStop"] = $t1;
				}
			}else{
				//Could not correctly detect start and end time
				debug($tmp);
			}

	 	}elseif(in_array($this->fileType,array("ascii-it","ascii-iv"))){
	 		$this->parameters["itemCode"] = trim(substr($data[1], strpos($data[1], ":")+1));
			$tmp = explode(" ",substr($data[3],strpos($data[3],":")+2));
			$day = substr($tmp[0],0,2);
			$month = substr($tmp[0],2,2);
			$year = "20".substr($tmp[0],4,2);
			$hour = substr($tmp[1],0,2);
			$min = substr($tmp[1],2,2);
			$t1 = strtotime($year."-".$month."-".$day." ".$hour.":".$min);
			$this->parameters["timeStart"] = $t1;
			$this->parameters["MeasurementParameters"][] = array("Parameter"=>array("name"=>"Voltage [V]"),"value"=>trim(substr($data[4],strpos($data[4], ":")+strlen(":"))));
			$this->parameters["MeasurementParameters"][] = array("Parameter"=>array("name"=>"Operator"),"value"=>trim(substr($data[2],strpos($data[2], ":")+strlen(":"))));
			if($this->fileType == "ascii-iv")
				$this->parameters["timeStop"] = $t1;
			elseif($this->fileType == "ascii-it"){
				//Get the seconds value of the last row
				$file = escapeshellarg($this->fileName); // for the security concious (should be everyone!)
				$line = `tail -n 1 $file`;
				$tmp = explode("\t",$line);
				$secondsSinceStart = floatval($tmp[0]);
				$this->parameters["timeStop"] =$this->parameters["timeStart"]+$secondsSinceStart;
			}

		}elseif(in_array($this->fileType,array("ascii-pxd"))){
			$this->parameters["measurementName"] = trim(substr($data[0],1));
			#Loop over the rows, adding them to the parameters["MeasurementParameters"] array as long as no row is empty
			for($i=1;$i<count($data);$i++){
				if(trim($data[$i]) == ""){
					$this->firstDataRow = $i+2; //Store the first data row as the one two after the empty one, the next one is the header row
					break;
				}
				$tmp = explode("\t",$data[$i]);
				$this->parameters["MeasurementParameters"][] = array("Parameter"=>array("name"=>trim($tmp[0])),"value"=>trim($tmp[1]));
			}

	 	}else{
	 		debug($data);
	 	}


		//Do stuff that is specific to each file
	 	switch ($this->fileType) {
			 case 'ascii-str':
				//Second row contains colnames
				$this->colNames = explode("\t",$data[1]);
				//Define measurement pairs with a corresponding key
				$this->firstDataRow = 6;  //First row of measurement data for this file type
				$this->detectEmptyCols();
				break;

			 case 'ascii-civ':
				$this->firstDataRow = 7; //First row of measurement data for this file type
				//Second row contains colnames
				$tmp = explode("\t",$data[1]);
				$this->colNames = array($tmp[0],$tmp[2],$tmp[0],$tmp[1]);
				//Third row contains temperatures
				$this->parameters["MeasurementParameters"][] = array("Parameter"=>array("name"=>"tempBefore"),"value"=>substr($data[2],strpos($data[2], "before")+strlen("before:"),9));
				$this->parameters["MeasurementParameters"][] = array("Parameter"=>array("name"=>"tempAfter"),"value"=>substr($data[2],strpos($data[2], "after") +strlen("after:"), 9));
				break;

			 case 'ascii-it':
				$this->firstDataRow = 7; //First row of measurement data for this file type
				//Seventh row contains colnames
				$tmpColNames = explode("\t",$data[6]);
				//Need to replace the degree symbol in temperature due to some weird caracter
				foreach($tmpColNames as $id=>$name){
					if(strpos($name,"temperature")> -1)
						$tmpColNames[$id] = "temperature [C]";
				}
				$this->colNames = $tmpColNames;
				break;

			 case 'ascii-iv':
				$this->firstDataRow = 6; //First row of measurement data for this file type
				//Seventh row contains colnames
				$this->colNames = array("voltage [V]","current [V]");
				break;
			 case 'ascii-pxd':
			 	$this->colNames = explode("\t",$data[$this->firstDataRow-1]);

			 	break;
			 default:
				 debug("Filetype ".$this->fileType." requires configuration here.");

				 break;
		}
 		// debug($this->parameters);
		// debug($this->colNames);
	 }
	 protected function setMeasurementPairs(){
	 	switch ($this->fileType) {
			case 'ascii-str':
				$this->parameters["device_id"] = 2;
				return $this->getFullMeasurement();
				break;
			case 'ascii-civ':
				$this->measurementPairs = array(array(0,1),array(2,3));
				$this->parameters["device_id"] = 1;
				return $this->getAllMeasurementPairs();
				break;
			case 'ascii-it':
				$this->parameters["device_id"] = 4;
				return $this->getFullMeasurement();
				break;
			case 'ascii-iv':
				$this->parameters["device_id"] = 5;
				return $this->getFullMeasurement();
				break;
			case 'ascii-pxd':
				$this->parameters["device_id"] = 5;
				return $this->getFullMeasurement();
				break;
		}
	}
	/**
	 * @param $pair array Array containing the measurement pair
	 * @return string Containing a written description of the measurement type depending on the cols and the file type
	 */
	public function getMeasurementTypeForPair($pair){
		foreach($pair as $field){
			echo $this->colNames[$field];
		}
	}
	/**
	 * Returns a descriptive Name for the Measurement e.g. Strip Measurement, CIV Measurement, ...
	 */
	public function getMeasurementName(){
		switch($this->fileType){
			case 'ascii-str':
				return "Strip Measurement: ";
			case 'ascii-civ':
				return "CIV Measurement";
			case 'ascii-it':
				return "It Measurement";
			case 'ascii-iv':
				return "IV Measurement";
			default:
				if(isset($this->parameters["measurementName"])){
					return $this->parameters["measurementName"];
				}
				return "Not recognized Measurement";
		}
	}
}
?>