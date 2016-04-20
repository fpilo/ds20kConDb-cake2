<div class="items form">
<?php echo $this->Form->create('Item');?>
	<fieldset>
	<legend><?php echo __('Changing the project of item '.$item['Item']['code']);?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('project_id');
		if($item['ItemSubtypeVersion']['has_components'] == 1) {
			echo $this->Form->input('recursive', array(
													'type' => 'checkbox', 
													'label' => 'Change components projects', 
													'title' => 'If checked the projects of all currently attached components will also change recursively'
												));
		}
		echo $this->Form->input('History.comment', array(
													'label' => 'The changes will be logged with an automatic generated comment. If necessary additional information can be inserted below and will be added as an extra comment.'
												));
													
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Change Project'); ?></h2>
	<ul>
		<li class="active last"><?php echo $this->Html->link(__('Cancel'), array('controller' => 'items', 'action' => 'view', $item['Item']['id'])); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>