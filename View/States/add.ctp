<div class="states form">
<?php echo $this->Form->create('State');?>
	<fieldset>
		<legend><?php echo __('Add State'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('description');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('States');?></h2>
	<ul>
		<li class="active last"><?php echo $this->Html->link(__('Cancel'), array('action' => 'index')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>