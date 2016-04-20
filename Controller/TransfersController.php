<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * Transfers Controller
 *
 * @property Transfer $Transfer
 * @property AclComponent $Acl
 */
class TransfersController extends AppController {

	public $components = array('RequestHandler');

	private $fromLocationId = null;

	public $paginate = array(
        'limit' => 50,
        'order' => array(
            'Transfer.id' => 'desc'
        ),
        'conditions' => array("Transfer.status"=>3), //Only paginate finished transfers
        //'recursive' => -1,
		'contain' => array('To','From','Deliverer',"Item")
	);

	//Search
	public $presetVars = array(
        array('field' => 'code', 'type' => 'value'),
		array('field' => 'item_subtype_version_id', 'type' => 'checkbox', 'model' => 'ItemSubtypeVersion'),
		array('field' => 'item_subtype_id', 'type' => 'checkbox', 'model' => 'ItemSubtype'),
		array('field' => 'item_type_id', 'type' => 'checkbox', 'model' => 'ItemType'),
		array('field' => 'location_id', 'type' => 'checkbox', 'model' => 'Location'),
		array('field' => 'state_id', 'type' => 'checkbox', 'model' => 'State'),
		array('field' => 'item_quality_id', 'type' => 'checkbox', 'model' => 'ItemQuality'),
		array('field' => 'manufacturer_id', 'type' => 'checkbox', 'model' => 'Manufacturer'),
		array('field' => 'project_id', 'type' => 'checkbox', 'model' => 'Project')
	);

/**
 * index method
 *
 * @return void
 */
	public function index() {
      $standard_location = $this->Transfer->User->getUserStandardLocation();
      $all_user_locations = $this->Transfer->User->getUsersLocations();
      $pending_transfers = $this->Transfer->getPendingFromLocations($standard_location,'filteredByProjects');
      $in_transit_transfers = $this->Transfer->getInTransitFromToLocations($all_user_locations,'filteredByProjects');
      
      $in_transit_standard_transfers = array(); 
      foreach($in_transit_transfers as $key=>$transfer) {
         if($transfer["To"]["id"]==$standard_location) {
            $in_transit_standard_transfers[] = $transfer;
			   unset($in_transit_transfers[$key]);
         }
      }

      $this->paginate['findType'] = 'filteredByProjects';		
		$this->paginate["conditions"] = array_merge($this->paginate["conditions"],array("or"=>array("Transfer.to_location_id"=>$all_user_locations,"Transfer.from_location_id"=>$all_user_locations)));
		$this->set('pending_transfers', $pending_transfers);
		$this->set('in_transit_transfers', $in_transit_transfers);
		$this->set('in_transit_standard_transfers', $in_transit_standard_transfers);
		$this->set('completed_transfers', $this->paginate());
		$this->set("usersLocations",$all_user_locations);
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Transfer->id = $id;
		if (!$this->Transfer->exists()) {
			throw new NotFoundException(__('Invalid transfer'));
		}
		$this->set("usersLocations",$this->Transfer->User->getUsersLocations());
		$transfers = $this->Transfer->find('first', array(
													'conditions' => array('Transfer.id' => $id),
													'contain' => array(
															'To',
															'From',
															'Recipient',
															'Deliverer',
															'User',
															'Item.ItemType',
															'Item.ItemSubtype',
															'Item.ItemSubtypeVersion',
															'Item.Location',
															'Item.Project',
															'Item.State',
															'Item.ItemQuality',
															'Item.ItemStocks')));
		foreach($transfers["Item"] as $i=>$item){
			$transfers["Item"][$i]["ItemTags"] = $this->Transfer->Item->getTagsForItem($item["id"]);
		}
		$this->set('transfer', $transfers);
	}

