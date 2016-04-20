<div class="itemSubtypeVersions index">
	<h2><?php echo __('Item Subtype Versions');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('version');?></th>
			<th><?php echo $this->Paginator->sort('manufacturer_id');?></th>
			<th><?php echo $this->Paginator->sort('comment');?></th>
			<th><?php echo $this->Paginator->sort('has_components');?></th>
			<th><?php echo $this->Paginator->sort('item_subtype_id');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($itemSubtypeVersions as $itemSubtypeVersion): ?>
	<tr>
		<td><?php echo h($itemSubtypeVersion['ItemSubtypeVersion']['id']); ?>&nbsp;</td>
		<td><?php echo h($itemSubtypeVersion['ItemSubtypeVersion']['version']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($itemSubtypeVersion['Manufacturer']['name'], array('controller' => 'manufacturers', 'action' => 'view', $itemSubtypeVersion['Manufacturer']['id'])); ?>
		</td>
		<td><?php echo h($itemSubtypeVersion['ItemSubtypeVersion']['comment']); ?>&nbsp;</td>
		<td><?php echo h($itemSubtypeVersion['ItemSubtypeVersion']['has_components']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($itemSubtypeVersion['ItemSubtype']['name'], array('controller' => 'item_subtypes', 'action' => 'view', $itemSubtypeVersion['ItemSubtype']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $itemSubtypeVersion['ItemSubtypeVersion']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $itemSubtypeVersion['ItemSubtypeVersion']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $itemSubtypeVersion['ItemSubtypeVersion']['id']), null, __('Are you sure you want to delete # %s?', $itemSubtypeVersion['ItemSubtypeVersion']['id'])); ?>
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
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Item Subtype Version'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Manufacturers'), array('controller' => 'manufacturers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Manufacturer'), array('controller' => 'manufacturers', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Item Subtypes'), array('controller' => 'item_subtypes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Item Subtype'), array('controller' => 'item_subtypes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Item Subtype Version Compositions'), array('controller' => 'item_subtype_version_compositions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Item Subtype Version Composition'), array('controller' => 'item_subtype_version_compositions', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Items'), array('controller' => 'items', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Item'), array('controller' => 'items', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Long Blobs'), array('controller' => 'long_blobs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Long Blob'), array('controller' => 'long_blobs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Item Subtype Versions'), array('controller' => 'item_subtype_versions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Component'), array('controller' => 'item_subtype_versions', 'action' => 'add')); ?> </li>
	</ul>
</div>
