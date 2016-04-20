<?php
App::uses('error','Lib');

/**
 *
 */
class fileReader extends error{

	protected $filePath;
	protected $compressedFilePath = null;
	protected $fp;

	function __construct($filePath) {
		$this->filePath = $filePath;
		if(file_exists($this->filePath)){
			$this->open();
		}elseif(file_exists(MEAS_TMP.DS.$this->filePath)){
			$this->filePath = MEAS_TMP.DS.$this->filePath;
			$this->open();
		}elseif(file_exists($this->filePath.".gz")){
			$this->filePath = $this->filePath.".gz";
			$this->open();
		}else{
			debug("File not found");
			return false;
		}
		return true;
	}

	private function open(){
		if(strpos($this->filePath,".gz")>0){
#			debug("found compressed file in ".$this->filePath);
			//Open from compressed format
			$this->compressedFilePath = $this->filePath;
			$this->filePath = str_replace(".gz","",$this->filePath);
			file_put_contents($this->filePath, gzdecode( file_get_contents($this->compressedFilePath)));
			$this->fp = fopen($this->filePath, "r");
		}else{
#			debug("found uncompressed file in ".$this->filePath);
			//open normally
			$this->fp = fopen($this->filePath, "r");
		}
	}
	
	function __destruct(){
		fclose($this->fp);
		if($this->compressedFilePath != null){ 
			//compressed file path is already set, unlink the copy created on demand
			unlink($this->filePath);
		}
	}
	
	protected function _resetFilepointerToStart(){
		fclose($this->fp);
		$this->fp = fopen($this->filePath, "r");
	}

	public function getRows($firstRow,$lastRow,$splitByCR=false){
		//Make sure that the second value is higher than the first one
		if($lastRow == -1) $lastRow = pow(2,20); //Some random high number that enables a user to just enter -1 if he wants all rows
		if($lastRow < $firstRow){ //Switch last and first row if they are not in the correct order to prevent endless loops
			$tmp = $lastRow;
			$lastRow = $firstRow;
			$firstRow = $tmp;
		}
		$this->_resetFilepointerToStart();
		$line = 0;
		$rows = array();
		while (($row = fgets($this->fp)) !== false) {
			if($splitByCR){
#				debug($row);
				$row = explode("\r",$row);
#				debug($line);
			}else{
				$row = array($row);
			}
			foreach($row as $r){
				$line++;
				if ($line < $firstRow) continue(2);
				if ($line > $lastRow) break(2);
				if(trim($r) != ""){//Remove empty rows
					$rows[] = $r;
				}
			}
		}
		return $rows;
	}
}
?>
