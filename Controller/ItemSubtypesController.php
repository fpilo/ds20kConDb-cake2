<?php
App::uses('AppController', 'Controller');
App::uses('Search', 'Controller/Component');
/**
 * ItemSubtypes Controller
 *
 * @property ItemSubtype $ItemSubtype
 */
class ItemSubtypesController extends AppController {

	public $components = array('Search');

	public $paginate = array(
        'limit' => 50,
        'maxLimit' => 500,
        'contain' => array('ItemSubtypeVersion.Manufacturer', 'ItemSubtypeVersion.Project', 'ItemType'),
		'joins' => array(
						array(
								'table' => 'item_subtype_versions',
								'alias' => 'ItemSubtypeVersion',
								'type' => 'left',
								'conditions' => array('ItemSubtypeVersion.item_subtype_id = ItemSubtype.id')
								),
						array(
								'table' => 'item_subtype_versions_projects',
								'alias' => 'ItemSubtypeVersionProject',
								'type' => 'left',
								'conditions' => array('ItemSubtypeVersionProject.item_subtype_version_id = ItemSubtypeVersion.id')
								)
						),
	);

/**
 * index method
 *
 * @return void
 */
	public function index() {
		if(!empty($this->request->data)) {
			$filter = $this->request->data;
			$this->Session->write('ItemSubtypeIndexFilter', $filter);

			// Reset page number after Search
			$this->request->params['named']['page'] = 1;
		}
		else
			$filter = $this->Session->read('ItemSubtypeIndexFilter');

		// debug($filter);
		$conditions = array();
		if(!empty($filter["ItemSubtype"]['project_id']))
			$conditions['AND']['ItemSubtypeVersionProject.project_id'] = $filter["ItemSubtype"]['project_id'];

		if(!empty($filter["ItemSubtype"]['item_type_id']))
			$conditions['AND']['ItemSubtype.item_type_id'] = $filter["ItemSubtype"]['item_type_id'];

		$this->paginate['conditions'] = $conditions;
		// debug($this->paginate);
		if(!empty($filter['limit'])) {
			$this->paginate['limit'] = $filter['limit'];
		} else {
			$filter['limit'] = $this->paginate['limit'];
		}

		$itemSubtypes = $this->paginate();

		$itemTypes = $this->ItemSubtype->ItemType->find('list');

		$this->set(compact('itemSubtypes', 'itemTypes', 'filter'));
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->ItemSubtype->id = $id;
		if (!$this->ItemSubtype->exists()) {
			throw new NotFoundException(__('Invalid item subtype'));
		}
		//$this->set('itemSubtype', $this->ItemSubtype->read(null, $id));

		$this->set('itemSubtype', $this->ItemSubtype->find('first', array(
																'conditions' => array('ItemSubtype.id' => $id),
																'contain'	=> array(
																		'ItemSubtypeVersion.Project',
																		'ItemSubtypeVersion.Manufacturer',
																		'ItemSubtypeVersion.DbFile.User',
																		'ItemType',
																		'DbFile.User'))));

		$locations = $this->ItemSubtype->Item->find('all', array(	'conditions' => array('Item.item_subtype_id' => $id),
																	'order' => array('Item.id' => 'desc'),
            														'contain' => array('Location'),
            														'group' => array('Item.location_id') ));

		$states = $this->ItemSubtype->Item->State->find('all', array(	'order' => array('State.name' => 'asc'),
																		'recursive' => 0));

		$count['total'] = 0;
		$count['available'] = 0;
		$overview = null;
		foreach($locations as $location) {
			$location_name = $location['Location']['name'];
			$location_id = $location['Location']['id'];
			foreach($states as $state) {
				$state_name = $state['State']['name'];
				$state_id = $state['State']['id'];
				$overview[$location_name][$state_name]['total'] = $this->ItemSubtype->Item->find('count', array('conditions' => array('AND' => array(
																	        								array('Item.item_subtype_id' => $id),
																											array('Location.id' => $location_id),
																											array('State.id' => $state_id) ))));
				$overview[$location_name][$state_name]['available'] = $this->ItemSubtype->Item->find('count', array('conditions' => array('AND' => array(
																	        								array('Item.item_subtype_id' => $id),
																											array('Location.id' => $location_id),
																											array('State.id' => $state_id),
																											array('(Item.id) NOT IN (SELECT Item.id
					FROM items AS Item left JOIN item_compositions AS CompositeItemO
					ON (CompositeItemO.component_id = Item.id) where CompositeItemO.valid = 1 ORDER BY Item.id)') ))));
				$count['total'] += $overview[$location_name][$state_name]['total'];
				$count['available'] += $overview[$location_name][$state_name]['available'];
			}
		}

		$this->set('overview', $overview);
		$this->set('count', $count);
/*
		$this->paginate = array('order' => array(
            						'Item.id' => 'desc'),
            					'contain' => array('Component','ItemType','ItemSubtypeVersion.Manufacturer','State', 'Location', 'Project'),
            					'conditions' => array('Item.item_subtype_id' => $id)
								);

		$this->set('items', $this->paginate('Item'));
 */
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$projects = array();
		$User = ClassRegistry::init('User');

		if ($this->request->is('post')) {
			//debug($this->request->data);
			if(!empty($this->request->data['SubtypeComponent'])) {
				$this->request->data['Component'] = $this->request->data['SubtypeComponent'];
				unset($this->request->data['SubtypeComponent']);

				$this->request->data['ItemSubtypeVersion']['has_components'] = '1';
				$position_check = $this->_checkPosition($this->request->data['Component']);
			}
			else {
				$this->request->data['ItemSubtypeVersion']['has_components'] = '0';
				$position_check = true;
			}

			if($position_check) {

				$this->ItemSubtype->create();
				if ($this->ItemSubtype->save($this->request->data)) {
					$id = $this->ItemSubtype->id;
					$this->request->data['ItemSubtypeVersion']['item_subtype_id'] = $id;
					$this->request->data['ItemSubtype']['id'] = $id;

					$this->ItemSubtype->ItemSubtypeVersion->create();
					if ($this->ItemSubtype->ItemSubtypeVersion->save($this->request->data)) {
				      debug(CACHE::clear(false,"default")); //Clear the default cache since it stores the prefetched array for all users
						$this->Session->delete('addItemSubtype');

						$this->loadModel('Log');
						$this->Log->saveLog('Item subtype added', $this->request->data);
						$this->Log->saveLog('Item subtype version added', $this->request->data);

						$this->Session->setFlash(__('The item subtype has been saved'), 'default', array('class' => 'notification'));
						return $this->redirect(array('controller' => 'item_subtypes', 'action' => 'view', $id));
					} else {
						$this->Session->setFlash(__('FAILURE: The item subtype could not be saved. Please, try again.'));
						return $this->redirect(array('controller' => 'item_subtypes', 'action' => 'add'));
					}
				} else {
					$this->Session->setFlash(__('FAILURE: The item subtype could not be saved. Please, check if you have filled all data fields correctly.'));
				}
			}
			else {
				$this->Session->setFlash(__('FAILURE: Please specify for every component a unique position.'));
			}
		}

		$conditions['OR']['Project.id'] = $User->getUsersProjects();
		$results = $this->ItemSubtype->ItemSubtypeVersion->Project->find('all', array(
												'conditions' => $conditions,
												'contain' => array('Manufacturer')));
		$projects = array();
		foreach($results as $project){
			$projects[$project['Project']['id']] = $project['Project']['name'];
			foreach($project['Manufacturer'] as $manufacturer) {
				$manufacturers[$project['Project']['id']][$manufacturer['id']] = $manufacturer['name'];
			}
		}


		// check if some components have been added to the new subtype
		if($this->Session->check('addItemSubtype')) {
			$components = $this->Session->read('addItemSubtype');
		}
		$itemTypes = $this->ItemSubtype->ItemType->find('list');

		$this->set(compact( 'itemTypes', 'projects', 'manufacturers', 'components'));
	}

	private function _checkPosition($components) {
		if(!empty($components)) {
			foreach($components as $key => $component) {
				$position = $component['position'];
				foreach($components as $k => $c) {
					if(($key != $k) && ($position == $c['position']))
						return false;
				}
			}
		}
		return true;
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->ItemSubtype->id = $id;
		if (!$this->ItemSubtype->exists()) {
			throw new NotFoundException(__('Invalid item subtype'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			unset($this->ItemSubtype->validate['item_type_id']);

			if ($this->ItemSubtype->save($this->request->data)) {

				$this->loadModel('Log');
				$this->Log->saveLog('Item subtype edited', $this->request->data);
				CACHE::clear(false,"default"); //Clear the default cache since it stores the prefetched array for all users

				$this->Session->setFlash(__('The item subtype has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'view', $id));
			} else {
				$this->Session->setFlash(__('FAILURE: The item subtype could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->ItemSubtype->read(null, $id);
		}
		$itemTypes = $this->ItemSubtype->ItemType->find('list');
		$this->set(compact('itemTypes'));
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
		$this->ItemSubtype->id = $id;
		if (!$this->ItemSubtype->exists()) {
			throw new NotFoundException(__('Invalid item subtype'));
		}
		$count_items = $this->ItemSubtype->Item->find('count', array(
								'conditions' => array('Item.item_subtype_id' => $id)));
		if($count_items <= 0) {
			$this->request->data = $this->ItemSubtype->find('first', array(
								'conditions' => array('ItemSubtype.id' => $id),
								'contain' => array(
									'ItemSubtypeVersion.ItemSubtype',
									'ItemSubtypeVersion.Component',
									'ItemSubtypeVersion.CompositeItemSubtypeVersion')));

			foreach($this->request->data['ItemSubtypeVersion'] as $itemSubtypeVersion) {
			 	if(!empty($itemSubtypeVersion['CompositeItemSubtypeVersion'])) {
			 		$this->Session->setFlash(__('FAILURE: This subtype cannot be deleted, because some versions of this subtype are related to other subtypes'));
					return $this->redirect(array('action' => 'index'));
				}
			}

			foreach($this->request->data['ItemSubtypeVersion'] as $itemSubtypeVersion) {
				$count_items = $this->ItemSubtype->ItemSubtypeVersion->Item->find('count', array(
								'conditions' => array('Item.item_subtype_version_id' => $itemSubtypeVersion['id'])));
				if($count_items <= 0) {
					//delete all ItemSubtypeVersionsCompositions
					foreach($itemSubtypeVersion['Component'] as $component) {
						$this->ItemSubtype->ItemSubtypeVersion->ItemSubtypeVersionsComposition->id = $component['ItemSubtypeVersionsComposition']['id'];
						$this->ItemSubtype->ItemSubtypeVersion->ItemSubtypeVersionsComposition->delete();
					}

					$this->ItemSubtype->ItemSubtypeVersion->id = $itemSubtypeVersion['id'];
					if (!$this->ItemSubtype->ItemSubtypeVersion->delete()) {
						$this->Session->setFlash(__('FAILURE: Item subtype version was not deleted'));
						return $this->redirect(array('action' => 'index'));
					}

					$this->loadModel('Log');
					$this->Log->saveLog('Item subtype version deleted', $itemSubtypeVersion);
				   debug(CACHE::clear(false,"default")); //Clear the default cache since it stores the prefetched array for all users
				}
				else {
					$this->Session->setFlash(__('FAILURE: This version cannot be deleted, because there are some items related to it'));
					return $this->redirect(array('action' => 'index'));
				}
			}

			$this->ItemSubtype->id = $id;
			if ($this->ItemSubtype->delete()) {

				$this->loadModel('Log');
				$this->Log->saveLog('Item subtype deleted', $this->request->data);

				$this->Session->setFlash(__('Item subtype deleted'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('FAILURE: Item subtype was not deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		else {
			$this->Session->setFlash(__('FAILURE: Item subtype cannot be deleted, because there are some items related to this subtype'));
			return $this->redirect(array('action' => 'index'));
		}
	}

	public function resetAdd() {
		$this->Session->delete('addItemSubtype');
		return $this->redirect(array('controller' => 'ItemSubtypes', 'action' => 'add'));
	}

	public function changelog() {
		$itemSubtypes = null;

		if(empty($this->request->params['pass'])) {
			$this->Session->setFlash('Please select an item subtype first', 'default', array('class' => 'warning'));
		}
		$ids = $this->request->params['pass'];
		foreach($ids as $id) {
			$this->ItemSubtype->id = $id;
			if (!$this->ItemSubtype->exists()) {
				throw new NotFoundException(__('Invalid item subtype'));
			}

			$itemSubtypes[] = $this->ItemSubtype->find('first', array(
								'conditions' => array('ItemSubtype.id' => $id),
								'contain' => array('ItemSubtypeVersion')));
		}

		$this->set('itemSubtypes', $itemSubtypes);
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function changeComment($id = null) {
		$this->ItemSubtype->id = $id;
		if (!$this->ItemSubtype->exists()) {
			throw new NotFoundException(__('Invalid item subtype'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			unset($this->ItemSubtype->validate['name']);
			unset($this->ItemSubtype->validate['shortname']);
			unset($this->ItemSubtype->validate['item_type_id']);

			if ($this->ItemSubtype->save($this->request->data)) {

				$this->loadModel('Log');
				$this->Log->saveLog('Item subtype edited', $this->request->data);

				$this->Session->setFlash(__('The new comment has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'view', $id));
			} else {
				$this->Session->setFlash(__('FAILURE: The changes could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->ItemSubtype->read(null, $id);
		}
	}

	/**
	 * method getProjectsForItemType
	 *
	 * @return array Assoc Array with key=>Value pairs of ProjectId=>ProjectName
	 * @author
	 */
	public function getProjectsForItemType($itemType = null)
	{
		$projects = array();
		$this->loadModel("ItemType");
		$this->ItemType->unbindModel(array("hasMany"=>array("ItemSubtypeVersionView","ItemSubtype","Item")));
		$projects = $this->ItemType->find("first",array("conditions"=>array("id"=>$itemType)));
		$return = array();
		foreach($projects["Project"] as $project){
			$return[$project["id"]] = $project["name"];
		}
		echo json_encode($return);
		$this->autoRender = false;
	}
}
