<div class="measurementTypes form">
<?php echo $this->Form->create('MeasurementType');?>
	<fieldset>
		<legend><?php echo __('Add Measurement Type'); ?></legend>
	<?php
	echo $this->Form->input('name');
	echo $this->Form->input('marker');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Measurement Types'); ?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('Cancel'), array('action' => 'index')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
