<div class="parameters view">
<h2><?php  echo __('Parameter');?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($parameter['Parameter']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($parameter['Parameter']['name']); ?>
			&nbsp;
		</dd>
	</dl>

	<br>

	<div class="related">
		<h3><?php echo __('Related Readings');?></h3>
		<?php if (!empty($parameter['Reading'])):?>
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
			foreach ($parameter['Reading'] as $reading): ?>
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
	</div>
</div>
<div id='verticalmenu'>
	<h2><?php echo __('Actions'); ?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $parameter['Parameter']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $parameter['Parameter']['id']), null, __('Are you sure you want to delete # %s?', $parameter['Parameter']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('New'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Parameters'), array('action' => 'index'));?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>

