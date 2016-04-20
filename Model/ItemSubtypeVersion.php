<?php
App::uses('AppModel', 'Model');
App::uses('Sanitize', 'Utility');


/**
 * ItemSubtypeVersion Model
 *
 * @property Manufacturer $Manufacturer
 * @property ItemSubtype $ItemSubtype
 * @property ItemSubtypeVersionComposition $ItemSubtypeVersionComposition
 * @property Item $Item
 * @property LongBlob $LongBlob
 */
class ItemSubtypeVersion extends AppModel {

	public $actsAs = array('Containable');
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'version';
	public $order = 'ItemSubtypeVersion.version ASC';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'version' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'A version number is required',
				//'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'manufacturer_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please select a manufacturer',
				//'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'has_components' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'item_subtype_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Manufacturer' => array(
			'className' => 'Manufacturer',
			'foreignKey' => 'manufacturer_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ItemSubtype' => array(
			'className' => 'ItemSubtype',
			'foreignKey' => 'item_subtype_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Item' => array(
			'className' => 'Item',
			'foreignKey' => 'item_subtype_version_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);


/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'DbFile' => array(
			'className' => 'DbFile',
			'joinTable' => 'db_files_item_subtype_versions',
			'foreignKey' => 'item_subtype_version_id',
			'associationForeignKey' => 'db_file_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		),
		'Component' => array(
			'className' => 'ItemSubtypeVersion',
			'joinTable' => 'item_subtype_versions_compositions',
			'foreignKey' => 'item_subtype_version_id',
			'associationForeignKey' => 'component_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		),
		'CompositeItemSubtypeVersion' => array(
			'className' => 'ItemSubtypeVersion',
			'joinTable' => 'item_subtype_versions_compositions',
			'foreignKey' => 'component_id',
			'associationForeignKey' => 'item_subtype_version_id',
			'unique' => 'keepExisting',
			//'conditions' => array('ItemComposition.valid' => 1)		//Show only attached items
		),
		'Project' => array(
			'className' => 'Project',
			'joinTable' => 'item_subtype_versions_projects',
			'foreignKey' => 'item_subtype_version_id',
			'associationForeignKey' => 'project_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);

	public function getItemSubtypeVersionsMultipleList(){
		$itemSubtypeVersions = $this->find('all', array(
											'contain' => array('ItemSubtype.ItemType'),
											'order' => array('ItemSubtypeVersion.version' => 'asc')
				));
		$result = array();
		//debug($itemSubtypeVersions);
		foreach($itemSubtypeVersions as $itemSubtypeVersion) {
			$itemType = $itemSubtypeVersion['ItemSubtype']['ItemType']['name'];
			$result[$itemType][$itemSubtypeVersion['ItemSubtypeVersion']['id']] = $itemSubtypeVersion['ItemSubtype']['name'].' v'.$itemSubtypeVersion['ItemSubtypeVersion']['version'];
		}
		return $result;
	}

	/*
	 * $properties ... Properties which are for every Component equal
	 * examlpe:
	 * 		$properties['location_id'] 	= $this->request->data['Item']['location_id'];
	 *		$properties['state_id'] 	= $this->request->data['Item']['state_id'];
	 *		$properties['project_id']	= $this->request->data['Item']['project_id'];
	 *
	 * returns all $components
	 */
	public function getComponentsRecursive($ids, $properties) {

		$components = $this->find('first', array(
						'conditions' => array('ItemSubtypeVersion.id' => $ids),
						'contain' => array('Component.ItemSubtype.ItemType')
					));

		foreach($components['Component'] as $key => $component) {
			foreach($properties as $name => $value) {
				$components['Component'][$key][$name] = $value;
			}

			if($component['has_components'] > 0) {
				$components['Component'][$key]['Component'] = $this->getComponentsRecursive($component['id'], $properties);
			}
		}
		return $components['Component'];
	}


	/*
	 * Returns only Versions which are relevant for the user.
	 * Depending on the users locations and related projects.
	 */
	public function getUsersItemSubtypeVersions() {

		$user = CakeSession::read("User");
		$userId = $user["User"]["id"];
		$filename = "UserSubtypeVersion_".$userId;
		$all_stuff = Cache::read($filename, "default");
		if(!$all_stuff or !array_key_exists(0,$all_stuff)) {
			
			$itemSubtypeVersions = array();
			$User = ClassRegistry::init('User');
			$ItemTypeModel = ClassRegistry::init('ItemType');
			$ItemSubtypeModel = ClassRegistry::init('ItemSubtype');
			$ItemSubtypeVersionView = ClassRegistry::init('ItemSubtypeVersionView');
			$all_item_types = $ItemTypeModel->find('all',array('recursive'=>0));
			$empty_file_types = array();
			foreach($all_item_types as $ait) {
			 
				$id = $ait['ItemType']['id'];
				$tmp_arr = $ItemSubtypeVersionView->find('first', array('conditions' => array('item_type_id'=>$id)));
				if(empty($tmp_arr)) {
				   $empty_file_types[$id]= array('n'=>$ait['ItemType']['name']); 
				   $this_item_subtypes = $ItemSubtypeModel->find('all',array('conditions'=>array('item_type_id'=>$id),'recursive'=>0));
				   foreach($this_item_subtypes as $tis) {
					  $empty_file_types[$id]['s'][$tis['ItemSubtype']['id']] = array('n'=>$tis['ItemSubtype']['name']);
				   } 
				}
			
			}

			$conditions['OR']['ItemSubtypeVersionView.project_id'] = $User->getUsersProjects();
			$results = $ItemSubtypeVersionView->find('all', array('conditions' => $conditions));			

			foreach($results as $itemSubtypeVersionView) {
			
				$project_id = (isset($itemSubtypeVersionView['ItemSubtypeVersionView']['project_id']))?$itemSubtypeVersionView['ItemSubtypeVersionView']['project_id']:$itemSubtypeVersionView['project']['project_id'];
				$project_name = (isset($itemSubtypeVersionView['ItemSubtypeVersionView']['project_name']))?$itemSubtypeVersionView['ItemSubtypeVersionView']['project_name']:$itemSubtypeVersionView['project']['project_name'];
				$project_name = str_replace('"', '\"', $project_name);
				$manufacturer_id = $itemSubtypeVersionView['ItemSubtypeVersionView']['manufacturer_id'];
				$manufacturer_name = str_replace('"', '\"', $itemSubtypeVersionView['ItemSubtypeVersionView']['manufacturer_name']);
				$item_type_name = str_replace('"', '\"', $itemSubtypeVersionView['ItemSubtypeVersionView']['item_type_name']);
				$item_type_id = $itemSubtypeVersionView['ItemSubtypeVersionView']['item_type_id'];
				$item_subtype_name = str_replace('"', '\"', $itemSubtypeVersionView['ItemSubtypeVersionView']['item_subtype_name']);
				$item_subtype_id = $itemSubtypeVersionView['ItemSubtypeVersionView']['item_subtype_id'];
				$version = $itemSubtypeVersionView['ItemSubtypeVersionView']['item_subtype_version_version'];
				$item_subtype_version_id = $itemSubtypeVersionView['ItemSubtypeVersionView']['item_subtype_version_id'];
				$item_subtype_version_name = $itemSubtypeVersionView['ItemSubtypeVersionView']['item_subtype_version_name'];

				$itemSubtypeVersions['Project'][$project_id]['Project']['name'] = $project_name;
				$itemSubtypeVersions['Project'][$project_id]['Project']['id'] = $project_id;
				$itemSubtypeVersions['Project'][$project_id]['Manufacturer'][$manufacturer_id]['Manufacturer']['name'] = $manufacturer_name;
				$itemSubtypeVersions['Project'][$project_id]['Manufacturer'][$manufacturer_id]['Manufacturer']['id'] = $manufacturer_id;
				$itemSubtypeVersions['Project'][$project_id]['Manufacturer'][$manufacturer_id]['ItemType'][$item_type_id]['ItemType']['name'] = $item_type_name;
				$itemSubtypeVersions['Project'][$project_id]['Manufacturer'][$manufacturer_id]['ItemType'][$item_type_id]['ItemType']['id'] = $item_type_id;
				$itemSubtypeVersions['Project'][$project_id]['Manufacturer'][$manufacturer_id]['ItemType'][$item_type_id]['ItemSubtype'][$item_subtype_id]['ItemSubtype']['name'] = $item_subtype_name;
				$itemSubtypeVersions['Project'][$project_id]['Manufacturer'][$manufacturer_id]['ItemType'][$item_type_id]['ItemSubtype'][$item_subtype_id]['ItemSubtype']['id'] = $item_subtype_id;
				$itemSubtypeVersions['Project'][$project_id]['Manufacturer'][$manufacturer_id]['ItemType'][$item_type_id]['ItemSubtype'][$item_subtype_id]['ItemSubtypeVersion'][$item_subtype_version_id]['version'] = $version;
				$itemSubtypeVersions['Project'][$project_id]['Manufacturer'][$manufacturer_id]['ItemType'][$item_type_id]['ItemSubtype'][$item_subtype_id]['ItemSubtypeVersion'][$item_subtype_version_id]['name'] = $item_subtype_version_name;
				$itemSubtypeVersions['Project'][$project_id]['Manufacturer'][$manufacturer_id]['ItemType'][$item_type_id]['ItemSubtype'][$item_subtype_id]['ItemSubtypeVersion'][$item_subtype_version_id]['id'] = $item_subtype_version_id;
			
			}
			
			$all_stuff = array($itemSubtypeVersions,$empty_file_types);
			Cache::write($filename,$all_stuff,"default");
		}
		
		return $all_stuff;
	}

	/*
	 * Returns only Versions which are relevant for the user.
	 * Depending on the users locations and related projects.
	 *
	 * Only subtypes which are a composition of other subtypes are returned.
	 */
	public function getUsersCompositeItemSubtypeVersions() {
		$itemSubtypeVersions = array();
		$User = ClassRegistry::init('User');
		$ItemSubtypeVersionView = ClassRegistry::init('ItemSubtypeVersionView');

		$conditions['OR']['ItemSubtypeVersionView.project_id'] = $User->getUsersProjects();
		$results = $ItemSubtypeVersionView->find('all', array('conditions' => $conditions));

		foreach($results as $itemSubtypeVersionView) {
			if($itemSubtypeVersionView['ItemSubtypeVersionView']['item_subtype_version_has_components'] > 0) {
				
				$project_id = (isset($itemSubtypeVersionView['ItemSubtypeVersionView']['project_id']))?$itemSubtypeVersionView['ItemSubtypeVersionView']['project_id']:$itemSubtypeVersionView['project']['project_id'];
				$project_name = (isset($itemSubtypeVersionView['ItemSubtypeVersionView']['project_name']))?$itemSubtypeVersionView['ItemSubtypeVersionView']['project_name']:$itemSubtypeVersionView['project']['project_name'];
				$project_name = str_replace('"', '\"', $project_name);
				$manufacturer_id = $itemSubtypeVersionView['ItemSubtypeVersionView']['manufacturer_id'];
				$manufacturer_name = str_replace('"', '\"', $itemSubtypeVersionView['ItemSubtypeVersionView']['manufacturer_name']);
				$item_type_name = str_replace('"', '\"', $itemSubtypeVersionView['ItemSubtypeVersionView']['item_type_name']);
				$item_type_id = $itemSubtypeVersionView['ItemSubtypeVersionView']['item_type_id'];
				$item_subtype_name = str_replace('"', '\"', $itemSubtypeVersionView['ItemSubtypeVersionView']['item_subtype_name']);
				$item_subtype_id = $itemSubtypeVersionView['ItemSubtypeVersionView']['item_subtype_id'];
				$version = $itemSubtypeVersionView['ItemSubtypeVersionView']['item_subtype_version_version'];
				$item_subtype_version_id = $itemSubtypeVersionView['ItemSubtypeVersionView']['item_subtype_version_id'];

				$itemSubtypeVersions['Project'][$project_id]['Project']['name'] = $project_name;
				$itemSubtypeVersions['Project'][$project_id]['Project']['id'] = $project_id;
				$itemSubtypeVersions['Project'][$project_id]['Manufacturer'][$manufacturer_id]['Manufacturer']['name'] = $manufacturer_name;
				$itemSubtypeVersions['Project'][$project_id]['Manufacturer'][$manufacturer_id]['Manufacturer']['id'] = $manufacturer_id;
				$itemSubtypeVersions['Project'][$project_id]['Manufacturer'][$manufacturer_id]['ItemType'][$item_type_id]['ItemType']['name'] = $item_type_name;
				$itemSubtypeVersions['Project'][$project_id]['Manufacturer'][$manufacturer_id]['ItemType'][$item_type_id]['ItemType']['id'] = $item_type_id;
				$itemSubtypeVersions['Project'][$project_id]['Manufacturer'][$manufacturer_id]['ItemType'][$item_type_id]['ItemSubtype'][$item_subtype_id]['ItemSubtype']['name'] = $item_subtype_name;
				$itemSubtypeVersions['Project'][$project_id]['Manufacturer'][$manufacturer_id]['ItemType'][$item_type_id]['ItemSubtype'][$item_subtype_id]['ItemSubtype']['id'] = $item_subtype_id;
				$itemSubtypeVersions['Project'][$project_id]['Manufacturer'][$manufacturer_id]['ItemType'][$item_type_id]['ItemSubtype'][$item_subtype_id]['ItemSubtypeVersion'][$item_subtype_version_id]['version'] = $version;
				$itemSubtypeVersions['Project'][$project_id]['Manufacturer'][$manufacturer_id]['ItemType'][$item_type_id]['ItemSubtype'][$item_subtype_id]['ItemSubtypeVersion'][$item_subtype_version_id]['id'] = $item_subtype_version_id;
			}
		}

		return $itemSubtypeVersions;
	}

	/**
	 * hasComponents method
	 * Validates if the given ItemSubtypeVersion has components
	 * Returns true if components are present, else false.
	 *
	 * @param $versionId The id of the ItemSubtypeversion
	 *
	 * @return boolean
	 */
	public function hasComponents($versionId) {
		$version = $this->find('first', array(
				'conditions' => array('ItemSubtypeVersion.id' => $versionId),
				'recursive' => -1
			)
		);

		if($version['ItemSubtypeVersion']['has_components'] == 1){
			return true;
		}

		return false;
	}
}
