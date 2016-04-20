<div class="items form">
<?php echo $this->Form->create('Item');?>
	<fieldset>
		<legend><?php echo __('Edit Item'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('code');
		echo $this->Form->input('project_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Item.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Item.id'))); ?></li>
		<li><?php echo $this->Html->link(__('Back to Item'), array('controller' => 'items', 'action' => 'view', $this->Form->value('Item.id'))); ?> </li>
	</ul>
</div>
