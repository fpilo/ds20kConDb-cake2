<div class="parameters form">
<?php echo $this->Form->create('Parameter');?>
	<fieldset>
		<legend><?php echo __('Add Parameter'); ?></legend>
	<?php
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div id="verticalmenu">
	<h2><?php echo __('Actions'); ?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('List Parameters'), array('action' => 'index'));?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
