<?php
App::uses('AppModel', 'Model');
/**
 * Checklist Model
 *
 * @property Checklist $Checklist
 */
class Checklist extends AppModel {
	public $order = 'Checklist.name ASC';

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
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Item' => array(
			'className' => 'Item',
			'foreignKey' => 'item_id'
			// 'conditions' => '',
			// 'type' => '',
			// 'fields' => '',
			// 'order' => '',
			// 'counterCache' => '',
			// 'counterScope' => ''
		),
		'ClTemplate' => array(
			'className' => 'ClTemplate',
			'foreignKey' => 'cl_template_id',
			'dependent' => false,
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
		'ClAction' => array(
			'className' => 'ClAction',
			'foreignKey' => 'checklist_id',
			// 'conditions' => '',
			// 'order' => '',
			// 'limit' => '',
			// 'offset' => '',
			'dependent' => true,
			'exclusive' => true
			// 'finderQuery' => ''
		)
	);


/**
 * createFromTemplate method
 * Changes the project_id of one item
 *
 * @param integer $cltemplateId The id of the check list template
 * @param string $name The name of the check list
 * @param string $description The description of the check list
 * @return integer Returns the id of the new check list, null if the saving wasn't successful
 */

	public function createFromTemplate($cltemplateId, $name, $description = NULL){

		$error = array();

		if(empty($cltemplateId)) $error = '[ERROR - Checklist Model::createFromTemplate] Missing check list template id.';
		if(empty($name)) $error = '[ERROR - Checklist Model::createFromTemplate] The check list name is not specified.';


		if(empty($error)){

			$this->ClTemplate->recursive=2;
			$this->ClTemplate->unbindModel(
												array('belongsTo' => array('ItemSubtype'))
										);
			$this->ClTemplate->ClAction->unbindModel(
													array('belongsTo' => array('Checklist','ClTemplate'))
											);
			$cltemplate = $this->ClTemplate->find('first', array('conditions' => array('ClTemplate.id' => $cltemplateId)));

			if(!empty($cltemplate)) {

				$checklist = array(	'name' => $name,
									'description' => $description,
									'cl_template_id' => $cltemplate['ClTemplate']['id']);

				$clActions = array();
				foreach($cltemplate['ClAction'] as $claction) {

						unset($claction['id']);
						unset($claction['checklist_id']);
						unset($claction['cl_template_id']);
						unset($claction['status_code']);

						foreach($claction['ClState'] as $keyStat => $clstate) {

							unset($claction['ClState'][$keyStat]);
							unset($clstate['id']);
							unset($clstate['cl_action_id']);
							$claction['ClState'][$keyStat] = $clstate;
						}
						$clActions[] = $claction;
				}

				$checklist['ClAction']=$clActions;

				$this->create();
				if($this->saveAssociated($checklist, array('deep' => true))) {
					$checklistId = $this->id;
				}
				else {
					$error = '[ERROR - Checklist Model::creteFromTemplate] The check list or its associated modeles cannot be saved.';
				}
			}
			else {
				$error = '[ERROR - Checklist Model::creteFromTemplate] The check list template has not been found.';
			}
		}

		if(!(empty($error))){
			//$this->Session->setFlash(__($error));
			return null;
		}
		else return $checklistId;

	}

/**
 * createFromTemplate method
 * Changes the project_id of one item
 *
 * @return boolean Returns true if the checklist was successfully initialized
 */

	public function init(){

		$clactions = $this->ClAction->find('all', array(
														'conditions' => array('ClAction.checklist_id' => $this->id
														)));

		foreach($clactions as $claction){
			$currstatusCode = $claction['ClAction']['status_code'];
			$newstatusCode = $currstatusCode & ~ 0xffff;

			$this->ClAction->updateAll(
										array('ClAction.status_code' => $newstatusCode),
										array('ClAction.checklist_id' => $this->id)
										);

		}

		return false;

	}

