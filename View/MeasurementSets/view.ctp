<?php
#	debug($measurementType);
	$this->Html->addCrumb('Measurement Sets', '/measurementSets');
	$this->Html->addCrumb($measurementSet['MeasurementSet']['name'], '/measurementSets/view/'.$measurementSet['MeasurementSet']['id']);
?>

<div class="measurementSets view">
<h1><?php  echo __('Measurement Set'); ?></h1>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($measurementSet['MeasurementSet']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($measurementSet['MeasurementSet']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Comment'); ?></dt>
		<dd>
			<?php echo $measurementSet['MeasurementSet']['comment']; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Parameter Table'); ?></dt>
		<dd>
			<?php echo $measurementSet['MeasurementSet']['parameter_table']; ?>
			&nbsp;
		</dd>
	</dl>

	<div class="related">
		<h3><?php echo __('Contains'); ?></h3>
		<?php if (!empty($measurementSet['Measurement'])): ?>
		<table cellpadding = "0" cellspacing = "0">
		<tr>
			<th><?php echo __('Id'); ?></th>
			<th><?php echo __('History Id'); ?></th>
			<th><?php echo __('Item'); ?></th>
			<th><?php echo __('Measurement Setup'); ?></th>
			<th><?php echo __('User'); ?></th>
			<th><?php echo __('Measurement Type'); ?></th>
			<th><?php echo __('Measurement Tags'); ?></th>
			<th><?php echo __('Start'); ?></th>
			<th><?php echo __('Stop'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>
		<?php
			$i = 0;
			foreach ($measurementSet['Measurement'] as $measurement): ?>
			<tr>
				<td><?php echo $measurement['id']; ?></td>
				<td><?php echo $measurement['history_id']; ?></td>
				<td><?php echo $item[$measurement['item_id']]; ?></td>
				<td><?php echo $device[$measurement['device_id']]; ?></td>
				<td><?php echo $user[$measurement['user_id']]; ?></td>
				<td><?php echo $measurementType[$measurement['measurement_type_id']]; ?></td>
				<td>
					<?php if(isset($measurementTags[$measurement["id"]])>0): ?>
						<?php foreach($measurementTags[$measurement["id"]] as $tagId=>$tagName): ?>
							<?php echo $this->Html->link($tagName, array('controller' => 'measurement_tags', 'action' => 'view', $tagId)); ?>
						<?php endforeach; ?>
					<?php endif; ?>
				</td>
				<td><?php echo $measurement['start']; ?></td>
				<td><?php echo $measurement['stop']; ?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'measurements', 'action' => 'view', $measurement['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'measurements', 'action' => 'edit', $measurement['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'measurements', 'action' => 'delete', $measurement['id']), null, __('Are you sure you want to delete # %s?', $measurement['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>
	</div>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Measurement Sets'); ?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('Back'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $measurementSet['MeasurementSet']['id'])); ?></li>
		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $measurementSet['MeasurementSet']['id']), null, __('Are you sure you want to delete %s?', $this->Form->value('MeasurementSet.name'))); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
