<?php

App::uses('fileReader','Lib');
App::uses('MeasurementObj','Lib');
App::uses('MeasurementData','Lib');
App::uses('MParameters','Lib');
App::uses('ItemParams','Lib');
App::uses('MTags','Lib');

/**
 *	Represents a component-List File stored in the standard CSV format
 *	
 */
class CmpListFile extends fileReader{
	
	//Properties
	private $measurementDevice;
	private $measurements = array(); //Associative Array of measurement Objects
	private $measurementFileId = null;
	private $infoSections = array("info");
	
	protected $sections = array();
	protected $markers = array();
	protected $fromDB = false;
	
	protected $measurementParameters;
	protected $measurementTags;
	protected $itemParameters;
	protected $originalFileName;
	
	
	function __construct($param = null,$originalFileName=null){
		if(is_numeric($param)){
			$this->_initFromDB($param,$originalFileName);
		}elseif($param != null){
			$this->_init($param,$originalFileName);
		}
	}
	
	protected function _init($fileName,$originalFileName=null){
		
		if(!parent::__construct($fileName)){
			throw new NotFoundException(__("File ".$fileName." could not be found, that's bad"));
		}
		if($originalFileName != null){
			$this->originalFileName = basename($originalFileName);
		}
		$this->_findSections();
		if($this->error){
			return false;
		}
		
		//Get Measurement Parameters
		/*if(!$this->fromDB){ //Check if from db and if no get the tags and parameters from the file
		
			$tmp = $this->getSectionAsArray("info");
			foreach($tmp[0] as $id=>$name){
				$parameters[$name] = $tmp[1][$id];
			}
			$this->measurementParameters = new MParameters($parameters);
		
		}*/
		return true;
	}

	protected function _initFromDB($measurementId,$originalFileName){
		$Measurement = ClassRegistry::init('MeasurementFile');
		$measurement = $Measurement->findById($measurementId);
		$this->fromDB = true;
		$this->measurementFileId = $measurement["MeasurementFile"]["id"];
		return $this->_init(MEAS_CONV.DS.$this->fileFolderFromId($this->measurementFileId),$originalFileName);
	}
	
	protected function _findSections(){
		
		$line = 1;
		$match = 0;
		$matches = array();
		while (($row = fgets($this->fp)) !== false) {
			if($row[0] == "["){
				if(preg_match("/\[([^)]+)\]/",$row,$matches)!= 0){
					$this->sections[$match] = array("name"=>$matches[1],"firstline"=>$line+1);
					if($match > 0){
						$this->sections[$match-1]["lastline"] = $line-1;
					}
					$match++;
				}
			}
			$line++;
		}
		$this->sections[$match-1]["lastline"] = $line-1;

	}
	
	public function setTags($tags){
		$this->measurementTags = new MTags($tags);
	}
	
	public function setMeasurementSetup($setupId){
		$this->measurementDevice = $setupId;
	}
	
	public function getSections(){
		$return = array();
		foreach($this->sections as $id=>$section){
			if(!in_array($section["name"],$this->infoSections)){
				$return[] = $section;
			}
		}
		return $return;
	}
	
	protected function inSections($item){
		foreach($this->sections as $id=>$array){
			if($item == $array["name"]) return true;
		}
		return false;
	}
	
	public function getSectionAsArray($sectionName,&$target=null){
		foreach($this->sections as $id=>$section){
			if($section["name"] == $sectionName){
				if($target !== null){
					$target = array_map(array($this,"_interpretCsvRow"), $this->getRows($section["firstline"], $section["firstline"]+2));
				}else{
					return array_map(array($this,"_interpretCsvRow"), $this->getRows($section["firstline"], $section["firstline"]+2));
				}
			}
		}
		return true;
	}
	
	public function getSectionAsPreview($sectionName){
		//Change the sections lastline parameter to firstline+6 if the difference is greater than 10
		$backup = 0;
		foreach($this->sections as $id=>$section){
			if($section["name"] == $sectionName){
				$backup = $section["lastline"];
				if($section["firstline"]+10 < $section["lastline"]){
					$this->sections[$id]["lastline"] = $section["firstline"]+6;
				}
				break;
			}
		}
		$mm = $this->getSectionAsMeasurement($sectionName,false);
		//Restore the original lastline value from the backup value
		$this->sections[$id]["lastline"] = $backup;
		return $mm;
	}
	
	public function getSectionAsMeasurement($sectionName,$useCache=true,$id=null){
		
		if(!isset($this->measurements[$sectionName]) || !$useCache){
			if(!$useCache){
				return new MeasurementObj($sectionName,$this->measurementTags,$this->measurementParameters,$id,$this); //$this->markers[$sectionName],
			}else{
				$this->measurements[$sectionName] = new MeasurementObj($sectionName,$this->measurementTags,$this->measurementParameters,$id,$this); //$this->markers[$sectionName],
			}
		}
		return $this->measurements[$sectionName];

	}
	
	public function getSectionCols($section,$col1,$col2=null,$col3=null){
		return $this->getSectionAsMeasurement($section)->getCols($col1,$col2,$col3);
	}
	
