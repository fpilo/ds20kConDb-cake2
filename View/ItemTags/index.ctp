<div class="itemTags index">
	<h1><?php echo __('Item Tags'); ?></h1>
	This overview shows all available item tags that can be assigned. Deletions here are final and remove the item tag from the system leading to information loss. <br />
	You can use the "View" button to list all items that have a certain tag assigned but it is recommended to use the Inventory page for this. Click "Items" to get there and use the search boxes to select your desired tag.
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($itemTags as $itemTag): ?>
	<tr>
		<td><?php echo h($itemTag['ItemTag']['id']); ?>&nbsp;</td>
		<td><?php echo h($itemTag['ItemTag']['name']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $itemTag['ItemTag']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $itemTag['ItemTag']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $itemTag['ItemTag']['id']), array(), __('BEWARE!!! Deletion of the Tag "%s" will delete all assignments of this tag to all items in the database. This Action is not reversible and can lead to a lot of information being lost!!!!', $itemTag['ItemTag']['name'])); ?>
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
	<h2><?php echo __('Item Tag'); ?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('New Item Tag'), array('action' => 'add')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
