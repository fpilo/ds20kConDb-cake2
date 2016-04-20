<?php
class SearchComponent extends Component {
	var $components = array('Session');

	public function getItemConditions($filter = array(), $model,&$joins) {
		$conditions = array();
		$user = $this->Session->read('User');


		if(!empty($filter['code'])) {
			$codes = $this->separate($filter['code']);

			foreach($codes as $code) {
				$conditions['OR'][] = array($model.'.code LIKE' => '%'.$code.'%');
				$conditions['OR'][] = array($model.'.comment LIKE' => '%'.$code.'%'); //This line makes the item code field also search for comments directly in the item
			}
		}

		if(!empty($filter['project_id'])) {
			$conditions['AND'][$model.'.project_id'] = $filter['project_id'];
		}
		else {
			foreach($user['Project'] as $project) {
				$conditions['AND'][$model.'.project_id'][] = $project['id'];
			}
		}

		if(!empty($filter['manufacturer_id']))
			$conditions['AND'][$model.'.manufacturer_id'] = $filter['manufacturer_id'];

		if(!empty($filter['item_type_id']))
			$conditions['AND'][$model.'.item_type_id'] = $filter['item_type_id'];

		if(!empty($filter['item_subtype_id']))
			$conditions['AND'][$model.'.item_subtype_id'] = $filter['item_subtype_id'];

		if(!empty($filter['item_subtype_version_id']))
			$conditions['AND'][$model.'.item_subtype_version_id'] = $filter['item_subtype_version_id'];

		if(!empty($filter['location_id'])) {
			$conditions['AND'][$model.'.location_id'] = $filter['location_id'];
		}
		else {
			foreach($user['Location'] as $location) {
				$conditions['AND'][$model.'.location_id'][] = $location['id'];
			}
		}

		if(!empty($filter['item_quality_id'])) {
			$conditions['AND'][$model.'.item_quality_id'] = $filter['item_quality_id'];
		}
		if(isset($filter["tag_id"])){//Only set conditions if tag_id is set
			//Check if all tags are set and if yes don't set condition thereby including also items where no tag is set. Maybe remove that at some point if wanted
			$itemTag = ClassRegistry::init('ItemTag');
			if(count($filter["tag_id"]) != $itemTag->find("count")){
				$conditions["AND"]["ItemTagsItem.item_tag_id"] = $filter["tag_id"];
				//Need to activate the join here so the limit can be applied.
				$joins = array(array("table"=>"item_tags_items","alias"=>"ItemTagsItem","type"=>"LEFT","conditions"=>array("ItemTagsItem.item_id = ItemView.id")));
				//WARNING: Mayor performance penalty caused by this join
			}
		}

		if(!empty($filter['state_id'])){
			$conditions['AND'][$model.'.state_id'] = $filter['state_id'];
		}

		if(empty($filter['show_all']) || $filter['show_all'] != 1) {
			// dont show items which are already used as a component of an item except if they are a stock item (if necessary this is overwritten by the next filter)
			$conditions[] = array('(
					('.$model.'.id) NOT IN (SELECT Item.id FROM items AS Item
						LEFT JOIN item_compositions AS CompositeItemO ON (CompositeItemO.component_id = Item.id) where CompositeItemO.valid = 1 ORDER BY Item.id)
					OR
					('.$model.'.id)  IN (SELECT item_id FROM item_stocks)
					)');
		}
		if(empty($filter['show_stocks']) || $filter['show_stocks'] != 1) {
			// dont show items which are already used as a component of an item
			$conditions[] = array('('.$model.'.id) NOT IN (SELECT item_id FROM item_stocks)');
		}

		return $conditions;
	}

	public function getStockConditions($filter = array(), $model) {
		$conditions = array();

		if(isset($filter['amount']))
			$conditions['AND'] = array('amount > '.$filter['amount']);

		if(!empty($filter['item_subtype_version_id']))
			$conditions['AND'][$model.'.item_subtype_version_id'] = $filter['item_subtype_version_id'];

		if(!empty($filter['project_id']))
			$conditions['AND']['ProjectsStocks.project_id'] = $filter['project_id'];

		if(!empty($filter['location_id']))
			$conditions['AND']['LocationsStocks.location_id'] = $filter['location_id'];

		return $conditions;
	}

	public function getSubtypeConditions($filter = array(), $model) {
		$conditions = array();

		if(!empty($filter['name']))
			$conditions['AND'][$model.'.name LIKE'] = '%'.$filter['name'].'%';

		if(!empty($filter['project_id']))
			$conditions['OR']['ItemSubtypeVersion'.'.project_id'] = $filter['project_id'];

		if(!empty($filter['item_type_id']))
			$conditions['AND'][$model.'.item_type_id'] = $filter['item_type_id'];

		return $conditions;
	}

	public function getLogConditions($filter = array(), $model) {
		$conditions = array();
		$logEventIds = array();

		//unset($filter['limit']);

		if(!empty($filter['comment']))
			$conditions['AND'][$model.'.comment LIKE'] = '%'.$filter['comment'].'%';

		if(!empty($filter['log_event_id'])) {
			$conditions['AND'][$model.'.log_event_id'] = $filter['log_event_id'];
		}

		if(!empty($filter['user_id'])) {
			$conditions['AND'][$model.'.user_id'] = $filter['user_id'];
		}

		return $conditions;
	}

/**
 * separate method
 *
 * @param string $codes
 * @return array $codes separated as array()
 *
 * Separates the codes for each item from the input string
 */
	private function separate($codes) {

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
}