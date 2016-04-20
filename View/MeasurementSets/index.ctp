<?php
	$this->Html->addCrumb('Measurement Sets', '/measurementSets');
?>

<div class="measurementSets index">
	<h2><?php echo __('Measurement Sets'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('comment'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($measurementSets as $measurementSet): ?>
	<tr>
		<td><?php echo h($measurementSet['MeasurementSet']['id']); ?>&nbsp;</td>
        <td><?php echo $this->Html->link(__($measurementSet['MeasurementSet']['name']), array('action' => 'view', $measurementSet['MeasurementSet']['id'])); ?>&nbsp;</td>
        <td><?php echo $measurementSet['MeasurementSet']['comment']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $measurementSet['MeasurementSet']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $measurementSet['MeasurementSet']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $measurementSet['MeasurementSet']['id']), array(), __('Are you sure you want to delete # %s?', $measurementSet['MeasurementSet']['id'])); ?>
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
<div id="verticalmenu">
	<h2><?php echo __('Measurement Sets'); ?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('Add'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Measurements'), array('controller' => 'measurements', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Measurement'), array('controller' => 'measurements', 'action' => 'add')); ?> </li>
	</ul>
    <?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