	private function _interpretCsvRow($string){
		$tmp = str_getcsv($string,",",'"');
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
	
	public function save(){
		if(!$this->fromDB){ //Only allow save if not from database import (if it is then it has already been saved)
			$Session = new SessionComponent(new ComponentCollection());
			$userId = $Session->read('User.User.id');

			$Event = ClassRegistry::init('Events');
			$eventData = $Event->findByName('Measurement');
			
			$Items = ClassRegistry::init('Items');
			$item = $Items->findByCode($this->measurementParameters->itemCode);
			//Get MeasurementDeviceId from Object
			if($this->measurementDevice !== null){
				$measurementDeviceId = $this->measurementDevice;
			}else{
				return false;
			}

			//Save MeasurementFile in Database thereby preserving the original file name. 
			$MeasurementFile= ClassRegistry::init('MeasurementFiles');
			if(!$MeasurementFile->save(array("MeasurementFiles"=>array("originalFileName"=>$this->originalFileName)))){
				debug($MeasurementFile->validationErrors);
			}
			$measurementFileId = $MeasurementFile->getlastInsertID();
			//Check if target folders exist
			if(!file_exists(dirname(MEAS_ORIG.DS.$this->fileFolderFromId($measurementFileId)))){
				mkdir(dirname(MEAS_ORIG.DS.$this->fileFolderFromId($measurementFileId)),0777,true);
			}
			if(!file_exists(dirname(MEAS_CONV.DS.$this->fileFolderFromId($measurementFileId)))){
				mkdir(dirname(MEAS_CONV.DS.$this->fileFolderFromId($measurementFileId)),0777,true);
			}
			//Move original File to original File Folder (with renaming according to measurement File ID)
			copy(MEAS_TMP.DS.$this->originalFileName,MEAS_ORIG.DS.$this->fileFolderFromId($measurementFileId));
			//Gzip original File to save space
			$this->_gZipFile(MEAS_ORIG.DS.$this->fileFolderFromId($measurementFileId));
			//Move converted CSV File to converted File Folder(with renaming according to measurement File ID)
			if(copy($this->filePath,MEAS_CONV.DS.$this->fileFolderFromId($measurementFileId))){
				fclose($this->fp);
				unlink($this->filePath);
			};
			//Gzip converted File to save space
			$this->_gZipFile(MEAS_CONV.DS.$this->fileFolderFromId($measurementFileId));
			
			$measurementIds = array();
			$saveWorked = true;
			foreach($this->getMeasurementSections() as $section){
				//Save Measurement in database with new ID and start and stop time associating to the itemID and the file path/name
				$measurement = array(
					'Measurement' => array(
						'item_id' => $item["Items"]["id"],
						'user_id' => $userId,
						'device_id' => $measurementDeviceId,
						'measurement_file_id' => $measurementFileId,
						'start' => date("Y-m-d H:i:s",$this->measurementParameters->startTime),
						'stop'  => date("Y-m-d H:i:s",$this->measurementParameters->stopTime),
					),
					'History' => array(
						'item_id' => $item["Items"]["id"],
						'event_id' => $eventData["Events"]["id"], 
						'user_id' => $userId,
						'comment' => "Saving Measurement"
					),
					//Save measurement parameters to the measurement
					"MeasurementParameter"=>$this->measurementParameters->getParametersForDB(),
					//Save measurement tags to the measurement
					"MeasurementTag"=>$this->measurementTags->getTagsForDB(),
				);
				$measurement['Measurement']['measurement_type_id'] = $section["MeasurementType"]["id"];
				$M = ClassRegistry::init("Measurement");
				if($M->saveAssociated($measurement,array("deep"=>true))){
					$measurementIds[] = $M->id;
				}else{
					$saveWorked = false;
					debug($measurement);
					debug("Validation ERRORS:");
					debug($M->validationErrors);
				}
			}
			if($saveWorked){
				//Save item parameters to the item
				$ItemsParameter = ClassRegistry::init('ItemsParameter');
				if($this->itemParameters != null) {
					foreach ($this->itemParameters->getParametersForDB() as $parameter) {
						if($parameter["value"] != ""){ //Only add parameter if value is not empty
							if ($ItemsParameter->addParameterToItem($item["Items"]["id"], array(
											"parameter" => $parameter["parameter_id"],
											"value" => $parameter["value"],
											"comment" => "Taken from measurement ".$measurementIds[0],
											"timestamp" => date("Y-m-d H:i:s", $this->measurementParameters->stopTime))) === false
							) {
								debug($parameter);
								debug($ItemsParameter->validationErrors);
							}
						}
					}
				}
			}
			
			return $measurementIds; //Return the array containing the Measurement IDs of the created measurements to allow the user to immedeately jump to them. 
		}
		return false;
	}

	/**
	 * @param $id The Id which is used as base
	 * */
	public static function fileFolderFromId($id){
		return sprintf("%04d",$id/1000000).DS.sprintf("%04d",$id/1000).DS."measurementFile_".sprintf("%04d",$id%1000);
	}
	
	public static function _gZipFile($filePath){
		file_put_contents($filePath.".gz", gzencode( file_get_contents($filePath)));
		unlink($filePath);
	}
}
?>
