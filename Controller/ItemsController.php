<?php
App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('Sanitize', 'Utility');
/**
 * Items Controller
 *
 * @property Item $Item
 */
class ItemsController extends AppController {

	var $uses = array('Item','StockView','ItemSubtypeVersion','ItemSubtypeVersionsComposition');
	
	// RequestHandler for Autocomplete
	public $components = array('Upload', 'RequestHandler', 'Search', 'Paginator', 'Linkify');

	public $helpers = array('My', 'Js');

	public $paginate = array(
        'limit' => 50,
        'maxLimit' => 500,
        'order' => array(
            'ItemView.code' => 'asc'
        ),
        //'recursive' => -1,
#		'contain' => array('ItemType','ItemSubtype','Manufacturer', 'ItemSubtypeVersion','State', 'Location', 'Project','ItemTag','ItemQuality'),
		// 'group' => array('Item.id'),
		// 'joins' => array(
						// array(
								// 'table' => 'item_compositions',
								// 'alias' => 'CompositeItem',
								// 'type' => 'left',
								// 'conditions' => array('CompositeItem.component_id = Item.id')
								// ),
						// array(
								// 'table' => 'item_stocks',
								// 'alias' => 'ItemStocks',
								// 'type' => 'left',
								// 'conditions' => array('ItemStocks.item_id = Item.id'),
						// )
						// ),
#		"joins" =>array(array("table"=>"item_tags_items","alias"=>"ItemTagsItem","type"=>"LEFT","conditions"=>array("ItemTagsItem.item_id = ItemView.id"))),
		// find only items which are not assembled to another item (show only available items)
		'conditions' => array("AND"=>array(
					'(ItemView.id) NOT IN (SELECT Item.id
					FROM items AS Item left JOIN item_compositions AS CompositeItemO
					ON (CompositeItemO.component_id = Item.id) where CompositeItemO.valid = 1 ORDER BY Item.id)',
		// find only non-stock items per default
					'(ItemView.id) NOT IN (SELECT item_id FROM item_stocks)'
					)
					)
	);

	// Session names
	public $sessionAssembleItemComposition = 'AssembleItemSubtype';

	/**
	 * create method, creates a new item and depending on the selections either
	 * 		creates it if necessary with multiple components or
	 * 		assembles it out of selected components or
	 * 		creates a stock item with a defined amount
	 * Also performs checks if the item_code is unique (if applicable), and which method to use depending on the item type and the user input
	 *
	 */
	public function create() {
		
		if($this->request->isAjax()){
			
			//AJAX Request, therefore at least stage two, check request data to determin what is required.
			$this->autoRender = false;
			
			if(isset($this->request->data["Item"]) && !isset($this->request->data["Item"]["item_quality_id"])){ //Step Two
				
				//Request contains Item array but not yet the quality, step two requested

				//Required for selectFromInventory method
				$this->Session->write("ItemData",$this->request->data);
				return $this->_step_two_view();

			}
			elseif(isset($this->request->data["step"])){ // SAVE (Step Four)	
			
				//Is step four, store values
				//First check if the "code" or the "amount" field are set.
				if(isset($this->request->data["Item"]["amount"])){
					$this->_register_stock_item();
				}elseif(isset($this->request->data["Item"]["code"]) && isset($this->request->data["Item"]["create_components"])){
					$this->_register_item(); //required for single item creation
				}elseif(isset($this->request->data["Item"]["code"])){
					$this->_assemble_item($this->request->data);
				}
				$this->set("description",$this->description);
				return $this->render("creation/progressbar");  //FP 2016-05-25 Questa progressbar prob. non esiste +!!!! 
			
			}
			elseif(isset($this->request->data["Item"]) && isset($this->request->data["Item"]["item_quality_id"])){ //Step Three
			
			//Request contains Item array and quality, step check which registration to execute and continue with that
				//First check if the "code" or the "amount" field are set.
				if(isset($this->request->data["Item"]["code"])){
					$faulty = array();
					if($this->Item->checkUniqueness($this->request->data["Item"],0,$faulty)){
						//Codes are unique, continue
						if(count($this->Item->separate($this->request->data["Item"]["code"]))>1){
							//More than one code show tab for each item
							// echo "here will be a component selector displaying one tab for each new item<br />";
							if(isset($this->request->data["Item"]["create_components"]))
								return $this->_view_tabbed_component_register();
							else
								return $this->_view_tabbed_component_selector();
						}else{
							//Display component view if applicable and/or the text describing what will happen on submit
							// echo "here will be the component view<br />";
							$this->set("itemCode",$this->request->data["Item"]["code"]);
							if(isset($this->request->data["Item"]["create_components"]))
								return $this->_view_component_register();
							else
								return $this->_view_component_selector();
						}
					}else{
						//Codes are not unique, throw error and exit
						if(count($faulty)>1)
							$this->_warning("The codes ".implode($faulty,", ")." exist already",true);
						else
							$this->_warning("The code ".$faulty[0]." exists already",true);
						return;
					}
				}
				elseif(isset($this->request->data["Item"]["amount"])){
					
 					// TODO: check and error messages if non stock items can be attached to this item subtype version
 					// echo "Amount set, showing message what will happen, or error message if the selected item has components";
					// echo "Amount set, showing component if only stock items are components, otherwise error message that a stock cannot be created of this itemsubtypeversion and what the requirements are<br />";
					$this->set("itemCodes",array(""));
					return $this->_view_component_selector(true,true);
				
				}else{
					
					//Somehow the javascript check failed, throw error and exit
					echo "You tricked the javascript check, well played... This is still as far as you'll get on this";
					return;
					
				}

			}
		}
		$locations = $this->Item->Location->getUsersLocations();
		$this->set(compact("locations"));
	}

	/**
	 * This function displays the view with tabbed components to enable configuration an creation of multiple items at once
	 *
	 */
	private function _view_tabbed_component_selector(){

		$this->set("itemCodes",$this->Item->separate($this->request->data["Item"]["code"]));
		return $this->_view_component_selector();

	}

	/**
	 * This function generates the required array for the component selector and displays it.
	 * It also calls the method that sets the descriptor string to be displayed before submitting the whole chain of actions
	 */
	private function _view_component_selector($onlyAllowStock=false,$isStock=false){
		
		$this->Item->ItemSubtypeVersion->unbindModel(array("hasMany"=>array("Item")));
		$assemble['ItemSubtypeVersion'] = $this->Item->ItemSubtypeVersion->find('first', array(
			'conditions' => array('ItemSubtypeVersion.id' => $this->request->data['Item']['item_subtype_version_id']),
			'contain' => array('ItemSubtype')
		));
		$assemble['ItemSubtypeVersion']['Components'] = $this->Item->ItemSubtypeVersion->Component->find('all', array(
			'order' => array('Component.position_numeric' => 'asc'),
			'conditions' => array('item_subtype_version_id' => $this->request->data['Item']['item_subtype_version_id']),
			'contain' => array(
				'ItemSubtypeVersion' => array(
					'fields' => array('version'),
					'ItemSubtype' => array(
						'fields' => array('name'),
						'ItemType' => array('fields' => array('name'))
						)
				)
			)
		));

		/*
		 * Initialize $assemble['Selection']
		 * Write data for Selection table for positions without an item.
		 */
		foreach($assemble['ItemSubtypeVersion']['Components'] as $component) {
			$position = $component['Component']['position'];
			if(empty($assemble['Selection'][$position])) {
				if($component['Component']['is_stock'] == false && !$onlyAllowStock) {
					$actions = array(__('Equip'), array('controller' => 'items', 'action' => 'selectFromInventory', $position, $this->request->data['Item']['item_subtype_version_id'], $component['Component']['is_stock']));
				}elseif($component['Component']['is_stock'] == false && $onlyAllowStock){
					$actions = "";
				} else {
					$actions = array(__('Equip Stock'), array('controller' => 'items', 'action' => 'selectFromInventory', $position, $this->request->data['Item']['item_subtype_version_id'], $component['ItemSubtypeVersionsComposition']['is_stock']));
				}
				$assemble['Selection'][$position] = array(
					 'position' => $position,
					 'type_name' => $component['ItemSubtypeVersion']['ItemSubtype']['ItemType']['name'],
					 'subtype_name' => $component['ItemSubtypeVersion']['ItemSubtype']['name'],
					 'subtype_version' => $component['ItemSubtypeVersion']['version'],
					 'code' => array('No item selected.', array('class' => 'highlight')),
					 'tags' => "-",
					 'state_name' => "-",
					 'quality' => "-",
					 'manufacturer_name' => "-",
					 'project_name' => "-",
					 'actions' => $actions
				);
			} else {
				$assemble['Selection'][$position]['subtype_version'].=', '.$component['ItemSubtypeVersion']['version'];
			}
		}
		
		$description = "";
		$submit = true;
		//Sort array by key (key = position). (low to high) if the item has components
		if(isset($assemble["Selection"])){
			//selection is set, there are components sort and write text accordingly
			ksort($assemble['Selection']);
			if($isStock){
				$description = $this->_warning("This Subtype Version has components, Stock Items can only be created of items without components.",false);
				$assemble["ItemSubtypeVersion"]["Components"] = array(); //Set the components to an empty array to signal this
				$submit = false;
			}else{
				$description = "If so desired you can now attach components. ";
			}

		}else{
			//no components, just write text and such
			if(isset($this->request->data["Item"]["code"])){
				$description = "No components available for this item subtype version. Clicking on 'Create item' will create ".count($this->Item->separate($this->request->data["Item"]["code"]))." Items with the current configuration";
			}else{
				$description = "Clicking on 'Create item' will create ".$this->request->data["Item"]["amount"]." Stock Items with the current configuration";
			}
		}
		$this->set(compact('assemble','description','submit'));
		$this->set("itemSubtypeVersion",$this->itemSubtypeVersion);
		return $this->render("creation/stepThree");
		
	}

	private function _view_tabbed_component_register(){
		$this->set("itemCodes",$this->Item->separate($this->request->data["Item"]["code"]));
		$this->_view_component_register();
	}

	private function _view_component_register(){
		
		$properties['location_id'] 	= $this->request->data['Item']['location_id'];
		$properties['state_id'] 	= 8;

		$components = $this->Item->ItemSubtypeVersion->getComponentsRecursive($this->request->data['Item']['item_subtype_version_id'], $properties);
		foreach($components as $component){
			if($component["Component"]["is_stock"]==1){
				$this->_warning("This Subtype Version has stock components, creating with new components not possible. Maybe you wanted to assemble?",true);
				return;
			}
		}
		
		$this->set("components",$components);
		$this->set("showShortName",$this->request->data["Item"]["show_shortName"]);
		$itemTags = $this->Item->ItemTag->find("list");
		$this->set('itemTags', $itemTags);
		$componentProjects = $this->Item->Project->find('list');
		$this->set('componentProjects', $componentProjects);
		$this->render("creation/register");
		
	}

	/**
	 * This internal function registers an item and its components in the database if all the parameters have been set correctly.
	 *
	 */
	private function _register_item($data = null){
		if($data == null)
			$data = $this->request->data;
		
		$eventIds = $this->Item->History->Event->getEventIds(array('Item created', 'Item attached', 'Item detached'));

		$newItemInsert = $data;
		$newItemInsert["ItemTag"] = $newItemInsert["Item"]["item_tags_id"];
		$newItemInsert['Item']['state_id'] 		= 1; //default state, i.e. state := unset
		unset($newItemInsert["Item"]["item_tags_id"],$newItemInsert["Item"]["code"]);

		//Get the Item code from the array key
		$tmp = array_keys($newItemInsert["ItemComposition"]);
		$itemCode = array_pop($tmp);
		$newItemInsert["Item"]["code"] = $itemCode;
		$this->_associateChecklist($newItemInsert);
#		debug($newItemInsert);
		$this->description = "Registering the Item with the code <b>".$itemCode."</b> with all selected components <br />";

		$location = $this->Item->Location->findById($newItemInsert["Item"]["location_id"]);
		$newItemInsert['Item']['comment'] .= "Item created at ".$location["Location"]["name"];
		
		//add the history event for item creation
		$history[] = array(	'event_id' 	=> $eventIds["Item created"],
							'comment'	=> $newItemInsert['Item']['comment']);

		$newItemInsert['History']	   = $history;

		if($this->Item->saveAll($newItemInsert)) {
			if(isset($newItemInsert["Item"]["checklist_id"]))
				$this->_setItemIdForChecklist($newItemInsert["Item"]["checklist_id"],$this->Item->id);
			if(!empty($newItemInsert["ItemComposition"][$itemCode]['Component'])) {
				$itemId = $this->Item->id;
				$status = $this->Item->saveComponentsRecursive($newItemInsert["ItemComposition"][$itemCode], $itemId, $itemCode,$newItemInsert["ItemTag"]);
				$itemHistory = $status['history'];
				$error = $status['error'];

				if(empty($error) && !empty($itemHistory)) {
					if(!$this->Item->History->saveAll($itemHistory)) {
						$error[] = 'Saving history of '.$code.' and its components failed';
					}
				}
			}
		} else {
			$error[] = 'Saving '.$itemCode.' failed';
			debug($this->Item->validationErrors);
		}
	}

	/**
	 * This internal function checks if all the required parameters for a stock item are set
	 * Then it checks if a stock item of this exact type, tag combination exists at any location anywhere and
	 * 		if yes uses the existing item_id and just adds a row to the item_stocks table (or updates it, if it is the same location)
	 * 		if no creates a stock item in the items table and adds a row to the item_stocks table with the new item_id
	 */
	private function _register_stock_item(){
		$this->description = "Creating <b>".$this->request->data["Item"]["amount"]."</b> Stock Items<br />";
		//Check if there is a stock item of this exact subtypeversion with the same quality and tags set
		if($this->Item->ItemStocks->configurationExists($this->request->data["Item"]["item_subtype_version_id"],$this->request->data["Item"]["item_quality_id"],$this->request->data["Item"]["item_tags_id"])){
			$this->description .= "configuration exists<br />";
			$location = $this->Item->Location->findById($this->request->data["Item"]["location_id"]);
			//If yes, check if there is already a stock of this type at the selected location
			if($this->Item->ItemStocks->stockExistsAtLocation($this->request->data["Item"]["location_id"])){
				//If yes, increase the stock by the set amount and display message that this was done
				//Store action in history
				$this->Item->History->insertIntoHistory("Stock Item amount changed",$this->Item->ItemStocks->itemId,"Added ".$this->request->data["Item"]["amount"]." stocks at ".$location["Location"]["name"].".");
				$this->description .= "stock was increased<br />";
				return $this->Item->ItemStocks->increaseStock($this->request->data["Item"]["location_id"],$this->request->data["Item"]["amount"]);
			}else{
				//If no, create stock at this location with the set amount
				//Store action in history
				$this->Item->History->insertIntoHistory("Stock Item amount changed",$this->Item->ItemStocks->itemId,"Added ".$this->request->data["Item"]["amount"]." stock to ".$location["Location"]["name"].".");
				$this->description .= "stock was added at this location<br />";
				return $this->Item->ItemStocks->addStockToLocation($this->request->data["Item"]["location_id"],$this->request->data["Item"]["amount"]);
			}
		}else{
			//If no, create a new stock item entry in the items table with the configuration and additionally a stock with the set amount at this location
			$this->description .= "configuration doesn't exist at the moment<br />";
			//Create new configuration in the items table with all the data set and a generic code
			$tableStatus = $this->Item->query("SHOW TABLE STATUS LIKE 'items'");
			$nextId = $tableStatus["0"]["TABLES"]["Auto_increment"];
			$newItemInsert = $this->request->data;
			$newItemInsert["Item"]["code"] = "Stock_".$nextId;
			$newItemInsert["Item"]["state_id"] = 8; //Hardcode the state for now
			$newItemInsert["ItemTag"] = $newItemInsert["Item"]["item_tags_id"];
			unset($newItemInsert["Item"]["amount"],$newItemInsert["Item"]["item_tags_id"]); //Remove the amount since it doesn't work like that
			if(!$this->Item->saveAll($newItemInsert)){
				debug($this->Item->validationErrors);
			}else{
				$this->description .= "Added configuration and set stock<br />";
				//Store action in history
				$location = $this->Item->Location->findById($newItemInsert["Item"]["location_id"]);
				$this->Item->History->insertIntoHistory("Item created",$nextId,"Created new Stock item with ".$this->request->data["Item"]["amount"]." available at ".$location["Location"]["name"].". ");
				//then set the stock at the location
				return $this->Item->ItemStocks->addStockToLocation($this->request->data["Item"]["location_id"],$this->request->data["Item"]["amount"],$nextId);
			}
		}
	}

	/**
	 * This internal function assembles an item checking if all the items selected for attachment:
	 * 		match the criteria, are still available and are on the same location
	 * It then writes the new configuration for the composite item into the database and reduces stock item amounts (if applicable)
	 */
	private function _assemble_item($data = null){
		if($data == null)
			$data = $this->request->data;
		if(!isset($data["Component"]))
			$data["Component"] = array();
		$this->description = "";
		if(!is_array($data["Item"]["code"]))
			$data["Item"]["code"] = array($data["Item"]["code"]);
		foreach ($data["Item"]["code"] as $itemCode){
			$newItemInsert = array();
			//Check the number of item codes
			if(isset($itemCode)){
				//One item to be assembled

				//Check each component
				foreach($data["Component"] as $component){
					if($this->Item->ItemStocks->isStockItem($component["component_id"])){
						//Is stock item: Check if there is sufficient stocks available for stock items

					}else{
						//Is no stock item: Check if it isn't attached yet/in the meantime

					}
				}


				//Save assembly
				$newItemInsert = $data;
				$newItemInsert["Item"]["code"] = $itemCode; //Set Code
				$newItemInsert["Item"]["state_id"] = 1; //default state, i.e. state := unset
				$newItemInsert["ItemTag"] = $newItemInsert["Item"]["item_tags_id"];
				$this->_associateChecklist($newItemInsert);
				if(!$this->Item->saveAll($newItemInsert)){
					debug($this->Item->validationErrors);
				}else{
					$location = $this->Item->Location->findById($newItemInsert["Item"]["location_id"]);
					$this->Item->History->insertIntoHistory("Item created",$this->Item->id,"Created new Item at ".$location["Location"]["name"]);
					if (!empty($newItemInsert["Component"]))
						$this->description .= "Registering the Item with the code <a href='".Router::url(array('controller'=>'items', 'action'=>'view',$this->Item->id))."'><b>".$itemCode."</b></a> and attaching all the selected components<br />";
					else
						$this->description .= "Registering the Item with the code <a href='".Router::url(array('controller'=>'items', 'action'=>'view',$this->Item->id))."'><b>".$itemCode."</b></a><br />";
					if(isset($newItemInsert["Item"]["checklist_id"]))
						$this->_setItemIdForChecklist($newItemInsert["Item"]["checklist_id"],$this->Item->id);
					//Saving was successful, reduce the stock item amounts by one for each component
					foreach($newItemInsert["Component"] as $component){
						if($this->Item->ItemStocks->isStockItem($component["component_id"])){
							$c = $this->Item->findById($component["component_id"]);
							$itemTypeString = $c["ItemType"]["name"]." ".$c["ItemSubtype"]["name"]." v".$c["ItemSubtypeVersion"]["version"];
							$this->Item->ItemStocks->reduceStockByOne($newItemInsert["Item"]["location_id"],$component["component_id"]);
							$this->Item->History->insertIntoHistory("Item attached",$this->Item->id,"Stock Item of type '".$itemTypeString."' was attached.");
							$this->Item->History->insertIntoHistory("Item attached",$component["component_id"],"One Stock was attached to '".$newItemInsert["Item"]["code"]."'.");

						}
					}
				}
			}else{
				return 0; //No codes
			}
		}

		return $this->render("creation/progressbar");

	}
	
	/**
	 * The internal function that returns the view for item registration --> Items without components
	 */
	private function _step_two_view(){
		
		$this->Item->ItemSubtypeVersion->unbindModel(array("hasMany"=>array("Item")));
		$itemSubtypeVersion = $this->Item->ItemSubtypeVersion->find('first', array(
																'conditions' => array('ItemSubtypeVersion.id' => $this->request->data['Item']['item_subtype_version_id']),
																'contain' => array('Component', 'ItemSubtype')));
		$this->set("itemSubtypeVersion",$itemSubtypeVersion);
																
		$itemQualities = $this->Item->ItemQuality->find('list');
		//Only get the tags applicable for this item type project combination
		$itemTags = $this->Item->ItemTag->getTagsForItemTypeAndProject($this->request->data["Item"]["item_type_id"],$this->request->data["Item"]["project_id"]);
		$this->set(compact('itemQualities','itemTags','assemble'));

		$itemTypesCreate = array("Wafer","Panel"); //Array containing the names of the item types where components should be created and not assembled
		$itemTypes = $this->Item->ItemType->find("list");
		$create = in_array($itemTypes[$this->request->data["Item"]["item_type_id"]],$itemTypesCreate)?TRUE:FALSE; //Check if selected item_type is in the array and if yes create even if not admin
		$this->set("create",$create);
	
		return $this->render("creation/stepTwo");
	
	}

	public function statistic() {
		if(!empty($this->request->data)) {
			$filter = $this->request->data;
			$this->Session->write('ItemIndexFilter', $filter);
		} else {
			$filter = $this->Session->read('ItemIndexFilter');
		}

		$conditions = $this->Search->getItemConditions($filter, 'ItemView',$this->paginate["joins"]);
		$this->loadModel("ItemView");
		$all_items = $this->ItemView->find('all', array(
								//'recursive' => -1,
            					//'group' => array('Item.location_id'),
            					//'fields' => 'DISTINCT Location.id, Location.name',
            					'joins' => $this->paginate["joins"],
								'conditions' => array('AND' => array($conditions))));
      $stock_items = 0;
      $total_available_stock = 0;
      foreach($all_items as $this_item) {
         $stockstring = "Stock (";
         $code = $this_item['ItemView']['code'];
         if(strpos($code,$stockstring)===0) {
            $stock_items += 1;
            $tmp = array();
            preg_match("/[0-9]+/",$code,$tmp);
            $total_available_stock += $tmp[0];
         }
      }
	   $number_of_items = count($all_items);

		$this->Item->unbindModel(
	        array(	'hasMany' => array('History', 'ChildStock','Measurement'),
	        		'hasAndBelongsToMany' => array('DbFile', 'CompositeItem', 'Component', 'Transfer')
	    ));
		$stat_locations = $this->ItemView->find('all', array(
								//'recursive' => -1,
            					//'group' => array('Item.location_id'),
            					'order' => array('location_name'),
            					'fields' => 'DISTINCT location_id, location_name',
            					'joins' => $this->paginate["joins"],
								'conditions' => array('AND' => array($conditions))));
		$stat_locations_header = Set::extract($stat_locations, '/ItemView/location_name');
		$stat_locations_ids	= Set::extract($stat_locations, '/ItemView/location_id');

		foreach($stat_locations_ids as $location_id) {
			$stat_locations_count[0][] = $this->ItemView->find('count', array(
								//'recursive' => 0,
            					//'group' => array('Item.location_id'),
            					//'fields' => 'DISTINCT State.id, State.name',
            					//'order' => array('State.name'),
            					'joins' => $this->paginate["joins"],
								'conditions' => array('AND' => array($conditions), 'location_id' => $location_id)));
		}
		foreach($stat_locations_header as $i => $location_name) {
			$stat_locations_cells[] = array($location_name, $stat_locations_count[0][$i]);
		}

		$this->Item->unbindModel(
	        array(	'hasMany' => array('History', 'ChildStock','Measurement'),
	        		'hasAndBelongsToMany' => array('DbFile', 'CompositeItem', 'Component', 'Transfer')
	    ));
		$stat_projects = $this->ItemView->find('all', array(
								'recursive' => 0,
            					//'group' => array('Item.location_id'),
            					'order' => array('project_name'),
            					'fields' => 'DISTINCT project_id,project_name',
            					'joins' => $this->paginate["joins"],
								'conditions' => array('AND' => array($conditions))));
		$stat_projects_header = Set::extract($stat_projects, '/ItemView/project_name');
		$stat_projects_ids	= Set::extract($stat_projects, '/ItemView/project_id');

		foreach($stat_projects_ids as $project_id) {
			$stat_projects_count[0][] = $this->ItemView->find('count', array(
								//'recursive' => 0,
            					//'group' => array('Item.location_id'),
            					//'fields' => 'DISTINCT State.id, State.name',
            					//'order' => array('State.name'),
            					'joins' => $this->paginate["joins"],
            					'conditions' => array('AND' => array($conditions), 'project_id' => $project_id)));
		}
		foreach($stat_projects_header as $i => $project_name) {
			$stat_projects_cells[] = array($project_name, $stat_projects_count[0][$i]);
		}

		$this->Item->unbindModel(
	        array(	'hasMany' => array('History', 'ChildStock','Measurement'),
	        		'hasAndBelongsToMany' => array('DbFile', 'CompositeItem', 'Component', 'Transfer')
	    ));
		$stat_states = $this->ItemView->find('all', array(
								'recursive' => 0,
            					//'group' => array('Item.location_id'),
            					'fields' => 'DISTINCT state_id, state_name',
            					'order' => array('state_name'),
            					'joins' => $this->paginate["joins"],
								'conditions' => array('AND' => array($conditions))));
		$stat_states_header = Set::extract($stat_states, '/ItemView/state_name');
		$stat_states_ids	= Set::extract($stat_states, '/ItemView/state_id');

		foreach($stat_states_ids as $state_id) {
			$stat_states_count[0][] = $this->ItemView->find('count', array(
								//'recursive' => 0,
            					//'group' => array('Item.location_id'),
            					//'fields' => 'DISTINCT State.id, State.name',
            					//'order' => array('State.name'),
            					'joins' => $this->paginate["joins"],
            					'conditions' => array('AND' => array($conditions), 'state_id' => $state_id)));
		}

		foreach($stat_states_header as $i => $state_name) {
			$stat_states_cells[] = array($state_name, $stat_states_count[0][$i]);
		}

		$this->set(compact('number_of_items', 'stat_locations_header', 'stat_locations_count',
                           'stat_projects_header', 'stat_projects_count', 'stat_states_header',
                           'stat_states_count', 'stat_locations_cells', 'stat_projects_cells',
                           'stat_states_cells', 'stock_items', 'total_available_stock'
      ));

		//$allItems = Set::extract($allItems, '/Item/location_id');
		/*
		 * check if there are some unavailable items (items which are part of another)
		 * which satisfy search criteria. if so warn the user
		 */
		if(empty($number_of_items) || $number_of_items == 0) {
			$countfilter = $filter;
			$countfilter['show_all'] = 1;
			$count = $this->Item->find('count', array('conditions' => $this->Search->getItemConditions($countfilter, 'ItemView',$this->paginate["joins"])));
			if($count > 0)
				$this->Session->setFlash(__('There are ' . $count . ' attached items. (Click on "Show all" in the filter menu to get statistics also from attached items.)'), 'default', array('class' => 'warning'));
		}

		// Search initiated
		if($this->RequestHandler->isAjax()) {
			$this->render('statisticTable', 'ajax');
		}

		$locations = $this->Item->Location->getUsersLocations();
		$states = $this->Item->State->find('list');
		$itemTags = $this->Item->ItemTag->find("list");
		$itemQualities = $this->Item->ItemQuality->find("list");

		$this->set(compact('items', 'filter', 'locations', 'itemSubtypeVersions','states','itemTags','itemQualities'));
	}

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index() {

		if(!empty($this->request->data)) {
			//New settings for the search
			$filter = $this->request->data;
			$oldFilter = $this->Session->read('ItemIndexFilter');
			//Compare old and new filter
			//if the amount of items per page changed reset the current page to the first
			if(isset($oldFilter["limit"])){
				if($filter["limit"] != $oldFilter["limit"]){
					$filter["named"]["page"] = 1;
					$this->request->params['named']["page"] = 1;
				}
			}
			$this->Session->write('ItemIndexFilter', $filter);
		} else {
			$filter = $this->Session->read('ItemIndexFilter');
		}
		#$this->_runtime("Initializing filter");

		if(!empty($filter['limit'])) {
			$this->paginate['limit'] = $filter['limit'];
		} else {
			$filter['limit'] = $this->paginate['limit'];
		}
		if(!isset($filter["location_id"]) || empty($filter["location_id"])){
			$filter["location_id"][] = $this->Item->Location->User->GetUserStandardLocation();
		}
		$this->paginate['conditions'] = $this->Search->getItemConditions($filter, 'ItemView',$this->paginate["joins"]);

		if($this->request->isAjax()) {
			// Reset page number after Search
			$this->request->data['named']['page'] = 1;
			$this->request->data['url'] = 'items/index/page:1';
			$this->request->data['paging']['Item']['page'] = 1;
			if(isset($this->request->data['paging']['Item']['options']['page'])) {
				$this->request->data['paging']['Item']['options']['page'] = 1;
			}
		}
		#$this->_runtime("Setting pagination conditions");
		
      #$this->Item->bindModel(array('hasOne'=>array("ItemTagsItem")),false);
      #$items = $this->paginate();
		$this->loadModel("ItemView");
		$this->Paginator->settings = $this->paginate;
		$this->Paginator->settings["group"] = array("ItemView.id","ItemView.location_id");
		$items = $this->Paginator->paginate('ItemView'); 
		#$this->_runtime("Paginating main inventory");

		$message = "";
		/*
		 * check if there are some unavailable items (items which are part of another)
		 * which satisfy search criteria. if so warn the user
		 */
		if(!isset($filter['show_all']) || $filter['show_all'] == 0) {
			$countfilter = $filter;
			$currentCount = $this->ItemView->find('count', array(
						'conditions' => $this->paginate['conditions'], //Use the normal conditions and count the amount of items in the current query. If I find out how to get this from the first pagination request I could replace this here with a variable access which would speed up things
						"joins" =>$this->paginate["joins"],
						"group"=>$this->Paginator->settings["group"]
						));
			debug($currentCount);
			#$this->_runtime("First count");
			$countfilter['show_all'] = 1;
			$count = $this->ItemView->find('count', array(
						'conditions' => $this->Search->getItemConditions($countfilter, 'ItemView',$this->paginate["joins"]),
						"joins" =>$this->paginate["joins"],
						"group"=>$this->Paginator->settings["group"]
						));
			debug($count);
			#$this->_runtime("Second count");

			if($count-($currentCount) > 0)
				$message .= __('There are ' . ($count-($currentCount)) . ' not showed attached items. (Click on "Show all" in the filter menu to unhide attached items.)<br />');
		}
		
		/*
		 * Show a count of items matching the criteria in other locations
		 *
		*/
		$countfilter = $filter;
		$usersLocations = array_flip($this->Item->Location->User->getUsersLocations()); //Get all allowed locations for the User
		foreach($countfilter["location_id"] as $locationId){
			unset($usersLocations[$locationId]);
		}
		if(count($usersLocations)>0){ //Check if no locations are available anymore and if yes set additional count to 0
			$countfilter["location_id"] = array_keys($usersLocations); //Set search option to only locations not used
			$count = $this->ItemView->find('count', array(
						'conditions' => $this->Search->getItemConditions($countfilter, 'ItemView',$this->paginate["joins"]),
						"joins" =>$this->paginate["joins"],
						));
		}else{
			$count = 0;
		}
		#$this->_runtime("Additional locations count");

		if($count > 0){
      $additional = count($items)>0 ? ' additional' : ''; //Set additional if applicable
			$are_is_items = $count==1 ? 'is one'.$additional.' item' : 'are '.$count.$additional.' items'; //Proper english
			$message .= __('There '.$are_is_items.' in an unselected location that matches your search criteria. <a onClick="searchWithAllLocations();" style="cursor:pointer;">Click here to search including all your locations.</a><br />');
    }
		
		/*
		 * Check if there are items in transfers that match the criteria
		 *
		*/
		if(empty($items)){
			$inTransferLocation = $this->Item->Location->findByName("In Transfer","Location.id");
			$countfilter = $filter;
			if(!empty($inTransferLocation)) $countfilter["location_id"] = $inTransferLocation["Location"]["id"]; //Set search option to transfer location id
			$this->paginate['conditions'] = $this->Search->getItemConditions($countfilter, 'ItemView',$this->paginate["joins"]);
			$count = $this->ItemView->find('count', array(
						'conditions' => $this->paginate['conditions'],
						"joins" =>array(array("table"=>"item_tags_items","alias"=>"ItemTagsItem","type"=>"LEFT","conditions"=>array("ItemTagsItem.item_id=ItemView.id")))
						));
			if($count > 0){
				if($count == 1) //Proper english
					$message .= __("There is one item in a transfer that matches your search criteria. It is now shown below. <br />");
				else
					$message .= __('There are ' . $count . ' items in transfers that match your search criteria. They are now shown below. <br />');
				$this->Paginator->settings = $this->paginate;
				$this->Paginator->settings["limit"] = $count; //Set the limit to the count so all items in transfers are always shown because the "next" button would not work since its not the normal pagination
				$items = $this->Paginator->paginate('ItemView');
			}
		}
		#$this->_runtime("In Transfer count (only if empty)");
		//Echo the message if applicable
		if($message != "")
			$this->Session->setFlash($message, 'default', array('class' => 'warning'));

		//Add additional information to the items array for each row
		foreach($items as $id=>$item){
			//Add tags to the displayed items
			$items[$id]["ItemTag"] = $this->Item->getTagsForItem($item["ItemView"]["id"]);
			//Defining the name displayed for a subtype version
			$items[$id]['ItemView']['svname'] = ($item['ItemView']['item_subtype_version_name'] != "")?$item['ItemView']['item_subtype_version']." (".$item['ItemView']['item_subtype_version_name'].")":$item['ItemView']['item_subtype_version'];
		}
		#$this->_runtime("Adding tags and svname");

      //Add number of attached components/free slots to items
		foreach($items as $id=>$item){
			$tmp = $this->Item->getSetComponents($item["ItemView"]["id"]);
			$items[$id]["Components"] = "";
			if(is_array($tmp)){
				if($tmp[1]!=0){
					$items[$id]["Components"] = $tmp[0]."/".$tmp[1]." attached";
				}
			}
		}
		#$this->_runtime("Setting attached components");
	
      // get item composition and pending transfers	
      foreach($items as $id=>$item){
         $tmp = $this->Item->find('first', array(
            'conditions' => array('Item.id' => $item["ItemView"]["id"]),
            'contain' => array('Transfer')
         ));
         if(empty($tmp['Transfer'])) {
            $items[$id]["TransferPending"]= false;
         } else {
            foreach($tmp['Transfer'] as $transfer) {
               if($transfer['status']==1) { $items[$id]["TransferPending"] = true; }
               else { $items[$id]["TransferPending"] = false; }
            }
         }
			if(strpos($item["ItemView"]["code"], "Stock (")===0){ // === needed because 0==false
				$items[$id]["isAttached"] = -1; //Make sure that stock items cannot be marked as attached.
         } else {
            $tmp['CompositeItem'] = $this->Item->getIsPartOfRecursive($item['ItemView']['id']);
            if(empty($tmp['CompositeItem'])) {
               $items[$id]['isAttached']= -1;
            } else {
               $items[$id]['isAttached']= $tmp['CompositeItem'];
            }
         }
		}
		#$this->_runtime("get item composition and pending transfers");

		//set pending transfers
		$pendingTransfers = array();
		$locationId = $this->Item->Location->User->getUserStandardLocation();
		$pendingTransfers[$locationId] = $this->Item->Transfer->getPendingFromLocations($locationId,'filteredByProjects');

		$locations = $this->Item->Location->getUsersLocations();
		#$this->_runtime("Available Transfers");
    
      // Do not make item code or item location an optional column because the javascript in inventory_table.ctp relies on them! 
      $columns = array();
      $columns[] = array('display'=>false,'file'=>'tags.ctp','sort_key'=>false,'title'=>'Tags');
      $columns[] = array('display'=>true,'file'=>false,'sort_key'=>'state_id','title'=>'State','link_text'=>'state_name','controller'=>'states','id'=>'state_id');
      $columns[] = array('display'=>false,'file'=>false,'sort_key'=>'ItemQuality.name','title'=>'Item Quality','link_text'=>'item_quality_name','controller'=>'item_qualities','id'=>'item_quality_id');
      $columns[] = array('display'=>false,'file'=>'components.ctp','sort_key'=>false,'title'=>'Components');
      $columns[] = array('display'=>true,'file'=>false,'sort_key'=>'item_type_id','title'=>'Item Type','link_text'=>'item_type_name','controller'=>'itemTypes','id'=>'item_type_id');
      $columns[] = array('display'=>true,'file'=>false,'sort_key'=>'item_subtype_id','title'=>'Item Subtype','link_text'=>'item_subtype_name','controller'=>'itemSubtypes','id'=>'item_subtype_id');
      $columns[] = array('display'=>true,'file'=>false,'sort_key'=>'item_subtype_version_id','title'=>'Version','link_text'=>'svname','controller'=>'item_subtype_versions','id'=>'item_subtype_version_id');
      $columns[] = array('display'=>true,'file'=>false,'sort_key'=>'manufacturer','title'=>'Manufacturer','link_text'=>'manufacturer_name','controller'=>'manufacturers','id'=>'manufacturer_id');
      $columns[] = array('display'=>true,'file'=>false,'sort_key'=>'project_id','title'=>'Project','link_text'=>'project_name','controller'=>'projects','id'=>'project_id');
      $columns[] = array('display'=>true,'file'=>'is_part_of.ctp','sort_key'=>false,'title'=>'is part of');
      
      // if columns are already set in the session read them from there else save them to session
      if($this->Session->check('ItemIndexColumns')) {
			$columns = $this->Session->read('ItemIndexColumns');
      } else {
			$this->Session->write('ItemIndexColumns', $columns);
      }

      // user changed what columns to display
      if(array_key_exists('cols',$this->request->data)) {
         foreach($columns as $col_key=>$col) {
            if($this->request->data['cols'][$col_key]==0) $columns[$col_key]['display'] = false;
            else $columns[$col_key]['display'] = true;
         }
			$this->Session->write('ItemIndexColumns', $columns);
      }
		$this->set(compact('columns'));

		// Search initiated
		if($this->request->isAjax()) {
		   $this->set(compact('items', 'filter', 'locations', 'pendingTransfers'));
			$this->render('inventoryTable', 'ajax');
		}
		//$itemSubtypeVersions = $this->Item->ItemSubtypeVersion->getUsersItemSubtypeVersions();
		$itemQualities = $this->Item->ItemQuality->find("list");
		$itemTags = $this->Item->ItemTag->find("list");
		// $this->_runtime("Get Final data");
		//debug($this->Item->testUser());

		$this->set('states', $this->Item->State->find('list'));
      //debug($items[0]);
		$this->set(compact('items', 'filter', 'locations', 'itemQualities', 'itemTags', 'pendingTransfers'));
		//$this->set(compact('items', 'filter', 'locations', 'itemSubtypeVersions','itemQualities','itemTags',"pendingTransfers"));
		#$this->_runtime("Everything else");
	}

	public function alert() {

	    if ($this->request->params['url']['ext'] != 'js') {
	        exit;
	    }

	    $this->layout = 'gen';
	    $this->ext = '.js';

	    $this->set("cacheDuration", '1 hour');

	    $data = "Hallo";
	    $this->set('data', $data);
	}

	/**
	 * view method
	 *
	 * @param string $id
	 * @return void
	 */
	public function view($id = null) {
		
		$this->loadModel('MeasurementTagsMeasurement');

		// check if item exists
		$this->Item->id = $id;
		if (!$this->Item->exists()) {
			throw new NotFoundException(__('Invalid item'));
		}

		// update item state
		$this->Item->updateState($this->Item->id);

		$this->Item->contain(array(
				"History",
				//"ItemSubtypeVersion.Component.ItemSubtype.ItemType", //FP Removed on 2016-06-23 because of changes
				"ItemSubtypeVersion",
				"Manufacturer",
				"ItemType",
				"ItemSubtype.DbFile.User",
				"ItemSubtypeVersion.DbFile.User",
				//"ItemSubtypeVersion.Manufacturer",
				"Project.DbFile.User",
				"Transfer.Deliverer",
				"Transfer.User",
				"Location",
				"Checklist",
				"State",
				"CompositeItem.CompositeItem",
				"Component.Location",
				"Component.ItemSubtypeVersion",
				"Component.ItemSubtype",
				"Component.ItemType",
				"Component.Manufacturer",
				"Component.State",
				"Component.Project",
				"Component.DbFile",
				"ItemComposition",
				"ItemsParameters.Parameter",
				"ItemTag",
				"ItemQuality",
				"DbFile.User"));

		$this->Item->Component->unbindModel(array(	
			'hasOne' => array('Checklist'),
			'hasMany' => array('History','Measurement'),
	    'hasAndBelongsToMany' => array('DbFile', 'CompositeItem', 'Component', 'Transfer')
	  ));

		$item = $this->Item->find('first', array(
			'conditions' 	=>	array('Item.id' => $id)
		));
		$item['CompositeItemChain']= array_reverse($this->Item->getIsPartOfRecursive($id));
		
		$components = $this->Item->ItemSubtypeVersion->Component->find('all', array(
			'order' => array('Component.position_numeric' => 'asc'),
			'conditions' => array('item_subtype_version_id' => $item['ItemSubtypeVersion']['id']),
			'contain' => array(
				'ItemSubtypeVersion' => array(
					'fields' => array('id','version','name','has_components'),
					'ItemSubtype' => array(
						'fields' => array('name','shortname'),
						'ItemType' => array('fields' => array('name'))
						)
				)
			)
		));
		$item['ItemSubtypeVersion']['Components'] = $components;
		
		//Return Item Data as json object if request is ajax
		if($this->request->isAjax()){
			$this->autoRender = false;
			echo json_encode($item);
			return;
		}
		
		$itemData["Item"] = $item["Item"];
		$this->Session->write("ItemData",$itemData);

		// Unbind relationship with Item for the next find('all') to save time by not joining tables
		$this->Item->Checklist->updateStatus($id);
		$this->Item->Checklist->unbindModel(array('belongsTo' => array('Item')));
		$this->Item->Checklist->recursive=2;
		$checklist = $this->Item->Checklist->find('first', array(
			'conditions' => array('Checklist.item_id' => $id)
		));

		// Unbind relationship with Item for the next find('all') to save time by not joining tables
		$this->Item->History->unbindModel(array('belongsTo' => array('Item')));
		$history = $this->Item->History->find('all', array(
			'conditions' => array('History.item_id' => $id),
			'contain' => array('Event', 'User')
		));
      $history = $this->Linkify->history_items($history);
		
      $limitedHistory = $this->Item->History->find('all', array(
			'conditions' => array('History.item_id' => $id, 'History.event_id'=>array(7,8,13)),
			'contain' => array('Event', 'User')
		));

      $item['TransferPending'] = -1;
		foreach($item['Transfer'] as $transfer) {
			$from_location_id = $transfer['ItemsTransfer']['from_location_id'];
			$to_location_id = $transfer['ItemsTransfer']['to_location_id'];

			$to = $this->Item->Location->find('first', array(
																'conditions' => array('Location.id' => $to_location_id),
																'recursive' => -1));
			$from = $this->Item->Location->find('first', array(
																'conditions' => array('Location.id' => $from_location_id),
																'recursive' => -1));

			$transfer['ItemsTransfer']['To'] = $to['Location'];
			$transfer['ItemsTransfer']['From'] = $from['Location'];
			$transfers[] = $transfer;
         if($transfer['status']==1) $item['TransferPending'] = $transfer['id'];
		}

		$measurements = $this->Item->Measurement->find("all",array(
										"fields"=>array("Measurement.id","MeasurementType.name","Measurement.start","Measurement.stop","History.created","User.first_name","User.last_name"),
										"conditions"=>array("Measurement.item_id"=>$this->Item->id),
										"order"=>array("History.created"=>"desc"),
										"recursive"=>true));
		//Add the measurement tags to the measurement array
		$measurementIds = array();
		foreach($measurements as $measurement)
			$measurementIds[] = $measurement["Measurement"]["id"];
		//Creates an array with measurementId=>measurementTags
		$measurement_tag_ids = $this->MeasurementTagsMeasurement->getTagsForMeasurementId($measurementIds);
		foreach($measurements as $Mid=>$measurement){
			if(isset($measurement_tag_ids[$measurement["Measurement"]["id"]]))
				$measurements[$Mid]["MeasurementTag"] = $measurement_tag_ids[$measurement["Measurement"]["id"]];
			else
				$measurements[$Mid]["MeasurementTag"] = array();
		}
		
      //set pending transfers
		$pendingTransfers = array();
		$pendingTransfers[$item['Item']['location_id']] = $this->Item->Transfer->getPendingFromLocations($item['Item']['location_id'],'filteredByProjects');

		$parameters = $this->Item->ItemsParameters->Parameter->find("list",array("order"=>array("name"=>"ASC")));
		$stockItemIds = $this->Item->ItemStocks->find("list",array("fields"=>"item_id"));
		sort($stockItemIds);
		$stockItemIds = json_encode($stockItemIds);
		$this->set(compact('item', 'history', 'limitedHistory', 'transfers','measurements','parameters','stockItemIds','checklist','pendingTransfers'));
		if(count($item["Component"])>0){
			$this->set("allComponents",$this->Item->getValidComponentsRecursive($id));
		}
		if($this->Item->isStock($id)){
			$this->set("locationWithAmount",$this->Item->ItemStocks->find("all",array("conditions"=>array("item_id"=>$id), "order"=>"Location.name")));
			return $this->render("view_stock");
		}
	}

	/**
	 * postRegistration method
	 *
	 * @param string $id The id of the parent item
	 * @param string $pos The position of the component, which should be registered subsequent
	 * @return void
	 *
	 * Allows a post registration of components of an item.
	 * For example: A wafer was registered without the main sensor because the sensor was delivered weeks later.
	 */
	public function postRegistration($id = null) {
		// Check if the necessary array from the html form was transmitted
		if($this->request->is('post'))	{
		    $item = $this->request->data;
			$this->Item->set($item);
			if($this->Item->validates()) {
			    // Check if the new item has usually components
			    $item_subtype_version_id = $item['Item']['item_subtype_version_id'];
                $itemSubtypeVersion = $this->Item->ItemSubtypeVersion->read(null, $item_subtype_version_id);
                if(!empty($itemSubtypeVersion['Component'])) {
                    // new item has components
                    // send the user to addItemComposition, same as by register item
                    $url = array('controller' => 'items', 'action' => 'postRegistration', $item['ItemComposition']['item_id']);
                    $item['Url'] = $url;
                    $this->Session->write('CommonItemData', $item);
                    $this->redirect(array('action' => 'addItemComposition'));
                } else {
                    // new item does not have components
                    $this->Item->postRegistration($item);
                }
			}else{
				debug($this->Item->validationErrors);
			}
		}

		// check if item exists
		$this->Item->id = $id;
		if (!$this->Item->exists()) {
			throw new NotFoundException(__('Invalid item'));
		}

		// read item data from database
		$this->Item->contain(array("ItemSubtypeVersion.Component.ItemSubtype.ItemType", "ItemSubtype"));
		$item = $this->Item->find('first', array(
			'conditions' 	=>	array('Item.id' => $id)
		));

		/**
		 * Create the standard codes of each component out of the current item code,
		 * their shortname and if necessary also out of ther position-
		 * Also check if the item already exists in the database.
		 */
		$components = $item['ItemSubtypeVersion']['Component'];
		foreach($components as $key => $component) {
			// create code
			if($this->_positionNecessary($components, $component)) {
				$code = $item['Item']['code'].'_'.$component['ItemSubtype']['shortname'].'_'.$component['ItemSubtypeVersionsComposition']['position'];
			} else {
				$code = $item['Item']['code'].'_'.$component['ItemSubtype']['shortname'];
			}
			$item['ItemSubtypeVersion']['Component'][$key]['code'] = $code;

			/*
			 * If item exists in database: created = true
			 * else: created = false
			 */
			$item['ItemSubtypeVersion']['Component'][$key]['created'] = ($this->Item->hasItem($code) ? true : false);
		}

		$this->set(compact('item'));
	}

	/**
	 * If two items are part of the same CompositeItem then the position number is requried in the item code for uniqueness
	 */
	private function _positionNecessary($items, $item) {
		foreach($items as $i) {
			if(($item['ItemSubtype']['id'] == $i['ItemSubtype']['id']) && ($item['ItemSubtypeVersionsComposition']['position'] != $i['ItemSubtypeVersionsComposition']['position']))
				return true;
		}
		return false;
	}

	public function deleteSession() {
		$this->Session->delete('CommonItemData');
		$this->Session->delete('NewSelection');
		$this->Session->delete('Selections');
	}

	private function _associateChecklist(&$item){
		//Associate default checklist if exists
		$this->Item->Checklist->ClTemplate->unbindModel(
														array(	'belongsTo' => array('ItemSubtype'),
																'hasMany' => array('ClAction')
														));
		$item_subtype_default_cltemplate =
			$this->Item->Checklist->ClTemplate->find('first', array(
																	'conditions' => array(
																							'ClTemplate.item_subtype_id' => $item['Item']['item_subtype_id'],
																							'ClTemplate.default' => true
																							)
																	));
		$code = $item['Item']['code'];
		if(!empty($item_subtype_default_cltemplate)){
			$checklistName = $code.'_cl';
			$checklistDescription = 'Created from default template';
			$checklistId = $this->Item->Checklist->createFromTemplate($item_subtype_default_cltemplate['ClTemplate']['id'],
																		$checklistName,$checklistDescription);
			if(!empty($checklistId)){
				$item['Item']['checklist_id']    = $checklistId;
				$item['Item']['cl_template_id']	= $item_subtype_default_cltemplate['ClTemplate']['id'];
				$firstclaction = $this->Item->Checklist->ClAction->find('first', array(
																					'conditions' => array('ClAction.list_number >' => 0),
																					'order' => array('ClAction.list_number' => 'asc')
																						));
				foreach($firstclaction['ClState'] as $clstate){
					if($clstate['type']=='source'){
						$this->Item->State->unbindModel(
														array(	'hasMany' => array('Item')
														));
						$state = $this->Item->State->find('first', array('conditions'=>array('State.name'=>$clstate['name'])));
						if(empty($state)){
							$this->Item->State->create;
							$state = array(
											'name' => $clstate['name'],
											'description' => $clstate['description']
											);
							$this->Item->State->save($state);
							$stateId = $this->Item->State->getInsertId();
						}
						else $stateId = $state['State']['id'];
						$item['Item']['state_id'] = $stateId;

						break;
					}
				}
			}
			else {
				$this->_error('Cannot create item checklist. Saving '.$code.' failed');
			}
		}
	}

	private function _setItemIdForChecklist($checklistId,$itemId){
		//Save the Item Id in the new Checklist
		if(isset($checklistId)) {
			$this->Item->Checklist->id = $checklistId;
			$this->Item->Checklist->saveField('item_id', $itemId);
			$this->Item->Checklist->init();
		}
	}

	public function transaction() {
	    // Read the transaction session data
		if($this->Session->check('transaction')) {	// addItemCompositionTransaction
			$transaction = $this->Session->read('transaction');
		} else {
			$this->Session->setFlash('Session for this action expired/deleted. Please repeat the item registration.');
			return $this->redirect(array('action' => 'index'));
		}
        // Start the transaction
		if($this->RequestHandler->isAjax()){
			$items = $transaction['Item'];

			if($this->request->data['action'] == 'next') {
				// read input data from Item\add
				$common_item_data = $this->Session->read('CommonItemData');

				// add History entry: Item created and Item attached
				$event_id = $transaction['event_id'];

				$item = reset($items);
				while(($item['status'] != 'pending') && ($item != FALSE)) {
					$item = next($items);
				}
				if($item == FALSE) {
					$transaction['progress'] = 100;
				} else {
					$code = key($items);
					$dataSource = $this->Item->getDataSource();
					$error = array();
					/***** Creating new item & its checklist (use default one if exist) ******/
					$newItem['Item']['code']			= $code;
					$newItem['Item']['item_subtype_version_id'] = $item['item_subtype_version_id'];
					$newItem['Item']['location_id'] 	= $item['location_id'];
					$newItem['Item']['item_quality_id'] = $item['item_quality_id'];
					$newItem['Item']['project_id'] 		= $item['project_id'];
					$newItem['Item']['state_id']		= 1; //default state, i.e. state := unset
					$newItem['Item']['comment'] 		= $item['comment'];
					//Only do this if a item_tag is set
					if(isset($item["item_tag"]))
						$newItem['ItemTag'] = $item["item_tag"];

					$item_subtype_version = $this->Item->ItemSubtypeVersion->find('first', array(
																'conditions' => array('ItemSubtypeVersion.id' => $item['item_subtype_version_id']),
																'contain' => array('ItemSubtype.ItemType')));

					$newItem['Item']['item_subtype_id'] = $item_subtype_version['ItemSubtype']['id'];
					$newItem['Item']['manufacturer_id'] = $item_subtype_version['ItemSubtypeVersion']['manufacturer_id'];
					$newItem['Item']['item_type_id']	= $item_subtype_version['ItemSubtype']['ItemType']['id'];



					$history[] = array(	'event_id' 	=> $event_id,
										'comment'	=> $common_item_data['Item']['comment']);

					$newItem['History']	   = $history;
					if(!empty($item['ChildStock'])) {
                    	$newItem['ChildStock'] = $item['ChildStock'];
					}
					/***** END *****/

					/* Begin database transaction */
					$dataSource->begin();

					$this->Item->create();
					if($this->Item->saveAll($newItem)) {

						if(!empty($common_item_data['ItemComposition'])) {
				            $common_item_data['ItemComposition']['component_id'] = $this->Item->id;
				            if(!$this->Item->ItemComposition->save($common_item_data['ItemComposition'])) {
				            	$error[] = 'Saving '.$code.' as a post registered component failed.';
							}
						}

						//Save the Item Id in the new Checklist
						if(isset($newItem['Item']['checklist_id'])) {
							$this->Item->Checklist->id = $newItem['Item']['checklist_id'];
							$this->Item->Checklist->saveField('item_id', $this->Item->id);
							$this->Item->Checklist->init();
						}

						if(!empty($item['Component'])) {
							$item_id = $this->Item->id;
							$status = $this->Item->saveComponentsRecursive($item, $item_id, $code);
							$itemHistory = $status['history'];
							$error = $status['error'];

							if(empty($error) && !empty($itemHistory)) {
							    $this->Item->History->create();
								if(!$this->Item->History->saveAll($itemHistory)) {
									$error[] = 'Saving history of '.$code.' and its components failed';
								}
							}
						}

					} else {
						$error[] = 'Saving '.$code.' failed';
					}

					if(empty($error)) {
						$items[$code]['status'] = 'success';
						$dataSource->commit();
					} else {
						$items[$code]['status'] = 'failed';
						$items[$code]['error'] = $error;
						$this->Item->Checklist->delete($checklistId); //not in transaction .....
						$dataSource->rollback();
					}

                    /* Database transaction ended */

					$transaction['Item'] = $items;

					$transaction['processed']	= $transaction['processed']+1;
					$transaction['progress']	= $transaction['processed']/$transaction['total']*100;
				}
			} else if($this->request->data['action'] == 'stop') {

			}
			$this->Session->write('transaction', $transaction);
			$this->set(compact ('transaction'));
			$this->render('progressbar', 'ajax');
		}

		$this->set(compact ('transaction'));
	}

	/**
	 * addItemComposition method
	 *
	 * @return void
	 *
	 * Registers a bunch of items, which can have components.
	 *
	 */
	public function addItemComposition() {

		// read input data from Item\add
		if($this->Session->check('CommonItemData')) {
			$common_item_data = $this->Session->read('CommonItemData');
			//debug($common_item_data);
		} else {
			return $this->redirect(array('action' => 'index'));
		}

		// Save Composite Items
		if(isset($this->request->data['ItemComposition']))
		{
			$itemCompositions = $this->request->data['ItemComposition'];

			if($this->_checkCodes($itemCompositions)) {
				//***** Create a new transaction object *****
				// Add Stock items to the transaction and remove them from normal items
				$stock = array();
				foreach($itemCompositions as $compositionsKey => $itemComposition) {
					foreach($itemComposition['Component'] as $componentKey => $component) {
						//debug($component);
						if(($component['is_stock'] == 1) && ($component['valid'] == 1) && ($component['isAttached'] == 0)) {
							if(isset($stock[$component['item_subtype_version_id']])) {
								$projects = $stock[$component['item_subtype_version_id']]['Project'];
                                $locations = $stock[$component['item_subtype_version_id']]['Location'];

                                if(!array_key_exists($component['project_id'], $projects)) {
                                    $projects[$component['project_id']] = array('project_id' => $component['project_id']);
                                }

                                if(!array_key_exists($component['location_id'], $locations)) {
                                    $projects[$component['location_id']] = array('location_id' => $component['location_id']);
                                }

								$stock[$component['item_subtype_version_id']] = array(
								    'amount' => $stock[$component['item_subtype_version_id']] + 1,
								    'item_subtype_version_id' => $component['item_subtype_version_id'],
								    'state_id' => $component['state_id'],
								    'Project' => $projects,
								    'Location' => $locations
                                );
							} else {
								$stock[$component['item_subtype_version_id']] = array(
                                    'amount' => 1,
                                    'item_subtype_version_id' => $component['item_subtype_version_id'],
                                    'state_id' => $component['state_id'],
                                    'Project' => array(array('project_id' => $component['project_id'])),
                                    'Location' => array(array('location_id' => $component['location_id'])),
                                    'ItemTag' => array(array('item_tag_id' => $component['item_tag_id']))
                                );
							}
							unset($itemCompositions[$compositionsKey]['Component'][$componentKey]);
						}
					}
					$itemCompositions[$compositionsKey]['ChildStock'] = $stock;
				}

				$this->Session->delete('transaction');
				$transaction['Item'] = $itemCompositions;
                // The URL as an cakephp array for the finish button after the transaction has stopped.
                // If no URL is passed as parameter, then the method will use: array('controller' => 'items', 'action' => 'index')
                $transaction['Url'] = (isset($common_item_data['Url']) ? $common_item_data['Url'] : null);
				// $transaction['ItemComposition'] is only set for post registered items
				$transaction['ItemComposition'] = (isset($common_item_data['ItemComposition']) ? $common_item_data['ItemComposition'] : null);

				foreach($transaction['Item'] as $code => $item) {
					$transaction['Item'][$code]['status'] = 'pending';
				}

				$transaction['total']	= count($transaction['Item']);
				$transaction['processed']	= 0;
				$transaction['progress']	= 0;
				// Saving the event_id in transaction saves a little bit of time
				$transaction['event_id']	= $this->Item->History->Event->getEventId('Item created');
				$this->Session->write('transaction', $transaction);
				//***** Transaction Object End *****
				// Start Transaction
				return $this->redirect(array('action' => 'transaction'));
			}
			else {
				$this->Session->setFlash('Every component needs a unique code', 'default', array('class' => 'warning'));
			}
		}

		$codes = $this->Item->separate($common_item_data['Item']['code']);
		foreach($codes as $key => $value) {
			// Escape quotes and other bad stuff for javascript
			$codes[$key] = Sanitize::html($value);
		}

		$item = $this->Item->ItemSubtypeVersion->find('first', array(
						'conditions' => array('ItemSubtypeVersion.id' => $common_item_data['Item']['item_subtype_version_id']),
						'contain' => array(
										'ItemSubtype.ItemType',
										'Manufacturer',
										'Project')));

		$item['Item'] = $common_item_data['Item'];
        $url = (isset($common_item_data['Url']) ? $common_item_data['Url'] : null);
		$properties['location_id'] 	= $common_item_data['Item']['location_id'];
		$properties['state_id'] = (isset($common_item_data['Item']['state_id']) ? $common_item_data['Item']['state_id'] : 1);
		//$properties['project_id']	= $common_item_data['Item']['project_id'];

		$components = $this->Item->ItemSubtypeVersion->getComponentsRecursive($common_item_data['Item']['item_subtype_version_id'], $properties);
		$componentProjects = $this->Item->Project->find('list');
		$itemTags = $this->Item->ItemTag->find("list");

        $this->set('url', $url);
		$this->set('item', $item);
		$this->set('components', $components);
		$this->set('itemCodes', $codes);
		$this->set('itemTags', $itemTags);
		$this->set('componentProjects', $componentProjects);
	}

	private function _checkCodes($itemCompositions) {
		$codes = $this->_getAllCodes($itemCompositions);

		// check if every item has a code
		foreach($codes as $key => $code) {
			if(empty($code)) {
				return false;
			}
		}

		// check for uniqueness within the new codes
		$result = array_unique($codes);
		if(count($codes) != count($result)) {
			return false;
		}



		return true;
	}

	private function _getAllCodes($items, $codes=array()) {
		foreach($items as $item) {
			$codes[] = $item['code'];

			if(!empty($item['Component'])) {
				$codes = $this->_getAllCodes($item['Component'], $codes);
			}
		}
		return $codes;
	}

	public function cancelAssemble() {
		$session = $this->sessionAssembleItemComposition;
		$this->Session->delete($session);
		return $this->redirect(array('action' => 'assemble'));
	}

	public function removeFromSelection($position = null) {
		$session = $this->sessionAssembleItemComposition;
		$assemble = $this->Session->read($session);
		unset($assemble['Selection'][$position]);
		unset($assemble['Component'][$position]);
		$this->Session->write($session, $assemble);
		return $this->redirect(array('action' => 'assembleItemComposition'));
	}

	/**
	 * $item_subtype_id ... id of the CompositeItemSubtype
	 */
	public function assembleItemComposition() {
		$session = $this->sessionAssembleItemComposition;
		$assemble = $this->Session->read($session);
		//debug($assemble);

		if($this->request->isPost()) {
			$dataSource = $this->Item->getDataSource();

			if(isset($this->request->data['Item']['code']))
				$assemble['Item']['code'] = $this->request->data['Item']['code'];
			if(isset($this->request->data['Item']['comment']))
				$assemble['Item']['comment'] = $this->request->data['Item']['comment'];
			if(isset($this->request->data['Item']['item_quality_id']))
				$assemble['Item']['item_quality_id'] = $this->request->data['Item']['item_quality_id'];
			if(isset($this->request->data['Item']['item_tags_id']))
				$assemble['ItemTag']['ItemTag'] = $this->request->data['Item']['item_tags_id'];

			//Associate default checklist if exists
			$this->Item->Checklist->ClTemplate->unbindModel(
															array(	'belongsTo' => array('ItemSubtype'),
																	'hasMany' => array('ClAction')
															));
			$item_subtype_default_cltemplate =
				$this->Item->Checklist->ClTemplate->find('first', array(
																		'conditions' => array(
																								'ClTemplate.item_subtype_id' => $assemble['Item']['item_subtype_id'],
																								'ClTemplate.default' => true
																								)
																		));

			if(!empty($item_subtype_default_cltemplate)){
				$code = $assemble['Item']['code'];
				$checklistName = $code.'_cl'; $checklistDescription = 'Created from default template';
				$checklistId = $this->Item->Checklist->createFromTemplate($item_subtype_default_cltemplate['ClTemplate']['id'],
																			$checklistName,$checklistDescription);

				if(!empty($checklistId)){
					$assemble['Item']['checklist_id'] = $checklistId;
					$assemble['Item']['cl_template_id']	= $item_subtype_default_cltemplate['ClTemplate']['id'];
					$firstclaction = $this->Item->Checklist->ClAction->find('first', array(
																						'conditions' => array('ClAction.list_number >' => 0),
																						'order' => array('ClAction.list_number' => 'asc')
																							));
					foreach($firstclaction['ClState'] as $clstate){
						if($clstate['type']=='source'){
							$this->Item->State->unbindModel(
															array(	'hasMany' => array('Item')
															));
							$state = $this->Item->State->find('first', array('conditions'=>array('State.name'=>$clstate['name'])));
							if(empty($state)){
								$this->Item->State->create;
								$state = array(
												'name' => $clstate['name'],
												'description' => $clstate['description']
												);
								$this->Item->State->save($state);
								$stateId = $this->Item->State->getInsertId();
							}
							else $stateId = $state['State']['id'];
							$assemble['Item']['state_id'] = $stateId;

							break;
						}
					}
				}
			}

			//*
			$dataSource->begin();
			$this->Item->set($assemble);
			if($this->Item->validates()) {
				$this->Item->create();
				if($this->Item->saveAssembledItem($assemble)) {

					//Save the Item Id in the new Checklist
					if(isset($assemble['Item']['checklist_id'])) {
						$this->Item->Checklist->id = $assemble['Item']['checklist_id'];
						$this->Item->Checklist->saveField('item_id', $this->Item->id);
						$this->Item->Checklist->init();
					}

					$this->Session->delete($session);
					$this->Session->setFlash('Item saved successful', 'default', array('class' => 'notification'));
					$dataSource->commit();
   				//return $this->redirect(array('action' => 'index'));
				}
				else {
					$this->Session->setFlash('Saving failed');
					$this->Item->Checklist->delete($checklistId); //not in transaction .....
					$dataSource->rollback();
				}
			}
			 //*/
		}
		if(isset($assemble)) {
			$itemSubtypeVersion = $this->Item->ItemSubtypeVersion->find('first', array(
																	'conditions' => array('ItemSubtypeVersion.id' => $assemble['Item']['item_subtype_version_id']),
																	'contain' => array('Component.ItemSubtype.ItemType', 'ItemSubtype')));

			$assemble['ItemSubtypeVersion'] = $itemSubtypeVersion;

			/*
			 * Initialize $assemble['Selection']
			 * Write data for Selection table for positions without an item.
			 */
			foreach($assemble['ItemSubtypeVersion']['Component'] as $component) {
				$position = $component['ItemSubtypeVersionsComposition']['position'];
				if(empty($assemble['Selection'][$position])) {
					$item_type_name = $component['ItemSubtype']['ItemType']['name'];
					$item_subtype_name =  $component['ItemSubtype']['name'];
					$item_subtype_version = $component['version'];
					$item_code = array('No item selected.', array('class' => 'highlight'));
					$state_name = '-';
					$manufacturer_name = '-';
					$project_name = '-';
					$item_tags = "-";
					$item_quality = "-";

					if($component['ItemSubtypeVersionsComposition']['is_stock'] == false) {
						$actions = array(__('Equip'), array('controller' => 'items', 'action' => 'selectFromInventory', $position));
					} else {
						$actions = array(__('Equip'), array('controller' => 'stocks', 'action' => 'select', $position));
					}

					$assemble['Selection'][$position] = array(
						'position' => $position,
						'type_name' => $item_type_name,
						'subtype_name' => $item_subtype_name,
						'subtype_version' => $item_subtype_version,
						'code' => $item_code,
						'tags' => $item_tags,
						'state_name' => $state_name,
						'quality' => $item_quality,
						'manufacturer_name' => $manufacturer_name,
						'project_name' => $project_name,
						'actions' => $actions
					);
				}
			}

			/*
			 * Sort array by key (key = position). (low to high)
			 */
			ksort($assemble['Selection']);

			$this->Session->write($session, $assemble);
		}
		else {
			return $this->redirect(array('action' => 'assemble'));
		}

		$itemQualities = $this->Item->ItemQuality->find('list');
		$itemTags = $this->Item->ItemTag->find('list');

		$this->set(compact('assemble', 'itemQualities','itemTags'));
	}

	public function selectFromInventory($position,$item_subtype_version_id,$stockItem=false){
		$data = $this->Session->read("ItemData");
		
      $filter = $this->request->query;
		$filter['project_id'][] = $data["Item"]["project_id"];
		$filter['location_id'][] = $data['Item']['location_id'];
		$filter['show_all'] = 0;	// make sure that only available items are listed

		$all_versions = $this->Item->ItemSubtypeVersion->ItemSubtypeVersionsComposition->find("all",array("conditions"=>array("item_subtype_version_id"=>$item_subtype_version_id,"position"=>$position),"fields"=>array("all_versions","component_id")));
		if(empty($all_versions)){
			$position = str_replace(" ","+",$position); //workaround to make + signs in urls work on the server. probably a problem with the URL forwarding
			$all_versions = $this->Item->ItemSubtypeVersion->ItemSubtypeVersionsComposition->find("all",array("conditions"=>array("item_subtype_version_id"=>$item_subtype_version_id,"position"=>$position),"fields"=>array("all_versions","component_id")));
		}

      foreach($all_versions as $this_version) {
         if($this_version["ItemSubtypeVersionsComposition"]["all_versions"] == 0) {
            $filter['item_subtype_version_id'][] = $this_version["ItemSubtypeVersionsComposition"]["component_id"];
         } else {
            $itemSubtypeId = $this->Item->ItemSubtypeVersion->find("first",array("conditions"=>array("ItemSubtypeVersion.id"=>$this_version["ItemSubtypeVersionsComposition"]["component_id"]),"fields"=>array("item_subtype_id")));
            $filter['item_subtype_id'][] = $itemSubtypeId["ItemSubtypeVersion"]["item_subtype_id"];
            unset($filter['item_subtype_version_id']);
            break; // get all versions, no need to look further
         }
      }

		$item_qualities = $this->Item->ItemQuality->find('list');

		if($stockItem){
			unset($filter["show_all"]);
			$items = $this->Paginator->paginate('StockView',$filter);
			$tags = $this->Item->ItemTag->find("list");
			$this->set(compact("items","position","item_qualities","tags"));
			$this->render("select_from_stocks");
		}else{ //Not stock item
			$this->paginate['conditions'] = $this->Search->getItemConditions($filter, 'ItemView',$this->paginate["joins"]);

			if(!empty($filter['limit'])) {
				$this->paginate['limit'] = $filter['limit'];
			} else {
				$filter['limit'] = $this->paginate['limit'];
			}
			$this->loadModel("ItemView");
			$this->Paginator->settings = $this->paginate;
			$items = $this->Paginator->paginate('ItemView');
			//Add tags to the displayed items
			foreach($items as $id=>$item){
				$items[$id]["ItemTag"] = $this->Item->getTagsForItem($item["ItemView"]["id"]);
			}
			$this->set(compact("items","position","item_qualities",'filter'));
			$this->render("select_from_inventory");
		}
	}

	public function cancelSelectFromInventoryForTransfer() {
		$this->Session->delete('Added');
		$this->Session->delete('Removed');
		return $this->redirect(array('controller' => 'transfers', 'action' => 'add'));
	}

	public function addSelectFromInventoryToTransfer() {

		$transfer = $this->Session->read('NewTransfer');	// get items which are already added to the transfer

		$added = $this->Session->read('Added');	// get items which were recently selected for adding to the transfer
		$removed = $this->Session->read('Removed');	// get items which were recently removed from the transfer

		// Merge this 3 arrays
		if(!empty($transfer['Selection']) && !empty($added['Selection'])) {
			$transfer['Selection'] = array_merge($transfer['Selection'], $added['Selection']);
		}
		elseif (empty($transfer['Selection']) && !empty($added['Selection'])) {
			$transfer['Selection'] = $added['Selection'];
		}

		if(!empty($removed)) {
			foreach($transfer['Selection'] as $key => $selectedItem) {
					foreach($removed['Selection'] as $removedItem) {
					if($selectedItem['Item']['id'] == $removedItem['Item']['id']) {
						unset($transfer['Selection'][$key]);
					}
				}
			}
		}

		$this->Session->delete('Added');
		$this->Session->delete('Removed');
		$this->Session->write('NewTransfer', $transfer);
		return $this->redirect(array('controller' => 'transfers', 'action' => 'add'));
	}

	/**
	 * changeCode method
	 *
	 * @param string $id
	 * @return void
	 */
	public function changeCode($id = null) {
		$this->Item->id = $id;
		if (!$this->Item->exists()) {
			throw new NotFoundException(__('Invalid item'));
		}
		$item = $this->Item->read(null, $id);

		if ($this->request->is('post') || $this->request->is('put')) {
			if($this->request->data['Item']['code'] == $item['Item']['code']) {//nothing changed
				$this->Session->setFlash(__('Nothing saved.'));
				return $this->redirect(array('action' => 'view', $id));
			}

			$event_id = $this->Item->History->Event->getEventId('Item code changed');

			$from = $item['Item']['code'];
			$to = $this->request->data['Item']['code'];

			$comment = 'Code changed from "'.$from.'" to "'.$to.'".';
			$user_comment = $this->request->data['History']['comment'];
			if(!empty($user_comment))
				$comment .= ' User comment: ' .$user_comment;

			$this->request->data['History'] = array(
								'event_id' 	=> $event_id,
								'comment'	=> $comment);

			unset($this->Item->validate['item_subtype_version_id']);
			unset($this->Item->validate['project_id']);
			unset($this->Item->validate['location_id']);
			unset($this->Item->validate['state_id']);
			unset($this->Item->validate['item_quality_id']);

			$this->Item->id = $id;

			if ($this->Item->History->saveAssociated($this->request->data, array('fieldlist' => array('code')))) {
				$this->Session->setFlash(__('New code saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'view', $id));
			} else {
				$this->request->data['History']['comment'] = $user_comment;
				$this->Session->setFlash(__('The item could not be saved. Please, try again.'));
//				debug($this->Item->History->validationErrors );
			}
		} else {
			$this->request->data = $item;
		}
	}

	public function changeItemSubtypeVersion($id = null) {
		$this->Item->id = $id;
		if (!$this->Item->exists()) {
			throw new NotFoundException(__('Invalid item'));
		}
		$item = $this->Item->find('first', array(
											'conditions' => array('Item.id' => $id),
											'contain' => array(
												'ItemType',
												'ItemSubtype',
												'ItemSubtypeVersion',
												'Component',
												'CompositeItem')));
		if(!empty($item['Component'])) {
			$this->Session->setFlash('Item has components. Version change not possible.', 'default', array('class' => 'warning'));
			return $this->redirect(array('action' => 'view', $id));
		}
		if(!empty($item['CompositeItem'])) {
			$this->Session->setFlash('Item is component. Version change not possible.', 'default', array('class' => 'warning'));
			return $this->redirect(array('action' => 'view', $id));
		}

		if ($this->request->is('post') || $this->request->is('put')) {

			$event_id = $this->Item->History->Event->getEventId('Item subtype changed');

			$from = $item['ItemType']['name'].' '.$item['ItemSubtype']['name'].' v'.$item['ItemSubtypeVersion']['version'];

			$newType = $this->Item->ItemType->find('first', array(
														'conditions' => array('ItemType.id' => $this->request->data['Item']['item_type_id']),
														'recursive' => -1));
			$newSubtype = $this->Item->ItemSubtype->find('first', array(
														'conditions' => array('ItemSubtype.id' => $this->request->data['Item']['item_subtype_id']),
														'recursive' => -1));
			$newVersion = $this->Item->ItemSubtypeVersion->find('first', array(
														'conditions' => array('ItemSubtypeVersion.id' => $this->request->data['Item']['item_subtype_version_id']),
														'recursive' => -1));

			$to = $newType['ItemType']['name'].' '.$newSubtype['ItemSubtype']['name'].' v'.$newVersion['ItemSubtypeVersion']['version'];

			$comment = 'Item Subtype changed from "'.$from.'" to "'.$to.'".';
			$user_comment = $this->request->data['History']['comment'];
			if(!empty($user_comment))
				$comment .= ' User comment: ' .$user_comment;

			$this->request->data['History'] = array(
								'event_id' 	=> $event_id,
								'comment'	=> $comment);

			$this->Item->id = $id;

			if ($this->Item->History->saveAssociated($this->request->data, array('validate' => false))) {
				$this->Session->setFlash(__('Changes saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'view', $id));
			} else {
				$this->request->data['History']['comment'] = $user_comment;
				$this->Session->setFlash(__('The changes could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $item;
		}

		$this->set(compact('item'));
	}

	/**
	 * changeState method
	 *
	 * @param string $id
	 * @return void
	 */
	public function changeState($id = null) {

		$this->Item->id = $id;
		if (!$this->Item->exists()) {
			throw new NotFoundException(__('Invalid item'));
		}
		$states = $this->Item->State->find('list');
		$item = $this->Item->read(null, $id);

		if ($this->request->is('post') || $this->request->is('put')) {

			$event_id = $this->Item->History->Event->getEventId('Item state changed');

			if(!empty($states[$item['Item']['state_id']])) $from = $states[$item['Item']['state_id']];
			else $from = null;
			$to = $states[$this->request->data['Item']['state_id']];

			$comment = 'State changed from "'.$from.'" to "'.$to.'".';
			$user_comment = $this->request->data['History']['comment'];
			if(!empty($user_comment))
				$comment .= ' User comment: ' .$user_comment;

			$this->request->data['History'] = array(
								'event_id' 	=> $event_id,
								'comment'	=> $comment);

			$this->Item->id = $id;

			if ($this->Item->History->saveAssociated($this->request->data, array('validate' => false))) {
				$this->Session->setFlash(__('New state saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'view', $id));
			} else {
				$this->request->data['History']['comment'] = $user_comment;
				$this->Session->setFlash(__('The item could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $item;
		}

		$this->set(compact('states'));
	}

	/**
	 * changeProject method
	 *
	 * @param string $id
	 * @return void
	 */
	public function changeProject($id = null) {
		$this->Item->id = $id;
		if (!$this->Item->exists()) {
			throw new NotFoundException(__('Invalid item'));
		}
		$projects = $this->Item->Project->find('list');
		$item = $this->Item->find('first',
				array(
						'conditions' => array('Item.id' => $id),
						'contain' => array('ItemSubtypeVersion')
					)
				);

		if ($this->request->is('post') || $this->request->is('put')) {
			// Validate if the components also need to be changed recursive
			$recursive = false;
			if(!empty($this->request->data['Item']['recursive']) && ($this->request->data['Item']['recursive']== 1)) {
				$recursive = true;
			}
			$projectId = $this->request->data['Item']['project_id'];

			$dataSource = $this->Item->getDataSource();
			$dataSource->begin();

			if($this->Item->changeProject($id, $projectId, $recursive)) {
				// Check if the user added addtional information
				if(!empty($this->request->data['History']['comment'])) {
					// ToDo: add this as a comment
					// Generate the automatic history comment
					$event_id = $this->Item->History->Event->getEventId('Comment');
					$comment = $this->request->data['History']['comment'];
					$history = array( 'History' => array(
												'item_id'	=> $id,
												'comment'	=> $comment,
												'event_id' 	=> $event_id
											)
									);

					$this->Item->History->create();
					$this->Item->History->save($history, array('validate' => 'first'));
				}

				$dataSource->commit();
				$this->Session->setFlash(__('Item transfered to '.$projects[$projectId]), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'view', $id));
			} else {
				$dataSource->rollback();
				$this->Session->setFlash(__('Was not able to save the current changes, please try again.'));
			}
		}

		$this->set(compact('projects', 'item'));
	}

	/**
	 * delete method
	 *
	 * @param string $id
	 * @return void
	 */
	public function delete($id = null, $code = null) {
		//debug($this->request->data);
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		if(!empty($this->request->data['Item'])) {
			$comp = $this->_deleteAll($this->request->data['Item']);
			if(!empty($comp)) {
				$this->set('items', $comp);
			}
			else {
				return $this->redirect(array('action' => 'index'));
			}
		}
		else if($id != null){
			$this->Item->id = $id;
			if (!$this->Item->exists()) {
				throw new NotFoundException(__('Invalid item'));
			}

			$histories = $this->Item->History->find('all', array(
													'conditions' => array('item_id' => $id),
													'recursive'  => -1));

			if(!empty($histories)){
				$this->Item->History->deleteAll(array('item_id' => $id));
			}

			//todo: Delete all Measurements
			$item = $this->Item->find('first', array(
									'conditions' => array('Item.id' => $id),
									'contain' => array(
										'Component',
										'Component.ItemType',
										'Component.ItemSubtype',
										'Component.ItemSubtypeVersion',
										'Component.Location',
										'Component.State',
										'Component.Manufacturer',
										'Component.Project')));

			$components = $item['Component'];

			$this->Item->id = $id;
			if ($this->Item->delete()) {
				$this->Session->setFlash(__('Item '.$code.' deleted'), 'default', array('class' => 'notification'));

				$this->loadModel('Log');
				$data['code'] = $code;
				$this->Log->saveLog('Item deleted', $data);

				if(!empty($components)) {
					$this->set('items', $components);
				}
				else {
					return $this->redirect(array('action' => 'index'));
				}
			}
			else {
				$this->Session->setFlash(__('Item '.$code.' was not deleted'));
				return $this->redirect(array('action' => 'index'));
			}
		}
	}

	private function _deleteAll($components) {
		$newComponents = array();

		foreach($components as $component) {
				$id = $component['id'];

				$this->Item->id = $id;
				if (!$this->Item->exists()) {
					//todo: überspringe das item und gehe zum nächsten
				}

				$histories = $this->Item->History->find('all', array(
														'conditions' => array('item_id' => $id),
														'recursive'  => -1));

				if(!empty($histories)){
					$this->Item->History->deleteAll(array('item_id' => $id));
				}

				//todo: Delete all Measurements
				$item = $this->Item->find('first', array(
											'conditions' => array('Item.id' => $id),
											'contain' => array(
												'Component',
												'Component.ItemType',
												'Component.ItemSubtype',
												'Component.ItemSubtypeVersion',
												'Component.Location',
												'Component.State',
												'Component.Manufacturer',
												'Component.Project')));

				$components = $item['Component'];

				$newComponents = array_merge($newComponents, $item['Component']);

				if ($this->Item->delete()) {
					$this->loadModel('Log');
					$data['code'] = $item['Item']['code'];
					$this->Log->saveLog('Item deleted', $data);
					//todo: erfolg
				}
				else {
					//todo: Fehler
				}
		}

		return $newComponents;
	}

	/**
	 * Detach method detaches an item from another at the given position (after sanity checks)
	 * If the given item Id is a stock item Id the item can also be added back to the stock (user Input)
	 */
	public function detach($itemId,$componentId,$position,$addBackToStock = false){
		if($this->request->isAjax()){

			$composition = $this->Item->ItemComposition->find('first', array(
															'conditions' => array('item_id' => $itemId,"component_id"=>$componentId,"position"=>$position,"valid"=>1),
															'recursive' => -1 ));
			if(count($composition)==0){
				$position = str_replace(" ","+",$position); //workaround to make + signs in urls work on the server. probably a problem with the URL forwarding
				$composition = $this->Item->ItemComposition->find('first', array(
					'conditions' => array('item_id' => $itemId,"component_id"=>$componentId,"position"=>$position,"valid"=>1),
					'recursive' => -1 ));
			}
			if(count($composition)==0){
				//Still not found, can't deal with this shit
				echo "couldn't detach item";
				return false;
			}
			$update = array("id"=>$composition["ItemComposition"]["id"],"valid"=>0);
			$this->Item->ItemComposition->save($update);

			$item = $this->Item->find("first",array(
											"conditions"=>array("Item.id"=>$itemId),
											'contain' => array('ItemSubtypeVersion.Component'),
											'recursive' => 2 )
									 );
			$component = $this->Item->find("first",array(
											"conditions"=>array("Item.id"=>$componentId),
											'recursive' => 1 )
									 );


			if(!$this->Item->isStock($componentId)){
				$this->Item->History->insertIntoHistory("Item detached",$itemId,"Item '".$component["Item"]["code"]."' detached.");
				$this->Item->History->insertIntoHistory("Item detached",$componentId,"Item detached from '".$item["Item"]["code"]."'.");
			}else{
				$text = "";
				if($addBackToStock=='true'){
					$this->Item->ItemStocks->increaseStockByOne($item["Item"]["location_id"],$componentId);
					$text = " and added back to the Stocks";
				}
				$itemTypeString = $component["ItemType"]["name"]." ".$component["ItemSubtype"]["name"]." v".$component["ItemSubtypeVersion"]["version"];
				$this->Item->History->insertIntoHistory("Item detached",$itemId,"Stock Item of type '".$itemTypeString."' was detached from position ".$position.$text.".");
				$this->Item->History->insertIntoHistory("Item detached",$componentId,"One Stock was detached from '".$item["Item"]["code"]."'".$text.".");
			}
		}
		$this->autoRender = false;

	}

	/**
	 * Attach method attaches an item to another at the given position (after sanity checks)
	 * If the given item Id is a stock item Id the item is also attached and the stock item amount at the current position of the item is reduced by one.
	 *
	 */
	public function attach($itemId,$componentId,$position){

		if($this->request->isAjax()){
			$this->Item->id=$itemId;
			if($this->Item->exists()){
				$item = $this->Item->find("first",array(
												"conditions"=>array("Item.id"=>$itemId),
												'contain' => array('ItemSubtypeVersion.Component'),
												'recursive' => 2 )
										 );
				$component = $this->Item->find("first",array(
												"conditions"=>array("Item.id"=>$componentId),
												'recursive' => 1 )
										 );
				//Check if position is supposed to contain a + sign or a space by checking for it in the database
				if($this->Item->ItemSubtypeVersion->find("count",array("joins"=>array(array("table"=>"item_subtype_versions_compositions","alias"=>"Composition","type"=>"LEFT","conditions"=>array("ItemSubtypeVersion.id = Composition.item_subtype_version_id"))),"conditions"=>array("ItemSubtypeVersion.id"=>$item["ItemSubtypeVersion"]["id"],"Composition.position"=>$position)))==0){
					$position = str_replace(" ","+",$position); //workaround to make + signs in urls work on the server. probably a problem with the URL forwarding
					if($this->Item->ItemSubtypeVersion->find("count",array("joins"=>array(array("table"=>"item_subtype_versions_compositions","alias"=>"Composition","type"=>"LEFT","conditions"=>array("ItemSubtypeVersion.id = Composition.item_subtype_version_id"))),"conditions"=>array("ItemSubtypeVersion.id"=>$item["ItemSubtypeVersion"]["id"],"Composition.position"=>$position)))==0){
						debug("Couldn't find Position in Database");
						return false;
					}
				}
				$itemComposition['valid'] = 1;
				$itemComposition['item_id'] = $itemId;
				$itemComposition['component_id'] = $componentId;
				$itemComposition['position'] = $position;
				$this->Item->ItemComposition->save($itemComposition);
				if(!$this->Item->isStock($componentId)){
					$this->Item->History->insertIntoHistory("Item attached",$itemId,"Item '".$component["Item"]["code"]."' attached.");
					$this->Item->History->insertIntoHistory("Item attached",$componentId,"Item attached to '".$item["Item"]["code"]."'.");
				}else{
					$itemTypeString = $component["ItemType"]["name"]." ".$component["ItemSubtype"]["name"]." v".$component["ItemSubtypeVersion"]["version"];
					$this->Item->History->insertIntoHistory("Item attached",$itemId,"Stock Item of type '".$itemTypeString."' was attached.");
					$this->Item->History->insertIntoHistory("Item attached",$componentId,"One Stock was attached to '".$item["Item"]["code"]."'.");
					$this->Item->ItemStocks->reduceStockByOne($item["Item"]["location_id"],$componentId);
				}


			}else{
				throw new NotFoundException(__('Item '. $itemId .' not found'));
			}


		}

		$this->autoRender = false;
	}


	public function saveForm(){
		if ($this->RequestHandler->isAjax()) {
			// Choose session based on formName
			$formName = $this->request->data['formName'];
			if($formName == 'AssembleItemComposition') {
				$session = $this->sessionAssembleItemComposition;
			} else {
				$session = "Unknown";
			}

			// Saving Form Data in Session
			$assemblyData = $this->Session->read($session);
			$assemblyData['Item'][$this->request->data['field']] = $this->request->data['value'];
			$this->Session->write($session, $assemblyData);
			$this->autoRender = false;
		}
	}

	public function generateCsv( $items = null ){
		$conditions = array();

		$filter = $this->Session->read('ItemIndexFilter');
		if(!empty($filter['limit'])) {
			$this->paginate['limit'] = $filter['limit'];
		} else {
			$filter['limit'] = $this->paginate['limit'];
		}
#		$this->paginate["joins"] = array(array("table"=>"item_tags_items","alias"=>"ItemTagsItem","type"=>"LEFT","conditions"=>array("ItemTagsItem.item_id = ItemView.id")));
		$this->paginate['conditions'] = $this->Search->getItemConditions($filter, 'ItemView',$this->paginate["joins"]);
#		debug($this->paginate['conditions']);
		$this->loadModel("ItemView");
		$this->Paginator->settings = $this->paginate;
		$this->Paginator->settings["group"] = array("ItemView.id","ItemView.location_id");
		$items = $this->Paginator->paginate('ItemView');;
#		$items = $this->ItemView->find('all', array(
#										'conditions' => $conditions));

		foreach($items as $item) {
			$tags = array();
			foreach($this->Item->getTagsForItem($item["ItemView"]["id"]) as $tag){
				$tags[] = $tag["ItemTag"]["name"];
			}
			$rows[] = array(
						'code' => $item['ItemView']['code'],
						'tags' => implode(", ",$tags),
						'quality' => $item['ItemView']['item_quality_name'],
						'type' => $item['ItemView']['item_type_name'],
						'subtype' => $item['ItemView']['item_subtype_name'],
						'version' => $item['ItemView']['item_subtype_version'],
						'location' => $item['ItemView']['location_name'],
						'state' => $item['ItemView']['state_name'],
						'manufacturer' => $item['ItemView']['manufacturer_name'],
						'project' => $item['ItemView']['project_name']
						);
		}

		$header = array('Code', 'Tags', 'Quality', 'Type', 'Subtype', 'Version', 'Location', 'State', 'Manufacturer', 'Project');

        // Write the csv file into a string
		$csv = implode(";", $header).";";

        foreach($rows as $row) {
            $csv.= "\r\n".implode(";", $row).";";
        }

        // Set the response Content-Type to vcard.
        $this->response->body($csv);
        // Add csv file type
        $this->response->type(array('csv' => 'text/csv'));
        $this->response->type('csv');

        //Optionally force file download
        $this->response->download('item_list.csv');

        // Return response object to prevent controller from trying to render a view
        return $this->response;

		/*
		 * Save File on Server
		 *

        //set the file name to save the View's output
        $path = WWW_ROOT . 'files/test.txt';	// WWW_ROOT = C:\xampp\htdocs\cakephp\ds20kcondb\webroot\
        $file = new File($path, true);

        //write the content to the file
        $file->write( $csv );

		$file->close();
		//*/
    }
	
	public function changeQuality($id=null){
		$this->Item->id = $id;
		if (!$this->Item->exists()) {
			throw new NotFoundException(__('Invalid item'));
		}
		$qualities = $this->Item->ItemQuality->find('list');
		$item = $this->Item->read(null, $id);

		if ($this->request->is('post') || $this->request->is('put')) {

			$event_id = $this->Item->History->Event->getEventId('Item Quality Changed');

			$from = $qualities[$item['Item']['item_quality_id']];
			$to = $qualities[$this->request->data['Item']['quality_id']];

			$comment = 'State changed from "'.$from.'" to "'.$to.'".';
			$user_comment = $this->request->data['History']['comment'];
			if(!empty($user_comment))
				$comment .= ' User comment: ' .$user_comment;

			$this->request->data['History'] = array(
								'event_id' 	=> $event_id,
								'comment'	=> $comment);

			//Due to some weird stuff quality_id needs to be changed to item_quality_id here
			$this->request->data["Item"]["item_quality_id"] = $this->request->data['Item']['quality_id'];
#			unset($this->request->data['Item']['quality_id']);
			if ($this->Item->History->saveAssociated($this->request->data, array('validate' => false))) {
				$this->Session->setFlash(__('New quality set'), 'default', array('class' => 'notification'));
				$this->redirect(array('action' => 'view', $id));
			} else {
				$this->request->data['History']['comment'] = $user_comment;
				$this->Session->setFlash(__('The item could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $item;
		}
		$this->set(compact('qualities'));
	}

	public function changeMultiple(){
		//Needs to be a post request with an array of item Ids that are supposed to be changed
		//Displays tag selector depending on the item type and project and an error message if the condition is not met
		//(alternatively maybe it will display only the tags available to all these groups or something)
		if(isset($this->request->data["selectedItems"])){
			$this->Session->write("selectedItems",$this->request->data["selectedItems"]);
			$selectedItems = $this->Session->read('selectedItems');
		}elseif($this->Session->read('selectedItems') != null){
			$selectedItems = $this->Session->read('selectedItems');
		}else{
			return $this->redirect(array('action' => 'index'));
		}

		foreach($selectedItems as $itemId){
			$this->Item->unbindModel(array(
						"hasMany"=>array("History","Measurement","ChildStock"),
						"hasAndBelongsToMany"=>array("DbFile","Component","Stock","Transfer","CompositeItem")
						));
			$items[$itemId] = $this->Item->findById($itemId);
		}
		$itemTypesProjects = array();
		$projectsItemTypes = array();
		$availableTags = array();
		foreach($items as $id=>$item){
			$itemTypeId = $item["ItemType"]["id"];
			$projectId = $item["Project"]["id"];
			if($this->Item->isStock($item["Item"]["id"])){
				$item["Item"]["code"] = "Stock Item ".$item['ItemSubtype']['name']." v".$item['ItemSubtypeVersion']['version'].", ".$item["ItemQuality"]["name"];

			}
			$itemTypesProjects[$itemTypeId][$projectId][$item["Item"]["id"]] = $item;
			$projectsItemTypes[$projectId][$itemTypeId][$item["Item"]["id"]] = $item;
			$availableTags[$projectId][$itemTypeId] = $this->Item->ItemTag->getTagsForItemTypeAndProject($itemTypeId,$projectId);
		}
		$projects = $this->Item->Project->find("list");
		$itemTypes = $this->Item->ItemType->find("list");

		// debug($itemTypesProjects);
		$this->set(compact("projectsItemTypes","projects","itemTypes","availableTags"));
		// debug($projectsItemTypes);
	}

	public function changeTags($id=null){
		$this->Item->id = $id;
		if (!$this->Item->exists()) {
			throw new NotFoundException(__('Invalid item'));
		}
#		$measurements = $this->Item->ItemTag->find("all",array("fields"=>array("name","Item")));
#		debug($measurements);
#		$measurements = Set::combine($measurements,'{n}.Measurement.id',array('{0} - {1}','{n}.Device.name','{n}.MeasurementType.name'),'{n}.Item.code');
		$item = $this->Item->findById($id,array("item_type_id","project_id"));
		$this->set("itemTags",$this->Item->ItemTag->getTagsForItemTypeAndProject($item["Item"]["item_type_id"],$item["Item"]["project_id"]));
		$this->set("item", $this->Item->read(null, $id));

	}

	public function setTagsForItem($id=null){
		if($this->request->isAjax){
			$this->autoRender = false;
			$tagIds = json_decode($this->request->data["itemTags"]);
			$success = true;
			if($id !== null && $tagIds !== null){
				//Get all tags set for the Item
				$setTags = $this->Item->ItemTagsItem->find("list",array("fields"=>array("item_tag_id","id"),"conditions"=>array("item_id"=>$id)));
				foreach($tagIds as $tagId){
					//Check if tag already set, if yes remove from list but don't insert
					if(isset($setTags[$tagId])){
						unset($setTags[$tagId]);
						continue; //skip rest of loop because it is only insertion
					}
					//If tag is not set add to item
					$itemTags[] = array("item_tag_id"=>$tagId,"item_id"=>$id);
					if($this->Item->ItemTagsItem->saveAll($itemTags)){
						$this->Item->History->addTagToItem($id,$tagId);
					}else{
						$success = false;
					}
				}
				//If tag is still in the list remove it
				foreach($setTags as $tagId=>$setTagId){
					if($this->Item->ItemTagsItem->delete($setTagId)){
						$this->Item->History->removeTagFromItem($id,$tagId);
					}else{
						$success = false;
					}
				}
				echo json_encode(array("success"=>$success));

			}else{
				throw new NotFoundException(__('Invalid item or tag'));
			}
		}else{
			throw new NotFoundException(__('Invalid Request'));
		}
	}

	/**
	 * Adds the tag with the given $tag_id to the item with $id
	 * Only reacts to ajax requests
	 */
	public function addTag($id=null,$tag_id=null){
		if($this->request->isAjax){
			if($id != null && $tag_id != null){
				$this->autoRender = false;
				$this->Item->id = $id;
				$tag_id = substr($tag_id, 4);
				$item = $this->Item->find("first",array("conditions"=>array("Item.id"=>$id),"recursive"=>1));
				$itemTags[] = array("item_tag_id"=>$tag_id,"item_id"=>$id);
				if($this->Item->ItemTagsItem->saveAll($itemTags)){
					$this->Item->History->addTagToItem($id,$tag_id);
					echo json_encode(array("success"=>true));
				}else{
					echo json_encode(array("success"=>false));
				}
			}else{
				throw new NotFoundException(__('Invalid item or tag'));
			}
		}else{
			throw new NotFoundException(__('Invalid Request'));
		}
	}

	/**
	 * Removes the tag with the given $tag_id from the item with $id if applicable
	 * Only reacts to ajax requests
	 */
	public function removeTag($id=null,$tag_id=null){
		if($this->request->isAjax){
			if($id != null && $tag_id != null){
				$this->autoRender = false;
				$this->Item->id = $id;
				$tag_id = substr($tag_id, 4);
				$item = $this->Item->find("first",array("conditions"=>array("Item.id"=>$id),"recursive"=>1));
				foreach($item["ItemTag"] as $tag){
					if($tag["id"] == $tag_id){
						$delId = $tag["ItemTagsItem"]["id"];
						break;
					}
				}
				if(isset($delId)){
					if($this->Item->ItemTagsItem->delete($delId)){
						$this->Item->History->removeTagFromItem($id,$tag_id);
						echo json_encode(array("success"=>true));
					}else{
						echo json_encode(array("success"=>false));
					}
				}else{
					echo json_encode(array("success"=>false));
				}
			}else{
				throw new NotFoundException(__('Invalid item or tag'));
			}
		}else{
			throw new NotFoundException(__('Invalid Request'));
		}

	}

	public function changeComment($id=null){
		$this->Item->id = $id;
		if (!$this->Item->exists()) {
			throw new NotFoundException(__('Invalid item'));
		}
		$item = $this->Item->read(null, $id);
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Item->saveAssociated($this->request->data, array('validate' => false))) {
				$this->Session->setFlash(__('New comment set'), 'default', array('class' => 'notification'));
				$this->redirect(array('action' => 'view', $id));
			}
		} else {
			$this->request->data = $item;
		}
	}

	public function addToItem($id=null,$whatToSave=''){
		$this->autoRender = false;
		$this->Item->id = $id;
		if (!$this->Item->exists()) {
			throw new NotFoundException(__('Invalid item'));
		}
		switch($whatToSave){
			case "removeItemParameter":
				$parameterId = $this->request->data["parameterId"];
				$parameter = $this->Item->ItemsParameters->findById($parameterId);
				if($this->Item->ItemsParameters->delete($parameterId)){
				//Add removal to history
				$this->Item->History->insertIntoHistory("Parameter removed", $id, "The parameter ".$parameter["Parameter"]["name"]." with the value '".$parameter["ItemsParameters"]["value"].
					"' and the comment '".$parameter["ItemsParameters"]["comment"]."' was removed from this item. ");
					echo json_encode(array("error"=>false));
				}else{
					echo json_encode(array("error"=>true));
				}
				break;
			case "addItemParameter":
				//Save new Item Parameter into database
				$lastId = $this->Item->ItemsParameters->addParameterToItem($id,$this->request->data);
				//request the new saved entry from the database
				$newEntry = $this->Item->ItemsParameters->findById($lastId);
				//Return the new entry as a table string to be appended
				if(isset($newEntry["ItemsParameters"])):
					echo "<tr>";
					echo "<td>".$newEntry["Parameter"]["name"]."</td>";
					echo "<td>".$newEntry["ItemsParameters"]["value"]."</td>";
					echo "<td>".$newEntry["ItemsParameters"]["comment"]."</td>";
					echo "<td>".$newEntry["ItemsParameters"]["timestamp"]."</td>";
					echo "</tr>";
				else:
					echo "<tr><td colspan='4'>Error during save</td></tr>";
				endif;
				break;
			case "setStockAmount":
				if($this->Item->ItemStocks->increaseStock($this->request->data["location_id"],$this->request->data["amount"],$id)){
					$this->Item->History->insertIntoHistory(
						"Stock Item amount changed",
						$id,
						"Manuall changed Stock Item Amount from ".$this->request->data["previousAmount"]." to ".($this->request->data["previousAmount"]+$this->request->data["amount"])."<br />".$this->request->data["userComment"]);
				}
				break;
			default:
				throw new NotFoundException(__('Not sure what to save'));
		}
	}

	private function _error($message,$echo = true){
		if($echo)
			echo '<div id="flashMessage" class="flash failure">'.$message.'</div>';
		else
			return '<div id="flashMessage" class="flash failure">'.$message.'</div>';
		return true;
	}
	
	private function _warning($message,$echo = true){
		if($echo)
			echo '<div id="flashMessage" class="flash warning">'.$message.'</div>';
		else
			return '<div id="flashMessage" class="flash warning">'.$message.'</div>';
		return true;
	}

	public function changeMultipleChecklists(){
	//Needs to be a post request with an array of item Ids that are supposed to be changed
	//Displays tag selector depending on the item type and project and an error message if the condition is not met
	//(alternatively maybe it will display only the tags available to all these groups or something)

		if(isset($this->request->data["selectedItems"])){
			$this->Session->write("selectedItems",$this->request->data["selectedItems"]);
			$selectedItems = $this->Session->read('selectedItems');
		}elseif($this->Session->read('selectedItems') != null){
			$selectedItems = $this->Session->read('selectedItems');
		}else{
			return $this->redirect(array('action' => 'index'));
		}

		foreach($selectedItems as $itemId){
			$this->Item->unbindModel(array(
						"hasMany"=>array("History","Measurement","ChildStock"),
						"hasAndBelongsToMany"=>array("DbFile","Component","Stock","Transfer","CompositeItem")
						));
			$items[$itemId] = $this->Item->findById($itemId);
		}

		$itemTypesProjects = array();
		$projectsItemTypes = array();
		$projectsItemSubtypes = array();
		$availableClTemplates = array();

		foreach($items as $id=>$item){
			$itemTypeId = $item["ItemType"]["id"];
			$itemSubtypeId = $item["ItemSubtype"]["id"];
			$projectId = $item["Project"]["id"];

			$itemTypesProjects[$itemTypeId][$projectId][$item["Item"]["id"]] = $item;
			$itemSubtypesProjects[$itemSubtypeId][$projectId][$item["Item"]["id"]] = $item;
			$projectsItemTypes[$projectId][$itemTypeId][$item["Item"]["id"]] = $item;
			$projectsItemSubtypes[$projectId][$itemSubtypeId][$item["Item"]["id"]] = $item;

			//ClTemplates are not classified according to Project ... copied from Tags page
			$availableClTemplates[$projectId][$itemSubtypeId] = $this->Item->Checklist->ClTemplate->find('list',array('conditions' => array('item_subtype_id' => $itemSubtypeId)));
		}
		$projects = $this->Item->Project->find("list");
		$itemTypes = $this->Item->ItemType->find("list");
		$itemSubtypes = $this->Item->ItemSubtype->find("list");

		// debug($itemSubtypesProjects);
		$this->set(compact("projectsItemTypes","projectsItemSubtypes","projects","itemTypes","itemSubtypes","availableClTemplates"));
		// debug($projectsItemSubTypes);
	}

	public function setChecklistForItem($id=null){

		if($this->request->isAjax){

			$this->autoRender = false;
			$clTemplateIds = json_decode($this->request->data["itemChecklists"]);
			$success = true;

			if($id != null && $clTemplateIds != null){

				//Check if checklist already exist
				$checklistNum = $this->Item->Checklist->find('count', array(
																			'conditions' => array(
																									'Checklist.item_id' => $id
																									)
																));

				if($checklistNum == 0){

					$item = $this->Item->findById($id);
					$itemSubtypeId = $item["ItemSubtype"]["id"];

					//Create checklist if template exists
					$this->Item->Checklist->ClTemplate->unbindModel(
																		array(	'belongsTo' => array('ItemSubtype'),
																				'hasMany' => array('ClAction')
																			));
					$clTemplate =
						$this->Item->Checklist->ClTemplate->find('first', array(
																				'conditions' => array(
																										'ClTemplate.id' => $clTemplateIds[0]
																										)
																				));

					if(!empty($clTemplate)){

						$code = $item['Item']['code'];
						$checklistName = $code.'_cl'; $checklistDescription = 'Created from template';
						$checklistId = $this->Item->Checklist->createFromTemplate($clTemplateIds[0],
																					$checklistName,$checklistDescription);

						$to = null; $from = null;
						if(!empty($checklistId)){

							$this->Item->Checklist->id = $checklistId;
							$this->Item->Checklist->saveField('item_id', $id);
							$this->Item->Checklist->init();

							$firstclaction = $this->Item->Checklist->ClAction->find('first', array(
																	'conditions' => array(
																							'ClAction.checklist_id ' => $checklistId,
																							'ClAction.list_number >' => 0
																						  ),
																	'order' => array('ClAction.list_number' => 'asc')
																));
							if(!is_null($firstclaction)){
								foreach($firstclaction['ClState'] as $clstate){
									if($clstate['type']=='source'){
										$this->Item->State->unbindModel(
																		array(	'hasMany' => array('Item')
																		));
										$state = $this->Item->State->find('first', array('conditions'=>array('State.name'=>$clstate['name'])));
										if(empty($state)){
											$this->Item->State->create;
											$state = array(
															'name' => $clstate['name'],
															'description' => $clstate['description']
															);
											$this->Item->State->save($state);
											$stateId = $this->Item->State->getInsertId();
										}
										else $stateId = $state['State']['id'];

										$states = $this->Item->State->find('list');
										$eventDesc = 'Item state changed';
										if(!empty($states[$item['Item']['state_id']])) $from = $states[$item['Item']['state_id']];
										else $from = null;
										$this->Item->id = $id;
										$this->Item->saveField('state_id', $stateId);
										$to = $states[$stateId];
										$comment = 'State changed from "'.$from.'" to "'.$to.'".';
										$this->Item->History->insertIntoHistory($eventDesc,$id,$comment);



										break;
									}
								}
							}
						}
					}

					echo json_encode(array("success"=>$success, "checklistId"=>$checklistId, "from"=>$from, "to"=>$to));

				}else{

					$success = false;
					echo json_encode(array("success"=>$success, "checklistNum"=>$checklistNum));
					// throw new ForbiddenException(__('Checklist already exists'));

				}
			}else if($id != null){

				$this->Item->id = $id;
				//Check if a checklist already exist
				$checklist = $this->Item->Checklist->find('first', array(
																			'conditions' => array(
																									'Checklist.item_id' => $id
																									)
																));
				//Remove the existing checklist
				if(!empty($checklist)){
					$this->Item->Checklist->delete($checklist["Checklist"]["id"]);

					$item = $this->Item->findById($id);
					$states = $this->Item->State->find('list');

					$eventDesc = 'Item state changed';
					if(!empty($states[$item['Item']['state_id']])) $from = $states[$item['Item']['state_id']];
					else $from = null;
					$this->Item->id = $id;
					$this->Item->saveField('state_id', '1');
					$to = $states[1];
					$comment = 'State changed from "'.$from.'" to "'.$to.'".';
					$this->Item->History->insertIntoHistory($eventDesc,$id,$comment);
				}

				$checklistId = null;
				$success = true;
				echo json_encode(array("success"=>$success, "checklistId"=>$checklistId, "from"=>$from, "to"=>$to));

			}else{

				throw new BadRequestException(__('Invalid item or checklist'));

			}
		}else{

			throw new NotFoundException(__('Invalid Request'));

		}

	}
	
	/***
	 * Returns a list of a requested item characteristic for one or multiple different item classifications
	 */
	function getAvailable(){
		$this->autoRender=false;
		//Checking if one of the values is set
		if(isset($this->request->data["next"]) && isset($this->request->data["Item"])){ //Required for item create
			$setParameters = $this->request->data["Item"];
		}elseif(isset($this->request->data["next"]) && isset($this->request->data["ItemSubtypeVersion"])){ //Required for subtypeversion add and edit
			$setParameters = $this->request->data["ItemSubtypeVersion"];
		}elseif(isset($this->request->data["next"]) && isset($this->request->data["Project"])){ //Required for subtypeversion add and edit
			$setParameters = $this->request->data["Project"];
		}else{
			echo "Error";
		}
		if(isset($setParameters)){
         #$requested = Inflector::variable(str_replace("_id","",$this->request->data["next"])); //remove _id suffix and convert to nicer name
			$requested = $this->request->data["next"];
			$return = array();
			switch($requested){
				case "item_type_id":
					//ItemType is requested and project id set get from ProjectItemTypes table
					if(isset($setParameters["project_id"])){
						$itemTypes = $this->Item->ItemType->Project->find("all",array("conditions"=>array("id"=>$setParameters["project_id"]),"contain"=>"ItemType"));
						foreach($itemTypes[0]["ItemType"] as $itemType){
							$return[$itemType["id"]] = $itemType["name"];
						}
					}
					break;
				case "item_subtype_id":
					//If ItemSubtype is requested and ItemType and Project are set get from ItemSubtypeVersions_project the ids and then from ItemSubtypeVersions again the ids and then from ItemSubtype table the names
					if(isset($setParameters["item_type_id"]) && isset($setParameters["project_id"])){
						$itemSubtypeVersions = $this->Item->ItemType->ItemSubtype->ItemSubtypeVersion->Project->find("all",array("conditions"=>array("id"=>$setParameters["project_id"]),"contain"=>array("ItemSubtypeVersion")));
						$iSVIds = array();
						foreach($itemSubtypeVersions[0]["ItemSubtypeVersion"] as $itemSubtypeVersion){
							$iSVIds[] = $itemSubtypeVersion["id"];
						}
						$itemSubtypeVersions = $this->Item->ItemType->ItemSubtype->ItemSubtypeVersion->find("all",array("conditions"=>array("ItemSubtypeVersion.id"=>$iSVIds),"contain"=>array("ItemSubtype")));
            #debug($itemSubtypeVersions);
						$iSIds = array(); //ItemSubtypeIds
						foreach($itemSubtypeVersions as $v){
							$iSIds[] = $v["ItemSubtype"]["id"];
						}
						$itemSubtypes = $this->Item->ItemType->ItemSubtype->find("all",array("conditions"=>array("ItemSubtype.id"=>$iSIds,"item_type_id"=>$setParameters["item_type_id"]),"fields"=>array("ItemSubtype.id","ItemSubtype.name","ItemSubtype.shortname")));
						foreach($itemSubtypes as $id=>$data){
							if($data["ItemSubtype"]["shortname"] == $data["ItemSubtype"]["name"]){
								$return[$data["ItemSubtype"]["id"]] = $data["ItemSubtype"]["shortname"];
							}else{
								$return[$data["ItemSubtype"]["id"]] = $data["ItemSubtype"]["shortname"]." - ".$data["ItemSubtype"]["name"];
							}
						}
					}
					break;
				case "item_subtype_version_id":
					//If ItemSubtypeVersion is requested and ItemSubtype and Project are set get from ItemSubtypeVersions_projects the ids and then from ItemSubtypeVersions the name, version, manufacturer and comment (replacing changelog)
					if(isset($setParameters["project_id"]) && isset($setParameters["item_subtype_id"])){
						$itemSubtypeVersions = $this->Item->ItemType->ItemSubtype->ItemSubtypeVersion->Project->find("all",array("conditions"=>array("id"=>$setParameters["project_id"]),"contain"=>array("ItemSubtypeVersion")));
						$iSVIds = array();
						foreach($itemSubtypeVersions[0]["ItemSubtypeVersion"] as $itemSubtypeVersion){
							$iSVIds[] = $itemSubtypeVersion["id"];
						}
						$itemSubtypeVersions = $this->Item->ItemType->ItemSubtype->ItemSubtypeVersion->find("all",array("conditions"=>array("ItemSubtypeVersion.id"=>$iSVIds,"item_subtype_id"=>$setParameters["item_subtype_id"])));
						foreach($itemSubtypeVersions as $v){
							$v["ItemSubtypeVersion"]["name"] = ($v["ItemSubtypeVersion"]["name"] != "")? "(".$v["ItemSubtypeVersion"]["name"].")":"";
							$return[$v["ItemSubtypeVersion"]["id"]] = $v["ItemSubtypeVersion"]["version"]." ".$v["ItemSubtypeVersion"]["name"]." - ".$v["Manufacturer"]["name"];
						}
					}
					break;
				case "manufacturer_id":
					if(isset($setParameters["item_subtype_version_id"])){
						$manufacturer = $this->Item->ItemType->ItemSubtype->ItemSubtypeVersion->find("all",array("conditions"=>array("ItemSubtypeVersion.id"=>$setParameters["item_subtype_version_id"]),"contain"=>array("Manufacturer")));
						if(isset($manufacturer[0])){
							$return = array("manufacturer_id"=>$manufacturer[0]["ItemSubtypeVersion"]["manufacturer_id"]);
						}else{
							$return = array();
						}
						break;
					}elseif(isset($setParameters["Project"])){
						$Project = $this->Item->Manufacturer->Project->findById($setParameters["Project"]);
						foreach($Project["Manufacturer"] as $manufacturer){
							$return[$manufacturer["id"]] = $manufacturer["name"];
						}
					}
					break;
				case "common_manufacturer_id":
				case "ItemSubtypeVersionManufacturerId":
               // see bitbucket issue #327 for debug shenanigans
	            $debug_level = Configure::read('debug');
	            Configure::write('debug', 0);
					if(isset($setParameters["Project"])){
							$Project = $this->Item->Manufacturer->Project->findById($setParameters["Project"]);
							foreach($Project["Manufacturer"] as $manufacturer){
								$return[$manufacturer["id"]] = $manufacturer["name"];
							}
					}
	            Configure::write('debug', $debug_level);
					break;
			}
			asort($return);
			echo json_encode($return);
		}
	}

}

