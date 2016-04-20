<?php
App::uses('AppModel', 'Model');
/**
 * Log Model
 *
 * @property User $User
 * @property LogEvent $LogEvent
 */
class Log extends AppModel {

	public $components = array('Session');
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'comment';
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric')
			),
		),
		'log_event_id' => array(
			'numeric' => array(
				'rule' => array('numeric')
			),
		),
		'comment' => array(
			'notempty' => array(
				'rule' => array('notempty')
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
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'LogEvent' => array(
			'className' => 'LogEvent',
			'foreignKey' => 'log_event_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);



	public function saveLog($log_event_name, $data){
#		debug($data);
		$logEvents = $this->LogEvent->find('list', array('fields' => array('LogEvent.name', 'LogEvent.id')));

		// if this Log Event does not exist create one
		if(empty($logEvents[$log_event_name])){
			$new_log_event['name'] = $log_event_name;
			$this->LogEvent->create();
			if($this->LogEvent->save($new_log_event)) {
				$logEvents[$log_event_name] = $this->LogEvent->id;
			}
		}

		$log['Log']['log_event_id'] = $logEvents[$log_event_name];

		if ($log_event_name == 'Item subtype deleted')
			$log['Log']['comment'] = 'Item Subtype "'.$data['ItemSubtype']['name'].'" deleted.';
		elseif ($log_event_name == 'Item subtype added')
			$log['Log']['comment'] = 'Item Subtype "'.$data['ItemSubtype']['name'].'" added.';
		elseif ($log_event_name == 'Item subtype edited')
			$log['Log']['comment'] = 'Item Subtype "'.$data['ItemSubtype']['name'].'" edited.';
		elseif ($log_event_name == 'Item subtype version deleted') {
			if(isset($data['version']))
				$log['Log']['comment'] = 'Version "'.$data['version'].'" deleted from item subtype "'.$data['ItemSubtype']['name'].'".';
			else
				$log['Log']['comment'] = 'Version "'.$data['ItemSubtypeVersion']['version'].'" deleted from item subtype "'.$data['ItemSubtype']['name'].'".';
		}
		elseif ($log_event_name == 'Item subtype version added')
			$log['Log']['comment'] = 'Version "'.$data['ItemSubtypeVersion']['version'].'" added to item subtype "'.$data['ItemSubtype']['name'].'".';
		elseif ($log_event_name == 'Item subtype version edited')
			$log['Log']['comment'] = 'Item Subtype Version"'.$data['ItemSubtypeVersion']['version'].'" edited.';
		elseif ($log_event_name == 'User edited')
			$log['Log']['comment'] = 'User "'.$data['User']['username'].'" edited.';
		elseif ($log_event_name == 'User added')
			$log['Log']['comment'] = 'User "'.$data['User']['username'].'" added.';
		elseif ($log_event_name == 'User deleted')
			$log['Log']['comment'] = 'User "'.$data['User']['username'].'" deleted.';
		elseif ($log_event_name == 'User login')
			$log['Log']['comment'] = 'User logged in. ('.$data['Ip'].')';
		elseif ($log_event_name == 'User login denied')
			$log['Log']['comment'] = 'Login denied for username: '.$data;
		elseif ($log_event_name == 'User logout')
			$log['Log']['comment'] = 'User logged out.';
		elseif ($log_event_name == 'User password reseted')
			$log['Log']['comment'] = $data['User']['username'].'\'s ('.$data['User']['id'].') password was reseted.';
		elseif ($log_event_name == 'Item deleted')
			$log['Log']['comment'] = 'Item '.$data['code'].' deleted';
		else
			$log["Log"]["comment"] = "";

		$this->create();
		$this->save($log);
	}

	/*
	 * Before saving a new entry to Log add the responsible (logged in) user.
	 */
	public function beforeSave($options = array()) {
		if(empty($this->data['Log']['user_id'])) {
			$User = ClassRegistry::init('User');

			if($User->getUserId() != null) {
				$this->data['Log']['user_id'] = $User->getUserId();
			} else {
				$this->data['Log']['user_id'] = 0;
			}
		}
	}
}
