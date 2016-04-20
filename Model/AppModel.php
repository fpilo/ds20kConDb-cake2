<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
var $inserted_ids = array();

public $actsAs = array('Search.Searchable', 'Containable');

/**
 * How to get ids after saveAll()?
 * http://www.daft-thoughts.com/development/get-previous-insert-ids-of-saveall-in-cakephp/
 *
 * How to use:
 * if($this->Post->saveAll($posts))
 *	{
 *		$post_ids=$this->Post->inserted_ids;	
 *	}
 */
	function afterSave($created, $options = array())
	{
		if($created)
		{
			$this->inserted_ids[] = $this->getInsertID();
		}

		return true;
	}
	
/*
* http://bakery.cakephp.org/articles/tapter/2006/10/08/limit-the-models-used-in-find-operations
*
* <?php  
 *   $this->User->useModel( array('ExtendedProfile') ); 
 *   $this->User->read(null, '1'); // Find the entry with id=1 
 *
?>
*
* <?php
*   $this->User->useModel( array("ExtendedProfile", "Article") );
*   $this->User->Article->useModel(); // To stop recursion there if Article has further relations for recursion > 1
*
?>
*
*/

	function useModel($params = array()) {
		if(!is_array($params)) {
			$params = array($params);
		}
			
		$classname = get_class($this); // for debug output
		
		foreach($this->associations() as $ass) {
			if(!empty($this->{$ass})) {
				// This model has an association '$ass' defined (like 'hasMany', ...)	
				$this->__backAssociation[$ass] = $this->{$ass};
			
				foreach($this->{$ass} as $model => $detail) {
					if(!in_array($model,$params)) {
						//debug("Ignoring association $classname <i>$ass</i> $model... ");
						$this->__backAssociation = array_merge($this->__backAssociation, $this->{$ass});
						unset($this->{$ass}[$model]);
					}
				}	
			}
		}	
		return true;
	}
	
	/**
	 * Returns the subselect options
	 * 
	 * For example if the project subselect changes and you want the new corresponding
	 * manufacturers use:
	 * 
	 * $options = $this->ItemSubtypeVersion->Project->getOptions($id, 'Project', 'Manufacturer', 'name');
	 *  
	 */
	
	public function getOptions($ids, $model) {
		$options = array();
		$conditions = array();
		$ItemSubtypeVersionView = ClassRegistry::init('ItemSubtypeVersionView');
		
		switch($model) {
			case 'Project':
				$User = ClassRegistry::init('User');
				$conditions['OR']['project_id'] = $User->getUsersProjects();
				
				$nField	= 'project_name';
				$iField	= 'project_id';
				
				break;
			case 'Manufacturer':
				$conditions['OR']['project_id'] = $ids['project'];
				
				$nField	= 'manufacturer_name';
				$iField	= 'manufacturer_id';
				
				break;
			case 'ItemType':				
				$conditions['AND']['ItemSubtypeVersionView.project_id'] 		= $ids['project'];
				$conditions['AND']['ItemSubtypeVersionView.manufacturer_id'] 	= $ids['manufacturer'];
				
				$nField	= 'item_type_name';
				$iField	= 'item_type_id';
				
				break;
			case 'ItemSubtype':				
				$conditions['AND']['ItemSubtypeVersionView.project_id'] 		= $ids['project'];
				$conditions['AND']['ItemSubtypeVersionView.manufacturer_id'] 	= $ids['manufacturer'];
				$conditions['AND']['ItemSubtypeVersionView.item_type_id'] 		= $ids['itemType'];
				
				$nField	= 'item_subtype_name';
				$iField	= 'item_subtype_id';
				
				break;
			case 'ItemSubtypeVersion':				
				$conditions['AND']['ItemSubtypeVersionView.project_id'] 		= $ids['project'];
				$conditions['AND']['ItemSubtypeVersionView.manufacturer_id'] 	= $ids['manufacturer'];
				$conditions['AND']['ItemSubtypeVersionView.item_type_id']		= $ids['itemType'];
				$conditions['AND']['ItemSubtypeVersionView.item_subtype_id']	= $ids['itemSubtype'];
				
				$nField	= 'item_subtype_version_version';
				$iField	= 'item_subtype_version_id';
				
				break;
		}

		$results = $ItemSubtypeVersionView->find('all', array('conditions' => $conditions, 'group' => ($iField)));
		
		if(empty($results)) {
			$options[] = array(
								'name'  => 'Nothing found',
								'value' => -1,
								'title' => 'Nothing found',
								'disabled' => true
							);
			return $options;
		}

		foreach($results as $result){
			$v = $result['ItemSubtypeVersionView'];
			$options[$v[$iField]] = array(
														'name'  => $v[$nField],
														'value' => $v[$iField],
														'title' => $v[$nField]
													);
			$this->_aasort($options, 'name');
		}

		return $options;
	}
	
	/**
	 * Sorts an array by its nested value chosen with $key
	 * 
	 * example:
	 * 	$autos[0] = array(Mercedes 	=> (Farbe => "rot", PS = 350));
	 * 	$autos[1] = array(Porsche	=> (Farbe => "blau", PS = 275));
	 * 
	 *  _aasort($autos, "Farbe") gives:
	 * 
	 * 	$autos[0] = array(Porsche	=> (Farbe => "blau", PS = 275));
	 * 	$autos[1] = array(Mercedes 	=> (Farbe => "rot", PS = 350));	 
	 */
	
	protected function _aasort (&$array, $key) {
	    $sorter=array();
	    $ret=array();
	    reset($array);
	    foreach ($array as $ii => $va) {	    	
	        $sorter[$ii]=$va[$key];
	    }
	    asort($sorter);
	    foreach ($sorter as $ii => $va) {
	        $ret[$ii]=$array[$ii];
	    }
	    $array=$ret;
	}
}
