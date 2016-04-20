<?php
App::uses('AppController', 'Controller');
/**
 * Stocks Controller
 *
 * @property Stock $Stock
 * @property PaginatorComponent $Paginator
 */
class StocksController extends AppController {

	var $uses = array('Stock', 'StockView');
/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Search');

	public $paginate = array(
        'limit' => 50,
        'maxLimit' => 500
	);

	public $sessionAssembleItemComposition = 'AssembleItemSubtype';


/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Paginator->settings = array(
	        'limit' => 20,
	        //'contain' => array('ItemSubtypeVersion.ItemSubtype.ItemType', 'Project', 'Location'),
		);
		//$this->StockView->recursive = 1;
		$stocks = $this->Paginator->paginate('StockView');
		$this->set(compact('stocks'));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Stock->exists($id)) {
			throw new NotFoundException(__('Invalid stock'));
		}
		$options = array('conditions' => array('Stock.' . $this->Stock->primaryKey => $id));
		$this->set('stock', $this->Stock->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$filter = $this->request->data['Stock'];
			$this->set('filter',$filter);
			if(isset($this->request->data['Stock']['location_id'])) {
				$this->request->data['Location']['Location'] = $this->request->data['Stock']['location_id'];
				unset($this->request->data['Stock']['location_id']);
			}
			if(isset($this->request->data['Stock']['project_id'])) {
				$this->request->data['Project']['Project'] = $this->request->data['Stock']['project_id'];
				unset($this->request->data['Stock']['project_id']);
			}
			if(isset($this->request->data['Stock']['stock_tag_id'])) {
				$this->request->data['StockTag']['StockTag'] = $this->request->data['Stock']['stock_tag_id'];
				unset($this->request->data['Stock']['stock_tag_id']);
			}
			if(empty($this->request->data['Stock']['state_id'])) {
				$this->Session->setFlash(__('Please select a state.'), 'default', array('class' => 'warning'));
				return $this->redirect(array('action' => 'add'));
			}
			if(empty($this->request->data['Stock']['item_subtype_version_id'])) {
				$this->Session->setFlash(__('Please select a Subtype Version.'), 'default', array('class' => 'warning'));
				return $this->redirect(array('action' => 'add'));
			}

			if(empty($this->request->data['Location']['Location'])) {
				$this->Session->setFlash(__('Please select at least one location.'), 'default', array('class' => 'warning'));
				return $this->redirect(array('action' => 'add'));
			}
			$this->Stock->set($this->request->data);
			if($this->Stock->validates()) {
				$this->Stock->create();
				if ($this->Stock->save($this->request->data)) {
					$this->Session->setFlash(__('The stock has been saved.'), 'default', array('class' => 'notification'));
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('The stock could not be saved. Please, try again.'));
				}
			}
		}
		$locations = $this->StockView->Location->getUsersLocations();
		$projects = $this->StockView->Project->getUsersProjects();
		$states = $this->Stock->State->find('list');
		$stockQualities = $this->Stock->StockQuality->find('list');
		$stockTags = $this->Stock->StockTag->find('list');
		$this->set(compact( 'locations', 'projects', 'states','stockQualities','stockTags'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Stock->exists($id)) {
			throw new NotFoundException(__('Invalid stock'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Stock->save($this->request->data)) {
				$this->Session->setFlash(__('The stock has been saved.'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The stock could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Stock.' . $this->Stock->primaryKey => $id));
			$this->request->data = $this->Stock->find('first', $options);
		}

		$itemSubtypeVersions = $this->Stock->ItemSubtypeVersion->getItemSubtypeVersionsMultipleList();
		$locations = $this->Stock->Location->getUsersLocations();
		$projects = $this->Stock->Project->getUsersProjects();
		$states = $this->Stock->State->find('list');
		$stockQualities = $this->Stock->StockQuality->find('list');
		$stockTags = $this->Stock->StockTag->find('list');
		$this->set(compact('itemSubtypeVersions', 'locations', 'projects', 'states','stockQualities','stockTags'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->loadModel('Stock');
		$this->Stock->id = $id;
		if (!$this->Stock->exists()) {
			throw new NotFoundException(__('Invalid stock'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Stock->delete()) {
			$this->Session->setFlash(__('The stock has been deleted.'), 'default', array('class' => 'notification'));
		} else {
			$this->Session->setFlash(__('The stock could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	/*
	 * Attach an item from stock
	 */
	public function attach($position, $item_id) {

		$item = $this->Stock->Item->find('first', array(
									'conditions' => array('Item.id' => $item_id),
									'contain' => array('ItemSubtypeVersion.Component')
								));

		//look which item_subtype_version is on this position
		foreach($item['ItemSubtypeVersion']['Component'] as $itemSubtypeComponent) {
			if ($itemSubtypeComponent['ItemSubtypeVersionsComposition']['position'] == $position) {
				$item_subtype_version_id = $itemSubtypeComponent['id'];
			}
		}

		// Get the correct project of the component at this position
		// It is possible that a component belongs to an other project than the parent item
		foreach($item['ItemSubtypeVersion']['Component'] as $componentSlot) {
			if($componentSlot['ItemSubtypeVersionsComposition']['position'] == $position) {
				$filter['project_id'] = $componentSlot['ItemSubtypeVersionsComposition']['project_id'];
			}
		}

		$filter['item_subtype_version_id'] = $item_subtype_version_id;
		$filter['amount'] = 0;
		$filter['location_id'] = $item['Item']['location_id'];

		$this->paginate['conditions'] = $this->Search->getStockConditions($filter, 'StockView');
		//$this->paginate['conditions'] = array('AND' => array('amount > 0', array('state_id' => 3)));
		if(!empty($filter['limit'])) {
			$this->paginate['limit'] = $filter['limit'];
		} else {
			$filter['limit'] = $this->paginate['limit'];
		}

		/*
		 * This model bindings are a workaround because else it wouldnt be possible to filter stocks for location/project id
		 */
		$this->StockView->bindModel(
				array('hasOne'=>
						array(
							'ProjectsStocks' => array('foreignKey' => 'stock_id'),
							'LocationsStocks' => array('foreignKey' => 'stock_id')
				)), false);

		$this->Paginator->settings = $this->paginate;
		$stocks = $this->Paginator->paginate('StockView');

		/*
		 * Unbind the models from the workaround
		 */
		$this->StockView->unbindModel(array('hasOne' => array('ProjectsStocks', 'LocationsStocks')));

		$states = $this->Stock->State->find('list');

		$this->set(compact('stocks', 'item_id', 'position', 'states'));
	}

	public function select($position = null) {
		$session = $this->sessionAssembleItemComposition;
		$assemble = $this->Session->read($session);
		$filter = array();

#		debug($assemble);

		$item_subtype_version_id = null;

		$components = $assemble['ItemSubtypeVersion']['Component'];
		foreach($components as $component) {
			$compositionInfo = $component['ItemSubtypeVersionsComposition'];
			if($compositionInfo['position'] == $position) {
				$project_id = $compositionInfo['project_id'];
			}
		}

		// get VersionId from Component at the selected position
		foreach($assemble['ItemSubtypeVersion']['Component'] as $component) {
			if($component['ItemSubtypeVersionsComposition']['position'] == $position) {
				$item_subtype_version_id = $component['id'];
				$item_subtype_id = $component['item_subtype_id'];
				$manufacturer = $component['manufacturer_id'];
				$all_versions = $component['ItemSubtypeVersionsComposition']['all_versions'];
			}
		}

		if($all_versions == 0) {
			$filter['item_subtype_version_id'] = $item_subtype_version_id;
		} else {
			$filter['item_subtype_id'] = $item_subtype_id;
		}
		$filter['amount'] = 0;
		$filter['project_id'] = $project_id;
		$filter['location_id'] = $assemble['Item']['location_id'];
		$filter['limit'] = 50;

		$MyPaginate['conditions'] = $this->Search->getStockConditions($filter, 'StockView');
		//$this->paginate['conditions'] = array('AND' => array('amount > 0', array('state_id' => 3)));
		if(!empty($filter['limit'])) {
			$MyPaginate['limit'] = $filter['limit'];
		} else {
			$filter['limit'] = $MyPaginate['limit'];
		}

		/*
		 * This model bindings are a workaround because else it wouldnt be possible to filter stocks for location/project id
		 */
		$this->StockView->bindModel(
				array('hasOne'=>
						array(
							'ProjectsStocks' => array('foreignKey' => 'stock_id'),
							'LocationsStocks' => array('foreignKey' => 'stock_id')
				)), false);

		$this->Paginator->settings = $MyPaginate;
		$stocks = $this->Paginator->paginate('StockView');

		/*
		 * Unbind the models from the workaround
		 */
		$this->StockView->unbindModel(array('hasOne' => array('ProjectsStocks', 'LocationsStocks')));

		if(empty($stocks)) {
			$this->Session->setFlash('Stock is empty or there is no stock for this Project/Location/SubtypeVersion.', 'default', array('class' => 'warning'));
			return $this->redirect(array('controller' => 'items', 'action' => 'assembleItemComposition'));
		} elseif(count($stocks) == 1) {
			// A suitable stock was found
			$stock = reset($stocks);

			$item_type_name = $stock['StockView']['item_type_name'];
			$item_subtype_name = $stock['StockView']['item_subtype_name'];
			$item_subtype_version = $stock['StockView']['version'];
			$item_code = 'This is an item from stock.';
			$state_name = $stock['StockView']['state_name'];
			$manufacturer_name = $stock['StockView']['manufacturer_name'];
			$project_name = '';
			$item_tags = "";
			$tmp_tags = array();
			foreach($stock['StockTag'] as $tag){
				$tmp_tags[] = $tag["name"];
			}
			if(count($tmp_tags)>0)	$item_tags = implode(", ", $tmp_tags);
			$item_quality = $stock["StockView"]["stock_quality_name"];
			foreach($stock['Project'] as $project) {
				$project_name .= $project['name'].' ';
			}
			$actions = 	array(__('Remove'), array('controller' => 'items','action' => 'removeFromSelection', $position));

			$assemble['Selection'][$position] = array(
				'position' => $position,
				'type_name' => $item_type_name,
				'subtype_name' => $item_subtype_name,
				'subtype_version' => $item_subtype_version,
				'code' => $item_code,
				'tags' => $item_tags,
				'state_name' => $state_name,
				'quality' => $item_quality,
				'manufacturer_name' => $manufacturer_name,
				'project_name' => $project_name,
				'actions' => $actions
			);

			$newComponent['stock_id'] = $stock['StockView']['id'];
			$newComponent['valid'] = 1;
			$newComponent['position'] = $position;

			$assemble['Component'][$position] = $newComponent;

			$this->Session->write($session, $assemble);

			return $this->redirect(array('controller' => 'items', 'action' => 'assembleItemComposition'));

		} elseif($this->request->isPost()) {
			if(!empty($this->request->data['Selection'])) {
				$stock_id = $this->request->data['Selection']['id'];

				$stock 	= $this->StockView->find('first', array(
															'conditions' => array('StockView.id' => $stock_id)
															));

				$item_type_name = $stock['StockView']['item_type_name'];
				$item_subtype_name = $stock['StockView']['item_subtype_name'];
				$item_subtype_version = $stock['StockView']['version'];
				$item_code = 'This is an item from stock.';
				$state_name = $stock['StockView']['state_name'];
				$manufacturer_name = $stock['StockView']['manufacturer_name'];
				$project_name = '';
				$item_tags = "";
				$tmp_tags = array();
				foreach($stock['StockTag'] as $tag){
					$tmp_tags[] = $tag["name"];
				}
				if(count($tmp_tags)>0)	$item_tags = implode(", ", $tmp_tags);
				$item_quality = $stock["StockView"]["stock_quality_name"];
				foreach($stock['Project'] as $project) {
					$project_name .= $project['name'].' ';
				}
				$actions = 	array(__('Remove'), array('controller' => 'items','action' => 'removeFromSelection', $position));

				$assemble['Selection'][$position] = array(
					'position' => $position,
					'type_name' => $item_type_name,
					'subtype_name' => $item_subtype_name,
					'subtype_version' => $item_subtype_version,
					'code' => $item_code,
					'tags' => $item_tags,
					'state_name' => $state_name,
					'quality' => $item_quality,
					'manufacturer_name' => $manufacturer_name,
					'project_name' => $project_name,
					'actions' => $actions
				);

				$newComponent['stock_id'] =  $stock_id;
				$newComponent['valid'] = 1;
				$newComponent['position'] = $position;

				$assemble['Component'][$position] = $newComponent;

				$this->Session->write($session, $assemble);

				return $this->redirect(array('controller' => 'items', 'action' => 'assembleItemComposition'));
			}
		}

		$this->set(compact('stocks', 'position'));
	}
}