	public function generateCSV($id = null){
		$this->Transfer->id = $id;
		if (!$this->Transfer->exists()) {
			throw new NotFoundException(__('Invalid transfer'));
		}
		$transfer = $this->Transfer->find('first', array(
													'conditions' => array('Transfer.id' => $id),
													'contain' => array(
															'To',
															'From',
															'Recipient',
															'Deliverer',
															'User',
															'Item.ItemType',
															'Item.ItemSubtype',
															'Item.ItemSubtypeVersion',
															'Item.Location',
															'Item.Project',
															'Item.State',
															'Item.ItemQuality',
															'Item.ItemStocks')));
		$this->autoRender = false;

		$fileString = "";
		$fileString .= "\"From\";\"".$transfer["From"]["name"]."\"\n";
		$fileString .= "\"To\";\"".$transfer["To"]["name"]."\"\n";
		$fileString .= "\"Recipient\";\"".$transfer["Recipient"]["first_name"]." ".$transfer["Recipient"]["last_name"]."\"\n";
		$fileString .= "\"Deliverer\";\"".$transfer["Deliverer"]["name"]."\"\n";
		$fileString .= "\"Tracking number\";\"".$transfer["Transfer"]["tracking_number"]."\"\n";
		$fileString .= "\"Shipping Date\";\"".$transfer["Transfer"]["shipping_date"]."\"\n";
		$fileString .= "\"Responsible User\";\"".$transfer["User"]["username"]."\"\n";
		$fileString .= "\"Comment\";\"".$transfer["Transfer"]["comment"]."\"\n";
		$fileString .= "\"\";\"\"\n";


		$header = array("Item code","Amount","Tags","State","Quality","ItemType","ItemSubtype","ItemSubtypeVersion","Location","Project");
		//Header
		$fileString .= '"'.implode('";"', $header)."\"\n";

		foreach($transfer["Item"] as $item){
			foreach($this->Transfer->Item->getTagsForItem($item["id"]) as $i=>$itemTag) $item["ItemTags"][$i] = $itemTag["ItemTag"]["name"];
			$tmp = array();
			if(count($item["ItemStocks"])>0){
				$tmp[] = "Stock Item";
			}else{
				$tmp[] = $item["code"];
			}
			$tmp[] = $item["ItemsTransfer"]["amount"];
			$tmp[] = implode(", ",$item['ItemTags']);
			$tmp[] = $item["State"]["name"];
			$tmp[] = $item["ItemQuality"]["name"];
			$tmp[] = $item["ItemType"]["name"];
			$tmp[] = $item["ItemSubtype"]["name"];
			$tmp[] = $item["ItemSubtypeVersion"]["version"];
			$tmp[] = $item["Location"]["name"];
			$tmp[] = $item["Project"]["name"];
			$fileString .= '"'.implode('";"', $tmp)."\"\n";
		}

        // Set the response Content-Type to vcard.
        $this->response->body($fileString);
        // Add csv file type
        $this->response->type(array('csv' => 'text/csv'));
        $this->response->type('csv');

        //Optionally force file download
        $this->response->download('transfer_'.$id.'.csv');

	}


/**
 * send method
 * takes a transfer id, changes the status to 2 (in transfer) and moves all the items in the transfer to the "in transfer" location
 * @param int $id
 * @return void
 */
	public function send($id = null)  {
		if($id == null)
			return false;
		$this->autoRender = false;
		//Get Id for "In Transfer" location
		$toLocation = $this->Transfer->To->findByName("In Transfer");
		$toLocationId = $toLocation["To"]["id"];
		$transfer = $this->Transfer->findById($id);
		$this->fromLocationId = $transfer["Transfer"]["from_location_id"];
		$selectedItems = $this->Transfer->getSelectedItems($id);
		$transferItems = $this->_getItemsIncludingSubitems($selectedItems,$id);
		//Set the location for all items and move stock items
		foreach($transferItems as $item){
			$this->Transfer->Item->changeLocationRecursive($item, $transfer["Deliverer"], $transfer["From"]["id"], $toLocationId);
		}
		//Set the status of the transfer to two
		$this->Transfer->id = $id;
		$this->Transfer->saveField('status', 2);
		//Send email to selected receiver with a link to this transfer so only a login is required.
		$link = "http://www.hephy.at".Router::url(array("controller"=>"Transfers","action"=>"view",$id));
		$mail = CakeEmail::deliver(
					$transfer["Recipient"]["email"],
					'New Transfer to '.$transfer["To"]["name"],
					'There is a new transfer on its way, you can use the link '.$link.' to view and receive the transfer in the database. ',
					array('from' => 'hephydb@heros.hephy.at')
				);
		return $this->redirect(array("action"=>"index"));
	}


