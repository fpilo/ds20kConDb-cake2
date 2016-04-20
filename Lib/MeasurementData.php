<?php
App::uses('error','Lib');

/**
 *	Contains the Data for one Measurement
 */
class MeasurementData extends error{

	protected $cols = array();
	protected $rows = array();
	protected $dataCache = array();
	

	function __construct($cols = array(),$rows = array()){
		if(count($cols) != 0 and empty($rows)){
			//Only one argument passed, first element is header and rest is data
			$this->setCols($cols[0]);
			$this->addRows(array_slice($cols,1));
		}elseif(count($cols) != 0){
			$this->setCols($cols);
			$this->addRows($rows);
		}
	}
	/**
	*	Needs to be an array of PositionId=>array("parameterId"=>"parameterName") pairs
	*/
	function setCols($cols){
		if(is_array($cols) && is_array($cols[0])) {
			$this->cols = $cols;
		}elseif(is_array($cols) && !is_array($cols[0])){
			$Parameters = ClassRegistry::init('Parameters');
			$dbParameters = $Parameters->find("list");
			$header = array();
			foreach($cols as $pos=>$paramName){
				$paramName = trim($paramName);
				foreach($dbParameters as $i=>$dbName){
					if(strcasecmp($dbName,$paramName)==0){
						$header[$pos] = array($i=>$dbName);
						break;
					}
				}
				if(!isset($header[$pos])){
					//Parameter was not found, create it
					$Parameters->save(array("name"=>$paramName));
					$header[$pos] = array($Parameters->getLastInsertID()=>$paramName);
					$Parameters->clear();
					$dbParameters = $Parameters->find("list");
				}
			}
			$this->cols = $header;
		}else{
			$this->setError('Needs to be an array of PositionId=>array("parameterId"=>"parameterName") pairs',$cols);
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
	
	private function colExists($colId){
		foreach($this->cols as $id=>$name){
			if(in_array($colId,array_keys($name))) return true;
		}
		return false;
	}
	
	function getDistinctValuesWhere($conditions,$col){
		//Check if cached variation exists
		if(!isset($this->dataCache[$col.serialize($conditions)])){
			//Doesn't exist, request Data, store and update cache then return
			$data = $this->getColsWhere($conditions,$col);
			if(!is_a($data,"MeasurementValues")){
				$return = $data;
			}else{
				$return = array();
				foreach($data->rows as $row){
					if(!in_array($row[0],$return)){
						$return[] = $row[0];
					}
				}
			} 
			$this->dataCache[$col.serialize($conditions)] = $return;
		}
		//return cached
		return $this->dataCache[$col.serialize($conditions)];

	}
	
	public function getAllCols(){
		$return = array();
		$cols = $this->cols;
		foreach($this->cols as $col=>$array){
			$return[0][$col] = array_pop($array);
		}
		foreach ($this->rows as $row){
			$tmp = array();
			foreach($this->cols as $col=>$array){
				$tmp[$col] = $row[$col];
			}
			$return[] = $tmp;
		}
		return $return;
	}
	
	/**
	*	Returns a Measurement Values Object or False
	*	The first parameter can be either an array of Parameter IDs or just one parameter ID
	*	For convenience purposes it is also possible to request up to three single parameter IDs as separate parameters. 
	*/
	function getCols($col1,$col2=null,$col3=null){
		return $this->getColsWhere(array(),$col1,$col2,$col3);
	}
	/**
	*	The $conditions variable needs to be an array of parameterId=>searchValue pairs of which all need to match. 
	*	
	*/
	public function getColsWhere($conditions,$col1,$col2=null,$col3=null){
		$rowSubset = array();
		if(!is_array($conditions)){
			$this->setError("No valid conditions",$conditions);
			return false;
		}
		foreach($conditions as $name=>$value){
			if(!$this->colExists($name)){
				$this->setError("Col ".$name." doesn't exist in Measurement",$conditions);
				return false;
			}

		}
		if(is_array($col1)){
			$requestedCols = $col1;
		}else{
			$requestedCols[] = $col1;
			if(!is_null($col2)){
				$requestedCols[] = $col2;
			}
			if(!is_null($col3)){
				$requestedCols[] = $col3;
			}
		}
		$positions = array();
		//Iterate over requested cols
		foreach($requestedCols as $id=>$col){
			//Iterate over set cols
			foreach($this->cols as $pos=>$arr){
				//If the requested col exists in the set cols copy the array information (id and name) over the simple id
				if(isset($arr[$col] )){
					$requestedCols[$id] = $arr;
					$positions[] = $pos;
					break;
				}
			}
			//Check if the value is now an array, and if not mark an error. 
			if(!is_array($requestedCols[$id] )){
				$this->setError("Parameter doesn't exist. ",array($col,$this->cols));
			}
		}
		//Check if error
		if(!$this->error){
			require_once("MeasurementValues.php");
			$measurementValues = new MeasurementValues();
			$measurementValues->setCols($requestedCols);
//			debug($measurementValues);
//			debug($positions);
			foreach($this->rows as $row){
				$tmp = array();
				foreach($row as $pos1=>$value1){
					foreach($conditions as $parameterId=>$value2){ //Iterate over conditions
						if(isset($this->cols[$pos1][$parameterId]) && $value1 != $value2){ //If a condition is set for this col AND the values are not equal skip the whole row
							break(2);
						}
					}
					if(in_array($pos1,$positions)){
						$tmp[array_search($pos1,$positions)] = $value1;
					}
				}
				if(count($tmp) == count($requestedCols)){
					$measurementValues->addRow($tmp);
				}
			}
			return $measurementValues;
		}
		return false;
	}
	
}
?>
