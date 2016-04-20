<div class="parameters form">
<?php echo $this->Form->create('Parameter');?>
	<fieldset>
		<legend><?php echo __('Edit Parameter'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div id='verticalmenu'>
	<h2><?php echo __('Actions'); ?></h2>
	<ul>
		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Parameter.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Parameter.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Parameters'), array('action' => 'index'));?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
