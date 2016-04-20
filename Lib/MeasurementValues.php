<?php
App::uses('error','Lib');
/**
 *	Contains a defined set of Values
 */
class MeasurementValues extends error{

	protected $cols = array();
	protected $rows = array();

	
	function __construct(){
	}
	/**
	*	Needs to be an array of PositionId=>array("parameterId"=>"parameterName") pairs
	*/
	function setCols($cols){
		if(is_array($cols)){
			$this->cols = $cols;
		}
	}
	
	function addRow($row){
		if(is_array($row) && count($row) == count($this->cols)){
			$this->rows[] = $row;
		}else{
			$this->setError("Row doesn't contain the correct amount of cols. Should be ".count($this->cols)." and not ".count($row),$row);
		}
	}
	
	function addRows($rows){
		if(is_array($rows)){
			foreach($rows as $row){
				$this->addRow($row);
			}
		}
	}
	
	function getData(){
		$return = array();
		foreach($this->rows as $rNum=>$row){
			foreach($this->cols as $cNum=>$col){
				$return[$rNum][$cNum] = $row[$cNum];
			}
		}
		return $return;
	}
	function getDataAsCSV(){
		$return = "";
		foreach($this->cols as $pos=>$col){
			$tmp[] = array_pop($col);
		}
		$return .= implode(",",$tmp)."\n";
		foreach($this->rows as $row){
			$return .= implode(",",$row)."\n";
		}
		return $return;
	}
	function getHeader(){
		return $this->cols;
	}
	
}
?>