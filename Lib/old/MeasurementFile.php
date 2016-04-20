<?php

App::uses('fileReader','Lib');

/**
 *
 */
class MeasurementFile extends fileReader{

	protected $measurementDevice;
	protected $originalFilePath;
	protected $convertedFilePath = "";



	private   $defaultSections = array("info","tags","parameters");
	/**
	*	If an id is passed the MeasurementFile object is created from the database. 
	*	otherwise a file path is expected to be used as the source
	*/

	function __construct($param = null){
		if(is_numeric($param)){
			$this->initFromDB($param);
		}elseif($param != null){
			$this->init($param);
		}
	}



}
?>