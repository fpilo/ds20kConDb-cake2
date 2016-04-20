<?php
App::uses('fileReader','Lib');
App::uses('MTags','Lib');
App::uses('MParameters','Lib');
App::uses('error','Lib');

/**
 *
 */
class MeasurementObj extends error{

	/**
	 * @var
     */
	protected $measurementType;
	protected $measurementParameters;
	protected $measurementTags;
	protected $measurementData;
	protected $measurementId;
	
	public static $cachePath = MEAS_CACHE;
	public static $cachePrefix = "measurement_";
	protected $cacheFolder = "";

	/**
	 *
	 * @param $type
	 * @param $tags
	 * @param $parameters
	 * @param null $originaldata
	 * @param null $id
	 * @param null $mFile
	 */
	function __construct($type,$tags,$parameters,$id = null,&$mFile=null){
		$this->measurementId = $id;
		if($this->measurementId != null){
			$this->cacheFolder = sprintf("%04d",$this->measurementId/1000000).DS.sprintf("%04d",$this->measurementId/1000).DS;
			//Check if measurementData Object is already stored in cache for this measurement Id
			if($this->_cached()){
				$this->setMeasurementDataFromCache();
#				debug("from cache");
			}else{
				if(is_a($mFile,"MFile")){ //measurement is not cached and measurement Id is set use the file Object to get the data
					$this->setMeasurementData($mFile->getSectionAsArray($type));
#					$this->runtime("getSectionAsArray");
#					debug("from passed data");
				}else{
					throw new Exception("Data not available");
				}
				$this->cache();
			}
			$this->setMeasurementType($type);
			$this->setMeasurementTags($tags);
			$this->setMeasurementParameters($parameters);
		}else{
			$this->setMeasurementType($type);
			$this->setMeasurementTags($tags);
			$this->setMeasurementParameters($parameters);
			if(is_a($mFile,"MFile")){ //measurement is not cached and measurement Id is not set use the file Object to get the data if it exists
				$this->setMeasurementData($mFile->getSectionAsArray($type));
			}else{
				throw new Exception("Data not available");
			}
		}
	}
	
	function __destruct(){
		$this->cache();
	}
	
	function setMeasurementType($measurementType){
		//TODO: Complete method
		$this->measurementType = $measurementType;
	}

	function setMeasurementTags($tags){
		if(is_a($tags,"MTags")){
			$this->measurementTags = $tags;
		}elseif(is_array($tags)){
			$this->measurementTags = new MTags($tags);
		}elseif($tags == null && $this->measurementId != null){
			//ID is set but tags are not (loading from DB)
			$MTM = ClassRegistry::init('MeasurementTagsMeasurement');
			$this->measurementTags = new MTags($MTM->find("all",array("conditions"=>array("measurement_id"=>$this->measurementId))));
		}else{
			$this->measurementTags = new MTags(array());
#			$this->setError("No valid form of Tags passed",$tags);
		}
	}

	function setMeasurementParameters($params){
		if(is_a($params,"MParameters")){
			$this->measurementParameters = $params;
		}elseif(is_array($params)){
			$this->measurementParameters = new MParameters($params);
		}elseif($params == null && $this->measurementId != null){
			//ID is set but tags are not (loading from DB)
			$Measurement = ClassRegistry::init('Measurement');
			$tmp = $Measurement->find("first",array("conditions"=>array("Measurement.id"=>$this->measurementId)));
			$this->measurementParameters = new MParameters($tmp["MeasurementParameter"]);
		}else{
			$this->setError("No valid form of Parameters passed",$params);
		}
	}

	function setMeasurementData($data){
		if(is_a($data,"MeasurementData")){
			$this->measurementData = $data;
		}elseif(is_array($data)){
			$this->measurementData = new MeasurementData($data);
		}else{
			$this->setError("No 'MeasurementData' object passed",$data);
		}
	}
	
	public function getHeader(){
		return $this->measurementData->cols;
	}
	
	public function getAllCols(){
		return $this->measurementData->getAllCols();
	}
	
	public function getMeasurementData(){
		return $this->measurementData;
	}
	
	public function getCols($col1,$col2=null,$col3=null){
		return $this->measurementData->getCols($col1,$col2,$col3);
	}
	
	public function getColsWhere($conditions,$col1,$col2=null,$col3=null){
		return $this->measurementData->getColsWhere($conditions,$col1,$col2,$col3);
	}
	public function getDistinctValuesWhere($conditions,$col){
		return $this->measurementData->getDistinctValuesWhere($conditions,$col);
	}
	
	public static function cached($id){
		return file_exists(self::$cachePath.sprintf("%04d",$id/1000000).DS.self::$cachePath.sprintf("%04d",$id/1000).DS.self::$cachePrefix.$id.".gz");
	}
	public static function deleteCache($id){
		return unlink(self::$cachePath.sprintf("%04d",$id/1000000).DS.self::$cachePath.sprintf("%04d",$id/1000).DS.self::$cachePrefix.$id.".gz");
	}
	private function _cached(){
		return file_exists(self::$cachePath.$this->cacheFolder.self::$cachePrefix.$this->measurementId.".gz");
	}
	
	private function cache(){
		//Create folder if necessary
		if (!file_exists(self::$cachePath.$this->cacheFolder)) {
			mkdir(self::$cachePath.$this->cacheFolder,0777,true);
		}
		file_put_contents(self::$cachePath.$this->cacheFolder.self::$cachePrefix.$this->measurementId.".gz",gzencode(serialize($this->measurementData)));
	}
	
	private function setMeasurementDataFromCache(){
		$this->measurementData = unserialize(gzdecode(file_get_contents(self::$cachePath.$this->cacheFolder.self::$cachePrefix.$this->measurementId.".gz")));
//		$this->runtime("startfromCache");
//		$tmp = file_get_contents(self::$cachePath.$this->cacheFolder.self::$cachePrefix.$this->measurementId.".gz");
//		$this->runtime("read File");
//		$tmp = gzdecode($tmp);
//		$this->runtime("decompress");
//		$this->measurementData = unserialize($tmp);
//		$this->runtime("unserialize");
	}
}
?>
