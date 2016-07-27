<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {

	public $helpers = array('Text');

	public $components = array('RequestHandler');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}

		$user = $this->User->find('first', array(
					'conditions' => array('User.id' => $id),
					'contain' => array('Project', 'Location', 'Group','StandardLocation')));

		$this->set(compact('user'));
	}

	public function viewLog($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->loadModel('Log');
		$this->paginate = array(
			'Log' => array(
		        'conditions' => array('Log.user_id' => $id),
		        'limit' => 20,
				'contain' => array('LogEvent'),
				'order' => array(
		            'Log.created' => 'desc'))
	    );
	    $logs = $this->paginate('Log');

		$user = $this->User->find('first', array(
					'conditions' => array('User.id' => $id),
					'contain' => array('Project', 'Location', 'Group')));

		$this->set(compact('user', 'logs'));
	}

	public function viewHistory($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}

		$this->loadModel('History');
		$this->paginate = array(
			'History' => array(
		        'conditions' => array('History.user_id' => $id),
		        'limit' => 20,
				'contain' => array('Event'),
				'order' => array(
		            'Log.created' => 'desc'))
	    );
	    $history = $this->paginate('History');

		$user = $this->User->find('first', array(
					'conditions' => array('User.id' => $id),
					'contain' => array('Project', 'Location', 'Group')));

		$this->set(compact('user', 'history'));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			if ($this->request->data['User']['password'] == $this->request->data['User']['password_check']) {
				$this->User->create();
				if ($this->User->save($this->request->data)) {
					$this->loadModel('Log');
					$this->Log->saveLog('User added', $this->request->data);

					$this->Session->setFlash(__('The user has been saved'), 'default', array('class' => 'notification'));
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
				}
			}
			else
				$this->Session->setFlash(__('Password check failed. Please re-enter password.'));
		}

		$projects = $this->User->Project->find('list');
		$locations = $this->User->Location->find('list');
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups', 'projects', 'locations'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null, $redirect = array('action'=>'index')) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			unset($this->User->validate['password']);
			if ($this->User->save($this->request->data)) {
				CACHE::clear(false,"default"); //Clear the default cache since it stores the prefetched array for all users

				$this->loadModel('Log');
				$this->Log->saveLog('User edited', $this->request->data);
				$this->Session->setFlash(__('The user has been saved'), 'default', array('class' => 'notification'));
				if($id == $this->Session->read("User.User.id"))
					$this->_updateUserSession();

				return $this->redirect($redirect);
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
		}

		$projects = $this->User->Project->find('list');
		$locations = $this->User->Location->find('list');
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups','locations','projects'));
	}

   public function edit_self() {
      /* fills $this->request->data with the values a user is not allowed to change himself and call $this->edit()
       * not using saveField() because it would not trigger the logging and other stuff that happens in edit()
       */
      $id = $this->User->getUserId();
      $this->request->data['Project']['Project'] = array_keys($this->User->Project->getUsersProjects());
      $this->request->data['Location']['Location'] = array_keys($this->User->Location->getUsersLocations());
      $u = $this->User->findById($id,array('recursive'=>0));
      $this->request->data['User']['username'] = $u['User']['username'];
      $this->request->data['User']['group_id'] = $u['User']['group_id'];
      $this->request->data['User']['add_projects'] = $u['User']['add_projects'];
      $this->request->data['User']['add_locations'] = $u['User']['add_locations'];
      return $this->edit($id, array('action'=>'view/'.$id));
   }

/**
 * settings method
 *
 * Users can change their settings
 */
	public function settings() {
      /*
		$id = $this->Session->read('User.User.id');
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('user', $this->User->read(null, $id));
      */
      return $this->redirect(array('action'=>'edit_self'));
	}