 /**
 * recieved method
 * takes a transfer id, changes the status to 3 (arrived) and moves all the items in the transfer to the target location as specified in the transfer
 * @param string $id
 * @return void
 */
	public function receive($id = null)  {
		if($id == null)
			return false;
		$this->autoRender = false;
		//Get Id for "In Transfer" location
		$fromLocation = $this->Transfer->From->findByName("In Transfer");
		$this->fromLocationId = $fromLocation["From"]["id"];

		$transfer = $this->Transfer->findById($id);
		$selectedItems = $this->Transfer->getSelectedItems($id);
		$transferItems = $this->_getItemsIncludingSubitems($selectedItems,$id);
		//Set the location for all items and move stock items
		foreach($transferItems as $item){
			$this->Transfer->Item->changeLocationRecursive($item, $transfer["Deliverer"], $this->fromLocationId, $transfer["To"]["id"]);
		}
		//Set the status of the transfer to three
		$this->Transfer->id = $id;
		$this->Transfer->saveField('status', 3);
		//Send email to the transfer starter that it has been received
		$link = "http://www.hephy.at".Router::url(array("controller"=>"Transfers","action"=>"view",$id));
		$mail = CakeEmail::deliver(
					$transfer["User"]["email"],
					'Transfer to '.$transfer["To"]["name"].' was received',
					"Your transfer to ".$transfer["To"]["name"]." was received. \nYou can check the transfer with the following link: ".$link,
					array('from' => 'hephydb@heros.hephy.at')
				);
		return $this->redirect(array("action"=>"index"));
	}

 /**
 * add method
 *
 * @return void
 */


