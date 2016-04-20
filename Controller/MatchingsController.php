<?php
App::uses('AppController', 'Controller');
/**
 * Matchings Controller
 *
 * @property Matching $Matching
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class MatchingsController extends AppController {

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
		$this->Matching->recursive = 0;
		$this->set('matchings', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Matching->exists($id)) {
			throw new NotFoundException(__('Invalid matching'));
		}
		$options = array('conditions' => array('Matching.' . $this->Matching->primaryKey => $id));
		$this->set('matching', $this->Matching->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Matching->create();
			$matching = $this->Matching->findByName($this->request->data["Matching"]["name"]);
			if(count($matching) > 0){
				$this->Session->setFlash(__('The matching could not be saved because this string is already assigned to one parameter. '));
			}else{
				if ($this->Matching->save($this->request->data)) {
					$this->Session->setFlash(__('The matching has been saved.'),'default',array("class"=>"notification"));
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('The matching could not be saved. Please, try again.'));
				}
			}
		}
		$parameters = $this->Matching->Parameter->find('list');
		$this->set(compact('parameters'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Matching->exists($id)) {
			throw new NotFoundException(__('Invalid matching'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Matching->save($this->request->data)) {
				$this->Session->setFlash(__('The matching has been saved.'),'default',array("class"=>"notification"));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The matching could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Matching.' . $this->Matching->primaryKey => $id));
			$this->request->data = $this->Matching->find('first', $options);
		}
		$parameters = $this->Matching->Parameter->find('list');
		$this->set(compact('parameters'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Matching->id = $id;
		if (!$this->Matching->exists()) {
			throw new NotFoundException(__('Invalid matching'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Matching->delete()) {
			$this->Session->setFlash(__('The matching has been deleted.'));
		} else {
			$this->Session->setFlash(__('The matching could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
