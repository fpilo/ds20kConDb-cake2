<div class="measurementQueues form">
<?php echo $this->Form->create('MeasurementQueue'); ?>
	<fieldset>
		<legend><?php echo __('Edit Measurement Queue'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('measurement_id');
		echo $this->Form->input('file_path');
		echo $this->Form->input('status');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div id='verticalmenu'>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('MeasurementQueue.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('MeasurementQueue.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Measurement Queues'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Measurements'), array('controller' => 'measurements', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Measurement'), array('controller' => 'measurements', 'action' => 'add')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
