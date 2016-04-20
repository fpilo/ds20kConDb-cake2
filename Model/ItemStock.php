<?php
App::uses('AppModel', 'Model');
/**
 * ItemStock Model
 *
 * @property Item $Item
 * @property Location $Location
 */
class ItemStock extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'amount';
	public $itemId = null;

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'item_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'location_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'amount' => array(
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
		'Item' => array(
			'className' => 'Item',
			'foreignKey' => 'item_id',
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
		)
	);

	public function stockExistsAtLocation($locationId,$configurationItemId = null){
		if($configurationItemId != null)
			$this->itemId = $configurationItemId;
		return ($this->find("count",array("conditions"=>array("item_id"=>$this->itemId,"ItemStocks.location_id"=>$locationId)))>0);
	}

	public function reduceStock($locationId,$amount = 0,$configurationItemId = null){
		if($configurationItemId != null)
			$this->itemId = $configurationItemId;

		if($this->stockExistsAtLocation($locationId)){
			$stockItemData = $this->find("first",array("conditions"=>array("item_id"=>$this->itemId,"ItemStocks.location_id"=>$locationId)));
			if($stockItemData["ItemStocks"]["amount"]>=$amount){ //if amount is not going to put the stock into the negative numbers
				$stockItemData["ItemStocks"]["amount"] -= $amount; //reduce the stock by the set amount
#				debug("reduced amount to ".$stockItemData["ItemStocks"]["amount"]);
				return $this->save($stockItemData);
			}
		}
		return false;
	}

	public function increaseStock($locationId,$amount = 0,$configurationItemId = null){
		if($configurationItemId != null){
			$this->itemId = $configurationItemId;
		}
		if($this->itemId == null) {
			return false;
		}
		if($this->stockExistsAtLocation($locationId)){
			$stockItemData = $this->find("first",array("conditions"=>array("item_id"=>$this->itemId,"ItemStocks.location_id"=>$locationId)));
			$stockItemData["ItemStocks"]["amount"] += $amount;
#			debug("updated amount to ".$stockItemData["ItemStocks"]["amount"]);
			return $this->save($stockItemData);
		}else{
#			debug("Added new stock at location ".$locationId." with ".$amount);
			return $this->addStockToLocation($locationId,$amount,$configurationItemId);
		}
	}

	public function increaseStockByOne($locationId,$configurationItemId = null){
		return $this->increaseStock($locationId,1,$configurationItemId);
	}
	public function reduceStockByOne($locationId,$configurationItemId = null){
		return $this->reduceStock($locationId,1,$configurationItemId);
	}

	public function isStockItem($itemId){
		return ($this->find("count",array("conditions"=>array("item_id"=>$itemId))) > 0);
	}

	/**
	 *
	 */
	public function addStockToLocation($locationId,$amount = 0,$configurationItemId = null){
		if($configurationItemId != null)
			$this->itemId = $configurationItemId;

		$newStock = array(
				"ItemStocks"=>array(
					"item_id"=>$this->itemId,
					"location_id"=>$locationId,
					"amount"=>$amount
				)
			);
		$this->clear();
		return $this->save($newStock);
	}


	/**
	 * Checks if a stock item with this subtypeVersion, quality and tags exists
	 */
	public function configurationExists($subtypeVersion,$quality,$tags){
		$this->Item->unbindModel(array("hasMany"=>array("Measurement","History")));
		$items = $this->Item->find("all",array(
			"conditions"=>array(
				"code LIKE"=>"Stock_%",
				"item_subtype_version_id"=>$subtypeVersion,
				"item_quality_id"=>$quality
			),
		));

		if(is_string($tags))
			$tags = array();

		if(count($items)>0){
			//Found at least one item with this subtype and quality combination, check if the tags are equal as well
			foreach($items as $item){//Iterate over all items
				//check if the count of tags is the same
				if(count($tags) != count($item["ItemTag"])){
					continue;
				}else{
					//Iterate over the item tags of this item and create an array with only the ids, then compare the two
					$tmp = array();
					foreach($item["ItemTag"] as $tag){
						$tmp[] = $tag["id"];
					}
					if($tags == $tmp){
						//Set the item id for this current stock where the configuration was found and return true
						$this->itemId = $item["Item"]["id"];
						return true;
					}
				}
			}
		}
		return false;
	}
}
