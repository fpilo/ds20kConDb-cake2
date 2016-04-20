<?php
	$this->Html->addCrumb('Measurement Sets', '/measurement_sets');
	$this->Html->addCrumb('Add', '/measurement_sets/add');
?>

<div class="measurementSets form">
<?php echo $this->Form->create('MeasurementSet'); ?>
	<fieldset>
		<legend><?php echo __('Add Measurement Set'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('comment');
		echo $this->Form->input('Measurement');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div  id="verticalmenu">
	<h2><?php echo __('Measurement Sets'); ?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('Cancel'), array("action"=>"index")); ?></li>
		<li><?php echo $this->Html->link(__('List Measurement Sets'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Measurements'), array('controller' => 'measurements', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Measurement'), array('controller' => 'measurements', 'action' => 'add')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
