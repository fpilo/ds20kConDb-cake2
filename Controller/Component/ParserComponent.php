<?php

	/**
	 * Usage:
	 * 
	 * -View
	 *  echo $this->Form->create('Files', array('type' => 'file'));
	 *  echo $this->Form->input('Files.', array('type' => 'file', 'multiple')); // Multiple File (HTML5 only) 
	 *  //echo $this->Form->input('Files.', array('type' => 'file', 'label' => 'Datei')); //Single File
	 *	echo $this->Form->submit('Speichern', array('name' => 'save'));
	 *	echo $this->Form->end();
	 *
	 * -Controller
	 *  public $components = array('Parser');
	 *  ...
	 *  $result = $this->Parser->parse($this->request->data['Data']['Files'], '/[\t\r\n]+/');
	 *
	 */
	 
class ParserComponent extends Component {
	
	 
	/** 
	 * List of permitted file types for upload.
	 * Other possible filetypes for example:
	 * 'application/vnd.ms-excel', 'application/msexcel', 'application/msword', 'application/mspowerpoint',
	 * 'application/pdf', 'application/octet-stream','image/gif','image/jpeg','image/pjpeg','image/png', 'image/tiff'
	 */	
	public $permitted = array('text/plain', 'application/octet-stream');
	
	/**
	 * Parses the content of a text file
	 *
	 * @params:
	 *    $patter = where to split file
	 *    $file  = filename
	 * @return:
	 *    array with content
	 */	 
	function parse($formdata, $pattern) {
		// loop through and deal with the files
	   foreach($formdata as $file)
	   {
		  // replace spaces with underscores
		  $filename = str_replace(' ', '_', $file['name']);
		  // assume filetype is false
		  $typeOK = false;
		  // check filetype is ok
		  $filetype = $file['type'];	//MIME-Type: application/octet-stream
		  foreach($this->permitted as $type)
		  {
			 if($type == $filetype) {
				$typeOK = true;
				break;
			 }
		  }
	 
		  // if file type ok parse the file
		  if($typeOK) {
			 // switch based on error code
			 switch($file['error']) {
				case 0:
					$filesize = filesize($file['tmp_name']);
					
					if(!$filesize > 0) {
						// an error occured
						$result['errors'][] = "Error uploading $filename. Please try again.";
						break;
					}
					
					$fp      = fopen($file['tmp_name'], 'r');
					$contentString = fread($fp, $filesize);
					//$result['contentString'][$file['name']] = $contentString;
					fclose($fp);
					
					//start Parsing
					$lines = preg_split('/[\r\n]+/', $contentString, -1, PREG_SPLIT_NO_EMPTY); //split content into lines by \r & \n
					
					$content = array();
					foreach($lines as $i => $line) {							
						if('*' != substr ($line, 0, 1)) {
							// First line without * is the Header
							if(empty($result['files'][$file['name']]['header'])) {
								$result['files'][$file['name']]['header'] = preg_split($pattern, $line, -1);
							} else {
								$content[] = preg_split($pattern, $line, -1); //split line into rows by user choosen pattern (e.g. \t)
							}
						}
					}
					
					$result['files'][$file['name']]['data'] = $content;
					$result['files'][$file['name']]['fixed_name'] = $filename;
					
				   break;
				case 3:
				   // an error occured
				   $result['errors'][] = "Error uploading $filename. Please try again.";
				   break;
				default:
				   // an error occured
				   $result['errors'][] = "System error uploading $filename. Contact webmaster.";
				   break;
			 }
		  } elseif($file['error'] == 4) {
			 // no file was selected for upload
			 $result['errors'][] = "No file Selected";
		  } else {
			 // unacceptable file type
			 $pr = implode(', ',$this->permitted);
			 $type = $file['type'];
			 $result['errors'][] = "$filename ($type) cannot be uploaded. Acceptable file types: $pr.";
		  }
	   }
		return $result;
	}
}
?>