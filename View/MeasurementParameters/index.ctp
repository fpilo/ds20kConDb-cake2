<div class="measurementParameters index">
	<h2><?php echo __('Measurement Parameters'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('measurement_id'); ?></th>
			<th><?php echo $this->Paginator->sort('parameter_id'); ?></th>
			<th><?php echo $this->Paginator->sort('value'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($measurementParameters as $measurementParameter): ?>
	<tr>
		<td><?php echo h($measurementParameter['MeasurementParameter']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($measurementParameter['Measurement']['id'], array('controller' => 'measurements', 'action' => 'view', $measurementParameter['Measurement']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($measurementParameter['Parameter']['name'], array('controller' => 'parameters', 'action' => 'view', $measurementParameter['Parameter']['id'])); ?>
		</td>
		<td><?php echo h($measurementParameter['MeasurementParameter']['value']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $measurementParameter['MeasurementParameter']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $measurementParameter['MeasurementParameter']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $measurementParameter['MeasurementParameter']['id']), array(), __('Are you sure you want to delete # %s?', $measurementParameter['MeasurementParameter']['id'])); ?>
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
	<h2><?php echo __('Measurement Parameters'); ?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('New Measurement Parameter'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Measurements'), array('controller' => 'measurements', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Measurement'), array('controller' => 'measurements', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Parameters'), array('controller' => 'parameters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Parameter'), array('controller' => 'parameters', 'action' => 'add')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
