<?php
App::uses('AppModel', 'Model');
/**
 * Item Model
 *
 * @property ItemSubtype $ItemSubtype
 * @property Location $Location
 * @property State $State
 * @property Manufacturer $Manufacturer
 * @property Project $Project
 * @property Item $Item
 * @property History $History
 */
class Item extends AppModel {
	var $displayField = 'code';
	public $actsAs = array("Containable");
/**
 * Validation rules
 *
 * @var array
 */

/*
 * Problems with in Transfer and item->find
 * or with saveAssociated and validation
 */
 	public $faulty = array();
	public $validate = array(
		'code' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'For every item a unique code is required',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'checkMultipleCodes' => array(
				'rule' => array('checkCodes'),
				'message' => 'Please specify a valid code'
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'checkUniqueness' => array(
				'rule' => array('checkUniqueness'),
				'message' => 'One or more codes are already in use'
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'item_subtype_version_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Item subtype required',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'project_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Project required',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'location_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Location required',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'item_quality_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Quality required',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);


/*
 * Custom validation rules
 */
	public function checkUniqueness($check,$dummy,&$faulty=null) { //Needs a dummy field as second parameter because the second parameter is always passed as an array by cakephp and therefore can't be a reference which I require.
		if($faulty == null){
			$faulty = $this->faulty;
		}
		$codes = $this->separate($check['code']);
		// check for uniqueness within the new codes
		$result = array_unique($codes);
		if(count($codes) != count($result))
			return false;

		// check uniqueness of the new codes within DB
		foreach($codes as $code) {
			$result = $this->find('first', array('conditions' => array('Item.code' => $code)));
			if($result != false){
				$faulty[] = $result["Item"]["code"];
			}
		}
		if(!empty($faulty))
			return false;

		return true;
    }

	public function checkCodes($check) {
		$codes = $this->separate($check['code']);

		if(count($codes) == 0) {
			return false;
		}

		return true;
    }

/**
 * separate method
 *
 * @param string $codes
 * @return array $codes separated as array()
 *
 * Separates the codes for each item from the input string
 */
	public function separate($codes) {

		// support for multiple items separated by semicolons or spaces

		// split string at every semicolon, colon and space character
		$codes = preg_split('/[\s,;]+/', $codes, -1, PREG_SPLIT_NO_EMPTY); //split content into lines by \r & \n
		// if someone typed something stupid like: code1;code2;;code3; => delete empty arrays
		foreach($codes as $key => $value) {
			// Escape quotes and other bad stuff
			//$codes[$key] = Sanitize::html($value);

			// remove empty codes
			if($codes[$key] == '') {
				unset($codes[$key]);
			}
		}

		return $codes;
	}

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'ItemSubtypeVersion' => array(
			'className' => 'ItemSubtypeVersion',
			'foreignKey' => 'item_subtype_version_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Location' => array(
			'className' => 'Location',
			'foreignKey' => 'location_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'State' => array(
			'className' => 'State',
			'foreignKey' => 'state_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'project_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Manufacturer' => array(
			'className' => 'Manufacturer',
			'foreignKey' => 'manufacturer_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ItemType' => array(
			'className' => 'ItemType',
			'foreignKey' => 'item_type_id',
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
		),
		'ItemQuality' => array(
			'className' => 'ItemQuality',
			'foreignKey' => 'item_quality_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
					)
	);

/**
 * hasOne associations
 *
 * @var array
 */
 	public $hasOne = array(
		'Checklist' => array(
			'className' => 'Checklist',
			'foreignKey' => 'item_id',
			'dependent' => true,
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
		'History' => array(
			'className' => 'History',
			'foreignKey' => 'item_id',
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
		'ChildStock' => array(
			'className' => 'Stock',
			'foreignKey' => 'parent_item_id',
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
		'Measurement' => array(
			'className' => 'Measurement',
			'foreignKey' => 'item_id',
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
		'ItemsParameters' =>array(
			'className' => 'ItemsParameter',
			'foreignKey' => 'item_id',
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
		'ItemStocks' =>array(
			'className' => 'ItemStock',
			'foreignKey' => 'item_id',
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

	public $hasAndBelongsToMany = array(
		'DbFile' => array(
			'className' => 'DbFile',
			'joinTable' => 'db_files_items',
			'foreignKey' => 'item_id',
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
			'className' => 'Item',
			'joinTable' => 'item_compositions',
			'foreignKey' => 'item_id',
			'associationForeignKey' => 'component_id',
			'unique' => 'keepExisting',
			//'conditions' => array('ItemComposition.valid' => 1)		//Show only attached items
		),
		'CompositeItem' => array(
			'className' => 'Item',
			'joinTable' => 'item_compositions',
			'foreignKey' => 'component_id',
			'associationForeignKey' => 'item_id',
			'unique' => 'keepExisting',
			//'conditions' => array('ItemComposition.valid' => 1)		//Show only attached items
		),
		'Stock' => array(
			'className' => 'Stock',
			'joinTable' => 'item_compositions',
			'foreignKey' => 'item_id',
			'associationForeignKey' => 'stock_id',
			'unique' => 'keepExisting',
			//'conditions' => array('ItemComposition.valid' => 1)		//Show only attached items
		),
		'Transfer' => array(
			'className' => 'Transfer',
			'joinTable' => 'items_transfers',
			'foreignKey' => 'item_id',
			'associationForeignKey' => 'transfer_id',
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
		'ItemTag' => array(
			'className' => 'ItemTag',
			'joinTable' => 'item_tags_items',
			'foreignKey' => 'item_id',
			'associationForeignKey' => 'item_tag_id',
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
	);

	/**
	 * changeProject method
	 * Changes the project_id of one item
	 *
	 * @param string $itemId The id of the item
	 * @param string $projectId The id of the project
	 * @param boolean $recursive If set true: All attached components are changed too.
	 * @return boolean Returns true if the saving was successful
	 */

	public function changeProject($itemId, $projectId, $recursive = false){
			$item = $this->find('first', array(
												'conditions' => array('Item.id' => $itemId),
												'recursive' => -1
											));

			if($recursive){
				// check if the Item has currently attached components
				// if so: change their project first
				if($this->hasComponents($itemId)){
					$components = $this->getValidComponents($itemId);
					foreach($components as $component) {
						if(!$this->changeProject($component['id'], $projectId, $recursive)) {
							return false; // Abort on error
						}
					}
				}
			}

			// Verify that the new project has the items ItemSubtypeVersion and Manufacturer added
			// addVersion checks if the version is related with the project, if not it generates one
			$this->Project->addVersion($projectId, $item['Item']['item_subtype_version_id']);
			$this->Project->addManufacturer($projectId, $item['Item']['manufacturer_id']);

			// Update the project_id of the item
			$this->read(null, $itemId);
			$this->set('project_id', $projectId);
			$this->validator()->remove('code');
			if(!$this->save()) {
				return false;	// Abort on error
			}



			$old_project = $this->Project->find('first', array(
														'conditions' => array('Project.id' => $item['Item']['project_id']),
														'recursive' => -1
													));

			$new_project = $this->Project->find('first', array(
														'conditions' => array('Project.id' => $projectId),
														'recursive' => -1
													));

			// Generate the automatic history comment
			$event_id = $this->History->Event->getEventId('Item project changed');
			$comment = 'Project changed from "'.$old_project['Project']['name'].'" to "'.$new_project['Project']['name'].'".';
			$history = array( 'History' => array(
										'item_id'	=> $itemId,
										'comment'	=> $comment,
										'event_id' 	=> $event_id
									)
							);

			$this->History->create();
			if ($this->History->save($history, array('validate' => 'first'))) {
				return true; // All data saved successful
			}

			return false; // An error occured
	}

	public function saveComponentsRecursive($itemComposition, $item_id, $code,$tags=array()){

		$event_ids = $this->History->Event->getEventIds(array('Item created', 'Item attached', 'Item detached'));
		$error = array();
		$itemHistory = array();

		foreach($itemComposition['Component'] as $component)
		{
//			if(isset($itemComposition["item_tag"])){
//				$component["item_tag"] = $itemComposition["item_tag"];
//			}else{
//				$component["item_tag"] = array();
//			}
			$component["item_tag"] = $tags;
			if(($component['valid'] == 1) && empty($error) )
			{
				$newComponent['Item']['code']			= $component['code'];
				$newComponent['Item']['item_subtype_version_id']= $component['item_subtype_version_id'];
				$newComponent['Item']['location_id'] 	= $component['location_id'];
				$newComponent['Item']['item_quality_id']= 6;
				$newComponent['Item']['project_id'] 	= $component['project_id'];
				$newComponent['Item']['state_id'] 		= 1; //default state, i.e. state := unset
				$newComponent['Item']['comment']		= "";

				$item_subtype_version = $this->ItemSubtypeVersion->find('first', array(
															'conditions' => array('ItemSubtypeVersion.id' => $component['item_subtype_version_id']),
															'contain' => array('ItemSubtype.ItemType')));

				$newComponent['Item']['item_subtype_id'] = $item_subtype_version['ItemSubtype']['id'];
				$newComponent['Item']['manufacturer_id'] = $item_subtype_version['ItemSubtypeVersion']['manufacturer_id'];
				$newComponent['Item']['item_type_id']	= $item_subtype_version['ItemSubtype']['ItemType']['id'];
				$newComponent['ItemTag'] = $component["item_tag"];

				$newComposite['item_id']	= $item_id;
				$newComposite['valid']		= $component['isAttached'];	// true := 1
				$newComposite['position']	= $component['position'];
				$newComponent['CompositeItem'][] = $newComposite;

				//Associate default checklist if exists
				$this->Checklist->ClTemplate->unbindModel(
															array(	'belongsTo' => array('ItemSubtype'),
																	'hasMany' => array('ClAction')
															));
				$component_subtype_default_cltemplate =
					$this->Checklist->ClTemplate->find('first', array(
																		'conditions' => array(
																								'ClTemplate.item_subtype_id' => $newComponent['Item']['item_subtype_id'],
																								'ClTemplate.default' => true
																								)
																		));
				if(!empty($component_subtype_default_cltemplate)){
					$checklistName = $code.'_cl'; $checklistDescription = 'Created from default template';
					$checklistId = $this->Checklist->createFromTemplate($component_subtype_default_cltemplate['ClTemplate']['id'],
																				$checklistName,$checklistDescription);
					if(!empty($checklistId)){
						$newComponent['Item']['checklist_id'] = $checklistId;
						$newComponent['Item']['cl_template_id']	= $component_subtype_default_cltemplate['ClTemplate']['id'];
						$firstclaction = $this->Checklist->ClAction->find('first', array(
																						'conditions' => array('ClAction.list_number >' => 0),
																						'order' => array('ClAction.list_number' => 'asc')
																							));
						foreach($firstclaction['ClState'] as $clstate){
							if($clstate['type']=='source'){
								$this->State->unbindModel(
															array(	'hasMany' => array('Item')
															));
								$state = $this->State->find('first', array('conditions'=>array('State.name'=>$clstate['name'])));
								if(empty($state)){
									$this->State->create;
									$state = array(
													'name' => $clstate['name'],
													'description' => $clstate['description']
													);
									$this->State->save($state);
									$stateId = $this->State->getInsertId();
								}
								else $stateId = $state['State']['id'];
								$newComponent['Item']['state_id'] = $stateId;

								break;
							}
						}
					}
				}


				$history[] = array(	'event_id' 	=> $event_ids['Item created'],
									'comment'	=> 'Item was created simultaneously with '.$code.'.');
				if($component['isAttached'] == true) {
					$history[] = array(	'event_id' 	=> $event_ids['Item attached'],
										'comment'	=> 'Item was attached to ' .$code. ' at arrival.');
				}
				else {
					$history[] = array(	'event_id' 	=> $event_ids['Item detached'],
										'comment'	=> 'Item was detached from ' .$code. ' at arrival.');
				}

				$newComponent['History']			= $history;
				$this->create();
				if($this->saveAll($newComponent)) {
					if(isset($component['Component'])) {
						$component_id = $this->id;
						$componentStatus = $this->saveComponentsRecursive($component, $component_id, $component['code'],$tags);
						$componentHistory = $componentStatus['history'];
						$error = array_merge($error, $componentStatus['error']);

						if(empty($error) && !empty($componentHistory)) {
							$this->History->create();
							if($this->History->saveAll($componentHistory)){

							} else {
								$error[] = 'An error occured while saving history of component '. $component['code'];
							}
						}
					}

					// debug($this->id);

					//Save the Item Id in the new Checklist
					if(isset($newComponent['Item']['checklist_id'])) {
						$this->Checklist->id = $newComponent['Item']['checklist_id'];
						$this->Checklist->saveField('item_id', $this->id);
						$this->Checklist->init();
					}

					$items[] = $newComponent;
					unset($history);
					unset($newComponent);

					if($component['isAttached'] == true) {
						$itemHistory[] = array(	'item_id'	=> $item_id,
												'event_id' 	=> $event_ids['Item attached'],
												'comment'	=> 'Item ' .$component['code']. ' was attached at arrival.');
					}
					else {
						$itemHistory[] = array(	'item_id'	=> $item_id,
												'event_id' 	=> $event_ids['Item detached'],
												'comment'	=> 'Item was not attached at arrival.');
					}
				} else {
					$error[] = 'An error occured while saving component '. $component['code'];
				}
			}
/* 			elseif(($component['valid'] == true) && empty($error) && (false)) { //old stuff from back when there was a checkbox to create stock items on the fly. no longer possible with tags and stuff. Always defaults to false of course

				$newComposite['item_id']	= $item_id;
				$newComposite['valid']		= $component['isAttached'];	// true := 1
				$newComposite['position']	= $component['position'];

				$this->ItemComposition->create();
				if($this->ItemComposition->saveAll($newComposite)) {

					if($component['isAttached'] == true) {
						$itemHistory[] = array(	'item_id'	=> $item_id,
												'event_id' 	=> $event_ids['Item attached'],
												'comment'	=> 'At arrival an item at position '.$component['position'].' was attached.');
					}else{
						$itemHistory[] = array(	'item_id'	=> $item_id,
												'event_id' 	=> $event_ids['Item attached'],
												'comment'	=> 'At arrival an item at position '.$component['position'].' was not attached.');
					}
				} else {
					$error[] = 'An error occured while saving component '. $component['code'];
					$error[] = $newComposite;
				}
			}*/
		}
		//debug($itemHistory);
		$status['error'] = $error;
		$status['history'] = $itemHistory;
		return $status;
	}

	public function saveAssembledItem($assemble) {
		$itemHistories = array();
		$event_ids = $this->History->Event->getEventIds(array('Item created', 'Item attached', 'Item detached'));

		$history[] = array(	'event_id' 	=> $event_ids['Item created'],
							'comment' => $assemble['Item']['comment']);

		if(isset($assemble['Component'])) {
			foreach($assemble['Component'] as $pos => $component) {
				if(isset($component['component_id'])) {
					$itemHistories[] = array(	'item_id'	=> $component['component_id'],
												'event_id' 	=> $event_ids['Item attached'],
												'comment'	=> 'Item attached to ' .$assemble['Item']['code']. '.');

					$history[] = array(	'event_id' 	=> $event_ids['Item attached'],
										'comment'	=> 'Item ' .$assemble['Selection'][$pos]['code']. ' attached.');
				} elseif(isset($component['stock_id'])) {
					/*
					$this->loadModel('Stock');
					$stock['Stock'] = $selectedItem['Stock'];
					$stock['Stock']['amount'] = $stock['Stock']['amount']-1;
					$this->Stock->save($stock);
					*/
					$stock_id = $component['stock_id'];

					if(!empty($amount_stock_items[$stock_id])) {
						$amount_stock_items[$stock_id] = $amount_stock_items[$stock_id]+1;
					} else {
						$amount_stock_items[$stock_id] = 1;
					}

					$type = $assemble['Selection'][$pos]['type_name'];
					$subtype = $assemble['Selection'][$pos]['subtype_name'];
					$subtype_version = $assemble['Selection'][$pos]['subtype_version'];

					$history[] = array(	'event_id' 	=> $event_ids['Item attached'],
										'comment'	=> 'Item from stock ('.$type.'-'.$subtype.' v'.$subtype_version.') attached to position '.$pos.'.');
				}

			}

			if(!empty($amount_stock_items)) {
				foreach($amount_stock_items as $stock_id => $needed_amount) {
					$Stock = ClassRegistry::init('Stock');

					$Stock->id = $stock_id;
					if (!$this->Stock->exists()) {
						return false;
					}
					$stocks[$stock_id] = $Stock->find('first', array('conditions' => array('Stock.id' => $stock_id), 'recursive' => -1));
					//debug($stocks[$stock_id]);
					$stocks[$stock_id]['Stock']['amount'] = $stocks[$stock_id]['Stock']['amount'] - $needed_amount;
					if($stocks[$stock_id]['Stock']['amount'] < 0) {
						return false;
					}
					$Stock->save($stocks[$stock_id]);
				}
			}

			unset($assemble['ItemSubtypeVersion']);
			unset($assemble['Selection']);
			$assemble['History']	= $history;

			$assemble['Stock'] = $assemble['Component'];
		}

		if($this->saveAssociated($assemble)) {
			if(!empty($itemHistories)) {
				$this->History->saveAll($itemHistories);
			}
			return true;
		}
		return false;
	}

	public function changeLocationRecursive($item, $deliverer, $fromId, $toId, $parentId = null) {
		if($this->isStock($item["Item"]["id"]) && $parentId == null){
#			debug($item["Item"]["id"]."Is a stock item and not attached, add amount to target location and remove amount from current location");
			//Is a stock item and not attached, add amount to target location and remove amount from current location
			$this->ItemStocks->increaseStock($toId,$item["Item"]["amount"],$item["Item"]["id"]);
			$this->ItemStocks->reduceStock($fromId,$item["Item"]["amount"],$item["Item"]["id"]);
			//Only insert on "real" transfer completion, otherweise there are two points in the history one for the start and one for the finish of a transfer
			if($this->Location->find("count",array("conditions"=>array("id"=>$toId,"name"=>"In Transfer")))==0)
				$this->History->insertIntoHistory("Transfer",$item["Item"]["id"],"moved ".$item["Item"]["amount"]." Stock as part of a transfer");
		}elseif($this->isStock($item["Item"]["id"]) && $parentId != null){
			//Is a stock item and attached ignore
			return;
		}else{
#			debug($item["Item"]["id"]."Is no stock item, iterate over components recursively (if applicable) and change location of item");
			//Is no stock item, iterate over components recursively (if applicable) and change location of item
			if(isset($item["Components"])){
				foreach($item["Components"] as $component){
					$this->changeLocationRecursive($component, $deliverer, $fromId, $toId, $item["Item"]["id"]);
				}
			}
			$this->Transfer->Item->id = $item["Item"]["id"];
			//Only insert on "real" transfer completion, otherweise there are two points in the history one for the start and one for the finish of a transfer
			if($this->Location->find("count",array("conditions"=>array("id"=>$toId,"name"=>"In Transfer")))==0)
				$this->History->insertIntoHistory("Transfer",$item["Item"]["id"],"Changing location of item ".$item["Item"]["code"]." as part of a transfer");
			$this->Transfer->Item->saveField('location_id', $toId);
		}
	}



	public function changeLocationRecursiveOld($item_id, $deliverer, $from, $to, $event_id, $parent_id = null) {

		$this->useModel( array('Component', 'Location') );
		$item = $this->find('first', array(
							'conditions' => array('Item.id' => $item_id),
							'recursive' => 1
							));
		//debug($item);
		$history= array();
		$newComponent['item_id'] = $item_id;
		$newComponent['is_part_of'] = $parent_id;
		$newComponent['from_location_id'] = $from['From']['id'];
		$newComponent['to_location_id'] = $to['To']['id'];
		$components[] = $newComponent;
		if(!empty($item['Component'])) {
			foreach($item['Component'] as $component) {
				if($component['ItemComposition']['valid'] == TRUE) {
					$newValues = array();
					$newValues = $this->changeLocationRecursive($component['id'], $deliverer, $from, $to, $event_id, $item_id);
					$history = array_merge( $history, $newValues['History']);
					$components = array_merge( $components, $newValues['ItemTransfer']);
				}
			}
		}

		$this->Transfer->Item->id = $item_id;
		$this->Transfer->Item->saveField('location_id', $to['To']['id']);

		$history[] = array('History' => array(
						'event_id' => $event_id,
						'item_id' => $item_id,
						'comment' => 'Item send via '.$deliverer['Deliverer']['name'].' from '. $item['Location']['name'] .' to '. $to['To']['name'] .'.'));

		$values['History'] = $history;
		$values['ItemTransfer'] = $components;

		return $values;
	}

	/**
	 * hasItem method
	 *
	 * @param string $code
	 * @return boolean true if $code is already used for an item, else returns false.
	 */
	public function hasItem($code) {
		$conditions = array('Item.code' => $code);
		return $this->hasAny($conditions);
	}

    /**
     *
     */

    public function postRegistration($item) {
        $event_id = $this->History->Event->getEventId('Post registered');

        $dataSource = $this->getDataSource();

        $history[] = array( 'event_id'  => $event_id,
                            'comment'   => "Post registration");

        $item['History'] = $history;

        // begin database transaction
        $dataSource->begin();

        $this->create();
        if($this->saveAll($item)) {
            $item['ItemComposition']['component_id'] = $this->id;
            if($this->ItemComposition->save($item['ItemComposition'])) {
                $dataSource->commit();
            }
        } else {
            $dataSource->rollback();
        }
    }

	/**
	 * hasComponents method
	 * Validates if the given Item has currently attached components
	 * Returns true if components are present, else false.
	 *
	 * @param int $itemId The id of the ItemSubtypeversion
	 *
	 * @return boolean
	 */
	public function hasComponents($itemId) {
		$item = $this->find('first', array(
				'conditions' => array('Item.id' => $itemId),
				'contain' => array('Component')
			)
		);
		// If the array is empty there are sure no components
		if(empty($item['Component'])) {
			return false;
		}

		// check if the components arer still attached to the item
		foreach($item['Component'] as $component) {
			if($component['ItemComposition']['valid'] == 1){
				return true;
			}
		}

		return false;
	}

	/**
	 * @param int $itemId id of the item whose components should be checked
	 * @param string $field string-representation of the parameter to be checked e.g. item_type_id 
	 * @param string $value value the field should be compared against. 
	 * @return array containing the itemIds of the matching items
	 */
	public function getValidComponentsWithCondition($itemId,$field,$value){
		$items = $this->getValidComponentsRecursive($itemId);
		$output = array();
		foreach($items as $item){
			$this->compareToConditionRecursive($item,$field,$value,$output);
		}
		return $output;
	}

	/**
	 * @param array $input input taken from the items array
	 * @param string $field string-representation of the parameter to be checked e.g. item_type_id
	 * @param string $value value the field should be compared against.
	 * @param array $output recursively passed array that will contain the output
	 */
	public function compareToConditionRecursive($input,$field,$value,&$output=array()){
		if(isset($input["id"])){
			if($input[$field] == $value){
				$output[] = $input["id"];
			}
			$this->compareToConditionRecursive($input["Component"],$field,$value,$output);
		}elseif(is_array($input) && !isset($input["id"])){
			foreach($input as $tmp){
				$this->compareToConditionRecursive($tmp,$field,$value,$output);
			}
		}
	}
	
	/**
	 * getValidComponentsRecursive
	 * Returns an array containing all components and subcomponents of an item that are currently attached.
	 * @param int $itemId The id of the Item to get the subelements for
	 * @param array $return The array that will contain all results
	 * 
	 * @return array $return containing a nested layout of the (sub)components of this item
	 */
	
	public function getValidComponentsRecursive($itemId,&$return=array()){
		$tmp = $this->getValidComponents($itemId);
		foreach($tmp as $item){
			unset($item["ItemComposition"]);
			if(strpos($item["code"],"Stock_")!== false){
				$stockItemData = $this->findById($item["id"],array("ItemSubtype.name","ItemSubtypeVersion.version"));
				$item["code"] = $stockItemData["ItemSubtype"]["name"]." v".$stockItemData["ItemSubtypeVersion"]["version"];
			}
			$return[$item["id"]] = $item;
			$this->getValidComponentsRecursive($item["id"],$return[$item["id"]]["Component"]);
		}
		return $return;
	}
	
	/**
	 * getValidComponents method
	 * Returns an array with only currently attached components.
	 *
	 * @param int $itemId The id of the Item to look for
	 *
	 * @return array returns
	 */
	public function getValidComponents($itemId) {
		$item = $this->find('first', array(
				'conditions' => array('Item.id' => $itemId),
				'contain' => array('Component')
			)
		);

		foreach($item['Component'] as $key => $component) {
			if($component['ItemComposition']['valid'] != 1){
				unset($item['Component'][$key]);
			}
		}

		return $item['Component'];
	}

	public function getNumberOfComponents($itemId){
		$tmp = $this->findById($itemId);
		$itemSubtypeVersionId = $tmp["Item"]["item_subtype_version_id"];
		$itemSubtypeVersion = $this->ItemSubtypeVersion->findById($itemSubtypeVersionId);
		return count($itemSubtypeVersion["Component"]);
	}

  /**
   * getIsPartOfRecursive and getIsPartOf
   * return item(s) that the item in question is a part of.
   * recursive until it hits an item that is not attached to anything itself
   */

   public function getIsPartOfRecursive($itemId,$only_valid=true,&$return=array()) {
      $tmp = $this->getIsPartOf($itemId,$only_valid);
      if(!empty($tmp)) {
         $element = reset($tmp); // because the key could be any number
         $return[] = $element;
         $this->getIsPartOfRecursive($element['id'],$only_valid,$return);
      }
      return $return;
   }
   public function getIsPartOf($itemId,$only_valid=true) {
      $item = $this->find('first', array(
            'conditions' => array('Item.id'=>$itemId),
            'contain' => array('CompositeItem')
         )
      );
      if($only_valid) {
         foreach($item['CompositeItem'] as $key => $val) {
            if($val['ItemComposition']['valid']=='0') {
               unset($item['CompositeItem'][$key]);
            }
         }
      }
      return $item['CompositeItem'];
   }

	/**
	 * updateState method
	 * Update the state_id of one item
	 *
	 * @param string $itemId The id of the item
	 * @return boolean Returns true if the state changed, false if not
	 */

	public function updateState($itemId){

		$item = $this->find('first', array(
											'conditions' => array('Item.id' => $itemId),
											'recursive' => -1
										));

		$oldState = $this->State->find('first', array(
													'conditions' => array('State.id' => $item['Item']['state_id']),
													'recursive' => -1
												));

		if(is_null($oldState)) $oldState="";

		$this->Checklist->unbindModel(array('belongsTo' => array('Item','ClTemplate')));
		$this->Checklist->recursive=0;
		$checklist = $this->Checklist->find('first', array(
			'conditions' => array('Checklist.item_id' => $itemId)
		));

		if(!empty($checklist)){
			$clActions = $this->Checklist->ClAction->find('all', array(
																		'conditions' => array('ClAction.checklist_id' => $checklist['Checklist']['id'],
																							  'ClAction.hierarchy_level' => 1),
																		'order' => array('ClAction.list_number ASC')
																		)
			);
		}

		if(empty($checklist) || empty($clActions)) return false; //Backward compatibility or deleted Checklist

		// if(empty($checklist) || empty($clActions)){ //No associated checklist or deleted checklist, dummy template without any actions

			// $this->State->unbindModel(array('hasMany' => array('Item')));
			// $newState = $this->State->find('first', array('conditions'=> array('State.name' => 'unset')));
			// $this->saveField('state_id',$newState['State']['id']);

			// if(strcmp($oldState['State']['name'],$newState['State']['name']) !== 0) {

				// // Generate the automatic history comment
				// $eventId = $this->History->Event->getEventId('Item state changed');
				// $comment = 'State changed from "'.$oldState['State']['name'].'" to "'.$newState['State']['name'].'".';
				// $history = array( 'History' => array(
											// 'item_id'	=> $itemId,
											// 'comment'	=> $comment,
											// 'event_id' 	=> $eventId
										// )
								// );

				// $this->History->create();
				// if ($this->History->save($history, array('validate' => 'first'))) {
					// return true; // All data saved successful
				// }
			// }
			// else return true;

		// }

		if(($clActions[0]['ClAction']['status_code'] >> 12 & 0x3) !== 3) {
			foreach($clActions[0]['ClState'] as $clState) {
				if($clState['type'] == 'source'){ $newState = $clState; break; }
			}
		}
		else {
			foreach($clActions as $clAction) {
				if(($clAction['ClAction']['status_code'] >> 12 & 0x3) == 3) {
					foreach($clAction['ClState'] as $clState) {
						// debug($clState);
						if($clState['type'] == 'target'){ $newState = $clState; break; }
					}
				}
			}
		}

		if(strcmp($oldState['State']['name'],$newState['name']) !== 0) {
			$this->State->unbindModel(array('hasMany' => array('Item')));
			$state = $this->State->find('first', array('conditions'=> array('State.name' => $newState['name'])));
			if(empty($state)){
				$this->State->create;
				$state = array(
								'id' => $newState['id'],
								'name' => $newState['name'],
								'description' => $newState['description']
								);
				$this->State->save($state);
				$stateId = $this->State->getInsertId();
			}
			else $stateId = $state['State']['id'];

			$this->saveField('state_id',$stateId);

			// Generate the automatic history comment
			$eventId = $this->History->Event->getEventId('Item state changed');
			$comment = 'State changed from "'.$oldState['State']['name'].'" to "'.$newState['name'].'".';
			$history = array( 'History' => array(
										'item_id'	=> $itemId,
										'comment'	=> $comment,
										'event_id' 	=> $eventId
									)
							);

			$this->History->create();
			if ($this->History->save($history, array('validate' => 'first'))) {
				return true; // All data saved successful
			}
		}
		return false; // An error occured or update not needed
	}

	public function isStock($itemId = null){
		if($itemId==null){
			$itemId = $this->id;
		}
		return ($this->ItemStocks->find("count",array("conditions"=>array("item_id"=>$itemId)))>0);
	}

	public function getTagsForItem($itemId){
		return $this->ItemTagsItem->query("SELECT id,name FROM `item_tags` as ItemTag WHERE id in (SELECT item_tag_id FROM item_tags_items WHERE item_id = $itemId)");
	}
	public function getSetComponents($itemId){
		return array(0=>count($this->getValidComponents($itemId)),1=>$this->getNumberOfComponents($itemId));
	}
	
	
	public function getParentItemIdsRecursive($itemId = null,$ids = array()){
		$tmp = $this->find('first', array(
			'conditions' => array('Item.id' => $itemId),
			'contain' => array('CompositeItem')
		));
		foreach($tmp["CompositeItem"] as $item){
			$ids = $this->getParentItemIdsRecursive($item["id"],$ids);
			$ids[] = $item["id"];
		}
		return $ids;
	}
	
	public function isAttached($itemId = null){
		$tmp = $this->find('first', array(
				'conditions' => array('Item.id' => $itemId),
				'contain' => array('CompositeItem')
			));
			foreach($tmp["CompositeItem"] as $item){
				if($item["ItemComposition"]["valid"] == 1) return true; //If this is a valid component return true
			}
		return false;
	}
}
