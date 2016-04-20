<?php
App::uses('AppController', 'Controller');
/**
 * Logs Controller
 *
 * @property Log $Log
 */
class LogsController extends AppController {
	public $components = array('RequestHandler', 'Search');

	public $paginate = array(
        'limit' => 50,        
        'maxLimit' => 500,
        'order' => array(
            'created' => 'desc'
        )
	);

/**
 * index method
 *
 * @return void
 */
	public function index() {
		if(!empty($this->request->data)) {
			$filter = $this->request->data;
			$this->Session->write('LogIndexFilter', $filter);	
		} else {
			$filter = $this->Session->read('LogIndexFilter');
		}

		$this->paginate['conditions'] = $this->Search->getLogConditions($filter, 'Log');
		
		if(!empty($filter['limit'])) {
			$this->paginate['limit'] = $filter['limit'];
		} else {
			$filter['limit'] = $this->paginate['limit'];
		}
		
		if($this->RequestHandler->isAjax()) {
			// Reset page number after Search
			$this->request->params['named']['page'] = 1;
			$this->request->params['url'] = 'logs/index/page:1';
			$this->request->params['paging']['Log']['page'] = 1;
			if(isset($this->request->params['paging']['Log']['options']['page'])) {
				$this->request->params['paging']['Log']['options']['page'] = 1;
			}
		}		
		
		$this->Log->recursive = 0;
		$mylogs = $this->paginate();
		
		$logEvents = $this->Log->LogEvent->find('list');
		$users = $this->Log->User->find('list');
		
		//debug($filter);
		
		
		$this->set(compact('mylogs', 'logEvents', 'filter', 'users'));
		
		if($this->RequestHandler->isAjax()) {
			$this->render('logTable', 'ajax');
		}
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Log->id = $id;
		if (!$this->Log->exists()) {
			throw new NotFoundException(__('Invalid log'));
		}
		$this->set('log', $this->Log->read(null, $id));
	}
}