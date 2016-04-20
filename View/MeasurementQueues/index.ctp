<div class="measurementQueues index">
	<h2><?php echo __('Measurement Queues'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('measurement_id'); ?></th>
			<th><?php echo $this->Paginator->sort('status'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($measurementQueues as $measurementQueue): ?>
	<tr>
		<td><?php echo h($measurementQueue['MeasurementQueue']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($measurementQueue['Measurement']['id'], array('controller' => 'measurements', 'action' => 'view', $measurementQueue['Measurement']['id'])); ?>
		</td>
		<td><?php echo h($measurementQueue['MeasurementQueue']['status']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $measurementQueue['MeasurementQueue']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $measurementQueue['MeasurementQueue']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $measurementQueue['MeasurementQueue']['id']), array(), __('Are you sure you want to delete # %s?', $measurementQueue['MeasurementQueue']['id'])); ?>
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
<div id='verticalmenu'>
	<ul>
		<li><?php echo $this->Html->link(__('List Measurements'), array('controller' => 'measurements', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Measurement'), array('controller' => 'measurements', 'action' => 'add')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