/**
 * updateStatus method
 * Select next possible action(s) in checklist.....
 *
 * @param string $itemId The id of the item
 * @return boolean Returns true if the next pointer changed, false if not
 */

	public function updateStatus($itemId) {

		$this->unbindModel(array('belongsTo' => array('Item','ClTemplate')));
		$checklist = $this->find('first', array(
												'conditions' => array('item_id' => $itemId),
												'recursive' => 2
												));
		if(empty($checklist)) return false;

		$item = $this->Item->find('first', array(
												'conditions' => array('Item.id' => $itemId),
												'recursive' => -1
												));

		$itemState = $this->Item->State->find('first', array(
													'conditions' => array('State.id' => $item['Item']['state_id']),
													'recursive' => -1
												));

		//Clear next flag in all actions
		foreach($checklist['ClAction'] as $iclAction => $clAction) {
			//if(($clAction['status_code'] >> 12 & 0x3) == 3) { ... only closed actions

				$currstatusCode = $clAction['status_code'];
				$newstatusCode = $currstatusCode & ~ 0x8000;
				$this->ClAction->updateAll(
					array('status_code' => $newstatusCode),
					array('ClAction.id' => $clAction['id'])
				);

		}

		//Loop on Lvl1 actions to flag next ones
		$nextLvl1ActionIds = array();
		foreach($checklist['ClAction'] as $iclAction => $clAction) {

			if(($clAction['status_code'] >> 12 & 0x3) == 1) break;

			if($clAction['hierarchy_level'] == 1) {

				if(($clAction['status_code'] >> 12 & 0x3) == 0) {

					//check that current fsm state is a possible source state
					foreach($clAction['ClState'] as $clState) {
						if($clState['type'] == 'source' && $clState['name'] == $itemState['State']['name']) {
								$nextLvl1ActionIds[0] = $clAction['id'];
								break;
						}
					}

					$currstatusCode = $clAction['status_code'];
					if(count($nextLvl1ActionIds) == 1) $newstatusCode = $currstatusCode | 0x8000;
					else $newstatusCode = $currstatusCode & ~ 0x8000;
					$this->ClAction->updateAll(
						array('status_code' => $newstatusCode),
						array('ClAction.id' => $clAction['id'])
					);

					if(count($nextLvl1ActionIds) == 1) {

						foreach($clAction['ClState'] as $clState) {
							if($clState['type'] == 'source') $sourceStates[] = $clState['name'];
							else if($clState['type'] == 'target') $targetState = $clState['name'];
						}

						foreach($checklist['ClAction'] as $jclAction => $nextclAction) {

							if($jclAction > $iclAction) {

								if(($nextclAction['hierarchy_level'] == 1) && ($nextclAction['status_code'] >> 12 & 0x3) == 0) {

									$sourceKey = false; $targetKey = false;
									foreach($nextclAction['ClState'] as $clState) {
										if(($clState['type'] == 'source') && strcmp($clState['name'],$targetState) == 0) {
											$targetKey = true;
											break;
										}
									}
									foreach($nextclAction['ClState'] as $clState) {
										if($clState['type'] == 'target') {
											$sourceKey = array_search($clState['name'], $sourceStates);
											break;
										}
									}

									if($sourceKey !== false && $targetKey !== false) {

										$nextLvl1ActionIds[] = $nextclAction['id'];
										$currstatusCode = $nextclAction['status_code'];
										$newstatusCode = $currstatusCode | 0x8000;
										$this->ClAction->updateAll(
											array('status_code' => $newstatusCode),
											array('ClAction.id' => $nextclAction['id'])
										);

									}
									else {

										$currstatusCode = $nextclAction['status_code'];
										$newstatusCode = $currstatusCode & ~ 0x8000;
										$this->ClAction->updateAll(
											array('status_code' => $newstatusCode),
											array('ClAction.id' => $nextclAction['id'])
										);

									}
								}
							}

						}

					}

					break;
				}
			}
		}

		//Loop on lvl2 action to flag next ones
		foreach($nextLvl1ActionIds as $nextLvl1ActionId) {

			$lvl1Action = $this->ClAction->findById($nextLvl1ActionId);
			foreach($checklist['ClAction'] as $lvl2Action) {
				if(($lvl2Action['hierarchy_level'] == 2) &&
				   ($lvl2Action['list_number'] == $lvl1Action['ClAction']['list_number']) &&
				   ($lvl2Action['status_code'] >> 12 & 0x3) == 0) {

					//remove next flag from parent action
					$currstatusCode = $lvl1Action['ClAction']['status_code'];
					$newstatusCode = $currstatusCode & ~ 0x8000;
					$this->ClAction->updateAll(
						array('status_code' => $newstatusCode),
						array('ClAction.id' => $nextLvl1ActionId)
					);

					//assign next flag to the first open lvl2 action found
					$currstatusCode = $lvl2Action['status_code'];
					$newstatusCode = $currstatusCode | 0x8000;
					$this->ClAction->updateAll(
						array('status_code' => $newstatusCode),
						array('ClAction.id' => $lvl2Action['id'])
					);

					break;

				}
			}
		}

		// // Generate the automatic history comment
			// $eventId = $this->History->Event->getEventId('Item state changed');
			// $comment = 'State changed from "'.$oldState['name'].'" to "'.$newState['name'].'".';
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

		return false; // An error occured or update not needed

	}

}
