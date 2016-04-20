<?php
App::uses('AppController', 'Controller');
/**
 * Histories Controller
 *
 * @property History $History
 */
class HistoriesController extends AppController {

	public $paginate = array(
        'limit' => 50,
        'order' => array(
            'History.created' => 'desc'
        )
    );
	public $components = array('Linkify');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->History->recursive = 0;
		$projects = array();
		foreach($this->Session->read("User.Project") as $tmp) {
			$projects[] = $tmp["id"];
		}
      if(count($projects)==1) { $conditions = array('Item.project_id'=>$projects); }
      else { $conditions = array('Item.project_id IN '=>$projects); }
		if($this->request->is('post')) {
			$conditions['History.comment LIKE'] = '%'.$this->request->data['History']['search_term'].'%';
		}
		$history = $this->paginate($conditions);
	   $this->set('histories',$this->Linkify->history_items($history));
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->History->id = $id;
		if (!$this->History->exists()) {
			throw new NotFoundException(__('Invalid history'));
		}
		$this->set('history', $this->History->read(null, $id));
	}

/**
 * addComment method
 *
 * @return void
 */
	public function addComment($item_id) {
		$this->History->Item->id = $item_id;
		if (!$this->History->Item->exists()) {
			throw new NotFoundException(__('Invalid item'));
		}
	
		if ($this->request->is('post')) {
			$event_id = $this->History->Event->find('list', array('fields' => array('Event.name','Event.id'), 'conditions' => array('name' => 'Comment')));
			$this->request->data['History']['event_id'] = $event_id['Comment'];

			$this->History->create();
			if ($this->History->save($this->request->data)) {
				$this->Session->setFlash(__('The comment has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('controller' => 'items', 'action' => 'view', $item_id));
			} else {
				$this->Session->setFlash(__('The comment could not be saved. Please, try again.'));
			} 
		}
		
		$item = $this->History->Item->find('first', array('recursive' => -1, 'conditions' => array('id' => $item_id)));
		
		$this->set(compact('item', 'item_id'));
	}
	
/**
 * editComment method
 *
 * @return void
 */
	public function editComment($id) {
		
		$this->History->id = $id;
		if (!$this->History->exists()) {
			throw new NotFoundException(__('Invalid history id.'));
		}
		
		$h = $this->History->find('first', array('contain' => array('Item'), 'conditions' => array('History.id' => $id)));
		$item = $h['Item'];
		
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->History->save($this->request->data)) {
				$this->Session->setFlash(__('The comment has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('controller' => 'items', 'action' => 'view', $item['id']));
			} else {
				$this->Session->setFlash(__('The comment could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $h;
		}
		
		
		
		$this->set(compact('item'));
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
		$this->History->id = $id;
		if (!$this->History->exists()) {
			throw new NotFoundException(__('Invalid history'));
		}
		if ($this->History->delete()) {
			$this->Session->setFlash(__('History deleted'), 'default', array('class' => 'notification'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('History was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}
}
