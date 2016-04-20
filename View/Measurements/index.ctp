<?php
	$this->Html->addCrumb('Measurements', '/measurements');
?>

<div class="measurements index">
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th>&nbsp;</th>
			<th><?php echo $this->Paginator->sort('item_id');?></th>
			<th>Tags</th>
			<th><?php echo $this->Paginator->sort('device_id',"Measurement Setup");?></th>
			<th><?php echo $this->Paginator->sort('user_id');?></th>
			<th><?php echo $this->Paginator->sort('measurement_type_id');?></th>
			<th>Measurement Status</th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($measurements as $measurement): ?>
	<tr>
		<td><?php echo $this->Form->checkbox('measurement',array("value"=>$measurement['Measurement']['id'],'hiddenField'=>false)); ?></td>
		<td>
			<?php echo $this->Html->link($measurement['Item']['code'], array('controller' => 'items', 'action' => 'view', $measurement['Item']['id'])); ?>
		</td>
		<td>
			<?php if(count($measurement["MeasurementTag"])>0): ?>
				<?php foreach($measurement["MeasurementTag"] as $tag): ?>
					<?php echo $this->Html->link($tag["name"], array('controller' => 'measurement_tags', 'action' => 'view', $tag['id'])); ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</td>
		<td>
			<?php echo $this->Html->link($measurement['Device']['name'], array('controller' => 'devices', 'action' => 'view', $measurement['Device']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($measurement['User']['username'], array('controller' => 'users', 'action' => 'view', $measurement['User']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($measurement['MeasurementType']['name'], array('controller' => 'measurement_types', 'action' => 'view', $measurement['MeasurementType']['id'])); ?>
		</td>
		<td>
			<?php echo ($measurement["Measurement"]["measurement_file_id"]==null)? "":"Converted"; ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $measurement['Measurement']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $measurement['Measurement']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $measurement['Measurement']['id']), null, __('Are you sure you want to delete # %s?', $measurement['Measurement']['id'])); ?>
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
	<h2><?php echo __('Measurements'); ?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('Add'), array('action' => 'add')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
