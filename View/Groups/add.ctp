<div class="groups form">
<?php echo $this->Form->create('Group');?>
	<fieldset>
		<legend><?php echo __('Add Group'); ?></legend>
	<?php
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Groups'); ?></h2>
	<ul>
		<li class='active last'><?php echo $this->Html->link(__('Cancel'), array('action' => 'index')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>