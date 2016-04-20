<div class="ClActions form">
<?php echo $this->Form->create('ClAction');?>

	<fieldset>
		<legend><?php echo __('Add Action'); ?></legend>
	<?php
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
		echo $this->Form->input('source_state_id', array(
															'size' => 5,
															'multiple' => true,
															'options' => $clSourceStates,
															'onchange' => 'SelectAll(this)',
															'id' =>'source_state_id',
															'empty' => '(choose at least one)'
														)
								);
		echo $this->Form->input('target_state_id', array(
															'size' => 1,
															'multiple' => false,
															'options' => $clTargetStates,
															'onchange' => 'SelectAll(this)',
															'id' =>'source_state_id',
															'empty' => '(choose one)'
														)
								);

	?>
	</fieldset>

<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('Actions');?></h2>
	<ul>
		<li class="active last"><?php echo $this->Html->link(__('Cancel'), array('action' => 'index')); ?> </li>
		<li class="active"><?php echo $this->Html->link(__('Add new state'), array('controller' => 'clStates', 'action' => 'add')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>

<?php echo $this->Html->script(array('jquery-1.6.4.min'));?>
<script type='text/javascript'>
		
	function toggleClStatesView() {
	
		var hlevel = parseInt($("#ClActionAddForm select:first").val());
		switch(hlevel){
			case 1:
				$("#ClActionAddForm select:eq(1)").show();
				$("#ClActionAddForm select:eq(2)").show();
				break;
			case 2:
				$("#ClActionAddForm select:eq(1)").hide();
				$("#ClActionAddForm select:eq(2)").hide();
				break;
			default:
				break;
		}
	
	}
		
</script>