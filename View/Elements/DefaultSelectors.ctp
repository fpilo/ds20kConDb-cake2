<?php

	$id = array(
				'project'				=> 'project_id',
				'manufacturer'			=> 'manufacturer_id',
				'itemType'				=> 'item_type_id',
				'itemSubtype'			=> 'item_subtype_id',
				'itemSubtypeVersion'	=> 'item_subtype_version_id',
				'location'				=> 'location_id',
				'state'					=> 'state_id'
			);

	$default = array(
				'div' 		=> false,
				'size' 		=> 8,
				'multiple' 	=> false,
				'onchange' 	=> 'SubselectChanged(this)',
				'style'		=> 'width: 100%'
			);

	if(!empty($htmlOptions['group'])) {
		foreach($id as $key => $value) {
			$id[$key] = $htmlOptions['group'].'_'.$value;
		}
	}

	if(isset($htmlOptions['multiple']) && ($htmlOptions['mulitple'] == true)) {
		$default['empty'] = '(Select all)';
	}

	$row1 = array();

	$individual = array(
				'controller'=> 'project',
				'label' 	=> 'Project',
				'id'		=> $id['project'],
				'options'	=> $options['project'],
				'childId'	=> $id['manufacturer']
			);
	$row1[] = $this->Form->input($id['project'], array_merge($default, $htmlOptions, $individual));

	$individual = array(
				'controller'=> 'manufacturer',
				'label' 	=> 'Manufacturer',
				'id'		=> $id['manufacturer'],
				'options'	=> $options['manufacturer'],
				'childId'	=> $id['itemType']
			);
	$row1[] = $this->Form->input($id['manufacturer'], array_merge($default, $htmlOptions, $individual));

	$individual = array(
				'controller'=> 'itemType',
				'label' 	=> 'ItemType',
				'id'		=> $id['itemType'],
				'options'	=> $options['itemType'],
				'childId'	=> $id['itemSubtype']
			);
	$row1[] = $this->Form->input($id['itemType'], array_merge($default, $htmlOptions, $individual));

	$individual = array(
				'controller'=> 'itemSubtype',
				'label' 	=> 'ItemSubtype',
				'id'		=> $id['itemSubtype'],
				'options'	=> $options['itemSubtype'],
				'childId'	=> $id['itemSubtypeVersion']
			);
	$row1[] = $this->Form->input($id['itemSubtype'], array_merge($default, $htmlOptions, $individual));

	$individual = array(
				'controller'=> 'itemSubtypeVersion',
				'label' 	=> 'ItemSubtypeVersion',
				'id'		=> $id['itemSubtypeVersion'],
				'options'	=> $options['itemSubtypeVersion']
			);
	$dummy = $this->Form->input($id['itemSubtypeVersion'], array_merge($default, $htmlOptions, $individual));

	$dummy .= '<br>'.$this->Html->link(__('Show Changelog'), array('controller' => 'itemSubtypes', 'action' => 'changelog'), array('id' => 'show_changelog', 'target' => '_blank'));
	$row1[] = $dummy;

	$row2 = array();

	if(isset($locations)) {
		if(empty($filter['location_id'])) {
			$filter['location_id'] = '';
		}
		$individual = array(
				'controller'=> 'location',
				'label' 	=> 'Location',
				'id'		=> $id['location'],
				//'options'	=> $options['itemType']
			);
		$row2[] = $this->Form->input($id['location'], array_merge($default, $htmlOptions, $individual));
	}

	if(isset($states)) {
		if(empty($filter['state_id'])) {
			$filter['state_id'] = '';
		}
		$individual = array(
				'controller'=> 'state',
				'label' 	=> 'State',
				'id'		=> $id['state'],
				//'options'	=> $options['itemType']
			);
		$row2[] = $this->Form->input($id['state'], array_merge($default, $htmlOptions, $individual));
	}
?>

<?php
	echo $this->Html->tableCells(array($row1, $row2));
?>