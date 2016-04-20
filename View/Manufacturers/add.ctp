<div class="manufacturers form">
<?php echo $this->Form->create('Manufacturer');?>
	<fieldset>
		<legend><?php echo __('Add Manufacturer'); ?></legend>
	<?php
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
		<li class="active last"><?php echo $this->Html->link(__('Cancel'), array('action' => 'index')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>