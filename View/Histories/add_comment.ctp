<div class="histories form">
<?php echo $this->Form->create('History');?>
	<fieldset>
		<legend><?php echo __('Add Comment to '.$item['Item']['code']); ?></legend>
	<?php
		echo $this->Form->hidden('item_id', array('value' => $item_id));
		echo $this->Form->input('comment');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Adding Comment'); ?></h2>
	<ul>
		<li class='active'><?php echo $this->Html->link(__('Back to Item'), array('controller' => 'items', 'action' => 'view', $item_id)); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
