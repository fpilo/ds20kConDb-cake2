<?php
	$this->Html->addCrumb('ClActions', '/clActions');
	$this->Html->addCrumb('Edit', '/clActions/edit/'.$this->data['ClAction']['id']);
?>

<div class="clActions form">
<?php echo $this->Form->create('ClAction');?>
	<fieldset>
		<legend><?php echo __('Edit Check list Action'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name', array('type' => 'text'));
		echo $this->Form->input('description', array('type' => 'text'));
		$hlevels = array('1'=>'1','2'=>'2');
		echo $this->Form->input('hierarchy_level', array(
															'type' => 'select',
															'options' => $hlevels, 
															'default' => '1', 
															'onchange' => 'toggleClStatesView(this)'
															)
								);
		
		unset($options); unset($selected);	
		$options=array(); $selected=array();
		foreach($this->data['ClState'] as $clstate){
			if($clstate['type']=="source"){
					
				$clstateid=$clstate['id'];
				$options[$clstateid]=$clstate['name'];
				$selected[$clstateid]=$clstateid;
						
			}			
		}
		foreach ($clSourceStates as $id => $name) {
					
			$optionsid=array_search($name,$options);
			if(FALSE == $optionsid){
				$options[$id]=$name;
			}
		}
		$seloptions = array(
						'type' => 'select',
						'size' => 5,
						'multiple' => true,
						'id' =>'source_state_id',
						'options' => $options,
						'selected' => $selected,
						'empty' => '(choose one)'
					);
		if($this->data['ClAction']['hierarchy_level']==2) $seloptions['style']='display:none';
		echo $this->Form->input('source_state_id', $seloptions);
								
		unset($options); unset($selected);	
		$options=array(); $selected=array();
		foreach($this->data['ClState'] as $clstate){
			if($clstate['type']=="target"){
					
				$clstateid=$clstate['id'];
				$options[$clstateid]=$clstate['name'];
				$selected[$clstateid]=$clstateid;
						
			}			
		}
		foreach ($clTargetStates as $id => $name) {
					
			$optionsid=array_search($name,$options);
			if(FALSE == $optionsid){
				$options[$id]=$name;
			}
		}
		$seloptions = array(
								'type' => 'select',
								'size' => 1,
								'multiple' => false,
								'id' =>'target_state_id',
								'options' => $options,
								'selected' => $selected,
								'empty' => '(choose one)'
							);
		if($this->data['ClAction']['hierarchy_level']==2) $seloptions['style']='display:none';
		echo $this->Form->input('target_state_id', $seloptions);
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('clActions');?></h2>
	<ul>
		<li class="active"><?php echo $this->Html->link(__('Cancel'), array('action' => 'view', $this->Form->value('ClAction.id'))); ?> </li>
		<li class="active"><?php echo $this->Html->link(__('Add new state'), array('controller' => 'clStates', 'action' => 'add')); ?> </li>
		<li class="active last"><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('ClAction.id')), null, __('Are you sure you want to delete %s?', $this->Form->value('ClAction.name'))); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>

<?php echo $this->Html->script(array('jquery-1.6.4.min'));?>
<script type='text/javascript'>
		
	function toggleClStatesView() {
	
		var hlevel = parseInt($("#ClActionEditForm select:first").val());
		switch(hlevel){
			case 1:
				$("#ClActionEditForm select:eq(1)").show();
				$("#ClActionEditForm select:eq(2)").show();
				break;
			case 2:
				$("#ClActionEditForm select:eq(1)").hide();
				$("#ClActionEditForm select:eq(2)").hide();
				break;
			default:
				break;
		}
	
	}
		
</script>