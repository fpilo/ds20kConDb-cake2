<div class="measuringPoints index">
	<h2><?php echo __('Measuring Points');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('measurement_id');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($measuringPoints as $measuringPoint): ?>
	<tr>
		<td><?php echo h($measuringPoint['MeasuringPoint']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($measuringPoint['Measurement']['id'], array('controller' => 'measurements', 'action' => 'view', $measuringPoint['Measurement']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $measuringPoint['MeasuringPoint']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $measuringPoint['MeasuringPoint']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $measuringPoint['MeasuringPoint']['id']), null, __('Are you sure you want to delete # %s?', $measuringPoint['MeasuringPoint']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Measuring Point'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Measurements'), array('controller' => 'measurements', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Measurement'), array('controller' => 'measurements', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Readings'), array('controller' => 'readings', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Reading'), array('controller' => 'readings', 'action' => 'add')); ?> </li>
	</ul>
</div>
