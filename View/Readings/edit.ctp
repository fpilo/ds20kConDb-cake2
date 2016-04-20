<div class="readings form">
<?php echo $this->Form->create('Reading');?>
	<fieldset>
		<legend><?php echo __('Edit Reading'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('measuring_point_id');
		echo $this->Form->input('parameter_id');
		echo $this->Form->input('value');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Reading.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Reading.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Readings'), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Measuring Points'), array('controller' => 'measuring_points', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Measuring Point'), array('controller' => 'measuring_points', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Parameters'), array('controller' => 'parameters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Parameter'), array('controller' => 'parameters', 'action' => 'add')); ?> </li>
	</ul>
</div>
