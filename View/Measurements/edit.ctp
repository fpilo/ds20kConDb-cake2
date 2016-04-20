<div class="measurements form">
<?php echo $this->Form->create('Measurement');?>
	<fieldset>
		<legend><?php echo __('Edit Measurement'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('item_id');
		echo $this->Form->input('device_id');
		echo $this->Form->input('measurement_type_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div id='verticalmenu'>
	<h2><?php echo __('Actions'); ?></h2>
	<ul>
		<li><?php echo $this->Form->postLink(__('Delete Measurement'), array('action' => 'delete', $this->Form->value('Measurement.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Measurement.id'))); ?> </li>
		<li><?php echo $this->Html->link(__('New Measurement'), array('action' => 'add')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>