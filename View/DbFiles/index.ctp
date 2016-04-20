<div class="dbFiles index">
	<h2><?php echo __('Db Files');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('real_name');?></th>
			<th><?php echo $this->Paginator->sort('comment');?></th>
			<th><?php echo $this->Paginator->sort('size');?></th>
			<th><?php echo $this->Paginator->sort('type');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($dbFiles as $dbFile): ?>
	<tr>
		<td><?php echo h($dbFile['DbFile']['id']); ?>&nbsp;</td>
		<td><?php echo $this->Html->link($dbFile['DbFile']['name'], array('action' => 'view', $dbFile['DbFile']['id'])); ?>&nbsp;</td>
		<td><?php echo h($dbFile['DbFile']['real_name']); ?>&nbsp;</td>
		<td><?php echo h($dbFile['DbFile']['comment']); ?>&nbsp;</td>
		<td><?php echo h($dbFile['DbFile']['size']); ?>&nbsp;</td>
		<td><?php echo h($dbFile['DbFile']['type']); ?>&nbsp;</td>
		<td><?php echo h($dbFile['DbFile']['created']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $dbFile['DbFile']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $dbFile['DbFile']['id']), null, __('Are you sure you want to delete # %s?', $dbFile['DbFile']['id'])); ?>
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
<div class="actions">
	<h3><?php echo __('List'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Item Subtype Versions'), array('controller' => 'item_subtype_versions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Item Subtypes'), array('controller' => 'item_subtypes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Items'), array('controller' => 'items', 'action' => 'index')); ?> </li>
	</ul>
</div>
