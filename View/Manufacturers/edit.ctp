<div class="manufacturers form">
<?php echo $this->Form->create('Manufacturer');?>
	<fieldset>
		<legend><?php echo __('Edit Manufacturer'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('address', array('type' => 'textarea'));
		echo $this->Form->input('phone_number');
		echo $this->Form->input('email');
		echo $this->Form->input('contact');
		echo $this->Form->input('Project', array('empty' => 'unselect'));
		echo $this->Form->input('comment', array('type' => 'textarea'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('Manufacturers');?></h2>
	<ul>
		<li class="active"><?php echo $this->Html->link(__('Cancel'), array('action' => 'view', $this->Form->value('Manufacturer.id')));?></li>
		<li class="active last"><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Manufacturer.id')), null, __('Are you sure you want to delete %s?', $this->Form->value('Manufacturer.name'))); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>