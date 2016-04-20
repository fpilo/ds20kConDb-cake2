<?php
/**
 *
 */
class error{
	protected $error = false;
	protected $errors = array();
	protected $startTime;
	
	public function __get($name) {
		return isset($this->$name) ? $this->$name : null;
	}

	protected function setError($msg,$data){
		$this->error = true;
		$this->errors[] = array("msg"=>$msg,"data"=>$data);
	}
	
	protected function runtime($msg){
		$now = microtime(true)-$this->startTime;
		debug($msg." took ".sprintf("%1.2f seconds",$now));
		$this->startTime = microtime(true);
		
	}
}
?>
