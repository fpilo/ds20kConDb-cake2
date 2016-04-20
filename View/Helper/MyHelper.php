<?php

App::uses('AppHelper', 'View/Helper');

class MyHelper extends AppHelper {

	public $helpers = array('Form', 'Js' => array('Jquery'), 'Html', 'Session');
	public $showShortName = true;

	/**
	 * listItems method
	 *
	 * Input: $items Items with components
	 *
	 * @return array An array with components and data for the form needed for the addItemComposition
	 */

	public function listItems($items, $formName, $itemCode) {
		$items = $this->_getRecursiveList($items);

		foreach($items as $key => $item) {
			foreach($item['field_name'] as $fieldKey =>$fieldName) {
				$items[$key]['field_name'][$fieldKey] = 'data['.$formName.']['.$itemCode.']'.$fieldName;
			}
			$items[$key]['code'] = $itemCode.$item['code'];
		}

		return $items;
	}

	private function _getRecursiveList($items, $codePrefix = null, $fieldPrefix = null, $positionPrefix = null, $depth = 0) {
		$list = array();
		$i = 0;

		foreach($items as $item) {
			$newFieldPrefix = $fieldPrefix.'[Component]['.++$i.']';

			$position = $item['ItemSubtypeVersionsComposition']['position'];
			if($positionPrefix != null) {
				$position = $positionPrefix.'.'.$position;
			}

			if($this->showShortName)
				$code = $codePrefix .'_'.$item['ItemSubtype']['shortname'];
			else
				$code = $codePrefix;
			// add position if necessary for uniqueness. TODO: this is broken, assumes that shortnames are unique and always used in nameing
			//if($this->_positionNecessary($items, $item)) {
			if(TRUE) {
				$code .= '_'.$item['ItemSubtypeVersionsComposition']['position'];
			}

			$list[] = array(
						// this are the field names for the register formular
						'field_name' => array(
							'code' => $newFieldPrefix.'[code]',
							'valid' => $newFieldPrefix.'[valid]',
							'version' => $newFieldPrefix.'[item_subtype_version_id]',
							'position' => $newFieldPrefix.'[position]',
							'location' => $newFieldPrefix.'[location_id]',
							'state' => $newFieldPrefix.'[state_id]',
							'project' => $newFieldPrefix.'[project_id]',
							'attached' => $newFieldPrefix.'[isAttached]',
							'is_stock' => $newFieldPrefix.'[is_stock]'
						),

						// this are the values for the formular fields
						'code' => $code,
						'item_subtype_version_id' => $item['id'],
						'item_type_name' => $item['ItemSubtype']['ItemType']['name'],
						'item_subtype_name' => $item['ItemSubtype']['name'],
						'item_subtype_version' => $item['version'],
						'position' => $item['ItemSubtypeVersionsComposition']['position'],
						'abs_position' => $position,
						'location_id' => $item['location_id'],
						'state_id' => $item['state_id'],
						'project_id' => $item['ItemSubtypeVersionsComposition']['project_id'],
						'attached' => $item['ItemSubtypeVersionsComposition']['attached'],
						'is_stock' => $item['ItemSubtypeVersionsComposition']['is_stock'],
						'description' => $item['ItemSubtype']['ItemType']['name'] . ' ' . $item['ItemSubtype']['name'] . ' v' . $item['version'],
						'has_components' => $item['has_components'],
						'depth' => $depth,
						 );

			if($item['has_components'] > 0) {
				$component_list = $this->_getRecursiveList($item['Component'], $code, $newFieldPrefix, $position, $depth+1);
				$list = array_merge($list, $component_list);
			}
		}
		return $list;
	}

/*
 * If two items that have the same ItemSubtype are part of the same CompositeItem then the position number is requried in the item code for uniqueness
 * TODO: this is actually a lie! when creating an item that has parts and those are newly created as well it uses the shortname of the subtype
 * for the code, but this is problematic because the shortname is not unique (and codes have to be unique) and it can also be empty. Fix this.
 */
	private function _positionNecessary($items, $item) {
		foreach($items as $i) {
			if(($item['ItemSubtype']['id'] == $i['ItemSubtype']['id']) && ($item['ItemSubtypeVersionsComposition']['position'] != $i['ItemSubtypeVersionsComposition']['position']))
				return true;
		}
		return false;
	}

/**
 * Transforms the php variable $itemSubtypeVersions into json array.
 *
 * the jason array has the form [projects, manufacturers, itemtypes] where
 * each is a list of id=>value mappings to establish the full hierachy
 *
 * Here is an example:
 * Legend: n=name, p=project, m=manufacturer, t=itemtype, s=subtype, v=version
 * projects = {
 *     1:{"n":"Belle-II SVD"},
 *     2:{"n":"Belle-II PXD"},
 *     3:{"n":"CMS"},
 * };
 * manufacturers = {
 *     //HLL belongs to project 1 and 2
 *     1:{"n": "HLL", "p": [1, 2]},
 *     // Europractice to 1 and 3
 *     2:{"n": "Europractice", "p": [1, 3]},
 *     // and Bonn to 2 and 3
 *     3:{"n": "Universität Bonn", "p": [2, 3]},
 * };
 * itemtypes = {
 *     //The Itemtype sensor exists in projects 1 and 3
 *     1:{"n": "Sensor", "p": [1, 3],
 *         "s": {
 *             //And has just one subtype, Rectangle large
 *             1:{"n": "Rectangular-Large",
 *                 "v": {
 *                     //But there are three versions of this subtype from three
 *                     //different manufacturers and belonging to different
 *                     //projects
 *                     1:{"n": "1", "m": 2, "p": [1, 3]},
 *                     2:{"n": "2", "m": 1, "p": [1]},
 *                     3:{"n": "3", "m": 3, "p": [3]},
 *                 },
 *             },
 *         }
 *     }
 *    //And so forth with the remaining item types
 * };
 */
        public function toJson($itemSubtypeVersions) {
            //helper function to create an array element if it does not exist
            $create_if_needed = function(&$arr, $obj, $pid, $mid=null, $children=null){
                //The id is always just "id"
                $id = $obj['id'];
                if(!isset($arr[$id])){
                    //We need a new object so choose an appropriate name
                    if(isset($obj["version"])){
                    	//Is a subtype Version, check if a shortname is set and use the usual syntax
	                    $name = ($obj["name"] != "")?$obj["version"]." (".$obj["name"].")":$obj["version"];
                    }else{
                    	//No subtype version just use name
                    	$name = $obj["name"];
                    }
                    //And add an array element with the correct project id
                    $arr[$id] = array("n"=>$name, "p"=>array($pid=>true));
                    //If we have a manufactuerer id add it too
                    if(!is_null($mid)) $arr[$id]["m"] = array($mid=>true);
                    //And if we have children (which could be an s for subtypes
                    //or an v for versions) add them too
                    if(!is_null($children)) $arr[$id][$children] = array();
                }else{
                    //Already exists, just update project and manufacturer ids
                    $arr[$id]["p"][$pid] = true;
                    if(!is_null($mid)) $arr[$id]["m"][$mid] = true;
                }
                return $id;
            };

            //So we create three arrays
            $projects = array();
            $manufacturers = array();
            $itemtypes = array();
            //And start filling and project level
				
			if(!empty($itemSubtypeVersions)) {	
				foreach($itemSubtypeVersions['Project'] as $project) {
					$pid = $project["Project"]["id"];
					//There should not be an project with this id but let's make sure
					assert(!isset($projects[$pid]));
					//Ok, add project, it just has a name
					$projects[$pid] = array("n"=>$project['Project']['name']);
					//So go over all the manufacturer for that project and add them
					//to the list of manufacturers ...
					foreach($project['Manufacturer'] as $manufacturer) {
						$mid = $create_if_needed($manufacturers, $manufacturer["Manufacturer"], $pid);
						//and so forth for the items but they might have subtypes
						foreach($manufacturer["ItemType"] as $itemtype){
							$tid = $create_if_needed($itemtypes, $itemtype["ItemType"], $pid, $mid, "s");
							//which we loop over again adding them to the subtype array,
							//this time with versions as children
							foreach($itemtype["ItemSubtype"] as $subtype){
								$sid = $create_if_needed($itemtypes[$tid]['s'], $subtype["ItemSubtype"], $pid, $mid, "v");
								//and finally the versions
								foreach($subtype["ItemSubtypeVersion"] as $version){
									$create_if_needed($itemtypes[$tid]['s'][$sid]['v'], $version, $pid, $mid);
								}
							}
						}
					}
				}
			}

            //Done but there might be redundant information as we have project
            //for both, item type and version. So let's optimize this by
            //removing redundant information if the projects or manufacturer
            //are the same between parents and children.

            //Remove dependencies from object if the parent dependencies are
            //identical
            $optimize = function(&$arr, $name, $parent=null){
                if(!isset($arr[$name])) return $parent;
                $new = array_keys($arr[$name]);
                sort($new);
                if(!is_null($parent) && ($parent == $new)){
                    unset($arr[$name]);
                    return $parent;
                }else{
                    $arr[$name] = $new;
                    return $new;
                }
            };
            //Recursively optimize the dependencies to remove redundancies
            $finalize = function(&$arr, $projects=null, $manufacturers=null) use (&$finalize, $optimize) {
                foreach($arr as &$item){
                    $child_projects = $optimize($item, "p", $projects);
                    $child_manufacturers = $optimize($item, "m", $manufacturers);
                    if(isset($item["s"])) $finalize($item["s"], $child_projects, $child_manufacturers);
                    if(isset($item["v"])) $finalize($item["v"], $child_projects, $child_manufacturers);
                }
            };
            $finalize($manufacturers);
            $finalize($itemtypes);

			//Custom Sort function for the name field
			$sortByName = function($a,$b){
				$tmp[0] = $a["n"];
				$tmp[2] = $b["n"];
				asort($tmp);
				$bla = array_keys($tmp);
				return $bla[0]-1;
			};

			//Loop over itemtypes to also sort the subtypes
			foreach($itemtypes as $i=>$itemType){
				//While at it also sort the versions
				uasort($itemtypes[$i]["s"],$sortByName);
				foreach($itemType["s"] as $j=>$version){
#					debug($itemtypes[$i]["s"]);
					uasort($itemtypes[$i]["s"][$j]["v"],$sortByName);
				}
			}
			uasort($itemtypes,$sortByName);
            //Done, return json encoded array
            return json_encode(array($projects, $manufacturers, $itemtypes));
        }

/**
 * Transforms the php variable $itemSubtypeVersions into javascript arrays.
 */
	public function toJavascript($itemSubtypeVersions) {
		$out = "\n";
		$out .= "<script type='text/javascript'>\n";

		$out .= "var project = new Array();\n";
		$out .= "var manufacturer = new Array();\n";
		$out .= "var itemType = new Array();\n";
		$out .= "var itemSubtype = new Array();\n";
		$out .= "var itemSubtypeVersion = new Array();\n";

		$out .= "var changeLogUrl = '".$this->Html->url(array('controller' => 'itemSubtypes', 'action' => 'changelog'))."/';\n";

		if(!empty($itemSubtypeVersions)) {
			foreach($itemSubtypeVersions['Project'] as $project) {
				$project_id = $project['Project']['id'];
				$project_name = $project['Project']['name'];

				$out .= "project[".$project_id."] = new Option(\"".$project_name."\", ".$project_id.");\n";

				if(isset($project['Manufacturer'])) {

					$out .= "manufacturer[".$project_id."] = new Array();\n";
					$out .= "itemType[".$project_id."] = new Array();\n";
					$out .= "itemSubtype[".$project_id."] = new Array();\n";
					$out .= "itemSubtypeVersion[".$project_id."] = new Array();\n";

					foreach($project['Manufacturer'] as $manufacturer) {
						$manufacturer_id = $manufacturer['Manufacturer']['id'];
						$manufacturer_name = $manufacturer['Manufacturer']['name'];

						$out .= "manufacturer[".$project_id."][".$manufacturer_id."] = new Option(\"".$manufacturer_name."\", ".$manufacturer_id.");\n";

						if(isset($manufacturer['ItemType'])) {

							$out .= "itemType[".$project_id."][".$manufacturer_id."] = new Array();\n";
							$out .= "itemSubtype[".$project_id."][".$manufacturer_id."] = new Array();\n";
							$out .= "itemSubtypeVersion[".$project_id."][".$manufacturer_id."] = new Array();\n";

							foreach($manufacturer['ItemType'] as $itemType) {
								$itemType_id = $itemType['ItemType']['id'];
								$itemType_name = $itemType['ItemType']['name'];

								$out .= "itemType[".$project_id."][".$manufacturer_id."][".$itemType_id."] = new Option(\"".$itemType_name."\", ".$itemType_id.");\n";

								if(isset($itemType['ItemSubtype'])) {

									$out .= "itemSubtype[".$project_id."][".$manufacturer_id."][".$itemType_id."] = new Array();\n";
									$out .= "itemSubtypeVersion[".$project_id."][".$manufacturer_id."][".$itemType_id."] = new Array();\n";

									foreach($itemType['ItemSubtype'] as $itemSubtype) {
										$itemSubtype_id = $itemSubtype['ItemSubtype']['id'];
										$itemSubtype_name = $itemSubtype['ItemSubtype']['name'];

										$out .= "itemSubtype[".$project_id."][".$manufacturer_id."][".$itemType_id."][".$itemSubtype_id."] = new Option(\"".$itemSubtype_name."\", ".$itemSubtype_id.");\n";

										if(isset($itemSubtype['ItemSubtypeVersion'])) {

											$out .= "itemSubtypeVersion[".$project_id."][".$manufacturer_id."][".$itemType_id."][".$itemSubtype_id."] = new Array();\n";

											foreach($itemSubtype['ItemSubtypeVersion'] as $itemSubtypeVersion) {
												$itemSubtypeVersion_id = $itemSubtypeVersion['id'];
												$itemSubtypeVersion_name = $itemSubtypeVersion['version'];

												$out .= "itemSubtypeVersion[".$project_id."][".$manufacturer_id."][".$itemType_id."][".$itemSubtype_id."][".$itemSubtypeVersion_id."] = new Option(\"".$itemSubtypeVersion_name."\", ".$itemSubtypeVersion_id.");\n";
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		$out .= "</script>\n";
		return $out;
	}
	public function getDescriptionForStatus($status){
		switch ($status) {
			case 1:
				return "To be converted";
				break;
			case 2:
				return "To be imported";
				break;
			case 3:
				return "Import finished";
				break;
			default:
				return "Status not defined";
				break;
		}
	}

}
