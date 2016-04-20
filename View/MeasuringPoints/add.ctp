<div class="measuringPoints form">
<?php echo $this->Form->create('MeasuringPoint');?>
	<fieldset>
		<legend><?php echo __('Add Measuring Point'); ?></legend>
	<?php
		echo $this->Form->input('measurement_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Measuring Points'), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Measurements'), array('controller' => 'measurements', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Measurement'), array('controller' => 'measurements', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Readings'), array('controller' => 'readings', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Reading'), array('controller' => 'readings', 'action' => 'add')); ?> </li>
	</ul>
</div>
