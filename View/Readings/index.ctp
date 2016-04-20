<div class="readings index">
	<h2><?php echo __('Readings');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('measuring_point_id');?></th>
			<th><?php echo $this->Paginator->sort('parameter_id');?></th>
			<th><?php echo $this->Paginator->sort('value');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($readings as $reading): ?>
	<tr>
		<td><?php echo h($reading['Reading']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($reading['MeasuringPoint']['id'], array('controller' => 'measuring_points', 'action' => 'view', $reading['MeasuringPoint']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($reading['Parameter']['name'], array('controller' => 'parameters', 'action' => 'view', $reading['Parameter']['id'])); ?>
		</td>
		<td><?php echo h($reading['Reading']['value']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $reading['Reading']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $reading['Reading']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $reading['Reading']['id']), null, __('Are you sure you want to delete # %s?', $reading['Reading']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Reading'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Measuring Points'), array('controller' => 'measuring_points', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Measuring Point'), array('controller' => 'measuring_points', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Parameters'), array('controller' => 'parameters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Parameter'), array('controller' => 'parameters', 'action' => 'add')); ?> </li>
	</ul>
</div>
