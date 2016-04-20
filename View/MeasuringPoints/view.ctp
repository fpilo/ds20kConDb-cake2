<div class="measuringPoints view">
<h2><?php  echo __('Measuring Point');?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($measuringPoint['MeasuringPoint']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Measurement'); ?></dt>
		<dd>
			<?php echo $this->Html->link($measuringPoint['Measurement']['id'], array('controller' => 'measurements', 'action' => 'view', $measuringPoint['Measurement']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Measuring Point'), array('action' => 'edit', $measuringPoint['MeasuringPoint']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Measuring Point'), array('action' => 'delete', $measuringPoint['MeasuringPoint']['id']), null, __('Are you sure you want to delete # %s?', $measuringPoint['MeasuringPoint']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Measuring Points'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Measuring Point'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Measurements'), array('controller' => 'measurements', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Measurement'), array('controller' => 'measurements', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Readings'), array('controller' => 'readings', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Reading'), array('controller' => 'readings', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Readings');?></h3>
	<?php if (!empty($measuringPoint['Reading'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Measuring Point Id'); ?></th>
		<th><?php echo __('Parameter Id'); ?></th>
		<th><?php echo __('Value'); ?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($measuringPoint['Reading'] as $reading): ?>
		<tr>
			<td><?php echo $reading['id'];?></td>
			<td><?php echo $reading['measuring_point_id'];?></td>
			<td><?php echo $reading['parameter_id'];?></td>
			<td><?php echo $reading['value'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'readings', 'action' => 'view', $reading['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'readings', 'action' => 'edit', $reading['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'readings', 'action' => 'delete', $reading['id']), null, __('Are you sure you want to delete # %s?', $reading['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Reading'), array('controller' => 'readings', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
