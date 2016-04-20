<?php
App::uses('AppController', 'Controller');
/**
 * ProjectsItemTypes Controller
 *
 * @property ProjectsItemType $ProjectsItemType
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class ProjectsItemTypesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

/**
 * index method
 *
 * @return void
 */
	public function index($groupBy = "Projects") {

		//TODO: If no groupBy value is set check the session storage for the grouping used the last time and use that

		$this->loadModel("User");
		//Get this users assigned projects to only display these for modification
		$usersProjects = array_flip($this->User->getUsersProjects());
		$this->ProjectsItemType->recursive = 1;
		$this->loadModel("ItemTag");
		$grouping = array();
      $this->Paginator->settings['limit'] = 10000;
      // why is this using pagination? TODO: fix this
		foreach($this->Paginator->paginate() as $projectsItemType):
			if(!isset($usersProjects[$projectsItemType["Project"]["id"]])) continue;
			if($groupBy == "Projects"){
				$grouping[$projectsItemType["Project"]["id"]]["name"] = $projectsItemType["Project"]["name"];
				$grouping[$projectsItemType["Project"]["id"]]["group"][$projectsItemType["ItemType"]["id"]]["name"] = $projectsItemType["ItemType"]["name"];
				$grouping[$projectsItemType["Project"]["id"]]["group"][$projectsItemType["ItemType"]["id"]]["ItemTags"] = $projectsItemType["ItemTag"];
			}else{
				$grouping[$projectsItemType["ItemType"]["id"]]["name"] = $projectsItemType["ItemType"]["name"];
				$grouping[$projectsItemType["ItemType"]["id"]]["group"][$projectsItemType["Project"]["id"]]["name"] = $projectsItemType["Project"]["name"];
				$grouping[$projectsItemType["ItemType"]["id"]]["group"][$projectsItemType["Project"]["id"]]["ItemTags"] = $projectsItemType["ItemTag"];
			}
		endforeach;
		//Add missing groups to the array to allow adding of fields for those too
      // what? that's twice the same code? TODO: figure out what actually should happen here
		if($groupBy == "Projects"){
			$groups = $usersProjects;
			$fields = $this->ProjectsItemType->ItemType->find("list");
		}else{
			$groups = $this->ProjectsItemType->ItemType->find("list");
			$fields = $usersProjects;
		}
		foreach($groups as $groupId=>$groupName){
			if(!isset($grouping[$groupId])) $grouping[$groupId] = array("name"=>$groupName,"group"=>array());
		}


		$this->set('grouping', $grouping);
		$this->set('groupBy', $groupBy);
		$this->set('newFields', $fields);
		$this->set('itemTags',$this->ItemTag->find("list",array("order"=>"lower(name) ASC")));
	}


/**
 * setTagsForGroup method
 * This method saves a group of measurement tags depending on the grouping
 * Group can be either Project or ItemTag
 * Field is the other choice
 * Logic is written independent, only during saving the difference needs to be considered
 *
 * @return success or failure json encoded
 */
	public function setTagsForGroup() {
		$this->autoRender = false; //Only output what I want and not a view
		$groups = json_decode($this->request->data["tagData"],true);
		$groupBy = $this->request->data["groupBy"];
		$projectItemTypes = array();
		$oneProjectItemType = array(
			'ProjectsItemType' => array(
				'project_id' => 0,
				'item_type_id' => 0,
				'id'=>null
			),
			'ItemTag' => array(
				'ItemTag' => array()
			)
		);
		foreach($groups as $groupId=>$group){
			if($group == null) continue;
			$groupToSaveId = $groupId;
			foreach($group as $fieldId=>$field){
				if(!is_array($field)) continue;
				$tmp = $oneProjectItemType;
				$tmp["ProjectsItemType"] = ($groupBy == "Projects") ? array("project_id"=>$groupId,"item_type_id"=>$fieldId): array("project_id"=>$fieldId,"item_type_id"=>$groupId);
				//Try to get an ID for this combination to replace existing instead of creating a new one
				$projectItemType = $this->ProjectsItemType->find("first",array("conditions"=>$tmp["ProjectsItemType"],"fields"=>"id","recursive"=>-1));
				//Set ID to null if new or to the correct ID if it needs to be replaced (with the same values of course)
				$tmp["ProjectsItemType"]["id"] = (isset($projectItemType["ProjectsItemType"]["id"])) ? $projectItemType["ProjectsItemType"]["id"]: null;
				$tmp["ItemTag"]["ItemTag"] = $field;
				$projectItemTypes[] = $tmp;
			}
		}

		$error = false;
		//Save all
		foreach($projectItemTypes as $projectItemType){
			if (!$this->ProjectsItemType->save($projectItemType)) {
				$error = true;
				$this->Session->setFlash(__('The projects item type could not be saved. Please, try again.'));
			}
		}
		echo json_encode(array("error"=>$error,"debugInfo"=>$projectItemTypes));
		return;
	}

