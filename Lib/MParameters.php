<?php
App::uses('error','Lib');
/**
 *	Contains the parameters for one Measurement
 */
class MParameters extends error{

	protected $parameters;
	protected $startTime;
	protected $stopTime;
	protected $itemCode;
	
	function __construct($params){
		if(is_array($params)){
			if(isset($params[0]) && isset($params[0]["parameter_id"])){
				$this->parameters = $params; //Appears to be in the correct format already since it is an array starting with 0 and the first element has an element "parameter_id"
			}else{
				$this->parameters = $this->_convertNameValuePair($params);
			}
		}
	}
	
	function getParameters(){
		return $this->parameters;
	}
	
	function getParametersForDB(){
		$return = array();
		foreach($this->parameters as $parameter){
			if($parameter["parameter_value"] != ""){
				$return[] = array("parameter_id"=>$parameter["parameter_id"],"value"=>$parameter["parameter_value"]);
			}
		}
		return $return;
	}
	
	function setItemCode($newItemCode){
		$this->itemCode = $newItemCode;
	}
	
	function getParameterValue($parameterName){
		return (isset($this->parameters[$parameterName]))? $this->parameters[$parameterName] : null;
	}
	
	private function _convertNameValuePair($array){
		$Parameters = ClassRegistry::init('Parameters');
		$dbParameters = $Parameters->find("list");
		$return = array();
		foreach(array_keys($array) as $id=>$parameter){
			//Sets the mandatory parameters such as start time, stop time, 
			if($parameter == "StartDateTime"){
				$tmp = (isset($array[$parameter]))? $array[$parameter] : null;
				$this->startTime = strtotime(str_replace("\"","",str_replace("'","",$tmp)));
				unset($array[$parameter]); //Remove from general parameters
				continue;
			}
			if($parameter == "StopDateTime"){
				$tmp = (isset($array[$parameter]))? $array[$parameter] : null;
				$this->stopTime = strtotime(str_replace("\"","",str_replace("'","",$tmp)));
				unset($array[$parameter]); //Remove from general parameters
				continue;
			}
			if($parameter == "ID"){
				$this->itemCode = (isset($array[$parameter]))? $array[$parameter] : null;
				unset($array[$parameter]); //Remove from general parameters
				continue;
			}
			//Additional method of finding the itemCode if it is an old style CSV file:
			if(($parameter == "Filename" || $parameter == "File name" ) && $this->itemCode == null){
				$tmp = (isset($array[$parameter]))? $array[$parameter] : null;
				$this->itemCode = substr($tmp,0,strpos($tmp,"_"));
				unset($array[$parameter]); //Remove from general parameters
				continue;
			}
			if(!in_array($parameter,$dbParameters)){
				$Matching = ClassRegistry::init('Matching');
				$match = $Matching->findByName($parameter);
				if(!empty($match)){
					$array[$match["Parameter"]["name"]] = $array[$parameter];
				}else{
					$this->setError($parameter,array($dbParameters,$array));
				}
				unset($array[$parameter]); //Remove since it has either been replaced or is not useable
			}
		}
		foreach($array as $name=>$value){
			$return[] = array(
				"parameter_id"=>array_search($name,$dbParameters),
				"parameter_name"=>$name,
				"parameter_value"=>$value
			);
		}
		return $return;
	}
	
	/**
	 * Checks the parameter names with the database to make sure that all set parameters are defined. 
	 * If one parameter is mising the value is checked against a conversion database to match it to a parameter from Files where the set Parameters cannot be adapted. 
	 * The result is checked again. 
	 * If this step fails as well the parameter is added to the "not used" array and the error flag is set. 
	 */
	private function checkParameters(){

	}
	
}
?>
