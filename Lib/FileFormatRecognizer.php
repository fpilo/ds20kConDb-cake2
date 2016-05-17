<?php

App::uses('fileReader','Lib');

/**
 *	To add a format definition one needs to do (at least) three things:
 * 	1. Add a unique description key to the $possibleFormats array with a unique method name as value
 * 	2. Add the checking method functionality, the name should start with: _is, that returns true if the format of the first few rows matches the definition of this file format
 * 	3. Add a conversion method to the $convertFunctions array with the same unique description key and a way for the system to call the conversion method. 
 */
class FileFormatRecognizer extends fileReader{

	protected $originalFilePath = "";
	protected $convertedFilePath = "";
	protected $fileFormatIdentifier = "";
	private $outData;
	private $useCarriageReturn = false;
	
	protected $foundMatch = false;
	protected $match = "";
	
	private $possibleFormats = array(
		"csv"=>"_isCSV",
		"ascii-it" =>"_isAsciiIt",  //ASCII Long term It measurements (Current over Time)
		"ascii-iv" =>"_isAsciiIv",  //ASCII IV curves
		"ascii-iv2" =>"_isAsciiIv2",  //ASCII IV curves 2nd format
		"ascii-civ"=>"_isAsciiCiv", //ASCII IV and CV curves
		"ascii-civ"=>"_isAsciiCiv", //ASCII IV and CV curves
		"ascii-str"=>"_isAsciiStr", //ASCII Strip File
	);
	
	private $conversionFunctions = array(
		"csv"	=>"_convertCSV",
		"ascii-it" =>"_convertAsciiIt",
		"ascii-iv" =>"_convertAsciiIv",
		"ascii-iv2" =>"_convertAsciiIv2",
		"ascii-civ"=>"_convertAsciiCiv",
		"ascii-str"=>"_convertAsciiStr",
	);
	
	function __construct($inputFile){
		$this->originalFilePath = $inputFile;
		parent::__construct($inputFile);
		$formats = array();
		foreach($this->possibleFormats as $str=>$func){
			$formats[$str] = $this->$func();
		}
		foreach($formats as $str=>$correct){
			if($correct){
#				debug("found match ".$str);
				$func = $this->conversionFunctions[$str];
				$this->$func();
				$this->foundMatch = true;
				$this->match = $str;
				break;
			}
		}
		if($this->match != "csv"){ //Only save converted csv if the input wasn't a CSV.
			$this->storeAsCSV();
		}
	}
	
	private function _isCSV(){
		$firstRow = $this->getRows(0,1);
		if(substr($firstRow[0],0,6) == "[info]"){
			return true;
		}
		return false;
	}
	
	private function _convertCSV(){
		$this->convertedFilePath = $this->originalFilePath;
	}
	
	private function _isAsciiStr(){
		$rows = $this->getRows(0,8);
		if($rows[0][0] == "*" && $rows[1][0] == "*" && $rows[2][0] == "*" ){
			return true;
		}
		return false;
	}
	
