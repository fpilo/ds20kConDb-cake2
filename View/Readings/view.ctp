<div class="readings view">
<h2><?php  echo __('Reading');?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($reading['Reading']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Measuring Point'); ?></dt>
		<dd>
			<?php echo $this->Html->link($reading['MeasuringPoint']['id'], array('controller' => 'measuring_points', 'action' => 'view', $reading['MeasuringPoint']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Parameter'); ?></dt>
		<dd>
			<?php echo $this->Html->link($reading['Parameter']['name'], array('controller' => 'parameters', 'action' => 'view', $reading['Parameter']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Value'); ?></dt>
		<dd>
			<?php echo h($reading['Reading']['value']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Reading'), array('action' => 'edit', $reading['Reading']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Reading'), array('action' => 'delete', $reading['Reading']['id']), null, __('Are you sure you want to delete # %s?', $reading['Reading']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Readings'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Reading'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Measuring Points'), array('controller' => 'measuring_points', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Measuring Point'), array('controller' => 'measuring_points', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Parameters'), array('controller' => 'parameters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Parameter'), array('controller' => 'parameters', 'action' => 'add')); ?> </li>
	</ul>
</div>
