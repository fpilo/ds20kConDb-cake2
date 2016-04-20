<div class="measurementTags form">
<?php echo $this->Form->create('MeasurementTag'); ?>
	<fieldset>
		<legend><?php echo __('Add Measurement Tag'); ?></legend>
	<?php
		echo $this->Form->input('name');
		// echo $this->Form->input('Measurement');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div id='verticalmenu'>
	<h2><?php echo __('Measurement Tags'); ?></h2>
	<ul>

		<li><?php echo $this->Html->link(__('List Measurement Tags'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('New Measurement'), array('controller' => 'measurements', 'action' => 'add')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
