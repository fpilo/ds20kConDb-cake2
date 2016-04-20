<div class="locations form">
<?php echo $this->Form->create('Location');?>
	<fieldset>
		<legend><?php echo __('Edit Location'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('full_name');
		echo $this->Form->input('address', array('type' => 'textarea'));
		echo $this->Form->input('phone_number');
		echo $this->Form->input('email');
		echo $this->Form->input('contact');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('Locations');?></h2>
	<ul>
		<li class="active"><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Location.id')), null, __('Are you sure you want to delete %s?', $this->Form->value('Location.name'))); ?></li>
		<li class="active last"><?php echo $this->Html->link(__('Cancel'), array('action' => 'view', $this->Form->value('Location.id'))); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>