 	public function add($id = null){
 		//Cases:
 		$redirect = false;
 		if($id==null && empty($this->request->data)){
			throw new NotFoundException("There was no data passed", 1);

 			//No post and no parameters, register as error as there needs to be at least one item in a transfer to define the from location

 		}elseif($id!=null && empty($this->request->data)){ /// Number 1 ------------------
 			//No post parameters but id, allow changes and set environment variables
 			// debug("transfer id set but no data -> view transfer for edit");

 			//Get Transfer Data From DB
 			$transfer = $this->Transfer->findById($id);
			$this->fromLocationId = $transfer["Transfer"]["from_location_id"];
			$selectedItems = $this->Transfer->getSelectedItems($id);
			$transferItems = $this->_getItemsIncludingSubitems($selectedItems,$id);
			$this->set("selectedItems",$selectedItems);
			$this->set("transferItems",$transferItems);
			$this->set("transfer",$transfer);

 		}elseif($id==null && !empty($this->request->data)){
 			//post parameters and no id
			if(!isset($this->request->data["Transfer"]) && isset($this->request->data["selectedItems"])){ /// Number 2 ------------------
				// debug("Items Selected but no transfer data -> inventory 'add to new transfer'");
				//No transfer data set but items selected, originated from the "add to transfer" button on the inventory page

				//Get from location from passed data
				$this->fromLocationId = $this->request->data["transferFromId"];
				$transfer["Transfer"]["from_location_id"] = $this->fromLocationId;
				$this->set("transfer",$transfer);

				//Check if one of the selected items is actually attached and if yes recursively add the parent item and add to information message
				$this->Session->setFlash('The transfer has been saved.','default', array('class' => 'notification'));

				//set the environment variable for them to be displayed
				$transferItems = $this->_getItemsIncludingSubitems($this->request->data["selectedItems"]);
				$this->set("selectedItems",$this->request->data["selectedItems"]);
				$this->set("transferItems",$transferItems);
			}elseif(isset($this->request->data["Transfer"]) && isset($this->request->data["selectedItems"])){ /// Number 3 ------------------
				// debug("Transfer data and Items set -> first save of new transfer");
				//this request originated from the form save button, now saving the transfer thereby assigning an id.
 				//save with status 1
 				//Items of this transfer are added in the next section
				$this->fromLocationId = $this->request->data["Transfer"]["from_location_id"];
 				$this->request->data["Transfer"]["status"] = 1;
 				$this->Transfer->save($this->request->data["Transfer"]);
				$id = $this->Transfer->id; //Assign newly created Id to this function so the following might be executed as well
				$this->request->data["Transfer"]["id"] = $id;
				//Activate redirect at the end of the method
				$redirect = true;
//				$this->set("id",$id);
			}

 		}
		$deliverers = $this->Transfer->Deliverer->find('list');
		//Select all projects of the user
		$projects = $this->Transfer->User->Project->find('list');
		//Select all (other) users with the same projects as firstname - lastname array with id
		$projects_users = $this->Transfer->User->Project->find("all",array("conditions"=>array("Project.id"=>array_keys($projects)),"contain"=>array("User.id","User.first_name","User.last_name","User.email")));
		$recipients = array();
		foreach($projects_users as $project){
			foreach($project["User"] as $user){
				if($user["last_name"] != "" && $user["first_name"] != "" && $user["email"] != "")
				$recipients[$user["id"]] = $user["last_name"].", ".$user["first_name"];
			}
		}
		asort($recipients); //Sort alphabetically (by last name since that is the first one displayed)

		$toLocations = $this->Transfer->To->find('list');
		$this->set(compact('toLocations', 'deliverers','recipients'));

		if($id!=null && !empty($this->request->data)){
 			// debug("id and data");
 			//post parameters and id
			if(isset($this->request->data["selectedItems"])){ /// Number 4 ------------------
				// debug("selected items and ID set -> 'inventory add to existing transfer'. Creating array of items");
 				//Update all items of this transfer
				if(!isset($this->request->data["Transfer"])){
					//If there is no transfer data passed get it from the database, including all the already set items and add it to the request variables
					$transfer = $this->Transfer->findById($id);
					$this->request->data["Transfer"] = $transfer["Transfer"];
					//Iterate over the items set for this transfer and if they are not in the newly added items add them to the array
					foreach($transfer["Item"] as $item){
						if($item["ItemsTransfer"]["is_part_of"]==null && !in_array($item["id"], $this->request->data["selectedItems"])){
							$this->request->data["selectedItems"][] = $item["id"];
						}
					}
				}
				$this->fromLocationId = $this->request->data["Transfer"]["from_location_id"];
				$transferItems = $this->_getItemsIncludingSubitems($this->request->data["selectedItems"],$id);
				//Set the data into the request variable so it is saved in the next if request with the new transfer data.
				$this->request->data['Item']['Item'] = $this->_getDependantItemIds($transferItems,$this->request->data["Transfer"]["from_location_id"],$this->request->data["Transfer"]["to_location_id"],$id);
				$this->set("selectedItems",$this->request->data["selectedItems"]);
				$this->set("transferItems",$transferItems);
			}
			if(isset($this->request->data["Transfer"])){ /// Number 5 ------------------
				//Update transfer with post data
				$this->Transfer->save($this->request->data);
				$this->Session->setFlash('The transfer has been saved.','default', array('class' => 'notification'));

			}
			$this->set("transfer",$this->request->data);
 		}
 		if($redirect)
			return $this->redirect(array("action"=>"add",$id));

 	}
/**
 * Recursive function using references to add all the components and subcomponents (practically indefinitely) as equal elements to the array so they are all transferred as well
 */
 	private function _getComponentsRecursive($itemId,&$componentsArray){
 		foreach($this->Transfer->Item->getValidComponents($itemId) as $component){
			$this->_getComponentsRecursive($component["id"], $componentsArray);
			$comp = $this->Transfer->Item->find("first",array("conditions"=>array("Item.id"=>$component["id"]),"contain"=>array("Location","ItemQuality","State","Project","Manufacturer","ItemType","ItemSubtype","ItemSubtypeVersion","Transfer")));
			$comp["ItemTags"] = $this->Transfer->Item->getTagsForItem($comp["Item"]["id"]);

			if($this->Transfer->Item->isStock($component["id"])){
				//Is stock: update code and location
				$comp[$component["id"]]["Item"]["code"] = "Stock Item";
				$comp[$component["id"]]["Item"]["location_id"] = $component["location_id"];
			}
			$componentsArray[] = $comp;
 		}
 	}

