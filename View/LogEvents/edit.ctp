<div class="logEvents form">
<?php echo $this->Form->create('LogEvent');?>
	<fieldset>
		<legend><?php echo __('Edit Log Event'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('description');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Action'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('View'), array('action' => 'view', $this->Form->value('LogEvent.id')));?></li>
	</ul>
	
	<h3><?php echo __('List'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Log Events'), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('Logs'), array('controller' => 'logs', 'action' => 'index')); ?> </li>
	</ul>
</div>
