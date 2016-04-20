<?php
App::uses('error','Lib');
/**
 *	Contains the tags for one Measurement
 */
class MTags extends error{

	
	protected $tags = array();

	function __construct($tags){
		if(is_array($tags)){
			$this->checkTags($tags);
		}
	}
	
	function getTags(){
		return $this->tags;
	}

	/**
	 * @return array
     */
	function getTagsForDB(){
		$return = array();
		foreach($this->tags as $id=>$tag){
			$return[] = array("MeasurementTagsMeasurement"=>array("measurement_tag_id"=>$id));
		}
		return $return;
	}
	/**
	 * Checks the parameter names with the database to make sure that all set tags are defined. 
	 * If one parameter is mising the value is checked against a conversion database to match it to a parameter from Files where the set Tags cannot be adapted. 
	 * The result is checked again. 
	 * If this step fails as well the parameter is added to the "not used" array and the error flag is set.
	 * @param $tags array() 
	 */
	private function checkTags($tags){
		$MeasurementTags = ClassRegistry::init('MeasurementTags');
		$dbTags = $MeasurementTags->find("list");
		//Check if $tags originates from the Database by checking for the existance of "id","name" and "MeasurementTagsMeasurement" parameters
		if(isset($tags[0]["MeasurementTagsMeasurement"])){
			//From Database
			$tmp = $tags;
			unset($tags);
			$tags = array();
			foreach($tmp as $id=>$tag){
				$tags[$tag["MeasurementTagsMeasurement"]["measurement_tag_id"]] = $dbTags[$tag["MeasurementTagsMeasurement"]["measurement_tag_id"]];
			}
		}elseif(isset($tags[0]["id"]) && isset($tags[0]["name"])) {
			$tmp = $tags;
			unset($tags);
			$tags = array();
			foreach ($tmp as $tag) {
				$tags[$tag["id"]] = $tag["name"];
			}
		}
		foreach($tags as $id=>$name){
			if(is_numeric($name)){ //Tag is numeric, probably tag id
				$this->tags[$name] = $dbTags[$name];
			}elseif(!in_array($name,$dbTags)){
				$this->setError("Tag '".$name."' is not in the Database. Rejecting",$dbTags);
			}else{
				$this->tags[array_search($name,$dbTags)] = $name;
			}
		}
	}
	
}
?>
