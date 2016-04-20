<?php
App::uses('fileReader','Lib');
App::uses('error','Lib');

/**
 *
 */
class Measurement extends error{

	protected $measurementType;
	protected $measurementParameters;
	protected $fromDB = false;
	
	/**
	*	If an id is passed the Measurement object is created from the database. 
	*	otherwise an empty measurement object is initialized. 
	*/
	function __construct($param = null){
		if(is_numeric($param)){
			$this->initFromDB($param);
		}elseif($param != null){
			$this->init($param);
		}
	}
	
	function setMeasurementType($measurementType){
		$this->measurementType = $measurementType;
	}

	private function initFromDB($measurementId){
		$Measurement = ClassRegistry::init('Measurement');
		$measurement = $Measurement->findById($measurementId);
		$this->fromDB = true;
		$this->init($measurement["filePath"]);
		$this->measurementType = $measurement["measurementType"];
	}

	private function init($filePath){
		$fileType = $this->fileType($filePath);
		if($fileType == "csv"){
			$this->convertedFilePath = $filePath;
		}else{
			$this->convertedFilePath = $this->convertFile($fileType,$filePath);
		}
		$this->file = new csvFile($this->convertedFilePath);
		$this->sections = $this->file->getSections();
		$tmp = $this->file->getSectionAsArray("info");
		foreach($tmp[0] as $id=>$name){
			$parameters[$name] = $tmp[1][$id];
		} 
		$this->measurementParameters = new MeasurementParameters($parameters);
	}
	
	public function getSectionCols($section,$col1,$col2=null,$col3=null){
		return $this->file->getSectionAsMeasurement($section)->getCols($col1,$col2,$col3);
	}
	
	public function getSections(){return $this->file->getSections();}
	
	private function fileType($filePath){
		$file = new fileReader($filePath);
		$rows = $file->getRows(0,10);
		if(strpos("[info]",$rows[0])==0){ //check if the file starts with the [info] section and assume a correct csv. 
			return "csv";
		}else{
			//TODO: Check against all file type class definitions of their first rows
		}
	}
	
	private function convertFile($fileType,$filePath){
		//TODO: Call the conversion method of the correct file type class.
		
		$this->measurementType = null; //TODO: Needs to set the measurement type based on the class used
	}
}
?>