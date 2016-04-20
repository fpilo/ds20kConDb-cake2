<div class="measurementTypes index">
	<h1><?php echo __('Measurement Types');?></h1>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('marker',"Marker in CSV");?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($measurementTypes as $measurementType): ?>
	<tr>
		<td><?php echo h($measurementType['MeasurementType']['id']); ?>&nbsp;</td>
		<td><?php echo $this->Html->link(__($measurementType['MeasurementType']['name']), array('action' => 'view', $measurementType['MeasurementType']['id'])); ?>&nbsp;</td>
		<td><?php echo __($measurementType['MeasurementType']['marker']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $measurementType['MeasurementType']['id']), null, __('Are you sure you want to delete # %s?', $measurementType['MeasurementType']['id'])); ?>
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
	<h2><?php echo __('Measurement Types'); ?></h2>
	<ul>		
		<li><?php echo $this->Html->link(__('New'), array('action' => 'add')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
