<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	private $startTime = 0;
	public $components = array(
				'Acl',
				'Auth' => array(
					'authorize' => array(
						'Controller',
						'Actions' => array('actionPath' => 'controllers')
					),
					'loginAction' => array(
					       'controller' => 'users',
					       'action' => 'login'
                    ),
                    'logoutRedirect' => array(
                            'controller' => 'users',
                            'action' => 'login'
                    ),
                    'loginRedirect' => array(
                            'controller' => 'items',
                            'action' => 'index'
                    )
				),
				'DebugKit.Toolbar',
				'Session'
			);

	public $helpers = array(
				'Html' => array('className' => 'ExtendedHtml'),
				'Form' => array('className' => 'ExtendedForm'),
				'Session', 'Js' => array('Jquery'),
				'My'
	);
	public $paginate=array();

	public function beforeFilter(){		
		parent::beforeFilter();
		$this->paginate['limit'] = 70;
		$this->startTime = microtime(true);
	}

	public function _runtime($descriptor,$debugOutput = true){
		$now = microtime(true)-$this->startTime;
		if($debugOutput){
			debug($descriptor." took ".sprintf("%1.2f seconds",$now));
		}else{
			$this->startTime = microtime(true);
			return $descriptor." took ".sprintf("%1.2f seconds",$now);
		}
	}
	/**
	 * Get the directory size
	 * @param directory $directory
	 * @return integer
	 */
	protected function _dirSize($directory) {
		$units = explode(' ', 'B KB MB GB TB PB');
		$size = 0;
		foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file){
			$size+=$file->getSize();
		}
		$mod = 1024;

		for ($i = 0; $size > $mod; $i++) {
			$size /= $mod;
		}

		$endIndex = strpos($size, ".")+3;
		return substr( $size, 0, $endIndex).' '.$units[$i];
	}


	public function beforeRender() {
	    
		// Before Loading a new page save the url of the current site
	    // So a back button can be realized easily by calling:
	    //     echo $this->Html->link(__('Back'), $referer);
		$this->set('referer',$this->referer());
		if(CakeSession::read("User")!=null){
			
			$this->loadModel("User");
			//Check if the user has switched between databases and if yes force a logout
			if($this->Session->read("Database.Instance") != Configure::read("Instance")){
				if ($this->Session->valid())
					$this->Session->destroy();
				$this->Session->setFlash('You changed between different database instances and were logged out as a precaution. ', 'default', array('class' => 'notification'));
				return $this->redirect($this->Auth->logout());
			}
			
			
			$this->loadModel("Transfer");
			//$standardLocation = $this->User->getUserStandardLocation();
			$standardLocation = $this->User->Location->find("first",array("conditions"=>array("Location.id"=>CakeSession::read("User.User.standard_location_id")),"recursive"=>-1));
			$locationId = $standardLocation['Location']['id'];
			$this->set("incomingTransfers",count($this->Transfer->getInTransitToLocations($locationId,'filteredByProjects')));
			$this->set("outgoingTransfers",count($this->Transfer->getInTransitFromLocations($locationId,'filteredByProjects'))+count($this->Transfer->getPendingFromLocations($locationId,'filteredByProjects')));
			$this->set("standardLocation",$standardLocation);
			$this->loadModel("ItemSubtypeVersion");
			$all_items = $this->ItemSubtypeVersion->getUsersItemSubtypeVersions();
			
			$this->set("itemSubtypeVersions",$all_items[0]);
			$this->set("emptyItemTypes",$all_items[1]);
			
		}
	}

	function isAuthorized($user) {
//        return $this->Auth->loggedIn(); // this line deactivates ACL. Also have a look at AppHelper to activate all links.
        return false;
    }
}
