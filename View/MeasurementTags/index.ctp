<div class="measurementTags index">
	<h2><?php echo __('Measurement Tags'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($measurementTags as $measurementTag): ?>
	<tr>
		<td><?php echo h($measurementTag['MeasurementTag']['id']); ?>&nbsp;</td>
		<td><?php echo h($measurementTag['MeasurementTag']['name']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $measurementTag['MeasurementTag']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $measurementTag['MeasurementTag']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $measurementTag['MeasurementTag']['id']), array(), __('Are you sure you want to delete # %s?', $measurementTag['MeasurementTag']['id'])); ?>
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
	<h2><?php echo __('Measurement Tags'); ?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('New Measurement Tag'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('New Measurement'), array('controller' => 'measurements', 'action' => 'add')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
