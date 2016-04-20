<script type="text/javascript">
	$(function(){
		var $tabs = $('#tabs').tabs();
	});
</script>

<div class="measurementTypes view">
	<div id="tabs" class='related'>
		<ul>
			<li><a href="#info"><?php echo __('Info'); ?></a></li>
			<li><a href="#measurements"><?php echo __('Measurements'); ?></a></li>
		</ul>

		<div id="info">
			<dl>
				<dt><?php echo __('Id'); ?></dt>
				<dd>
					<?php echo h($measurementType['MeasurementType']['id']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Name'); ?></dt>
				<dd>
					<?php echo h($measurementType['MeasurementType']['name']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Marker'); ?></dt>
				<dd>
					<?php echo h($measurementType['MeasurementType']['marker']); ?>
					&nbsp;
				</dd>
			</dl>
		</div>

		<div id="measurements">
			<?php if (!empty($measurements[0])):?>
			<table cellpadding = "0" cellspacing = "0">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('History Id'); ?></th>
				<th><?php echo __('Item'); ?></th>
				<th><?php echo __('Measurement Setup'); ?></th>
				<th><?php echo __('User'); ?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
			<?php
#				debug($measurementType2);
				$i = 0;
				foreach ($measurements as $measurement): ?>
				<tr>

					<td><?php echo $this->Html->link(__($measurement["Measurement"]['id']), array("controller"=>"measurements",'action' => 'view', $measurement["Measurement"]['id'])); ?></td>
					<td><?php echo $this->Html->link(__($measurement["History"]['id']), array("controller"=>"histories",'action' => 'view', $measurement["History"]['id'])); ?></td>
					<td><?php echo $this->Html->link(__($measurement["Item"]['code']), array("controller"=>"items",'action' => 'view', $measurement["Item"]['id'])); ?></td>
					<td><?php echo $this->Html->link(__($measurement["Device"]['name']), array("controller"=>"devices",'action' => 'view', $measurement["Device"]['id'])); ?></td>
					<td><?php echo $this->Html->link(__($measurement["User"]['username']), array("controller"=>"users",'action' => 'view', $measurement["User"]['id'])); ?></td>
					<td class="actions">
						<?php echo $this->Html->link(__('View'), array('controller' => 'measurements', 'action' => 'view', $measurement["Measurement"]['id'])); ?>
						<?php echo $this->Html->link(__('Edit'), array('controller' => 'measurements', 'action' => 'edit', $measurement["Measurement"]['id'])); ?>
						<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'measurements', 'action' => 'delete', $measurement["Measurement"]['id']), null, __('Are you sure you want to delete # %s?', $measurement["Measurement"]['id'])); ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</table>
		<?php endif; ?>
		</div>
	</div>
</div>

<div id='verticalmenu'>
	<span><?php  echo __('Measurement Type:'); ?></span>
	<span><?php  echo __($measurementType['MeasurementType']['name']);?></span>
	<ul>
		<li><?php echo $this->Html->link(__('Back'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $measurementType['MeasurementType']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $measurementType['MeasurementType']['id']), null, __('Are you sure you want to delete # %s?', $measurementType['MeasurementType']['id'])); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
