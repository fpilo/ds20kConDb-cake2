<div class="measurementParameters view">
<h2><?php echo __('Measurement Parameter'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($measurementParameter['MeasurementParameter']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Measurement'); ?></dt>
		<dd>
			<?php echo $this->Html->link($measurementParameter['Measurement']['id'], array('controller' => 'measurements', 'action' => 'view', $measurementParameter['Measurement']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Parameter'); ?></dt>
		<dd>
			<?php echo $this->Html->link($measurementParameter['Parameter']['name'], array('controller' => 'parameters', 'action' => 'view', $measurementParameter['Parameter']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Value'); ?></dt>
		<dd>
			<?php echo h($measurementParameter['MeasurementParameter']['value']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div id='verticalmenu'>
	<h2><?php echo __('Measurement Parameters'); ?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Measurement Parameter'), array('action' => 'edit', $measurementParameter['MeasurementParameter']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Measurement Parameter'), array('action' => 'delete', $measurementParameter['MeasurementParameter']['id']), array(), __('Are you sure you want to delete # %s?', $measurementParameter['MeasurementParameter']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Measurement Parameters'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Measurement Parameter'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Measurements'), array('controller' => 'measurements', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Measurement'), array('controller' => 'measurements', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Parameters'), array('controller' => 'parameters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Parameter'), array('controller' => 'parameters', 'action' => 'add')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
