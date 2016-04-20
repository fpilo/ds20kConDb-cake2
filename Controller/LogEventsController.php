<?php
App::uses('AppController', 'Controller');
/**
 * LogEvents Controller
 *
 * @property LogEvent $LogEvent
 */
class LogEventsController extends AppController {


/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->LogEvent->recursive = 0;
		$this->set('logEvents', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->LogEvent->id = $id;
		if (!$this->LogEvent->exists()) {
			throw new NotFoundException(__('Invalid log event'));
		}
		
		$this->loadModel('Log');
		$this->paginate = array(
			'Log' => array(
		        'conditions' => array('Log.log_event_id' => $id),
		        'limit' => 20,
				'contain' => array('User'),
				'order' => array(
		            'Log.created' => 'desc'))
	    );		
	    $logs = $this->paginate('Log');
		//debug($data);
		$logEvent = $this->LogEvent->find('first', array('conditions' => array('LogEvent.id' => $id), 'recursive' => -1));
	    $this->set(compact('logs', 'logEvent'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->LogEvent->id = $id;
		if (!$this->LogEvent->exists()) {
			throw new NotFoundException(__('Invalid log event'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->LogEvent->save($this->request->data)) {
				$this->Session->setFlash(__('The log event has been saved'), 'default', array('class' => 'notification'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The log event could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->LogEvent->read(null, $id);
		}
	}
}