	private function _convertAsciiStr(){
		$rows = $this->getRows(0,-1);
		$this->outData["info"] = array();
		$this->outData["tags"] = array();

		$tmp = explode("' ",trim(str_replace("*", "", $rows[1])));
		foreach($tmp as $field){
			if($field[0] == "D"){
				//Fourth row check after "Detector name" for the identifier string
				//Found Detector Name, save it to array
				$this->outData["info"]["ID"] = substr($field, strpos($field, "'")+1);
				//Search for detector in database and inform the user if the detector was found or not.
			}elseif($field[0] == "t"){
				//Fourth row check after "type" for the sensor type
				//Found Sensor type, save it to array
				$this->outData["info"]["sensorType"] = substr($field, strpos($field, "'")+1);
			}
		}
		//For the start and stop times use regex
		preg_match_all("/<.*?>/", $rows[2],$tmp);
		if(count($tmp[0])==2){
			//correct number of time elements, convert them to numbers and then save the lower value as starting value
			$t1 = date("d.m.Y H:i:s",strtotime(substr($tmp[0][0],1,-1)));
			$t2 = date("d.m.Y H:i:s",strtotime(substr($tmp[0][1],1,-1)));
			if($t1<$t2){
				$this->outData["info"]["StartDateTime"] = $t1;
				$this->outData["info"]["StopDateTime"] = $t2;
			}else{
				$this->outData["info"]["StartDateTime"] = $t2;
				$this->outData["info"]["StopDateTime"] = $t1;
			}
		}else{
			//Could not correctly detect start and end time
			debug($tmp);
		}
		$header = explode("\t",trim(str_replace("*", "", $rows[0])));
		$data = array();
		//Create array for automatic empty col removal:
		$empty = array_fill(0,count($header)+1,true);
		foreach($rows as $num=>$row){
			if($num<3) continue;
			$tmp = explode("\t",$row);
			foreach($tmp as $pos=>$value){
				$empty[$pos] = ($value != 0 && $empty[$pos])?false:$empty[$pos];
			}
		}
		foreach($rows as $num=>$row){
			if($num<3) continue;
			$tmp = explode("\t",$row);
			foreach($tmp as $pos=>$value){
				if($empty[$pos]){
					unset($tmp[$pos]);
					continue;
				}
			}
			$data[] = $tmp;
		}
		$Parameter = ClassRegistry::init('Parameter');
		$dbParameter = $Parameter->find("list");
		foreach($header as $id=>$colName){
			if($empty[$id]){
				unset($header[$id]);
				continue;
			}
			$paramId = array_search(trim($colName),$dbParameter);
			if($paramId != null){
				$header[$id] = array($paramId=>trim($colName));
			}
		}
		foreach($header as $id=>$value){
			$keys[] = array_keys($value)[0];
		}
		$measurement = new MeasurementData($header,$data);
		$this->outData["stripmeas"] = $measurement->getCols($keys)->getDataAsCSV();
		return true;
	}
	
	private function _isAsciiIt(){
		$rows = $this->getRows(0,8);
		if($rows[0][0] == "H" && $rows[1][0] == "s" && $rows[2][0] == "o" && $rows[3][0] == "s" && $rows[4][0] == "V" && $rows[5][0] == "t" ){
			return true;
		}
		return false;
	}
	
	private function _convertAsciiIt(){
		$rows = $this->getRows(0,-1);
		$this->outData["info"] = array();
		$this->outData["tags"] = array();

		$header = explode("\t",$rows[5]);
		//Need to replace the degree symbol in temperature due to some weird caracter
		foreach($header as $id=>$name){
			if(strpos($name,"temperature")> -1)
				$header[$id] = "temperature [C]";
		}
		$this->outData["info"]["ID"] = trim(substr($rows[1],strpos($rows[1],":")+1));
		$this->outData["info"]["Operator"] = trim(substr($rows[2],strpos($rows[2],":")+1));
		$startTime = trim(substr($rows[3],strpos($rows[3],":")+1));
		$this->outData["info"]["Voltage [V]"] = trim(substr($rows[4],strpos($rows[4],":")+1));
		//Reformat the weird date-time format of the file to a normal one by taking each separate value as its byte value
		$startTime = $startTime[0].$startTime[1].".".$startTime[2].$startTime[3].".20".$startTime[4].$startTime[5].$startTime[6].$startTime[7].$startTime[8].":".$startTime[9].$startTime[10];
		$this->outData["info"]["StartDateTime"] = $startTime;
		$data = array();
		foreach($rows as $num=>$row){
			if($num<6) continue;
			$tmp = explode("\t",$row);
			$data[] = array_map("trim",$tmp);
			$lastTime = $tmp[0];
		}
		$this->outData["info"]["StopDateTime"] = date("d.m.Y H:i",strtotime($this->outData["info"]["StartDateTime"])+$lastTime);
		$Parameter = ClassRegistry::init('Parameter');
		$dbParameter = $Parameter->find("list");
		foreach($header as $id=>$colName){
			$paramId = array_search(trim($colName),$dbParameter);
			if($paramId != null){
				$header[$id] = array($paramId=>trim($colName));
			}
		}
		$measurement = new MeasurementData($header,$data);
		foreach($header as $id=>$value){
			$keys[] = array_keys($value)[0];
		}
		$this->outData["itmeas"] = $measurement->getCols($keys)->getDataAsCSV();
		return true;
	}
	
