<?php
App::uses('AppModel', 'Model');
/**
 * History Model
 *
 * @property Item $Item
 * @property Event $Event
 */
class History extends AppModel {

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
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => 'event_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	/*
	 * Before saving a new history add the responsible (logged in) user.
	 */
	public function beforeSave($options = array()) {
		if(empty($this->data['History']['user_id'])) {
			$User = ClassRegistry::init('User');

			$this->data['History']['user_id'] = $User->getUserId();
		}
	}

	public function insertIntoHistory($eventDescription,$item_id,$comment,$userId=null){
		$event_id = $this->Event->getEventId($eventDescription);
		$insert['History'] = array(
								'item_id'	=> $item_id,
								'event_id' 	=> $event_id,
								'comment'	=> $comment);
		if($userId != null)
			$insert["History"]["user_id"] = $userId;
		$this->saveAll($insert);
	}

	public function addTagToItem($item_id,$tag_id){
		$eventDescription = "Tag added";
		$ItemTag = ClassRegistry::init('ItemTag');
		$tag_name = $ItemTag->findById($tag_id,array("ItemTag.name"));
		$comment = "The Tag ".$tag_name["ItemTag"]["name"]." was added";
		$this->insertIntoHistory($eventDescription, $item_id, $comment);
	}
	public function removeTagFromItem($item_id,$tag_id){
		$eventDescription = "Tag removed";
		$ItemTag = ClassRegistry::init('ItemTag');
		$tag_name = $ItemTag->findById($tag_id,array("ItemTag.name"));
		$comment = "The Tag ".$tag_name["ItemTag"]["name"]." was removed";
		$this->insertIntoHistory($eventDescription, $item_id, $comment);
	}
}
