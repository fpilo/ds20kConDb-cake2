<?php
App::uses('AppController', 'Controller');
/**
 * ItemSubtypeVersions Controller
 *
 * @property ItemSubtypeVersion $ItemSubtypeVersion
 */
class ItemSubtypeVersionsController extends AppController {

	public $components = array('RequestHandler');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->ItemSubtypeVersion->recursive = 0;
		$this->set('itemSubtypeVersions', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->ItemSubtypeVersion->id = $id;
		$measurements = array();
		if (!$this->ItemSubtypeVersion->exists()) {
			throw new NotFoundException(__('Invalid item subtype version'));
		}
		$itemSubtypeVersion = $this->ItemSubtypeVersion->find('first', array(
																			'conditions' => array('ItemSubtypeVersion.id' => $id),
																			'contain' => array(
																						'Manufacturer',
																						'Project',
																						'ItemSubtype.ItemType',
																						'ItemSubtype.DbFile.User',
																						'DbFile.User',
																						'Item',
																						'Component.ItemSubtype.ItemType',
																						'Component.Manufacturer')));
      //debug($itemSubtypeVersion);
		$componentProjects = $this->ItemSubtypeVersion->Project->find('list');
		foreach($itemSubtypeVersion['Component'] as $key => $myComponent) {
			$itemSubtypeVersion['Component'][$key]['ItemSubtypeVersionsComposition']['project_name'] = $componentProjects[$myComponent['ItemSubtypeVersionsComposition']['project_id']];
		}

		$bla = $this->ItemSubtypeVersion->Item->Measurement->MeasurementType->findByName("Strip Measurement","MeasurementType.id");
		$stripMeasurementTypeId = $bla["MeasurementType"]["id"];
		$bla = $this->ItemSubtypeVersion->Item->Measurement->MeasurementType->findByName("Strip errors","MeasurementType.id");
		$stripErrorMeasurementTypeId = $bla["MeasurementType"]["id"];

		$stripMeasurements = $this->ItemSubtypeVersion->Item->find("all",array(
												'joins'=>array(
													array(
														'table'=>'measurements',
														'alias'=>'Measurement',
														'type'=>'inner',
														'conditions'=>array("Measurement.item_id=Item.id")
													),
												),
												'conditions'=>array("ItemSubtypeVersion.id"=>$id,"Measurement.measurement_type_id"=>$stripMeasurementTypeId),
												'contain'=>array("ItemSubtypeVersion","Measurement","Measurement.MeasurementTag"),
												'fields'=>array("Item.id","Item.code")
											));
		#Since the cakephp join is weird and also adds measurements that are not of type 5 they need to be filtered out here
		foreach($stripMeasurements as $num1=>$item){
			foreach($item["Measurement"] as $num2=>$measurement){
				if($measurement["measurement_type_id"] != $stripMeasurementTypeId) unset($stripMeasurements[$num1]["Measurement"][$num2]);
			}
		}
		$measurementTypes = $this->ItemSubtypeVersion->Item->Measurement->MeasurementType->find("list");
		$measurementDevices = $this->ItemSubtypeVersion->Item->Measurement->Device->find("list");
		#Array containing a key=>value pair of  Measurement Id and The Measurement Parameter Value for the StripMeasurementId (if applicable) otherwise 0
		$stripErrorMeasurements = $this->ItemSubtypeVersion->Item->find("list",array(
												'joins'=>array(
													array(
														'table'=>'measurements',
														'alias'=>'Measurement',
														'type'=>'INNER',
														'conditions'=>array("Measurement.item_id=Item.id")
													),
													array(
														'table'=>'measurement_parameters',
														'alias'=>'MeasurementParameter',
														'type'=>'LEFT',
														'conditions'=>array("MeasurementParameter.measurement_id=Measurement.id","MeasurementParameter.parameter_id"=>123)
													),
												),
												'conditions'=>array("ItemSubtypeVersion.id"=>$id,"Measurement.measurement_type_id"=>$stripErrorMeasurementTypeId),
												'contain'=>array("ItemSubtypeVersion"),
												'fields'=>array("Measurement.id","MeasurementParameter.value")
											));
		$previousBatch = array();
		$measurements = array();
		foreach($stripMeasurements as $num1=>$item){
         #debug($item["Measurement"]);
			foreach($item["Measurement"] as $num2=>$measurement){
				#construct array of values that are in a nicer format than the standard format
				$temp = array();
				$temp["id"] = $item["Item"]["id"];
				$temp["code"] = $item["Item"]["code"];
				$temp["measurement_id"] = $measurement["id"];
				$temp["measurement_type_id"] = $measurement["measurement_type_id"];
				$temp["measurement_type"] = $measurementTypes[$measurement["measurement_type_id"]];
				$temp["measurement_device_id"] = $measurement["device_id"];
				$temp["measurement_device"] = $measurementDevices[$measurement["device_id"]];
				$temp["measurement_tags"] = array();
				foreach($measurement["MeasurementTag"] as $mTag){
					$temp["measurement_tags"][$mTag["id"]] = $mTag["name"];
				}
				$stripErrorMeasurement = $this->ItemSubtypeVersion->Item->Measurement->MeasurementParameter->getStripErrorMeasurement($measurement["id"]);
				if(isset($stripErrorMeasurement["MeasurementParameter"]["measurement_id"])){
					$temp["strip_error_measurement_id"] = $stripErrorMeasurement["MeasurementParameter"]["measurement_id"];
				}
            #debug($temp);
            
            #condition that checks if the measurement is in either the measurements or previousBatch arrays
            if(in_array($measurement["id"], $stripErrorMeasurements)
                  &&
                  (
                     !in_array($measurement["id"],array_keys($measurements))
                     ||
                     !in_array($measurement["id"],array_keys($previousBatch)) 
                  )
               )
            {
            # debug($item["Item"]["code"]." already has a Strip Error Measurement associated, ignoring");
               $previousBatch[$temp["measurement_id"]] = $temp;
            }else{
               $measurements[$temp["measurement_id"]] = $temp;
            }
			}
		}
		$this->set(compact("measurements","previousBatch"));
		$this->set('itemSubtypeVersion', $itemSubtypeVersion);
	}

