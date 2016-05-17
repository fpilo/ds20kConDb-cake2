<?php
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

/**
 * CakePHP Plupload Plugin
 * Plupload Controller
 *
 * Copyright (c) 2011 junichi11
 *
 * @author junichi11
 * @license MIT LICENCE
 */
class PluploadController extends PluploadAppController {

	public $name = 'Plupload';

	public $uses = array();

	public $helpers = array('Session', 'Plupload.Plupload');

	public $components = array('Session', 'RequestHandler', 'Plupload.Plupload');




/**
 * upload
 * Generic function that is called when the plupload plugin tries to upload a file
 * Calls the function required according to the parameter
 */
	public function upload() {
		parent::upload();
		$error = null;

		// HTTP headers for no cache etc
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		// Settings
		if(isset($this->request['data']['uploadtype'])){
			if($this->request['data']['uploadtype'] == "measurement"){
				$this->_upload_measurement();
			}elseif($this->request['data']['uploadtype'] == "cmpList"){
				$this->_upload_cmpList();
			}elseif($this->request['data']['uploadtype'] == "file"){
				$this->_upload_attachmentfile();
			}else 
				$this->set('response', array('code' => 101, 'message' => 'Failed before upload.'));
		}else{
			$this->set('response', array('code' => 101, 'message' => 'Failed before upload.'));
		}

	}

/**
 * _upload_measurement
 * Handles measurement uploads by passing a parameter of where to save measurement files temporary
 * returns a request object that allows for ajax to request a preview
 */


	function _upload_measurement(){
		$result = $this->_upload_file(MEAS_TMP);
		$return["local"] = $result["fileName"];
		if(isset($this->request["data"]["itemId"]))
			$return["itemId"] = $this->request["data"]["itemId"];
		$this->set("response",$return);
	}

/**
 * _upload_cmpList
 * Handles components list uploads by passing a parameter of where to save cmpList files temporary
 * returns a request object that allows for ajax to request a preview
 */


	function _upload_cmpList(){
		$result = $this->_upload_file(CMPLIST_TMP);
		$return["local"] = $result["fileName"];
		if(isset($this->request["data"]["itemId"]))
			$return["itemId"] = $this->request["data"]["itemId"];
		$this->set("response",$return);
	}

/**
 * _upload_attachmentfile
 * saves a file as an attachment to an item
 *
 */

	function _upload_attachmentfile(){
		$result = $this->_upload_file();
		if(!is_array($result)){
			return false;
		}
		// Create database entry
		$dbFile	= array(
			'DbFile' => array(
				'name' => $this->request->data['name'],
				'real_name' => $result['fileName'],
				'type' => $_FILES['file']['type'],
				'size' => $result['size']
			),
			$this->data['model'] => array($this->data['foreign_key'])
		);
		$dbFile['targetDir'] = $result['targetDir'];

		Configure::write('debug', 0);
		$this->loadModel('DbFile');
		$this->DbFile->set($dbFile);
		if($this->DbFile->validates()) {
			$this->DbFile->saveAll($dbFile);
			//Store the adding of a file only in the history if the target is an item, otherwise skip
			if($this->data["model"] == "Item"){
				$this->loadModel("History");
				$item_id = $this->data["foreign_key"];
				$this->History->insertIntoHistory("File added to item",$item_id,"Uploaded file ".$this->request->data["name"]);
			}
			$this->set('response', array('code' => 0, 'message' => 'Successfuly saved new file.'));
		} else {
			@unlink($filePath);
			$this->set('response', array('code' => 104, 'message' => 'Failed to register file in database.'));
			return false;
		}
	}


	function _upload_file($targetDir = null){
		//$targetDir = 'uploads';
		$targetDir = (!isset($targetDir)) ? $this->Plupload->getUploadDir(): $targetDir;
		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds

		// 5 minutes execution time
		@set_time_limit(5 * 60);

		// Uncomment this one to fake upload time
		// usleep(5000);

		// Get parameters
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
		$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

		$size = 0;
		if($chunk == 0) {
			$this->Session->delete('PlUploadFileSize');
		} else {
			$size = $this->Session->read('PlUploadFileSize');
		}

		$size = $size + $_FILES['file']['size'];
		$this->Session->write('PlUploadFileSize', $size);

		// Clean the fileName for security reasons
		$fileName = preg_replace('/[^\w\._]+/', '_', $fileName);

		// Make sure the fileName is unique
		if (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
			$ext = strrpos($fileName, '.');
			$fileName_a = substr($fileName, 0, $ext);
			$fileName_b = substr($fileName, $ext);

			$count = 1;
			while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
				$count++;

			$fileName = $fileName_a . '_' . $count . $fileName_b;
		}

		$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

		// Create target dir
		if (!file_exists($targetDir))
			@mkdir($targetDir);

		// Remove old temp files
		if ($cleanupTargetDir && is_dir($targetDir) && ($dir = opendir($targetDir))) {
			while (($file = readdir($dir)) !== false) {
				$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

				// Remove temp file if it is older than the max age and is not the current file
				if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
					@unlink($tmpfilePath);
				}
			}

			closedir($dir);
		} else {
			$this->set('response', array('code' => 100, 'message' => "Failed to open upload directory $targetDir."));
			return false;
		}


		// Look for the content type header
		if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
			$contentType = $_SERVER["HTTP_CONTENT_TYPE"];

		if (isset($_SERVER["CONTENT_TYPE"]))
			$contentType = $_SERVER["CONTENT_TYPE"];

		// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
		if (strpos($contentType, "multipart") !== false) {
			if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
				// Open temp file
				$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
				if ($out) {
					// Read binary input stream and append it to temp file
					$in = fopen($_FILES['file']['tmp_name'], "rb");

					if ($in) {
						while ($buff = fread($in, 4096))
							fwrite($out, $buff);
					} else {
						$this->set('response', array('code' => 101, 'message' => 'Failed to open input stream.'));
						return false;
					}
					fclose($in);
					fclose($out);
					@unlink($_FILES['file']['tmp_name']);
				} else {
					$this->set('response', array('code' => 102, 'message' => 'Failed to open output stream.'));
					return false;
				}
			} else {
				$this->set('response', array('code' => 103, 'message' => 'Failed to move uploaded file.'));
				return false;
			}
		} else {
			// Open temp file
			$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
			if ($out) {
				// Read binary input stream and append it to temp file
				$in = fopen("php://input", "rb");

				if ($in) {
					while ($buff = fread($in, 4096))
						fwrite($out, $buff);
				} else {
					$this->set('response', array('code' => 101, 'message' => 'Failed to open input stream.'));
					return false;
				}

				fclose($in);
				fclose($out);
			} else {
				$this->set('response', array('code' => 102, 'message' => 'Failed to open output stream.'));
				return false;
			}
		}

		// Check if file has been uploaded
		if (!$chunks || $chunk == $chunks - 1) {
			// Strip the temp .part suffix off
			rename("{$filePath}.part", $filePath);
			return array("fileName"=>$fileName,"size"=>$size,"targetDir"=>$targetDir);
		}else{
			$this->set('response', array('code' => 105, 'message' => 'Failed to upload file.'));
			return false;
		}
	}

	/**
	 * widget
	 * @param string $ui jquery | jquryui
	 */
	public function widget($ui = "jqueryui") {
		$this->set('ui', $ui);
		$additionalCallbacks = $this->Session->read('additionalCallbacks');
		$this->set('additionalCallbacks',$additionalCallbacks);
	}

}