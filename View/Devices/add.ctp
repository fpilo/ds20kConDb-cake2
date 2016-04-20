<div class="devices form">
<?php echo $this->Form->create('Device');?>
	<fieldset>
		<legend><?php echo __('Add Measurement Setup'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('location_id');
		echo $this->Form->input('MeasurementType');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('Measurement Setups'); ?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('Cancel'), array('action' => 'index'));?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
