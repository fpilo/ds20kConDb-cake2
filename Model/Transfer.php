<?php
App::uses('AppModel', 'Model');
/**
 * Transfer Model
 *
 * @property Item $Item
 * @property History $History
 */
class Transfer extends AppModel {

	public $actsAs = array('Containable');
	public $states = array(
						1=>"pending",
						2=>"in transfer",
						3=>"received"
					  );
   public $findMethods = array('filteredByProjects'=>true);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'To' => array(
			'className' => 'Location',
			'foreignKey' => 'to_location_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'From' => array(
			'className' => 'Location',
			'foreignKey' => 'from_location_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Deliverer' => array(
			'className' => 'Deliverer',
			'foreignKey' => 'deliverer_id'
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		),
		'Recipient' => array(
			'className' => 'User',
			'foreignKey' => 'recipient_id',

		)
	);

	public $hasAndBelongsToMany = array(
		'Item' => array(
			'className' => 'Item',
			'joinTable' => 'items_transfers',
			'foreignKey' => 'transfer_id',
			'associationForeignKey' => 'item_id',
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

	public function beforeSave($options = array()) {
		if(empty($this->data['Transfer']['user_id'])) {
			$User = ClassRegistry::init('User');
			$this->data['Transfer']['user_id'] = $User->getUserId();
		}
	}

   protected function _findFilteredByProjects($state,$query,$results=array()) {
      if($state=='after') {
         $return = array();
         $projectIds = $this->User->getUsersProjects();
         foreach($results as $transfer) {
            foreach($transfer['Item'] as $item) {
               if(in_array($item['project_id'],$projectIds)) {
                  $return[] = $transfer;
                  break; // stop the inner loop so $transfer only is added once
               }
            }
         }
         return $return;
      } else return $query;
   }

	public function getPendingFromLocations($locationIds,$find="all"){
		return $this->find($find,array("conditions"=>array("from_location_id"=>$locationIds,"status"=>1)));
	}

	public function getInTransitFromLocations($locationIds,$find="all"){
		return $this->find($find,array("conditions"=>array("from_location_id"=>$locationIds,"status"=>2)));
	}

	public function getInTransitToLocations($locationIds,$find="all"){
		return $this->find($find,array("conditions"=>array("to_location_id"=>$locationIds,"status"=>2)));
	}

   public function getInTransitFromToLocations($locationIds,$find="all"){
      return $this->find($find,array("conditions"=>array("status"=>2,"or"=>array("to_location_id"=>$locationIds,"from_location_id"=>$locationIds))));
   }

	/**
	 * Returns an array of ids of the selected items, not of the components, so the rest of the script works with this data
	 *
	 */
	public function getSelectedItems($transferId){
		$itemIds = array();
		$results =  $this->query("SELECT `item_id`,`amount` FROM `items_transfers` WHERE `transfer_id` = ".($transferId*1)." AND `is_part_of` IS NULL");
		foreach($results as $result){
			$itemIds[] = $result["items_transfers"]["item_id"];
		}
		return $itemIds;
	}
	public function getItemsWithAmount($transferId){
		$itemIds = array();
		$results =  $this->query("SELECT `item_id`,`amount` FROM `items_transfers` WHERE `transfer_id` = ".($transferId*1)." AND `is_part_of` IS NULL");
		foreach($results as $result){
			$itemIds[$result["items_transfers"]["item_id"]]["amount"] = $result["items_transfers"]["amount"];
		}
		return $itemIds;
	}

}