	private function _checkPosition($myComponents) {
		if(!empty($myComponents)) {
			foreach($myComponents as $key => $myComponent) {
				$position = $myComponent['position'];
				foreach($myComponents as $k => $c) {
					if(($myComponent['item_subtype_id'] != $c['item_subtype_id']) && ($position == $c['position'])) {
						return false;
               }
				}
			}
		}
		return true;
	}

/**
 * add method
 *
 * @return void
 */
	public function add($id) {
		$User = ClassRegistry::init('User');

		if ($this->request->is('post')) {
			$this->ItemSubtypeVersion->create();
			$this->request->data['ItemSubtypeVersion']['item_subtype_id'] = $id;
			$itemSubtype = $this->ItemSubtypeVersion->ItemSubtype->find('first', array('conditions' => array('ItemSubtype.id' => $id)));
			$this->request->data['ItemSubtype'] = $itemSubtype['ItemSubtype'];

			if(!empty($this->request->data['SubtypeComponent'])) {
				$this->request->data['Component'] = $this->request->data['SubtypeComponent'];
				unset($this->request->data['SubtypeComponent']);

				$this->request->data['ItemSubtypeVersion']['has_components'] = '1';
				$position_check = $this->_checkPosition($this->request->data['Component']);
			}
			else {
				$this->request->data['ItemSubtypeVersion']['has_components'] = '0';
				$position_check = true;
			}

			if($position_check) {
				if(!empty($this->request->data['ItemSubtypeVersion']['manufacturer_id'])) {
					//*
					if ($this->ItemSubtypeVersion->save($this->request->data)) {
						$this->Session->delete('addItemSubtypeVersion.'.$id);
						CACHE::clear(false,"default"); //Clear the default cache since it stores the prefetched array for all users
						$this->Session->setFlash(__('The item subtype version has been saved'), 'default', array('class' => 'notification'));

						$this->loadModel('Log');
						$this->Log->saveLog('Item subtype version added', $this->request->data);

						return $this->redirect(array('controller' => 'item_subtypes', 'action' => 'view', $id));
					} else {
						$this->Session->setFlash(__('The item subtype version could not be saved. Please check the data for correctness.'));
					}
					//*/
				} else {
					$this->ItemSubtypeVersion->set($this->request->data);
					$this->ItemSubtypeVersion->validates();
					//debug($this->ItemSubtypeVersion->invalidFields());
					$this->Session->setFlash(__('Please select a Manufacturer for the new version.'));
				}
			}
			else {
				$this->Session->setFlash(__('Components with different subtypes must have a different position.'));
			}
		}

		foreach($User->getUsersProjects() as $project_name => $project_id) {
			$projects[$project_id] = $project_name;
		}

		/**** create $manufacturers[project_id] ****/
		$conditions['OR']['Project.id'] = $User->getUsersProjects();

		$results = $this->ItemSubtypeVersion->Project->find('all', array(
												'conditions' => $conditions,
												'contain' => array('Manufacturer')));

		foreach($results as $project){
			foreach($project['Manufacturer'] as $manufacturer) {
				$manufacturers[$project['Project']['id']][$manufacturer['id']] = $manufacturer['name'];
			}
		}
		/*******************************************/

		$latest_version = 0;

		$parentItemSubtype = $this->ItemSubtypeVersion->ItemSubtype->find('first', array(
															'conditions' => array('ItemSubtype.id' => $id),
															'contain' => array('ItemSubtypeVersion', 'ItemType')));
      //select max(version) from item_subtype_versions i2 where i2.item_subtype_id = i1.item_subtype_id
    
		$max_version = $this->ItemSubtypeVersion->find('first', array(
															'conditions' => array('ItemSubtypeVersion.item_subtype_id' => $id),
															'fields' => array('MAX(ItemSubtypeVersion.version) as max'),
															'recursive' => -1
															));

		if($max_version[0]['max'] != NULL) {
			$max_version = $max_version[0]['max'];
			$latest_version = $this->ItemSubtypeVersion->find('first', array(
															'conditions' => array('AND' => array(
																	'ItemSubtypeVersion.item_subtype_id' => $id,
																	'ItemSubtypeVersion.version' => $max_version
																	)
																),
															'contain' => array(
																	'Component.Manufacturer',
																	'Component.ItemSubtype.ItemType'
																	)
															));

			$componentProjects = $this->ItemSubtypeVersion->Project->find('list');
			foreach($latest_version['Component'] as $key => $myComponent) {
				$latest_version['Component'][$key]['ItemSubtypeVersion']['id'] 				= $myComponent['id'];
				$latest_version['Component'][$key]['ItemSubtypeVersion']['item_subtype_id'] = $myComponent['item_subtype_id'];
				$latest_version['Component'][$key]['ItemSubtypeVersion']['version'] 		= $myComponent['version'];
				$latest_version['Component'][$key]['ItemSubtypeVersion']['manufacturer_id'] = $myComponent['manufacturer_id'];
				$latest_version['Component'][$key]['ItemSubtypeVersion']['has_components'] 	= $myComponent['has_components'];
				$latest_version['Component'][$key]['ItemSubtypeVersion']['comment']			= $myComponent['comment'];
				$latest_version['Component'][$key]['ItemSubtypeVersionsComposition']['project_name'] = $componentProjects[$myComponent['ItemSubtypeVersionsComposition']['project_id']];
				unset($latest_version['Component'][$key]['id']);
				unset($latest_version['Component'][$key]['item_subtype_id']);
				unset($latest_version['Component'][$key]['version']);
				unset($latest_version['Component'][$key]['manufacturer_id']);
				unset($latest_version['Component'][$key]['has_components']);
				unset($latest_version['Component'][$key]['comment']);
			}
		}
		else {
			$max_version = 0;
		}

		if ($this->Session->check('addItemSubtypeVersion.'.$id)) {
			$myComponents = $this->Session->read('addItemSubtypeVersion.'.$id);
		} else {
			$myComponents = $latest_version['Component'];
			$this->Session->write('addItemSubtypeVersion.'.$id, $myComponents);
		}
		$this->set("editWithAttached",false); // to make the view work with update_components.ctp
		$this->set(compact('latest_version', 'parentItemSubtype', 'projects', 'manufacturers', 'myComponents', 'componentProjects'));
	}

