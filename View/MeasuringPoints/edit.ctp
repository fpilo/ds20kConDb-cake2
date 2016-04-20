<div class="measuringPoints form">
<?php echo $this->Form->create('MeasuringPoint');?>
	<fieldset>
		<legend><?php echo __('Edit Measuring Point'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('measurement_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('MeasuringPoint.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('MeasuringPoint.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Measuring Points'), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Measurements'), array('controller' => 'measurements', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Measurement'), array('controller' => 'measurements', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Readings'), array('controller' => 'readings', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Reading'), array('controller' => 'readings', 'action' => 'add')); ?> </li>
	</ul>
</div>
