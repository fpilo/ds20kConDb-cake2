<div class="itemQualities form">
<?php echo $this->Form->create('ItemQuality'); ?>
	<fieldset>
		<legend><?php echo __('Edit Item Quality'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('comment');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div id='verticalmenu'>
	<h2><?php echo __('Item Quality'); ?></h2>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('ItemQuality.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('ItemQuality.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Item Qualities'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Items'), array('controller' => 'items', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Item'), array('controller' => 'items', 'action' => 'add')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
