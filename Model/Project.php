<?php
App::uses('AppModel', 'Model');
/**
 * Project Model
 *
 * @property ItemSubtypeVersionView $ItemSubtypeVersionView
 * @property ItemSubtypeVersionsComposition $ItemSubtypeVersionsComposition
 * @property Item $Item
 * @property DbFile $DbFile
 * @property ItemSubtypeVersion $ItemSubtypeVersion
 * @property Manufacturer $Manufacturer
 * @property ItemType $ItemType
 * @property Stock $Stock
 * @property User $User
 */
class Project extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
	public $order = 'Project.name ASC';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
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
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'ItemSubtypeVersionView' => array(
			'className' => 'ItemSubtypeVersionView',
			'foreignKey' => 'project_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'ItemSubtypeVersionsComposition' => array(
			'className' => 'ItemSubtypeVersionsComposition',
			'foreignKey' => 'project_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Item' => array(
			'className' => 'Item',
			'foreignKey' => 'project_id',
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
			'joinTable' => 'db_files_projects',
			'foreignKey' => 'project_id',
			'associationForeignKey' => 'db_file_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		),
		'ItemSubtypeVersion' => array(
			'className' => 'ItemSubtypeVersion',
			'joinTable' => 'item_subtype_versions_projects',
			'foreignKey' => 'project_id',
			'associationForeignKey' => 'item_subtype_version_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		),
		'Manufacturer' => array(
			'className' => 'Manufacturer',
			'joinTable' => 'manufacturers_projects',
			'foreignKey' => 'project_id',
			'associationForeignKey' => 'manufacturer_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		),
		'ItemType' => array(
			'className' => 'ItemType',
			'joinTable' => 'projects_item_types',
			'foreignKey' => 'project_id',
			'associationForeignKey' => 'item_type_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'with' => 'ProjectsItemType',
		),
		'Stock' => array(
			'className' => 'Stock',
			'joinTable' => 'projects_stocks',
			'foreignKey' => 'project_id',
			'associationForeignKey' => 'stock_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		),
		'User' => array(
			'className' => 'User',
			'joinTable' => 'projects_users',
			'foreignKey' => 'project_id',
			'associationForeignKey' => 'user_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);
	public function getUsersProjects($id = null) {
      if(empty($id) or !$this->User->exists($id)) {
         $User = ClassRegistry::init('User');

         $conditions['OR']['Project.id'] = $User->getUsersProjects();
         $projects = $this->find('list', array('conditions' => $conditions));
      } else {
         $projects = $this->User->find('first',array('conditions'=>array('id'=>$id),'contain'=>array('Project'),'recursive'=>0))['Project'];
      } 
		return $projects;
	}

	/**
	 * versionExists method
	 * Validates if a ItemSubtypeVersion is related to the project
	 *
	 * @param string $projectId The id of the project
	 * @param string $versionId The id of the version
	 * @return boolean
	 */
	public function versionExists($projectId, $versionId) {
		$version = $this->ItemSubtypeVersionsProject->find('first',
			array(
				'conditions' => array(
					'AND' => array(
						'ItemSubtypeVersionsProject.item_subtype_version_id' => $versionId,
						'ItemSubtypeVersionsProject.project_id' => $projectId
					)
				)
			));

		return !empty($version);
	}

	/**
	 * addVersion method
	 * Adds a relation between the version and all of its components and the project
	 *
	 * @param string $projectId The id of the project
	 * @param string $versionId The id of the version
	 * @param boolean $recursive If true all Version components will be added recursivly
	 * @return void
	 */
	public function addVersion($projectId, $versionId, $recursive = false) {
		//first check if the version really needs to be added
		if(!$this->versionExists($projectId, $versionId)) {

			if($recursive){
				// check if the Version has components
				// if so: add them first
				if($this->ItemSubtypeVersion->hasComponents($versionId)){
					$version = $this->ItemSubtypeVersion->find('first', array(
																			'conditions' => array('ItemSubtypeVersion.id' => $versionId),
																			'contain' => array('Component')
																		));
					foreach($version['ItemSubtypeVersion']['Component'] as $componentVersion) {
						$this->addVersion($projectId, $componentVersion['id'], $recursive);
					}
				}
			}

			// add the version to the project by creating an entry in the
			// item_subtype_versions_projects table
			$relation = array(
					'project_id' => $projectId,
					'item_subtype_version_id' => $versionId
				);

			$this->ItemSubtypeVersionsProject->create();
			$this->ItemSubtypeVersionsProject->save($relation);
		}
	}
	/**
	 * manufacturerExists method
	 * Validates if a Manufacturer is related to the project
	 *
	 * @param string $projectId The id of the project
	 * @param string $manufacturerId The id of the manufacturer
	 * @return boolean
	 */
	public function manufacturerExists($projectId, $manufacturerId) {
		$manufacturer = $this->ManufacturersProject->find('first',
			array(
				'conditions' => array(
					'AND' => array(
						'ManufacturersProject.manufacturer_id' => $manufacturerId,
						'ManufacturersProject.project_id' => $projectId
					)
				)
			));

		return !empty($manufacturer);
	}
	/**
	 * addmanufacturer method
	 * Adds a relation between the manufacturer and all of its components and the project
	 *
	 * @param string $projectId The id of the project
	 * @param string $manufacturerId The id of the manufacturer
	 * @param boolean $recursive If true all manufacturer components will be added recursivly
	 * @return void
	 */
	public function addManufacturer($projectId, $manufacturerId) {
		//first check if the manufacturer really needs to be added
		if(!$this->manufacturerExists($projectId, $manufacturerId)) {

			// add the manufacturer to the project by creating an entry in the
			// manufacturers_projects table
			$relation = array(
					'project_id' => $projectId,
					'manufacturer_id' => $manufacturerId
				);

			$this->ManufacturersProject->create();
			$this->ManufacturersProject->save($relation);
		}
	}
}
