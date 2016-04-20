<?php
/**
 *
 */
abstract class fileContainer{

	protected $fileType;
	protected $fileName;
	protected $fp;
	protected $parameters = array();
	protected $colNames = array();
	protected $emptyCols = array();
	protected $measurementPairs = array();
	protected $firstDataRow = 1;

	function __construct($fileType,$fileName) {
		$this->fileType = $fileType;
		$this->fileName = $fileName;
		$this->parameters["fileType"] = $fileType;
		$this->parameters["fileName"] = $fileName;
		$this->fp = fopen($this->fileName, "r");
	}

	protected function resetFilepointerToStart(){
		fclose($this->fp);
		$this->fp = fopen($this->fileName, "r");
	}

	/**
	 * @return array Returns an assoc array containing all stored parameters
	 */
	 public function getAllParameters()
	 {
		 return $this->parameters;
	 }

	 public function getParameter($param){
	 	return (isset($this->parameters[$param])) ? $this->parameters[$param] : false;
	 }
	 /**
	  * Intelligent function recognizing the measurement type and calling the corresponding correct function to either return measurement pairs (as in the CIV file, or one array containing all cols to be saved)
	  */
	 public function getMeasurement()
	 {
		 return $this->setMeasurementPairs();
	 }

	protected function getFullMeasurement()
	{
		$return = array();
		foreach($this->colNames as $id=>$colName){
			if( in_array($colName, $this->emptyCols)) continue;
			$return[] = $id;
		}
		return array($return);
	}

	 protected function getAllMeasurementPairs(){
	 	$return = array();
	 	foreach($this->measurementPairs as $pair){
	 		$return[] = $pair;
	 	}
		return $return;
	 }

	 public function getHeaderForMeasurementPair($pair){
		$tmp = array();
		foreach($pair as $id){
			$tmp[] = $this->colNames[$id];
		}
	 	return $tmp;
	 }

	 public function getDataForMeasurementPair($pair){
	 	$this->resetFilepointerToStart();
	 	//Read first rows that contain only comments to set file pointer to where the data starts
	 	for($i=0;$i<$this->firstDataRow;$i++){
	 		$tmp = fgets($this->fp);
	 	}
	 	$data = array();
	 	while($tmp = fgets($this->fp)){
	 		$data[] = explode("\t",$tmp);
	 	}
		$return = array();
		foreach($data as $row){
			$tmp = array();
			foreach($pair as $col){
				if(!isset($row[$col])) break(2); //If the row doesn't have enough cols anymore (e.g. CIV file with different number of C and I measurements) skip the rest
				$tmp[] = floatval(trim($row[$col]));
			}
			$return[] = $tmp;
		}
		return $return;
	 }
	 /**
	  * Sets the cols to be not used during the import (usually columns full of zeros)
	  */
	 public function setEmptyCols($emptyCols){
	 	$this->emptyCols = array();
	 	foreach($emptyCols as $colId){
	 		$this->emptyCols[$colId] = $this->colNames[$colId];
		}
#		print_r($this->emptyCols);
	 }
	/**
	 * Checks for cols containing only zeroes and sets them to be ignored
	 * @opt_param int $num Number of rows that need to be 0 for the column to be considered empty
	 */

	 protected function detectEmptyCols($num = 3)
	 {
	 	//Get the first $num of data rows to detect empty cols
		$this->resetFilepointerToStart();
	 	$data = array();
	 	for($i=0;$i<$this->firstDataRow+$num;$i++){
	 		$data[] = explode("\t",fgets($this->fp));
	 	}
		$emptyCols = $this->colNames;
		for($i=$this->firstDataRow;$i<$this->firstDataRow+$num;$i++){
			foreach($emptyCols as $id=>$colName){
				if($data[$i][$id] != 0){
					unset($emptyCols[$id]);
				}
			}
		}
		$this->emptyCols = $emptyCols;
	 }


	 public function getPreviewRows($num=5){
	 	$this->resetFilepointerToStart();
	 	$data = array();
	 	for($i=0;$i<$this->firstDataRow+$num;$i++){
	 		$data[] = explode("\t",fgets($this->fp));
	 	}
		$return = array();
		for($i=$this->firstDataRow;$i<$this->firstDataRow+$num;$i++){
			$tmp = array();
			foreach($this->colNames as $id=>$col){
				if(!isset($this->emptyCols[$id])){ //Col is not set as zero, use
					$tmp[$id] = $data[$i][$id];
				}else{
					$tmp[$id] = array($data[$i][$id],array("class"=>"emptyCol"));
				}
			}
			$return[] = $tmp;
		}
		return $return;

	 }
	 public function getPreviewColnames($previewId)
	 {
	 	$return = array();
		foreach($this->colNames as $id=>$col){
			if(!isset($this->emptyCols[$id])){ //Col is not set as zero, use
				$return[] = array($col=>array("id"=>"preview_".$previewId."_".$id));
			}else{
				$return[] = array($col=>array("id"=>"preview_".$previewId."_".$id, "class"=>"emptyCol"));
			}
		}
		return $return;
	 }
	 /**
	  * Returns only the col names that are not marked as empty
	  */
	 public function getUsedCols(){
	 	$return = array();
	 	foreach($this->colNames as $colName){
	 		if(!in_array($colName, $this->emptyCols))
				$return[] = $colName;
	 	}
		return $return;
	 }
	 public function setParameter($name,$value){
	 	$this->parameters[$name] = $value;
	 }

	protected abstract function setMeasurementPairs();

	public function readAllData(){
		return true;
	}
}

?>