	private function _getItemsIncludingSubitems($itemIds = array(),$transferId=null){
		if($transferId != null)
			$selectedItemsWithAmount = $this->Transfer->getItemsWithAmount($transferId);
		else{
			$selectedItems = array();
			foreach($itemIds as $itemId){
				$selectedItemsWithAmount[$itemId] = array("amount"=>1);
			}
		}
		$return = array();
		$this->Transfer->Item->unbindModel(array("hasMany"=>array("History")));
		foreach($itemIds as $itemId){
#			debug($itemId);
			$item = $this->Transfer->Item->find("first",array("conditions"=>array("Item.id"=>$itemId),"contain"=>array("Location","ItemQuality","State","Project","Manufacturer","ItemType","ItemSubtype","ItemSubtypeVersion","Transfer")));
			$item["Item"]["amount"] = (isset($selectedItemsWithAmount[$itemId]["amount"])) ? $selectedItemsWithAmount[$itemId]["amount"]:1;
			$item["ItemTags"] = $this->Transfer->Item->getTagsForItem($itemId);
			if($this->Transfer->Item->isStock($itemId)){
				//Is stock: update code and location
				$item["Item"]["code"] = "Stock Item";
				$item["Item"]["location_id"] = $this->fromLocationId;
				$this->Transfer->From->unbindModel(array("hasMany"=>"Item"));
				$bla = $this->Transfer->From->findById($this->fromLocationId);
				$item["Location"] = $bla["From"];

				//Save amount and max amount into the array
				$tmp = $this->Transfer->Item->ItemStocks->find("first",array("conditions"=>array("item_id"=>$itemId,"ItemStocks.location_id"=>$this->fromLocationId),"fields"=>array("amount")));
#				debug($tmp);
				$item["Item"]["maxAmount"] = $tmp["ItemStocks"]["amount"]*1;
			}
			$this->_getComponentsRecursive($itemId, $item["Components"]);
			if($item["Components"]==null) $item["Components"] = array();
			$return[$itemId] = $item;
		}
#		debug($return);
		return $return;
	}

	private function _getDependantItemIds($items,$from,$to,$transferId,$amount=1){
		$transferItems = array();
		foreach($items as $item){
			$newComponent = array();
			$newComponent['item_id'] = $item["Item"]["id"];
			$newComponent['transfer_id'] = $transferId;
			$newComponent['is_part_of'] = NULL;
			$newComponent['from_location_id'] = $from;
			$newComponent['to_location_id'] = $to;
			$newComponent['amount'] = $item["Item"]["amount"];
			$transferItems[] = $newComponent;
			foreach($item["Components"] as $component){
				$newComponent = array();
				$newComponent['item_id'] = $component["Item"]["id"];
				$newComponent['transfer_id'] = $transferId;
				$newComponent['is_part_of'] = $item["Item"]["id"];
				$newComponent['from_location_id'] = $from;
				$newComponent['to_location_id'] = $to;
				$newComponent['amount'] = $amount;
				$transferItems[] = $newComponent;
			}
		}
		return $transferItems;
	}


	public function updateStockItemAmountOfTransfer($transferId,$itemId,$amount){
		if(!$this->request->isAjax()){
			throw new InvalidArgumentException("No Ajax request, aborting");
		}else{
			$transferItems = $this->_getItemsIncludingSubitems($this->Transfer->getSelectedItems($transferId),$transferId);
			if(is_numeric($itemId) && is_numeric($amount))
				$transferItems[$itemId]["Item"]["amount"] = $amount;
			else
				throw new InvalidArgumentException("Invalid Argument");
			$transfer = $this->Transfer->findById($transferId);
			$transfer["Item"]["Item"] = $this->_getDependantItemIds($transferItems,$transfer["Transfer"]["from_location_id"],$transfer["Transfer"]["to_location_id"],$transferId);
			if($this->Transfer->save($transfer)){
				echo json_encode(array("success"=>true,"message"=>"Amount successfully updated"));
			}else{
				echo json_encode(array("success"=>false,"message"=>"Storing of new amount failed"));
			}
		}
		$this->autoRender = false;
	}

