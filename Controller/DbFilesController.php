<?php
App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
/**
 * DbFiles Controller
 *
 * @property DbFile $DbFile
 * @property Plupload.PluploadComponent $Plupload.Plupload
 */
class DbFilesController extends AppController {

/**
 * Helpers
 *
 * @var array
 */
	public $helpers = array('Html', 'Plupload.Plupload');
/**
 * Components
 *
 * @var array
 */
	public $components = array('Plupload.Plupload');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->DbFile->recursive = 0;
		$this->set('dbFiles', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id The id of the database file
 * @param string $model The related model to this file (Project, Item, ItemType, ItemSubtype or ItemSubtypeversion)
 * @param string $modelId The id of the related model object
 * @return void
 */
	public function view($id = null) {
		$this->DbFile->id = $id;
		if (!$this->DbFile->exists()) {
			throw new NotFoundException(__('Invalid db file'));
		}
		$dbFile = $this->DbFile->find('first', array('conditions' => array('DbFile.id' => $id)));

		$uploadDir = $this->Plupload->getUploadDir();
		$dir = new Folder($uploadDir);
		$dbFile['DbFile']['filepath'] = $dir->pwd() . DS . $dbFile['DbFile']['real_name'];
		if(mime_content_type($dbFile['DbFile']['filepath']) != $dbFile['DbFile']['type']){
			$dbFile['DbFile']['type'] = mime_content_type($dbFile['DbFile']['filepath']);
			$this->DbFile->save($dbFile);
			debug("updated");
			$dbFile = $this->DbFile->find('first', array('conditions' => array('DbFile.id' => $id)));
		}

		$dbFile['DbFile']['content'] = $this->_load($dbFile);
		$this->set(compact('dbFile'));
	}

/**
 * add method
 *
 * @return void
 */
	public function add($model, $id = null) {
		if(array_key_exists($model, $this->DbFile->hasAndBelongsToMany)) {
			//*
			$this->DbFile->{$model}->id = $id;
			if ($this->DbFile->{$model}->exists()) {
				$url = Router::url(array('plugin' => 'plupload','controller' => 'plupload', 'action' => 'upload'));
				$this->Plupload->setUploaderOptions(array(
			        'runtimes' => 'html5',
					'url' => $url,
					//'url' => '/ds20kcondb/plupload/plupload/upload',
					'max_file_size' => Configure::read('Upload.max_file_size'),
					'chunk_size' => Configure::read('Upload.chunk_size'),
					'multipart_params' => array(
							'data[uploadtype]' => "file",
							'data[model]' => $model,
							'data[foreign_key]' => $id,)
					//'init' => array( 'UploadComplete' => 'function(up, files) { alert("hui"); }')
			    ));

				$this->set('id', $id);
				$this->set('model', $model);
				$this->set('item', $this->DbFile->{$model}->find('first', array('conditions' => array($model.'.id' => $id), 'recursive' => 0)));
			} else {
				$this->Session->setFlash($model.' with id '.$id.' not found.');
			}
		} else {
			$this->Session->setFlash('You cannot add a file to an '. $model);
			//$this->redirect($this->referer());
		}
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->DbFile->id = $id;
		if (!$this->DbFile->exists()) {
			throw new NotFoundException(__('Invalid db file'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->DbFile->save($this->request->data)) {
                $this->Session->setFlash(__('The db file has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The db file could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->DbFile->read(null, $id);
		}
		$itemSubtypeVersions = $this->DbFile->ItemSubtypeVersion->find('list');
		$itemSubtypes = $this->DbFile->ItemSubtype->find('list');
		$items = $this->DbFile->Item->find('list');
		$this->set(compact('itemSubtypeVersions', 'itemSubtypes', 'items'));
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($model, $item_id, $db_file_id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->DbFile->id = $db_file_id;
		if (!$this->DbFile->exists()) {
			throw new NotFoundException(__('Invalid file'));
		}

		$this->DbFile->recursive=-1;
		$db_file = $this->DbFile->findById($db_file_id);

		$dataSource = $this->DbFile->getDataSource();
		$dataSource->begin();

		if ($this->DbFile->delete()) {
			$uploadDir = $this->Plupload->getUploadDir();

			$dir = new Folder($uploadDir);
			$file = new File($dir->pwd() . DS . $db_file['DbFile']['real_name']);
		    if($file->delete()){
		    	$dataSource->commit();
		    	$this->Session->setFlash(__('File deleted'), 'default', array('class' => 'notification'));
		    } else {
				$dataSource->rollback();
		    	$this->Session->setFlash(__('File was not deleted'));
		    }
		    $file->close(); // Be sure to close the file when you're done
		} else {
			$dataSource->rollback();
			$this->Session->setFlash(__('File was not deleted'));
		}
		return $this->redirect($this->referer());
	}

	/**
	 * download method
	 *
	 * @param string $id
	 * @return void
	 */
	function download($id, $download = false) {
        $this->DbFile->recursive=-1;
        $db_file = $this->DbFile->findById($id);

        $uploadDir = $this->Plupload->getUploadDir();
        $dir = new Folder($uploadDir);
        $db_file['DbFile']['filepath'] = $dir->pwd() . DS . $db_file['DbFile']['real_name'];

        //Optionally force file download
        if(!$download) {
           $this->response->file($db_file['DbFile']['filepath']);
        } else {
            if(in_array($db_file['DbFile']['type'],array('application/octet-stream','image/svg+xml'))) {
                $this->response->type('text/plain');
            }

            $this->response->file(
                $db_file['DbFile']['filepath'],
                array(
                    'download' => true,
                    'name' => $db_file['DbFile']['name']
                )
            );
        }

        // Return response object to prevent controller from trying to render a view
        return $this->response;
    }

    /*
     * Loads the content of a plain text file or a PDF.
     * If the file is a PDF its returning a Response to prevent loading a view,
     * so the PDF will be loaded from a browser plugin
     */
    private function _load($dbFile, $download = false) {
        $uploadDir = $this->Plupload->getUploadDir();
        $dir = new Folder($uploadDir);
        $dbFile['DbFile']['filepath'] = $dir->pwd() . DS . $dbFile['DbFile']['real_name'];

        switch ($dbFile['DbFile']['type']) {
            case 'text/plain':
            //case 'application/octet-stream':
                //IMPORTANT!  turn off debug output, will corrupt filestream.
                Configure::write('debug', 0);

                $file = new File($dbFile['DbFile']['filepath']);
                $content = $file->read();
                $file->close();
                return $content;
                break;
			case 'image/svg+xml':
            case 'application/pdf':
                $this->response->file(
                    $dbFile['DbFile']['filepath'],
                    array('download' => $download, 'name' => $dbFile['DbFile']['name'])
                );

                // Setting the response MimeType
                $this->response->type($dbFile['DbFile']['type']);

                // Return response object to prevent controller from trying to render a view
                return $this->response;
                break;
			default:
        }
    }

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function changeComment($id = null) {

		$this->DbFile->id = $id;
		if (!$this->DbFile->exists()) {
			throw new NotFoundException(__('Invalid db file'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			unset($this->DbFile->validate['name']);
			unset($this->DbFile->validate['real_name']);
			unset($this->DbFile->validate['type']);
			unset($this->DbFile->validate['size']);

			//$this->loadModel('Log');
			//$this->Log->saveLog('Item subtype edited', $this->request->data);

			if ($this->DbFile->save($this->request->data)) {
				$this->Session->setFlash(__('The comment of the db file has been saved'), 'default', array('class' => 'notification'));

				$ref = $this->Session->read('Referer');
				$this->Session->delete('Referer');
				return $this->redirect($ref);
			} else {
				$this->Session->setFlash(__('The db file could not be saved. Please, try again.'));
			}
		} else {
			$ref = $this->referer();
			$this->Session->write('Referer', $ref);
			$this->request->data = $this->DbFile->read(null, $id);
			$this->set('ref', $ref);
		}
	}
}