	private function _isAsciiIv(){
		$rows = $this->getRows(0,8);
		if($rows[0][0] == "H" && $rows[1][0] == "s" && $rows[2][0] == "o" && $rows[3][0] == "s" && $rows[4][0] == "V" && is_numeric($rows[5][0])){
			return true;
		}
		return false;
	}

	private function _convertAsciiIv(){
		$rows = $this->getRows(0,-1);
		$this->outData["info"] = array();
		$this->outData["tags"] = array();

		$header = array("Voltage [V]","Current [nA]");
		$this->outData["info"]["ID"] = trim(substr($rows[1],strpos($rows[1],":")+1));
		$this->outData["info"]["Operator"] = trim(substr($rows[2],strpos($rows[2],":")+1));
		$startTime = trim(substr($rows[3],strpos($rows[3],":")+1));
		$this->outData["info"]["Voltage [V]"] = trim(substr($rows[4],strpos($rows[4],":")+1));
		//Reformat the weird date-time format of the file to a normal one by taking each separate value as its byte value
		$startTime = $startTime[0].$startTime[1].".".$startTime[2].$startTime[3].".20".$startTime[4].$startTime[5].$startTime[6].$startTime[7].$startTime[8].":".$startTime[9].$startTime[10];
		$this->outData["info"]["StartDateTime"] = $startTime;
		$this->outData["info"]["StopDateTime"] = $startTime;
		$data = array();
		foreach($rows as $num=>$row){
			if($num<5) continue;
			$tmp = explode("\t",$row);
			$data[] = array_map("trim",$tmp);
		}
		$Parameter = ClassRegistry::init('Parameter');
		$dbParameter = $Parameter->find("list");
#		debug($dbParameters);
		foreach($header as $id=>$colName){
			$paramId = array_search(trim($colName),$dbParameter);
			if($paramId != null){
				$header[$id] = array($paramId=>trim($colName));
			}
		}
#		debug($header);
		$measurement = new MeasurementData($header,$data);
		foreach($header as $id=>$value){
			$keys[] = array_keys($value)[0];
		}
#		debug($measurement);
		$this->outData["ivmeas"] = $measurement->getCols($keys)->getDataAsCSV();
		return true;
	}
	
	private function _isAsciiIv2(){
		$rows = $this->getRows(0,5);
		if($rows[0][0] == "I" && $rows[0][1] == "V" && $rows[2][0] == "V" && $rows[2][1] == "o"){
			return true;
		}
		return false;
	}
	
	private function _convertAsciiIv2(){
		$rows = $this->getRows(0,-1);
		$this->outData["info"] = array();
		$this->outData["tags"] = array();
		
		$tmp = array_map("trim",explode("  ",$rows[2]));
		$header = array($tmp[0],$tmp[1]);
		$this->outData["info"]["ID"] = false;
		$startTime = trim($rows[1]);
		foreach($tmp as $bla){
			if(strpos($bla,": ")!== false){
				$tmp2 = explode(": ",$bla);
				$this->outData["info"][$tmp2[0]] = $tmp2[1];
			}
		}
		$this->outData["info"]["StartDateTime"] = $startTime;
		$this->outData["info"]["StopDateTime"] = $startTime;
		$data = array();
		foreach($rows as $num=>$row){
			if($num<3) continue;
			$tmp = explode("\t",$row);
			$data[] = array_map("trim",$tmp);
		}
		$Parameter = ClassRegistry::init('Parameter');
		$dbParameter = $Parameter->find("list");
//		debug($dbParameter);
		foreach($header as $id=>$colName){
			$paramId = array_search(trim($colName),$dbParameter);
			if($paramId != null){
				$header[$id] = array($paramId=>trim($colName));
			}
		}
#		debug($header);
		$measurement = new MeasurementData($header,$data);
		foreach($header as $id=>$value){
			$keys[] = array_keys($value)[0];
		}
		$this->outData["ivmeas"] = $measurement->getCols($keys)->getDataAsCSV();
		return true;
	}
	
