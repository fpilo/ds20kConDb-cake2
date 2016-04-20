<div class="devices index">
	<h2><?php echo __('Measurement Setups');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('location_id');?></th>
			<th>Assigned Measurement-Types</th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($devices as $device): ?>
	<tr>
		<td><?php echo h($device['Device']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link(__($device['Device']['name']), array('action' => 'view', $device['Device']['id'])); ?>&nbsp;			
		</td>
		<td>
			<?php echo $this->Html->link($device['Location']['name'], array('controller' => 'locations', 'action' => 'view', $device['Location']['id'])); ?>
		</td>
		<td>
			<?php 
				$output = array();
				foreach($device["MeasurementType"] as $mType){
					$output[] = $this->Html->link($mType['name']."(".$mType['marker'].")", array('controller' => 'measurement_types', 'action' => 'view', $mType["id"]));
				}
				echo implode(", ",$output);
			?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $device['Device']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $device['Device']['id']), null, __('Are you sure you want to delete %s?', $device['Device']['name'])); ?>
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
	<h2><?php  echo __('Measurement Setups'); ?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('New'), array('action' => 'add')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
