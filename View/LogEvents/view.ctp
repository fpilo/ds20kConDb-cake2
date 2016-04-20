<div class="logEvents view">
	<div>
		<h3><?php echo __('Info');?></h3>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($logEvent['LogEvent']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Name'); ?></dt>
			<dd>
				<?php echo h($logEvent['LogEvent']['name']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Description'); ?></dt>
			<dd>
				<?php echo h($logEvent['LogEvent']['description']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	
	<br>
	
	<div class="related">
		<h3><?php echo __('Last Logs');?></h3>
		<?php if (!empty($logs)):?>
		<table cellpadding = "0" cellspacing = "0">
		<tr>
			<th><?php echo __('Id'); ?></th>
			<th><?php echo __('User'); ?></th>
			<th><?php echo __('Comment'); ?></th>
			<th><?php echo __('Created'); ?></th>
			<th class="actions"><?php echo __('Actions');?></th>
		</tr>
		<?php
			$i = 0;
			foreach ($logs as $log): ?>
			<tr>
				<td><?php echo $log['Log']['id'];?></td>
				<td><?php echo $log['User']['username'];?></td>
				<td><?php echo $log['Log']['comment'];?></td>
				<td><?php echo $log['Log']['created'];?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'logs', 'action' => 'view', $log['Log']['id'])); ?>					
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
	<?php endif; ?>
	</div>
</div>

<div id='verticalmenu'>
	<span><?php  echo __('Log Event: '); ?></span><br>
	<span><?php  echo __($logEvent['LogEvent']['name']);?></span>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
