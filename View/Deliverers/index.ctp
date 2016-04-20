<div class="deliverers index">
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('homepage');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($deliverers as $deliverer): ?>
	<tr>
		<td><?php echo h($deliverer['Deliverer']['id']); ?>&nbsp;</td>
		<td><?php echo $this->Html->link($deliverer['Deliverer']['name'], array('action' => 'view', $deliverer['Deliverer']['id'])); ?>&nbsp;</td>
		<td><?php echo h($deliverer['Deliverer']['homepage']); ?>&nbsp;</td>
		<td class="actions">			
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $deliverer['Deliverer']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $deliverer['Deliverer']['id']), null, __('Are you sure you want to delete %s?', $deliverer['Deliverer']['name'])); ?>
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
<h2><?php echo __('Deliverers'); ?></h2>
	<ul>
		<li class='active'><?php echo $this->Html->link(__('Create new deliverer'), array('controller' => 'deliverers', 'action' => 'add')); ?></a></li>
	</ul>
	
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>