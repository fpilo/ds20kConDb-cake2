<?php

App::uses('fileReader','Lib');

/**
 *
 */
class csvFile extends fileReader{

	protected $sections = array();
	private   $defaultSections = array("info","tags","parameters");

	function __construct($fileName){
		parent::__construct($fileName);
		$this->findSections();
	}

	private function findSections(){
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
	
	public function getSections(){
		return $this->sections;
	}
	
	public function getSectionAsArray($sectionName,&$target=null){
		foreach($this->sections as $id=>$section){
			if($section["name"] == $sectionName){
#				return $this->getRows($section["firstline"],$section["lastline"]);
				if($target !== null){
					$target = array_map(array($this,"_interpretCsvRow"),$this->getRows($section["firstline"],$section["lastline"]));
				}else{
					return array_map(array($this,"_interpretCsvRow"),$this->getRows($section["firstline"],$section["lastline"]));
				}
			}
		}
	}
	
	public function getSectionAsMeasurement($sectionName){
		$data = array();
		$this->getSectionAsArray($sectionName,$data);
		$this->Parameters = ClassRegistry::init('Parameters');
		$dbParameters = $this->Parameters->find("list");
		$header = array();
		foreach($data[0] as $pos=>$paramName){
			foreach($dbParameters as $id=>$dbName){
				if($dbName == $paramName){
					$header[$pos] = array($id=>$dbName);
					break;
				}
			}
		}
		return new MeasurementData($header,array_slice($data,1));
	}
	
	private function _interpretCsvRow($string)
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