	public function resetAdd($item_subtype_id) {
		$this->Session->delete('addItemSubtypeVersion.'.$item_subtype_id);
		return $this->redirect(array('controller' => 'ItemSubtypeVersions', 'action' => 'add', $item_subtype_id));
	}

	public function removeAllComponents($item_subtype_id) {
		$this->Session->write('addItemSubtypeVersion.'.$item_subtype_id, array());
		return $this->redirect(array('controller' => 'ItemSubtypeVersions', 'action' => 'add', $item_subtype_id));
	}

	public function resetEdit($item_subtype_version_id) {
		$this->Session->delete('editItemSubtypeVersion.'.$item_subtype_version_id);
		return $this->redirect(array('controller' => 'ItemSubtypeVersions', 'action' => 'edit', $item_subtype_version_id));
	}

/**
 * editComment method
 *
 * @param string $id
 * @return void
 */
	public function editComment($id = null) {
		$this->ItemSubtypeVersion->id = $id;
		if (!$this->ItemSubtypeVersion->exists()) {
			throw new NotFoundException(__('Invalid item subtype version'));
		}

		$itemSubtypeVersion = $this->ItemSubtypeVersion->find('first', array(
																		'conditions' => array('ItemSubtypeVersion.id' => $id),
																		'recursive' => -1,
																		'contain'=>array("ItemSubtype.ItemType","ItemSubtype")));

		if ($this->request->is('post') || $this->request->is('put')) {
			unset($this->ItemSubtypeVersion->validate['version']);
			unset($this->ItemSubtypeVersion->validate['has_components']);
			unset($this->ItemSubtypeVersion->validate['item_subtype_id']);
			unset($this->ItemSubtypeVersion->validate['manufacturer_id']);

			//F.P. 2014-02-28: ItemSubtypeVersion version is not defined in the comment edit form --> added by hand
			$this->request->data['ItemSubtypeVersion']['version']=$itemSubtypeVersion['ItemSubtypeVersion']['version'];

			if ($this->ItemSubtypeVersion->save($this->request->data)) {
				$this->loadModel('Log');
				$this->Log->saveLog('Item subtype version edited', $this->request->data);

				$this->Session->setFlash(__('The item subtype version has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('controller' => 'item_subtype_versions', 'action' => 'view', $itemSubtypeVersion['ItemSubtypeVersion']['id']));
			} else {
				debug($this->ItemSubtypeVersion->validationErrors);
				$this->Session->setFlash(__('The item subtype version could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $itemSubtypeVersion;
		}
	}
/**
 * editName method
 *
 * @param string $id
 * @return void
 */
	public function editName($id = null) {
		$this->ItemSubtypeVersion->id = $id;
		if (!$this->ItemSubtypeVersion->exists()) {
			throw new NotFoundException(__('Invalid item subtype version'));
		}

		$itemSubtypeVersion = $this->ItemSubtypeVersion->find('first', array(
																		'conditions' => array('ItemSubtypeVersion.id' => $id),
																		'recursive' => -1));

		if ($this->request->is('post') || $this->request->is('put')) {
			unset($this->ItemSubtypeVersion->validate['version']);
			unset($this->ItemSubtypeVersion->validate['has_components']);
			unset($this->ItemSubtypeVersion->validate['item_subtype_id']);
			unset($this->ItemSubtypeVersion->validate['manufacturer_id']);

			//F.P. 2014-02-28: ItemSubtypeVersion version is not defined in the comment edit form --> added by hand
			$this->request->data['ItemSubtypeVersion']['version']=$itemSubtypeVersion['ItemSubtypeVersion']['version'];

			if ($this->ItemSubtypeVersion->save($this->request->data)) {
				$this->loadModel('Log');
				$this->Log->saveLog('Item subtype version renamed', $this->request->data);

				CACHE::clear(false,"default"); //Clear the default cache since it stores the prefetched array for all users
				$this->Session->setFlash(__('The item subtype version has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('controller' => 'item_subtype_versions', 'action' => 'view', $itemSubtypeVersion['ItemSubtypeVersion']['id']));
			} else {
				debug($this->ItemSubtypeVersion->validationErrors);
				$this->Session->setFlash(__('The item subtype version could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $itemSubtypeVersion;
		}
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$User = ClassRegistry::init('User');

      // see if there exist any items of this subtypeVersion, if yes need to disallow removal of components
		$count_items = $this->ItemSubtypeVersion->Item->find('count', array(
								'conditions' => array('Item.item_subtype_version_id' => $id)));
      $editWithAttached = ($count_items>0);

		$this->ItemSubtypeVersion->id = $id;
		if (!$this->ItemSubtypeVersion->exists()) {
			throw new NotFoundException(__('Invalid item subtype version'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			unset($this->ItemSubtypeVersion->validate['version']);
			unset($this->ItemSubtypeVersion->validate['has_components']);
			unset($this->ItemSubtypeVersion->validate['item_subtype_id']);

			//Make sure all the data required for saving is present
			// $itemSubtypeVersion = $this->ItemSubtypeVersion->find("first");
			// $this->request->data["ItemSubtypeVersion"] = array_merge($this->request->data["ItemSubtypeVersion"],$itemSubtypeVersion["ItemSubtypeVersion"]);
			// $this->request->data["Project"] = array("Project"=>array(1));

			if(!empty($this->request->data['SubtypeComponent'])) {
				$this->request->data['Component'] = $this->request->data['SubtypeComponent'];
				unset($this->request->data['SubtypeComponent']);
			}

			if(!empty($this->request->data['Component'])) {
				$this->request->data['ItemSubtypeVersion']['has_components'] = '1';
				$position_check = $this->_checkPosition($this->request->data['Component']);
			} else {
				$this->request->data['ItemSubtypeVersion']['has_components'] = '0';
				$this->request->data['Component'] = array(0=>array());
				$position_check = true;
			}
			
			if(isset($this->request->data["ItemSubtypeVersion"]["project_id"])){
				//A component was added and therefore some data needs to be removed
				unset($this->request->data["ItemSubtypeVersion"]["item_subtype_version_id"]);
				unset($this->request->data["ItemSubtypeVersion"]["item_type_id"]);
				unset($this->request->data["ItemSubtypeVersion"]["item_subtype_id"]);
				unset($this->request->data["ItemSubtypeVersion"]["item_subtype_version_id"]);
			}
			unset($this->request->data["Item"]);

			if($position_check) {
				//debug($this->request->data);
				if ($this->ItemSubtypeVersion->save($this->request->data)) {
					$this->Session->delete('editItemSubtypeVersion.'.$id);
					
					$this->loadModel('Log');
					$this->Log->saveLog('Item subtype version edited', $this->request->data);
					CACHE::clear(false,"default"); //Clear the default cache since it stores the prefetched array for all users

					$this->Session->setFlash(__('The item subtype version has been saved'), 'default', array('class' => 'notification'));
					return $this->redirect(array('controller' => 'item_subtype_versions', 'action' => 'view', $id));
				} else {
					debug($this->ItemSubtypeVersion->validationErrors);
					$this->Session->setFlash(__('The item subtype version could not be saved. Please, try again.'));
				}//*/
			}
			else {
				$this->Session->setFlash(__('Please specify for every component a unique position.'));
			}
		}

		$itemSubtypeVersion = $this->ItemSubtypeVersion->find('first', array(
																		'conditions' => array('ItemSubtypeVersion.id' => $id),
																		'contain' => array(
																		'Manufacturer',
																		'ItemSubtype.ItemType',
																		'Component.ItemSubtype.ItemType',
																		'Component.Manufacturer',
																		'CompositeItemSubtypeVersion',
																		'Project')));


		$this->request->data = $itemSubtypeVersion;

		$itemSubtype = $itemSubtypeVersion['ItemSubtype'];

		if(!$this->Session->check('editItemSubtypeVersion.'.$id)) {
			$myComponents = $itemSubtypeVersion['Component'];

			$componentProjects = $this->ItemSubtypeVersion->Project->find('list');

			foreach($myComponents as $key => $myComponent) {
				$myComponents[$key]['ItemSubtypeVersion']['id'] 				= $myComponent['id'];
				$myComponents[$key]['ItemSubtypeVersion']['item_subtype_id'] 	= $myComponent['item_subtype_id'];
				$myComponents[$key]['ItemSubtypeVersion']['version'] 			= $myComponent['version'];
				$myComponents[$key]['ItemSubtypeVersion']['manufacturer_id'] 	= $myComponent['manufacturer_id'];
				$myComponents[$key]['ItemSubtypeVersion']['has_components'] 	= $myComponent['has_components'];
				$myComponents[$key]['ItemSubtypeVersion']['comment']			= $myComponent['comment'];
				$myComponents[$key]['ItemSubtypeVersionsComposition']['project_name'] = $componentProjects[$myComponent['ItemSubtypeVersionsComposition']['project_id']];

				unset($myComponents[$key]['id']);
				unset($myComponents[$key]['item_subtype_id']);
				unset($myComponents[$key]['version']);
				unset($myComponents[$key]['manufacturer_id']);
				unset($myComponents[$key]['has_components']);
				unset($myComponents[$key]['comment']);
			}
			$this->Session->write('editItemSubtypeVersion.'.$id, $myComponents);
		} else {
			$myComponents = $this->Session->read('editItemSubtypeVersion.'.$id);
		}

		$common_projects[] 					= array('name' => 'No projects', 'title' => 'No projects found', 'value' => -1,'disabled' => true);
		$common_manufacturers[]				= array('name' => 'Select a project', 'title' => 'No project selected', 'value' => -1, 'disabled' => true);
		$component_projects[]				= array('name' => 'No projects', 'title' => 'No projects found', 'value' => -1, 'disabled' => true);
		$component_manufacturers[]			= array('name' => 'Select a project', 'title' => 'No project selected', 'value' => -1, 'disabled' => true);
		$component_item_types[]				= array('name' => 'Select a manufacturer', 'title' => 'No manufacturer selected', 'value' => -1, 'disabled' => true);
		$component_item_subtypes[]			= array('name' => 'Select a type', 'title' => 'No item type selected', 'value' => -1, 'disabled' => true);
		$component_item_subtype_versions[]	= array('name' => 'Select a subtype', 'title' => 'No subtype selected', 'value' => -1, 'disabled' => true);


		$this->_restoreFormValues(
					$this->request->data,
					$common_projects,
					$common_manufacturers,
					$common_comment,
					$component_projects,
					$component_manufacturers,
					$component_item_types,
					$component_item_subtypes,
					$component_item_subtype_versions);

		/*
		// Read & Update Session
		$controller = 'project';
		$group	= 'component';
		$ids	= 2;
		$sName	= 'FormItemSubtypeVersionEdit';
		$session = $this->Session->read($sName);
		debug($session);
		//$session[$group][$controller] = $ids;
		$this->Session->write($sName, $session);

		$options = $this->ItemSubtypeVersion->getOptions($session[$group], 'ItemType');
		debug($options);
		//*/
      
      $list_states = $this->ItemSubtypeVersion->Item->State->find('list');

		$this->set(compact('editWithAttached',
                     'itemSubtypeVersions',
                     'list_states',
							'predecessor',
							'common_projects',
							'common_manufacturers',
							'common_comment',
							'component_projects',
							'component_manufacturers',
							'component_item_types',
							'component_item_subtypes',
							'component_item_subtype_versions',
							'myComponents'));
	}

	private function _restoreFormValues( &$data, &$common_projects,	&$common_manufacturers,	&$common_comment, &$component_projects,
		&$component_manufacturers, &$component_item_types, &$component_item_subtypes, &$component_item_subtype_versions) {

		$sName = 'FormItemSubtypeVersionEdit';
		$session = ($this->Session->check($sName)) ? $this->Session->read($sName) : array();

		// restore old values
		$common_projects = $this->ItemSubtypeVersion->getOptions(null, 'Project');
		if(isset($session['common']) && isset($session['common']['project'])) {
			$data['Project']['Project'] = $session['common']['project'];

			$common_comment = (isset($session['common']['comment'])) ? $session['common']['comment'] : '';
			$common_manufacturers = $this->ItemSubtypeVersion->getOptions($session['common'], 'Manufacturer');
			if(isset($session['common']['manufacturer']) && $session['common']['manufacturer'] != "") {
				$data['ItemSubtypeVersion']['manufacturer_id'] = $session['common']['manufacturer'];
			}
		}

		$component_projects = $this->ItemSubtypeVersion->getOptions(null, 'Project');
		if(isset($session['component']) && isset($session['component']['project'])) {
			$data['ItemSubtypeVersion']['component_project_id'] = $session['component']['project'];

			$component_manufacturers = $this->ItemSubtypeVersion->getOptions($session['component'], 'Manufacturer');
			if(isset($session['component']['manufacturer'])) {
				$data['ItemSubtypeVersion']['component_manufacturer_id'] = $session['component']['manufacturer'];

				$component_item_types = $this->ItemSubtypeVersion->getOptions($session['component'], 'ItemType');
				if(isset($session['component']['itemType'])) {
					$data['ItemSubtypeVersion']['component_item_type_id'] = $session['component']['itemType'];

					$component_item_subtypes = $this->ItemSubtypeVersion->getOptions($session['component'], 'ItemSubtype');
					if(isset($session['component']['itemSubtype'])) {
						$data['ItemSubtypeVersion']['component_item_subtype_id'] = $session['component']['itemSubtype'];

						$component_item_subtype_versions = $this->ItemSubtypeVersion->getOptions($session['component'], 'ItemSubtypeVersion');
						if(isset($session['component']['itemSubtypeVersion'])) {
							$data['ItemSubtypeVersion']['component_item_subtype_version_id'] = $session['component']['itemSubtypeVersion'];
						}
					}
				}
			}
		}
	}

/**
 * delete method
 *
 * @param string $id
 * @return if delete was a success TRUE else FALSE
 */
	public function delete($id = null) {
		/*
		if (!empty($this->request->data)) {
			$controller = $this->request->data['controller'];
			$action = $this->request->data['action'];
			$param = $this->request->data['param'];
			$redirect = array('controller' => $controller, 'action' => $action, $param);
		}
		else
			$redirect = array('action' => 'index');
		*/

		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->ItemSubtypeVersion->id = $id;
		if (!$this->ItemSubtypeVersion->exists()) {
			throw new NotFoundException(__('Invalid item subtype version'));
		}
		$count_items = $this->ItemSubtypeVersion->Item->find('count', array(
								'conditions' => array('Item.item_subtype_version_id' => $id)));

		$item_subtype_version = $this->ItemSubtypeVersion->find('first', array(
								'conditions' => array('ItemSubtypeVersion.id' => $id), 'recursive' => -1));

		$redirect = array('controller' => 'ItemSubtypes', 'action' => 'view', $item_subtype_version['ItemSubtypeVersion']['item_subtype_id']);

		if($count_items <= 0) {
			$this->request->data = $this->ItemSubtypeVersion->read(null, $id);

		 	if(!empty($this->request->data['CompositeItemSubtypeVersion'])) {
		 		$this->Session->setFlash(__('FAILURE: This version cannot be deleted, because it is part of other subtypes'));
				return $this->redirect(array('controller' => 'ItemSubtypeVersions', 'action' => 'view', $id));
			}

			foreach($this->request->data['Component'] as $myComponent) {
				$this->ItemSubtypeVersion->ItemSubtypeVersionsComposition->id = $myComponent['ItemSubtypeVersionsComposition']['id'];
				$this->ItemSubtypeVersion->ItemSubtypeVersionsComposition->delete();
			}

			if ($this->ItemSubtypeVersion->delete()) {

				debug(CACHE::clear(false,"default")); //Clear the default cache since it stores the prefetched array for all users
				$this->loadModel('Log');
				$this->Log->saveLog('Item subtype version deleted', $this->request->data);
				$this->Session->setFlash(__('Item subtype version deleted'), 'default', array('class' => 'notification'));

				return $this->redirect($redirect);
			}
			$this->Session->setFlash(__('Item subtype version was not deleted'));
			return $this->redirect($redirect);
		}
		else {
			$this->Session->setFlash(__('This version cannot be deleted, because there are '.$count_items.' related items.'));
			return $this->redirect($redirect);
		}
	}

	public function changeComponent() {
		if($this->RequestHandler->isAjax()){
			$dummy = $this->request->data['dummy'];
			$field = $this->request->data['field'];
			$value = $this->request->data['value'];
			$session = $this->request->data['session'];

			$myComponents = $this->Session->read($session);

			if(!empty($myComponents[$dummy])) {
				$myComponents[$dummy]['ItemSubtypeVersionsComposition'][$field] = $value;
				$this->Session->write($session, $myComponents);
			}
		}
		$this->set('myComponents', $myComponents);
		$this->render('update_components', 'ajax');
	}

	public function addComponent() {
		$myComponents = array();

		if($this->RequestHandler->isAjax()) {
			if(isset($this->request->data["editWithAttached"]) && $this->request->data["editWithAttached"]==1) {
			   $this->set("editWithAttached",true);
			} else {
		      $this->set("editWithAttached",false);
         }
			$session = $this->request->data['session'];
			$myComponents = $this->Session->read($session);
         $next_position = 1;
         foreach($myComponents as $component) {
            if($component['ItemSubtypeVersionsComposition']['position']>=$next_position) {
               $next_position = 1 + $component['ItemSubtypeVersionsComposition']['position'];
            }
         }
			$id = $this->request->data['itemSubtypeVersionId'];
         if($id=='') {
			   $subtypeid = $this->request->data['itemSubtypeId'];
            $tmp = $this->ItemSubtypeVersion->find('first', array('conditions'=>array('ItemSubtype.id' => $subtypeid)));
            $this->request->data['itemSubtypeVersionId'] = array($tmp['ItemSubtypeVersion']['id']);
            $add_as_all_versions = true;
         } else {
            $add_as_all_versions = false;
         }

			$project_id = $this->request->data['projectId'];
			$project_name = $this->request->data['projectName'];
         
         if( ($project_id != null) && ($project_name != null) ) {
            foreach($this->request->data['itemSubtypeVersionId'] as $id) {
               $this->ItemSubtypeVersion->id = $id;
               if($this->ItemSubtypeVersion->exists() ) {
                  $myComponent = $this->ItemSubtypeVersion->find('first', array(
                                          'conditions' => array('ItemSubtypeVersion.id' => $id),
                                          'contain' => array('Manufacturer', 'ItemSubtype.ItemType')));
                  $myComponent['ItemSubtypeVersionsComposition']['project_id'] = $project_id;
                  $myComponent['ItemSubtypeVersionsComposition']['project_name'] = $project_name;
                  $myComponent['ItemSubtypeVersionsComposition']['attached'] = 0;
                  $myComponent['New'] = true;
                  $myComponent['ItemSubtypeVersionsComposition']['position'] = $next_position;
                  $myComponent['ItemSubtypeVersionsComposition']['all_versions'] = $add_as_all_versions;
                     
         
                  if(!empty($myComponent)) {
                     $myComponents[] = $myComponent;
                     $this->Session->write($session, $myComponents);
                  }
               }
            }
         }
		}
		$this->set('myComponents', $myComponents);
		$this->render('update_components', 'ajax');
	}

	public function removeComponent() {
		if($this->request->isAjax()){
			if(isset($this->request->data["editWithAttached"]) && $this->request->data["editWithAttached"]==1){
				$this->set("editWithAttached",true);
			}else{
				$this->set("editWithAttached",false);
			}
			$dummy = $this->request->data['dummy'];
			$session = $this->request->data['session'];

			$myComponents = $this->Session->read($session);
			unset($myComponents[$dummy]);

			$this->Session->write($session, $myComponents);
		}
		$this->set('myComponents', $myComponents);
		$this->render('update_components', 'ajax');
	}

	public function subselectChanged() {

		if($this->RequestHandler->isAjax()){
			$controller = $this->request->data['controller'];
			$group 		= $this->request->data['group'];
			$value 		= $this->request->data['value'];

			// Read & Update Session
			$sName = 'FormItemSubtypeVersionEdit';
			$session = $this->Session->read($sName);
			$session[$group][$controller] = $value;
			$this->Session->write($sName, $session);

			switch($controller) {
				case "project":
					$options = $this->ItemSubtypeVersion->getOptions($session[$group], 'Manufacturer');
					break;
				case "manufacturer":
					$options = $this->ItemSubtypeVersion->getOptions($session[$group], 'ItemType');
					break;
				case "itemType":
					$options = $this->ItemSubtypeVersion->getOptions($session[$group], 'ItemSubtype');
					break;
				case "itemSubtype":
					$options = $this->ItemSubtypeVersion->getOptions($session[$group], 'ItemSubtypeVersion');
					break;
			}

			$this->set('options', $options);
			$this->render('subselect_changed', 'ajax');
		}
	}
}

