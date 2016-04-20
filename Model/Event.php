<?php
App::uses('AppModel', 'Model');
/**
 * Event Model
 *
 * @property History $History
 */
class Event extends AppModel {

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $displayField = 'name';

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'History' => array(
			'className' => 'History',
			'foreignKey' => 'event_id',
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

	/*
	 * Returns the Id of an single Event.
	 */
	public function getEventId($name = null){
		$id = $this->find('list', array('fields' => array('Event.name','Event.id'), 'conditions' => array('name' => $name)));

		/*
		 * If no Event with this name was found
		 * create a new event in database.
		 */
		if(empty($id)){
			/*
			 * Abort if no name was given.
			 * If a known name was inserted set the default description.
			 */
			if(empty($name))
				return false;
            else if($name == 'Post registered')
                $event['Event'] = array('name' => $name, 'description' => 'Item was registered after the registration of the parent composite item.');
			else if($name == 'Item created')
				$event['Event'] = array('name' => $name, 'description' => 'Item was applied to the database.');
			else if($name == 'Measurement')
				$event['Event'] = array('name' => $name, 'description' => 'Item was measured.');
			else if($name == 'Transfer')
				$event['Event'] = array('name' => $name, 'description' => 'Item was shipped to another location.');
			else if($name == 'Destroyed')
				$event['Event'] = array('name' => $name, 'description' => 'Item was destroyed');
			else if($name == 'Item attached')
				$event['Event'] = array('name' => $name);
			else if($name == 'Item detached')
				$event['Event'] = array('name' => $name);
			else if($name == 'Comment')
				$event['Event'] = array('name' => $name, 'description' => 'This is a user comment');
			else if($name == 'Item project changed')
				$event['Event'] = array('name' => $name, 'description' => 'The project of this item changed.');
			else if($name == 'Item state changed')
				$event['Event'] = array('name' => $name, 'description' => 'The state of this item changed.');
			else if($name == 'Item edited')
				$event['Event'] = array('name' => $name, 'description' => 'The item data was edited by a user.');
			else
				$event['Event'] = array('name' => $name);

			/*
			 * Create Event and get Id of it.
			 */
			$this->create();
			if ($this->save($event)) {
				$name = $event['Event']['name'];
				$id = $this->find('list', array('fields' => array('Event.name','Event.id'), 'conditions' => array('name' => $name)));
			} else {
				return false;
			}
		}

		return $id[$name];
	}

	/*
	 * Returns the Id of multiple Events.
	 */
	public function getEventIds($names = null){

		$ids = $this->find('list', array('fields' => array('Event.name','Event.id'), 'conditions' => array('name' => $names)));

		foreach($names as $name) {
			if(empty($ids[$name])){
				if(empty($name))
					return false;
				else if($name == 'Item created')
					$event['Event'] = array('name' => $name, 'description' => 'Item was applied to the database.');
				else if($name == 'Measurement')
					$event['Event'] = array('name' => $name, 'description' => 'Item was measured.');
				else if($name == 'Transfer')
					$event['Event'] = array('name' => $name, 'description' => 'Item was shipped to another location.');
				else if($name == 'Destroyed')
					$event['Event'] = array('name' => $name, 'description' => 'Item was destroyed');
				else if($name == 'Item attached')
					$event['Event'] = array('name' => $name);
				else if($name == 'Item detached')
					$event['Event'] = array('name' => $name);
				else if($name == 'Comment')
					$event['Event'] = array('name' => $name, 'description' => 'This is a user comment');
				else if($name == 'Item state changed')
					$event['Event'] = array('name' => $name, 'description' => 'The state of this item changed.');
				else if($name == 'Item edited')
					$event['Event'] = array('name' => $name, 'description' => 'The item data was edited by a user.');
				else if($name == 'Item subtype changed')
					$event['Event'] = array('name' => $name, 'description' => 'The version, subtype or even the type of this item was changed.');
				else
					$event['Event'] = array('name' => $name);

				$this->create();
				if ($this->save($event)) {
					$name = $event['Event']['name'];
					$ids[$name] = $this->find('list', array('fields' => array('Event.name','Event.id'), 'conditions' => array('name' => $name)));
				} else {
					return false;
				}
			}
		}

		return $ids;
	}
}
