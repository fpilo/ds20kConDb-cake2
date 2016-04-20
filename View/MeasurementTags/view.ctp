<div class="measurementTags view">
<h2><?php echo __('Measurement Tag'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($measurementTag['MeasurementTag']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($measurementTag['MeasurementTag']['name']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div id='verticalmenu'>
	<h2><?php echo __('Measurement Tags'); ?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Measurement Tag'), array('action' => 'edit', $measurementTag['MeasurementTag']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Measurement Tag'), array('action' => 'delete', $measurementTag['MeasurementTag']['id']), array(), __('Are you sure you want to delete # %s?', $measurementTag['MeasurementTag']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Measurement Tags'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Measurement Tag'), array('action' => 'add')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
<div class="related view">
	<h3><?php echo __('Related Measurements'); ?></h3>
	<?php if (!empty($measurementTag['Measurement'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('History Id'); ?></th>
		<th><?php echo __('Item Id'); ?></th>
		<th><?php echo __('Measurement Setup Id'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Measurement Type Id'); ?></th>
		<th><?php echo __('Start'); ?></th>
		<th><?php echo __('Stop'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($measurementTag['Measurement'] as $measurement): ?>
		<tr>
			<td><?php echo $measurement['id']; ?></td>
			<td><?php echo $measurement['history_id']; ?></td>
			<td><?php echo $measurement['item_id']; ?></td>
			<td><?php echo $measurement['device_id']; ?></td>
			<td><?php echo $measurement['user_id']; ?></td>
			<td><?php echo $measurement['measurement_type_id']; ?></td>
			<td><?php echo $measurement['start']; ?></td>
			<td><?php echo $measurement['stop']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'measurements', 'action' => 'view', $measurement['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'measurements', 'action' => 'edit', $measurement['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'measurements', 'action' => 'delete', $measurement['id']), array(), __('Are you sure you want to delete # %s?', $measurement['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Measurement'), array('controller' => 'measurements', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
