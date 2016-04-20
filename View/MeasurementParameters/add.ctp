<div class="measurementParameters form">
<?php echo $this->Form->create('MeasurementParameter'); ?>
	<fieldset>
		<legend><?php echo __('Add Measurement Parameter'); ?></legend>
	<?php
		echo $this->Form->input('measurement_id');
		echo $this->Form->input('parameter_id');
		echo $this->Form->input('value');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div id='verticalmenu'>
	<h2><?php echo __('Measurement Parameters'); ?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('New Measurement Parameter'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Measurement Parameters'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Measurements'), array('controller' => 'measurements', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Measurement'), array('controller' => 'measurements', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Parameters'), array('controller' => 'parameters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Parameter'), array('controller' => 'parameters', 'action' => 'add')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
