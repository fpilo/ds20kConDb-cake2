<?php
App::uses('AppController', 'Controller');
/**
 * ItemTags Controller
 *
 * @property ItemTag $ItemTag
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class ItemTagsController extends AppController {

	var $uses = array('ItemTag','StockView');

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
	public function index() {
		$this->ItemTag->recursive = 0;
		$this->set('itemTags', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->ItemTag->exists($id)) {
			throw new NotFoundException(__('Invalid item tag'));
		}
		$this->ItemTag->id= $id;
		$this->loadModel("ItemView");
		$itemIds = $this->ItemTag->ItemTagsItem->find("list",array("conditions"=>array("ItemTagsItem.item_tag_id"=>$id),"fields"=>array("ItemTagsItem.item_id","ItemTagsItem.item_id")));
		$items = $this->ItemView->find("all",array(
								"conditions"=>array("ItemView.id"=>$itemIds),
								)
							);
		foreach($items as $itemId=>$item){
			$items[$itemId]["ItemTag"] = $this->ItemTag->Item->getTagsForItem($item["ItemView"]["id"]);
		}
		$itemTag = $this->ItemTag->find("first",array("conditions"=>array("ItemTag.id"=>$id)));

		$stocks = array(); //$this->StockView->find("all",array("conditions"=>array("StockView.id"=>$stockIds)));
		$this->set(compact('items','itemTag','stocks'));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->ItemTag->create();
			if($this->ItemTag->findByName($this->request->data["ItemTag"]["name"])){
				//Item Tag exists already, don't add
				if($this->request->isAjax()){
					$this->autoRender = false;
					echo json_encode(array("success"=>false,"message"=>"Tag exists already"));
					return;
				}else{
					$this->Session->setFlash(__('The item tag exists already and was not saved again.'), 'default', array('class' => 'notification'));
					return $this->redirect(array('action' => 'index'));
				}
			}
			if ($this->ItemTag->save($this->request->data)) {
				if($this->request->isAjax()){
					$this->autoRender = false;
					echo json_encode(array("success"=>true,"newTagId"=>$this->ItemTag->id));
					return;
				}else{
					$this->Session->setFlash(__('The item tag has been saved.'), 'default', array('class' => 'notification'));
					return $this->redirect(array('action' => 'index'));
				}
			} else {
				$this->Session->setFlash(__('The item tag could not be saved. Please, try again.'));
			}
		}
#		$items = $this->ItemTag->Item->find('list');
#		$this->set(compact('items'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->ItemTag->exists($id)) {
			throw new NotFoundException(__('Invalid item tag'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->ItemTag->save($this->request->data)) {
				$this->Session->setFlash(__('The item tag has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The item tag could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ItemTag.' . $this->ItemTag->primaryKey => $id));
			$this->request->data = $this->ItemTag->find('first', $options);
		}
#		$items = $this->ItemTag->Item->find('list');
#		$this->set(compact('items'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->ItemTag->id = $id;
		if (!$this->ItemTag->exists()) {
			throw new NotFoundException(__('Invalid item tag'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->ItemTag->delete()) {
			$this->Session->setFlash(__('The item tag has been deleted.'));
		} else {
			$this->Session->setFlash(__('The item tag could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	/**
	 * method getTagsForItemType
	 *
	 * @return json_array Containing id=>Value pairs of tags available for the item_type_id given
	 * @author
	 */
	function getTagsForItemTypeAndProject($item_type_id = null,$project_id=null)
	{
		$tmp = $this->ItemTag->ProjectsItemType->find("first",array("conditions"=>array("ItemType.id"=>$item_type_id,"Project.id"=>$project_id)));
		$return = array();
		foreach($tmp["ItemTag"] as $id=>$tags) $return[$tags["id"]] = $tags["name"];
		echo json_encode($return);
		$this->autoRender = false;
	}
}
