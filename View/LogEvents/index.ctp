<div class="logEvents index">
	<h2><?php echo __('Log Events');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('description');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($logEvents as $logEvent): ?>
	<tr>
		<td><?php echo h($logEvent['LogEvent']['id']); ?>&nbsp;</td>
		<td><?php echo $this->Html->link(__($logEvent['LogEvent']['name']), array('action' => 'view', $logEvent['LogEvent']['id'])); ?>&nbsp;</td>
		<td><?php echo h($logEvent['LogEvent']['description']); ?>&nbsp;</td>
		<td class="actions">			
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $logEvent['LogEvent']['id'])); ?>
			<?php //echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $logEvent['LogEvent']['id']), null, __('Are you sure you want to delete # %s?', $logEvent['LogEvent']['id'])); ?>
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
	<h2><?php  echo __('Log Events'); ?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('Logs'), array('controller' => 'logs', 'action' => 'index')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>