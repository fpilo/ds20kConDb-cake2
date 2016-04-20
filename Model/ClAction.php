<?php
App::uses('AppModel', 'Model');
/**
 * ClAction Model
 *
 * @property Item $Item
 */
class ClAction extends AppModel {

	public $order = 'ClAction.name ASC';

	var $status = array (1 => 'failed', 3 => 'passed', 7 => 'skipped');

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'ClState' => array(
			'className' => 'ClState',
			'foreignKey' => 'cl_action_id',
			'dependent' => true,
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
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Checklist' => array(
			'className' => 'Checklist',
			'foreignKey' => 'checklist_id'
			// 'conditions' => '',
			// 'type' => '',
			// 'fields' => '',
			// 'order' => '',
			// 'counterCache' => '',
			// 'counterScope' => ''
		),
		'ClTemplate' => array(
			'className' => 'ClTemplate',
			'foreignKey' => 'cl_template_id'
			// 'conditions' => '',
			// 'type' => '',
			// 'fields' => '',
			// 'order' => '',
			// 'counterCache' => '',
			// 'counterScope' => ''
		)
	);

/**
 * createEvent method
 * Create a new event in the history of an item
 *
 * @param string $id The id of the checklist action
 * @param string $status The status of the checklist action
 * @param string $notes Additional notes for the event
 * @return boolean Returns true if the event is created, false if not
 */

	public function createEvent($id,$status,$notes = NULL){

		$this->id = $id;
		if(!isset($this->id)) return false;

		$this->recursive=-1;
		$name = $this->findById($this->id, array('name'));
		$checklistId = $this->findById($this->id, array('checklist_id'));
		$hierarchyLvl = $this->findById($this->id, array('hierarchy_level'));
		$this->Checklist->recursive=0;
		$this->Checklist->unbindModel(array(
											'belongsTo' => array('ClTemplate'),
											'hasMany' => array('ClAction'),
										)
									);
		$itemId = $this->Checklist->find('first',
												array(
														'fields' => array('Item.id'),
														'conditions' => array('Checklist.id' => $checklistId['ClAction']['checklist_id'])
													)
										);
		$eventId = $this->Checklist->Item->History->Event->getEventId('Item checklist changed');
		if($hierarchyLvl['ClAction']['hierarchy_level'] == 2) $comment = 'Sub-';
		else $comment = null;
		$comment = $comment.'Action "'.$name['ClAction']['name'].'" '.$this->status[$status].'.';
		if(!empty($notes)) $comment = $comment.' Additional notes: '.$notes;
		$history = array( 'History' => array(
								'item_id'	=> $itemId['Item']['id'],
								'comment'	=> $comment,
								'event_id' 	=> $eventId
							)
					);


		$this->Checklist->Item->History->create();
		if($this->Checklist->Item->History->save($history, array('validate' => 'first')))
			return true;

		return false;

	}

}
