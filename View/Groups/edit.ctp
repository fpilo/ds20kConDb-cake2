<div class="groups form">
<?php echo $this->Form->create('Group');?>
	<fieldset>
		<legend><?php echo __('Edit Group'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Groups'); ?></h2>
	<ul>
		<li class='active'><?php echo $this->Html->link(__('Cancel'), array('action' => 'index')); ?> </li>
		<li class='active last'><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Group.id')), null, __('Are you sure you want to delete %s?', $this->Form->value('Group.name'))); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>