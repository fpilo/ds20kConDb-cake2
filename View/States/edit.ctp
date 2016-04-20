<div class="states form">
<?php echo $this->Form->create('State');?>
	<fieldset>
		<legend><?php echo __('Edit State'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('description');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('States');?></h2>
	<ul>
		<li class="active"><?php echo $this->Html->link(__('Cancel'), array('action' => 'view', $this->Form->value('State.id'))); ?> </li>
		<li class="active last"><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('State.id')), null, __('Are you sure you want to delete %s?', $this->Form->value('State.name'))); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
