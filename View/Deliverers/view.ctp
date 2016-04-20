<script type="text/javascript">
			$(function(){
				restoreTabs('deliverersTabIndex');
			});
</script>
<div id="tabs" class='related deliverers view'>
		<ul>
			<li><a href="#information"><?php echo __('Information'); ?></a></li>
			<li><a href="#transfers"><?php echo __('Transfers'); ?></a></li>
		</ul>
		
		<div id="information">
			<dl>
				<dt><?php echo __('Id'); ?></dt>
				<dd>
					<?php echo h($deliverer['Deliverer']['id']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Name'); ?></dt>
				<dd>
					<?php echo h($deliverer['Deliverer']['name']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Homepage'); ?></dt>
				<dd>
					<?php echo h($deliverer['Deliverer']['homepage']); ?>
					&nbsp;
				</dd>
			</dl>
		</div>
		
		<div id="transfers">
			<h3><?php echo __('Related Transfers');?></h3>
			<?php if (!empty($deliverer['Transfer'])):?>
			<table cellpadding = "0" cellspacing = "0">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Shipping Date'); ?></th>
				<th><?php echo __('From Location'); ?></th>
				<th><?php echo __('To Location'); ?></th>
				<th><?php echo __('Tracking Number'); ?></th>
				<th><?php echo __('Link'); ?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
			<?php
				$i = 0;
				foreach ($deliverer['Transfer'] as $transfer): ?>
				<tr>
					<td><?php echo $transfer['id'];?></td>
					<td><?php echo $transfer['shipping_date'];?></td>
					<td><?php foreach($transfer['From'] as $from) echo $from['name'].' <br>';?></td>
					<td><?php echo $transfer['To']['name'];?></td>
					<td><?php echo $transfer['tracking_number'];?></td>
					<td><?php echo $transfer['link'];?></td>
					<td class="actions">
						<?php echo $this->Html->link(__('View'), array('controller' => 'transfers', 'action' => 'view', $transfer['id'])); ?>
						<?php echo $this->Html->link(__('Edit'), array('controller' => 'transfers', 'action' => 'edit', $transfer['id'])); ?>
						<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'transfers', 'action' => 'delete', $transfer['id']), null, __('Are you sure you want to delete # %s?', $transfer['id'])); ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</table>
		<?php endif; ?>
		</div>
</div>

<div id='verticalmenu'>
<h2><?php echo 'Deliverer: '.$deliverer['Deliverer']['name']; ?></h2>
	<ul>
		<li class='active'><?php echo $this->Html->link(__('Edit deliverer'), array('action' => 'edit', $deliverer['Deliverer']['id'])); ?> </li>
		<li class='active'><?php echo $this->Form->postLink(__('Delete deliverer'), array('action' => 'delete', $deliverer['Deliverer']['id']), null, __('Are you sure you want to delete # %s?', $deliverer['Deliverer']['id'])); ?> </li>
		<li class='active last'><?php echo $this->Html->link(__('Create new deliverer'), array('controller' => 'deliverers', 'action' => 'add')); ?></a></li>	
	</ul>
	
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