	private function _isAsciiCiv(){
		$rows = $this->getRows(0,8);
		if($rows[0][0] == "*" && $rows[1][0] == "v" && $rows[2][0] == "*" && $rows[3][0] == "*" && $rows[4][0] == "*" && count(explode("\t",$rows[6]))==4 ){
			return true;
		}else{
			$rows = $this->getRows(0,15,true); //Try again while also getting carriage return values
			if($rows[0][0] == "*" && $rows[1][0] == "v" && $rows[2][0] == "*" && $rows[3][0] == "*" && $rows[4][0] == "*" && count(explode("\t",$rows[6]))==4 ){
				$this->useCarriageReturn = true;
				return true;
			}
		}
		return false;
	}
	
	private function _convertAsciiCiv(){
		$rows = $this->getRows(0,-1,$this->useCarriageReturn);
		$this->outData["info"] = array();
		$this->outData["tags"] = array();
		
		$tmp = explode("' ",trim(str_replace("*", "", $rows[3])));
		foreach($tmp as $field){
			if($field[0] == "D"){
				//Fourth row check after "Detector name" for the identifier string
				//Found Detector Name, save it to array
				$this->outData["info"]["ID"] = substr($field, strpos($field, "'")+1);
				//Search for detector in database and inform the user if the detector was found or not.
			}elseif($field[0] == "t"){
				//Fourth row check after "type" for the sensor type
				//Found Sensor type, save it to array
				$this->outData["info"]["sensorType"] = substr($field, strpos($field, "'")+1);
			}
		}
		//For the start and stop times use regex
		preg_match_all("/<.*?>/", $rows[4],$tmp);
		if(count($tmp[0])==2){
			//correct number of time elements, convert them to numbers and then save the lower value as starting value
			$t1 = date("d.m.Y H:i:s",strtotime(substr($tmp[0][0],1,-1)));
			$t2 = date("d.m.Y H:i:s",strtotime(substr($tmp[0][1],1,-1)));
			if($t1<$t2){
				$this->outData["info"]["StartDateTime"] = $t1;
				$this->outData["info"]["StopDateTime"] = $t2;
			}else{
				$this->outData["info"]["StartDateTime"] = $t2;
				$this->outData["info"]["StopDateTime"] = $t1;
			}
		}else{
			//Could not correctly detect start and end time
			debug($tmp);
		}
		$header = explode("\t",$rows[1]);
		//Third row contains temperatures
		$this->outData["info"]["tempBefore"] = substr($rows[2],strpos($rows[2], "before")+strlen("before:"),9);
		$this->outData["info"]["tempAfter"] = substr($rows[2],strpos($rows[2], "after")+strlen("after:"),9);
		$data1 = array();
		$data2 = array();
		foreach($rows as $num=>$row){
			if($num<6) continue;
			$tmp = explode("\t",$row);
			$data1[] = array(trim($tmp[0]),trim($tmp[1]));
			if(isset($tmp[2])){
				$data2[] = array(trim($tmp[2]),trim($tmp[3]));
			}
		}
		$Parameter = ClassRegistry::init('Parameter');
		foreach($header as $id=>$colName){
			$paramId = $Parameter->paramIdForName($colName);
			if($paramId != null){
				$header[$id] = array($paramId=>trim($colName));
			}
		}
#		debug($header);
		$measurement1 = new MeasurementData(array($header[0],$header[2]),$data1);
		$measurement2 = new MeasurementData(array($header[0],$header[1]),$data2);
		$this->outData["ivmeas"] = $measurement1->getCols(array_keys($header[0])[0],array_keys($header[2])[0])->getDataAsCSV();
		$this->outData["cvmeas"] = $measurement2->getCols(array_keys($header[0])[0],array_keys($header[1])[0])->getDataAsCSV();
		return true;
	}
	
	private function storeAsCSV(){
		if($this->foundMatch){
			$text = "";
			foreach($this->outData as $marker=>$data){
				if(is_array($data) && count($data) == 0) continue;
				$text .= "[".$marker."]\n";
				if(is_array($data)){
					$text .= implode(",",array_keys($data))."\n";
					$text .= implode(",",array_values($data))."\n\n";
				}else{
					$text .= $data."\n";
				}
			}
			//TODO: Check if the file doesn't exist already
			$newFilePath = MEAS_TMP.DS.basename($this->originalFilePath).".csv";
			file_put_contents($newFilePath,$text);
			$this->convertedFilePath = $newFilePath;
		}
	}
}
?>