/**
 * removeFieldFromGroup method
 * removes a field from a given group depending on the grouping Parameter
 *
 * @return success or failure json encoded
 */
	function removeFieldFromGroup() {
		$this->autoRender = false; //Only output what I want and not a view
		$groupBy = $this->request->data["groupBy"];
		$groupId = $this->request->data["group"];
		$fieldId = $this->request->data["field"];
		$conditions = ($groupBy == "Projects") ? array("project_id"=>$groupId,"item_type_id"=>$fieldId): array("project_id"=>$fieldId,"item_type_id"=>$groupId);
		$projectItemType = $this->ProjectsItemType->find("first",array("conditions"=>$conditions,"fields"=>"id","recursive"=>-1));
		if(isset($projectItemType["ProjectsItemType"])){
			$this->ProjectsItemType->id = $projectItemType["ProjectsItemType"]["id"];
			if ($this->ProjectsItemType->delete()) {
				$error = false;
			} else {
				$error = true;
				$this->Session->setFlash(__('The projects item type could not be deleted. Please, try again.'));
			}
		}else{
			$error = false;
		}
		echo json_encode(array("error"=>$error));
		return;
	}

/**
 * addFieldToGroup method
 * adds a field to a given group depending on the grouping Parameter
 *
 * @return success or failure json encoded
 */
	public function addFieldToGroup() {
		$this->autoRender = false; //Only output what I want and not a view
		$groupBy = $this->request->data["groupBy"];
		$groupId = $this->request->data["group"];
		$fieldId = $this->request->data["field"];
		$conditions = ($groupBy == "Projects") ? array("project_id"=>$groupId,"item_type_id"=>$fieldId): array("project_id"=>$fieldId,"item_type_id"=>$groupId);
		//Check if this exists before creating it
		$exists = $this->ProjectsItemType->find("count",array("conditions"=>$conditions,"fields"=>"id","recursive"=>-1));
		if($exists>0){
			//Exists already, some other user might have created it in the meantime
		}
		//TODO: Check functionality
		$newProjectItemType["ProjectsItemType"] = $conditions;
		$error = $this->ProjectItemType->save($newProjectItemType);

		echo json_encode(array("error"=>$error));
		return;
	}


/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->ProjectsItemType->exists($id)) {
			throw new NotFoundException(__('Invalid projects item type'));
		}
		$options = array('conditions' => array('ProjectsItemType.' . $this->ProjectsItemType->primaryKey => $id));
		$this->set('projectsItemType', $this->ProjectsItemType->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->ProjectsItemType->create();
			debug($this->request->data);
			return;
			//Check if this combination already exists and if yes abort
			if($this->ProjectsItemType->find("count",array("conditions"=>array(
						"ProjectsItemType.project_id"=>$this->request->data["ProjectsItemType"]["project_id"],
						"ProjectsItemType.item_type_id"=>$this->request->data["ProjectsItemType"]["item_type_id"]
						))
				)>0)
			{
				//Combination exists already, abort
				$this->Session->setFlash(__('This combination of a Project and Item Type exists already'));
			}else{
				//Combination doesn't exist yet allow saving
				if ($this->ProjectsItemType->save($this->request->data)) {
					$this->Session->setFlash(__('The projects item type has been saved.'),"default",array("class"=>"notification"));
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('The projects item type could not be saved. Please, try again.'));
				}
			}
		}
		$projects = $this->ProjectsItemType->Project->find('list');
		$itemTypes = $this->ProjectsItemType->ItemType->find('list');
		$itemTags = $this->ProjectsItemType->ItemTag->find('list');
		$this->set(compact('projects', 'itemTypes','itemTags'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->ProjectsItemType->exists($id)) {
			throw new NotFoundException(__('Invalid projects item type'));
		}
		if ($this->request->is(array('post', 'put'))) {
			debug($this->request->data);
			return;
			if ($this->ProjectsItemType->save($this->request->data)) {
				$this->Session->setFlash(__('The projects item type has been saved.'),"default",array("class"=>"notification"));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The projects item type could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProjectsItemType.' . $this->ProjectsItemType->primaryKey => $id));
			$this->request->data = $this->ProjectsItemType->find('first', $options);
		}
		$projects = $this->ProjectsItemType->Project->find('list');
		$itemTypes = $this->ProjectsItemType->ItemType->find('list');
		$itemTags = $this->ProjectsItemType->ItemTag->find('list');
		$this->set(compact('projects', 'itemTypes','itemTags'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->ProjectsItemType->id = $id;
		if (!$this->ProjectsItemType->exists()) {
			throw new NotFoundException(__('Invalid projects item type'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->ProjectsItemType->delete()) {
			$this->Session->setFlash(__('The projects item type has been deleted.'));
		} else {
			$this->Session->setFlash(__('The projects item type could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
