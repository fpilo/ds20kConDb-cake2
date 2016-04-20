<div class="measurementQueues view">
<h2><?php echo __('Measurement Queue'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($measurementQueue['MeasurementQueue']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Measurement'); ?></dt>
		<dd>
			<?php echo $this->Html->link($measurementQueue['Measurement']['id'], array('controller' => 'measurements', 'action' => 'view', $measurementQueue['Measurement']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('File Path'); ?></dt>
		<dd>
			<?php echo h($measurementQueue['MeasurementQueue']['file_path']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Status'); ?></dt>
		<dd>
			<?php echo h($measurementQueue['MeasurementQueue']['status']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div id='verticalmenu'>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Measurement Queue'), array('action' => 'edit', $measurementQueue['MeasurementQueue']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Measurement Queue'), array('action' => 'delete', $measurementQueue['MeasurementQueue']['id']), array(), __('Are you sure you want to delete # %s?', $measurementQueue['MeasurementQueue']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Measurement Queues'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('List Measurements'), array('controller' => 'measurements', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Measurement'), array('controller' => 'measurements', 'action' => 'add')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