/**
 * changePassword method
 *
 * @return void
 */
	public function changePassword() {
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->request->data['User']['password'] == $this->request->data['User']['password_check']) {
				$this->User->id = $this->request->data['User']['id'];
				if (!$this->User->exists()) {
					throw new NotFoundException(__('Invalid user'));
				}
				unset($this->User->validate['username']);
				unset($this->User->validate['group_id']);
				unset($this->User->validate['standard_location_id']);
				if ($this->User->save($this->request->data)) {
					$this->Session->setFlash(__('Password changed.'), 'default', array('class' => 'notification'));
					$this->_updateUserSession();
					//$this->redirect(array('action' => 'settings', $id));
				} else {
					$this->Session->setFlash(__('The password could not be saved. Please, try again.'));
				}
			}
			else
				$this->Session->setFlash(__('Password check failed. Please re-enter password.', 'default', array('class' => 'warning')));
		}
	}

	/**
 * changeStandardLocation method
 *
 * @return void
 */
	public function changeStandardLocation() {
		$this->User->id = $this->Session->read('User.User.id');
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->User->id = $this->request->data['User']['id'];
			if (!$this->User->exists()) {
				throw new NotFoundException(__('Invalid user'));
			}
			unset($this->User->validate['username']);
			unset($this->User->validate['group_id']);
			unset($this->User->validate['password']);
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('Standard location changed.'), 'default', array('class' => 'notification'));
				$this->_updateUserSession();
				$this->Session->write('Auth.User.standard_location_id',$this->request->data["User"]["standard_location_id"]);
				$this->redirect(array('action' => 'settings'));
			} else {
				$this->Session->setFlash(__('The standard location could not be saved. Please, try again.'));
				debug($this->User->invalidFields());
			}
		}
		$this->request->data = $this->User->read(null, $this->User->id);
		$this->set("locations",array_flip($this->User->getUsersLocations()));
	}


	public function resetPassword($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('User not found in database'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->request->data['User']['password'] == $this->request->data['User']['password_check']) {
				unset($this->User->validate['username']);
				unset($this->User->validate['group_id']);
				unset($this->User->validate['standard_location_id']);
				if ($this->User->save($this->request->data)) {
					$this->loadModel('Log');
					$this->Log->saveLog('User password resetted', $this->request->data);

					$this->Session->setFlash(__('Password changed.'), 'default', array('class' => 'notification'));
					$this->_updateUserSession();
				} else {
					$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
				}
			}
			else
				$this->Session->setFlash(__('Password check failed. Please re-enter password.'), 'default', array('class' => 'warning'));
		} else {
			$this->request->data = $this->User->findById($id);
		}
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
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->data = $this->User->read(null, $id);
		if ($this->User->delete()) {
			$this->loadModel('Log');
			$this->Log->saveLog('User deleted', $this->request->data);

			$this->Session->setFlash(__('User deleted'), 'default', array('class' => 'notification'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}

	/* Hide Links for unauthorized users
	 *
	 * Session: http://www.justkez.com/understanding-cakephp-sessions/
	 */
	public function login() {

     if ($this->Session->read('Auth.User')) {
			$this->Session->setFlash('You are logged in!', 'default', array('class' => 'notification'));
			return $this->redirect('/', null, false);
		}
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$this->_updateUserSession();
				$this->_updateAuthSession();
				$this->Session->write("Database.Instance",Configure::read("Instance"));

				$this->loadModel('Log');
				$data['Ip'] = (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $this->request->clientIp();

				$this->Log->saveLog('User login', $data);

				return $this->redirect($this->Auth->redirect());
			} else {
				$this->loadModel('Log');
				$this->Log->saveLog('User login denied', $this->request->data['User']['username']);

				$this->Session->setFlash('Your username or password is incorrect.');

				// sleep against robots and brute force
				sleep(2);
			}
		}
  }

	/*
	 * function _updateAuthSession
	 *
	 * Saving an array of all controller/actions for validating
	 * access rights.
	 * */
	protected function _updateAuthSession() {
		/* If group ACL's is not enough and you need also the possibility to make individual changes for users.
		 * Then you will need something like this:
		 *
		 * $aro_ids[] = $this->Session->read('Auth.User.group_id');
		 * $aro_ids[] = please enter here the users aro_id attention thats not the user_id so dont use this $this->Session->read('Auth.User.id');
		 */

		$aro_ids = $this->Session->read('Auth.User.group_id');
		$aco = $this->_getAcoTree($aro_ids);
		//debug($aro_ids);
		$this->_writeAuthSession($aco);
	}

	protected function _getAcoTree($aro_ids) {
		$aco = array();

		/* If group ACL's is not enough and you need also the possibility to make individual changes for users.
		 * Then you will need something like this:
		 *
		 * foreach($aro_ids as $aro_id)
		 *		$conditions['OR'][] = array('Aro.id' => $aro_id);
		 *
		 * $acoList = $this->Acl->Aco->Permission->find('all', array('conditions' => $conditions));
		 *
		 * or just get $aco for each $aro_id and merge this arrays
		 */

		$acoList = $this->Acl->Aco->Permission->find('all', array('conditions' => array('Aro.foreign_key' => $aro_ids)));
		
		foreach($acoList as $tmpaco) {
			if($tmpaco['Aco']['parent_id'] == NULL) {
				$root = $tmpaco['Aco'];
			}

		}
		if(!empty($root)) {
			$aco = $this->_getAcoChilds($acoList, $root);
		}
		return $aco;
	}

	protected function _getAcoChilds($acoList, $root, $path = null) {
		$parent_id = $root['id'];
		foreach($acoList as $child) {
			if($child['Aco']['id'] == $parent_id) {
				$root['Permission'] = $child['Permission'];
				if($path != NULL)
					$root['path'] = $path.'/'.$child['Aco']['alias'];
				else
					$root['path'] = $child['Aco']['alias'];
			}
		}

		foreach($acoList as $child) {
			if($child['Aco']['parent_id'] == $parent_id) {
				$root['Child'][] = $this->_getAcoChilds($acoList, $child['Aco'], $root['path']);
			}
		}

		return $root;
	}

	/*
	 * function _updateUserSession
	 *
	 * Saving data about the user
	 * (Id, Name, related locations and related projects)
	 * */

	protected function _updateUserSession() {
		$userId = $this->Session->read('Auth.User.id');
		$this->Session->write('User', $this->User->find('first', array(
												'conditions' => array('User.id' => $userId),
												'contain' => array('Group', 'Location', 'Project'))));
#		debug($this->Session->read("User"));
	}

	/*
	 * Only allow access if $permission > 0 or $parentPermission > 0
	 */

	protected function _writeAuthSession($aco, $parentPermission = null) {
		$permission = $aco['Permission']['_read'];

		if (($permission == 0) && ($parentPermission != null))
			$permission = $parentPermission;

		if($permission > 0)
			$this->Session->write('Auth.User.Permissions.' .$aco['path'], true);

		if(isset($aco['Child'])) {
			foreach($aco['Child'] as $child) {
				$this->_writeAuthSession($child, $permission);
			}
		}
	}

	public function logout() {
		$this->loadModel('Log');
		$this->Log->saveLog('User logout', null);

		if ($this->Session->valid())
			$this->Session->destroy();

		$this->Session->setFlash('Logout successful', 'default', array('class' => 'notification'));
		return $this->redirect($this->Auth->logout());
	}

	public function beforeFilter() {
		
		parent::beforeFilter();
		
		//Enable what follows to populate the acos_aros table
		$this->Auth->allow('initDB');
		
	}

	public function initDB($group_id) {
		
		if(!isset($group_id)){
			echo "Please specify a valid group_id";
			exit;
		}
		
		$group = $this->User->Group;
		
		/**
		 * Build aco list
		 */
		$acos = $this->Acl->Aco->query(
			"SELECT * FROM acos as Aco
				ORDER BY Aco.lft ASC
			"
		);
		
		$tmpAcos = array();
		foreach($acos as $aco){
			$tmpAco = $aco['Aco'];
			$tmpAcos[] = $tmpAco;
		}
		
		$acos = $tmpAcos;
		foreach($acos as $aco){
			$alias = $aco['alias'];
			if($aco['parent_id']!=null){
				$parentIds[0] = array_search($aco['parent_id'], array_column($acos, 'id'));
				$alias = $acos[$parentIds[0]]['alias']."/".$alias;
				
				if($acos[$parentIds[0]]['parent_id']!=null){
					$parentIds[1] = array_search($acos[$parentIds[0]]['parent_id'], array_column($acos, 'id'));
					$alias = $acos[$parentIds[1]]['alias']."/".$alias;
				}
			}		
			
			$acosAlias[] = $alias;
		}
		
		$group->id = $group_id; //1 - Administrators
		foreach($acosAlias as $alias){
			if(substr_count($alias, '/')==0) $this->Acl->allow($group, $alias);
			if(substr_count($alias, '/')==1) $this->Acl->inherit($group, $alias);
			if(substr_count($alias, '/')>=2) $this->Acl->inherit($group, $alias);
		}
		
		// Allow admins to everything
		/**
		debug($this->Acl->check($group, 'controllers/Stocks')); die;

		$this->Acl->allow($group, 'controllers');
		$this->Acl->allow($group, 'controllers/Checklists');
		$this->Acl->allow($group, 'controllers/ClActions');
		$this->Acl->allow($group, 'controllers/ClStates');
		$this->Acl->allow($group, 'controllers/ClTemplates');
		$this->Acl->allow($group, 'controllers/DbFiles');
		$this->Acl->allow($group, 'controllers/Deliverers');
		$this->Acl->allow($group, 'controllers/Devices');
		$this->Acl->allow($group, 'controllers/Events');
		$this->Acl->allow($group, 'controllers/Groups');
		$this->Acl->allow($group, 'controllers/Histories');
		$this->Acl->allow($group, 'controllers/ItemQualities');
		$this->Acl->allow($group, 'controllers/ItemSubtypeVersions');
		$this->Acl->allow($group, 'controllers/ItemSubtypes');
		$this->Acl->allow($group, 'controllers/ItemTags');
		$this->Acl->allow($group, 'controllers/ItemTypes');
		$this->Acl->allow($group, 'controllers/Items');
		$this->Acl->allow($group, 'controllers/Locations');
		$this->Acl->allow($group, 'controllers/LocationsUsers');
		$this->Acl->allow($group, 'controllers/LogEvents');
		$this->Acl->allow($group, 'controllers/Logs');
		$this->Acl->allow($group, 'controllers/Manufacturers');
		$this->Acl->allow($group, 'controllers/Matchings');
		$this->Acl->allow($group, 'controllers/MeasurementParameters');
		$this->Acl->allow($group, 'controllers/MeasurementQueues');
		$this->Acl->allow($group, 'controllers/MeasurementSets');
		$this->Acl->allow($group, 'controllers/MeasurementTags');
		$this->Acl->allow($group, 'controllers/MeasurementTypes');
		$this->Acl->allow($group, 'controllers/Measurements');
		$this->Acl->allow($group, 'controllers/MeasuringPoints');
		$this->Acl->allow($group, 'controllers/Pages');
		$this->Acl->allow($group, 'controllers/Parameters');
		$this->Acl->allow($group, 'controllers/Projects');
		$this->Acl->allow($group, 'controllers/ProjectsItemTypes');
		$this->Acl->allow($group, 'controllers/ProjectsUsers');
		$this->Acl->allow($group, 'controllers/Readings');
		$this->Acl->allow($group, 'controllers/States');
		$this->Acl->allow($group, 'controllers/Stocks');
		$this->Acl->allow($group, 'controllers/Transfers');
		$this->Acl->allow($group, 'controllers/Users');
		$this->Acl->allow($group, 'controllers/AclExtras');
		$this->Acl->allow($group, 'controllers/AclManager');
		$this->Acl->allow($group, 'controllers/Filter');
		$this->Acl->allow($group, 'controllers/Plupload');
		$this->Acl->allow($group, 'controllers/Search');
		$this->Acl->allow($group, 'controllers/DebugKit');
*/
		// we add an exit to avoid an ugly "missing views" error message
		echo "all done";
		exit;
	}
	
}


