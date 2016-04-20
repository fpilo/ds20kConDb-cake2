<?php
class UploadComponent extends Component {

	
	// list of permitted file types, this is only images but documents can be added
	public $permitted = array(
					'image/gif','image/jpeg','image/pjpeg','image/png', 'image/tiff', 
					'text/plain',
					'application/vnd.ms-excel', 'application/msexcel', 'application/msword', 'application/mspowerpoint',
					'application/pdf', 'application/octet-stream');
	
	/**
	 * Usage:
	 *  -View
	 * echo $this->Form->create('Files', array('type' => 'file'));
	 * echo $this->Form->input('Files.', array('type' => 'file', 'multiple')); // HTML5 Multiple File
	 *	//echo $this->Form->input('Files.', array('type' => 'file', 'label' => 'Datei')); //Single File
	 *	echo $this->Form->submit('Speichern', array('name' => 'save'));
	 *	echo $this->Form->end();
	 *
	 * -Controller
	 * public $components = array('Upload');
	 * ...
	 * $result = $this->Upload->copyTo('img/uploads', $this->request->data['Files']['Files']);
	 *
	 * /

    /**
	 * http://blog.mixable.de/cakephp-upload-von-dateien-und-grafiken-vereinfachen/
	 * uploads files to the server
	 * @params:
	 *    $folder  = the folder to upload the files e.g. 'img/files'
	 *    $formdata   = the array containing the form files
	 *    $objectId  = id of the item (optional) will create a new sub folder
	 * @return:
	 *    will return an array with the success of each file upload
	 */
	function copyTo($folder, $formdata, $objectId = null)
	{
	   // setup dir names absolute and relative
	   $folder_url = WWW_ROOT.$folder;
	   $rel_url = $folder;
	 
	   // create the folder if it does not exist
	   if(!is_dir($folder_url)) {
		  mkdir($folder_url);
	   }
	 
	   // if objectId is set create an item folder
	   if($objectId)
	   {
		  // set new absolute folder
		  $folder_url = WWW_ROOT.$folder.'/'.$objectId;
		  // set new relative folder
		  $rel_url = $folder.'/'.$objectId;
		  // create directory
		  if(!is_dir($folder_url)) {
			 mkdir($folder_url);
		  }
	   }
	 
	   // loop through and deal with the files
	   foreach($formdata as $file)
	   {
		  // replace spaces with underscores
		  $filename = str_replace(' ', '_', $file['name']);
		  // assume filetype is false
		  $typeOK = false;
		  // check filetype is ok
		  foreach($this->permitted as $type)
		  {
			 if($type == $file['type']) {
				$typeOK = true;
				break;
			 }
		  }
	 
		  // if file type ok upload the file
		  if($typeOK) {
			 // switch based on error code
			 switch($file['error']) {
				case 0:
				   // check filename already exists
				   if(!file_exists($folder_url.'/'.$filename)) {
					  // create full filename
					  $full_url = $folder_url.'/'.$filename;
					  $url = $rel_url.'/'.$filename;
					  // upload the file
					  $success = move_uploaded_file($file['tmp_name'], $url);
				   } else {
					  // create unique filename and upload file
					  ini_set('date.timezone', 'Europe/London');
					  $now = date('Y-m-d-His');
					  $full_url = $folder_url.'/'.$now.$filename;
					  $url = $rel_url.'/'.$now.$filename;
					  $success = move_uploaded_file($file['tmp_name'], $url);
				   }
				   // if upload was successful
				   if($success) {
					  // save the url of the file
					  $result['urls'][] = $url;
				   } else {
					  $result['errors'][] = "Error uploaded $filename. Please try again.";
				   }
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
			 $result['errors'][] = "$filename cannot be uploaded. Acceptable file types: $pr.";
		  }
	   }
		return $result;
	}
	
	/* deprecated
	function mediumBlob($formdata)
	{
	   // loop through and deal with the files
	   foreach($formdata as $file)
	   {
		  // replace spaces with underscores
		  $filename = str_replace(' ', '_', $file['name']);
		  // assume filetype is false
		  $typeOK = false;
		  // check filetype is ok
		  $filetype = $file['type'];
		  foreach($this->permitted as $type)
		  {
			 if($type == $filetype) {
				$typeOK = true;
				break;
			 }
		  }
	 
		  // if file type ok upload the file
		  if($typeOK) {
			 // switch based on error code
			 switch($file['error']) {
				case 0:
					$filesize = filesize($file['tmp_name']);
					
					if(!$filesize > 0)
					{
						// an error occured
						$result['errors'][] = "Error uploading $filename. Please try again.";
						break;
					}
					
					$fp      = fopen($file['tmp_name'], 'r');
					$content = fread($fp, $filesize);
					fclose($fp);
					
					if(!get_magic_quotes_gpc())
					{
						$filename = addslashes($filename);
					}
					
					$blob['name'] = $filename;
					$blob['size'] = $filesize;
					$blob['comment'] = $filename . ' ' . $filesize;
					$blob['content'] = $content;
					$blob['type'] = $filetype;
					$newResult['MediumBlob'] = $blob;
					$result[] = $newResult;
					
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
	*/
	
	/**
	 * Returns a valid array for a HABTM relation for saveAll
	 *
	 * @params:
	 *    	$formdata	= the array containing the form files
	 *		$comment = File comment
	 *    	$objectId 	= id of the related object
	 *	   	$model		= name of the related model
	 * @return:
	 *    on failure: will return a description of the errors in array result['errors']
	 *	  on success: returns an array like (if objectIds is only one number)
	 *			array(	[0] => array( 
	 *						['Item'] => array(
	 *							[id] => objectId
	 *						)
	 *						['MediumBlob'] => array(
	 *							['name'] => 'myName',
	 *							['size'] => 'mySize',
	 *							...
	 *						)
	 *					)
	 *					[1] => array( 
	 *						['Item'] => array(
	 *							[id] => objectId
	 *						)
	 *						['MediumBlob'] => array(
	 *							['name'] => 'myName',
	 *							['size'] => 'mySize',
	 *							...
	 *						)
	 *					)
	 *					...
	 *	  on success: returns an array like (if objectIds is an array([Item] => array([0]=> 1002, [1] => 1003)))
	 *			array(	[0] => array( 
	 *						['Item'] => array(
 *								[0] => 1002,
 *								[1] => 1003
	 *						)
	 *						['MediumBlob'] => array(
	 *							['name'] => 'myName1',
	 *							['size'] => 'mySize1',
	 *							...
	 *						)
	 *					)
	 *					[1] => array( 
	 *						['Item'] => array(
 *								[0] => 1002,
 *								[1] => 1003
	 *						)
	 *						['MediumBlob'] => array(
	 *							['name'] => 'myName2',
	 *							['size'] => 'mySize2',
	 *							...
	 *						)
	 *					)
	 *					...
	 */
	function addMediumBlob($formdata, $comment, $objectIds, $model)
	{
	   // loop through and deal with the files
	   debug($formdata);
	   debug($objectIds);
	   foreach($formdata as $file)
	   {
		  // replace spaces with underscores
		  $filename = str_replace(' ', '_', $file['name']);
		  // assume filetype is false
		  $typeOK = false;
		  // check filetype is ok
		  $filetype = $file['type'];
		  /*
		  foreach($this->permitted as $type)
		  {
			 if($type == $filetype) {
				$typeOK = true;
				break;
			 }
		  }
		   */ 
		  // accepting every type 
		  $typeOK = true;
	 
		  // if file type ok upload the file
		  if($typeOK) {
			 // switch based on error code
			 switch($file['error']) {
				case 0:
					$filesize = filesize($file['tmp_name']);
					
					if(!$filesize > 0)
					{
						// an error occured
						$result['errors'][] = "Error uploading $filename. Please try again.";
						break;
					}
					
					$fp      = fopen($file['tmp_name'], 'r');
					$content = fread($fp, $filesize);
					fclose($fp);
					
					if(!get_magic_quotes_gpc())
					{
						$filename = addslashes($filename);
					}
					
					$blob['name'] = $filename;
					$blob['size'] = $filesize;
					$blob['comment'] = $comment;
					//$blob['content'] = $content;
					$blob['type'] = $filetype;
					$newResult['MediumBlob'] = $blob;
					
					if(is_array($objectIds))
						$newResult[$model] = $objectIds;
					else
						$newResult[$model]['id'] = $objectIds;
					
					$result[] = $newResult;
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
	
	function addLongBlob($formdata, $comment, $objectIds, $model)
	{
	   // loop through and deal with the files
	   foreach($formdata as $file)
	   {
		  // replace spaces with underscores
		  $filename = str_replace(' ', '_', $file['name']);
		  // assume filetype is false
		  $typeOK = false;
		  // check filetype is ok
		  $filetype = $file['type'];
		  /*
		  foreach($this->permitted as $type)
		  {
			 if($type == $filetype) {
				$typeOK = true;
				break;
			 }
		  }
		   */
		  // accepting every type 
		  $typeOK = true;
	 
		  // if file type ok upload the file
		  if($typeOK) {
			 // switch based on error code
			 switch($file['error']) {
				case 0:
					$filesize = filesize($file['tmp_name']);
					
					if(!$filesize > 0)
					{
						// an error occured
						$result['errors'][] = "Error uploading $filename. Please try again.";
						break;
					}
					
					$fp      = fopen($file['tmp_name'], 'r');
					$content = fread($fp, $filesize);
					fclose($fp);
					
					if(!get_magic_quotes_gpc())
					{
						$filename = addslashes($filename);
					}
					
					$blob['name'] = $filename;
					$blob['size'] = $filesize;
					$blob['comment'] = $comment;
					$blob['content'] = $content;
					$blob['type'] = $filetype;
					$newResult['LongBlob'] = $blob;
					if(is_array($objectIds))
						$newResult[$model] = $objectIds;
					else
						$newResult[$model]['id'] = $objectIds;
					$result[] = $newResult;
					
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
					foreach($lines as $i => $line) {
						if('#' != substr ($line, 0, 1)) {
							$content[] = preg_split($pattern, $line, -1, PREG_SPLIT_NO_EMPTY); //split line into rows by user choosen pattern (e.g. \t)
						}
					}
					
					$result['files'][$file['name']] = $content;
					
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