	public function removeItemFromTransfer($transferId = null,$itemId = null){
		$transfer = $this->Transfer->findById($transferId);
		$selectedItems = $this->Transfer->getSelectedItems($transferId);
		$updatedTransfer = array();

		foreach($selectedItems as $key=>$id) {
			if($itemId == $id)
				unset($selectedItems[$key]);
		}
		$updatedTransfer["Transfer"] = $transfer["Transfer"];

		$updatedTransfer["Item"]["Item"] = $this->_getDependantItemIds($this->_getItemsIncludingSubitems($selectedItems,$transferId),$transfer["Transfer"]["from_location_id"],$transfer["Transfer"]["to_location_id"],$transferId);;
		if($this->Transfer->save($updatedTransfer)){
			echo json_encode(array("success"=>true,"message"=>"Item successfully removed"));
		}else{
			echo json_encode(array("success"=>false,"message"=>"Removal if item failed"));
		}
		$this->autoRender = false;
	}

	public function removeAllItemsFromTransfer($transferId){
		foreach($this->Transfer->getSelectedItems($transferId) as $itemId){
			$this->removeItemFromTransfer($transferId,$itemId);
		}
		$this->render('empty_table', 'ajax');
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Transfer->id = $id;
		$transfer = array();
		if (!$this->Transfer->exists()) {
			throw new NotFoundException(__('Invalid transfer'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Transfer->save($this->request->data)) {
				$this->flash(__('The transfer has been saved.'), array('action' => 'index'));
				return $this->redirect(array('action' => 'view', $id));
			} else {
				$this->flash(__('The transfer has not been saved.'));
			}
		} else {
			//$this->request->data = $this->Transfer->read(null, $id);
			$transfer = $this->Transfer->find('first', array(
													'conditions' => array('Transfer.id' => $id),
													'contain' => array(
															'To',
															'From',
															'Deliverer',
															'User',
															'Item.ItemType',
															'Item.ItemSubtype',
															'Item.ItemSubtypeVersion',
															'Item.Location',
															'Item.Project',
															'Item.State')));
			//$this->request->data = $this->Transfer->find('first', array('conditions' => array('Transfer.id' => $id)));
		}
		$froms = $this->Transfer->From->find('list');
		$to_locations = $this->Transfer->To->find('list');
		$deliverers = $this->Transfer->Deliverer->find('list');
		$this->set(compact('transfer', 'items', 'froms', 'to_locations', 'deliverers'));
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Transfer->id = $id;
		if (!$this->Transfer->exists()) {
			throw new NotFoundException(__('Invalid transfer'));
		}
		if ($this->Transfer->delete()) {
			$this->flash(__('Transfer deleted'), array('action' => 'index'));
		}
		$this->flash(__('Transfer was not deleted'), array('action' => 'index'));
		return $this->redirect(array('action' => 'index'));
	}

	public function saveForm(){
		if ($this->RequestHandler->isAjax()) {
			$transfer = $this->Session->read('NewTransfer');
			$transfer['Transfer'][$this->request->data['field']] = $this->request->data['value'];
			$this->Session->write('NewTransfer', $transfer);
		}
		$this->autoRender = false;
	}

	public function transferItems($id){
		$transfer = $this->Transfer->find('first', array(
													'conditions' => array('Transfer.id' => $id),
													'contain' => array(
															'Item.ItemType',
															'Item.ItemSubtype',
															'Item.ItemSubtypeVersion',
															'Item.Location',
															'Item.Project',
															'Item.State',
															'Item.ItemQuality',
															'Item.ItemStocks')));
		foreach($transfer["Item"] as $i=>$item){
			$transfer["Item"][$i]["ItemTags"] = $this->Transfer->Item->getTagsForItem($item["id"]);
		}
		$this->set("transfer",$transfer);
	}
}
