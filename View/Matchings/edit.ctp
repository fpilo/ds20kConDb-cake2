<div class="matchings form">
<?php echo $this->Form->create('Matching'); ?>
	<fieldset>
		<legend><?php echo __('Edit Matching'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('parameter_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div id='verticalmenu'>
	<h2><?php  echo __('Matching');?></h2>
	<ul>
		<li class="active last"><?php echo $this->Html->link(__('New'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Matching.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('Matching.id'))); ?></li>
		<li class="active last"><?php echo $this->Html->link(__('List'), array('action' => 'index')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
