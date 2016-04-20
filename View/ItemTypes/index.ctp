<?php
	$this->Html->addCrumb('Item Types', '/item_types/index/');
?>
<div class="itemTypes index">
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('project');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
#	debug($itemTypes);
	foreach ($itemTypes as $itemType): ?>
	<tr>
		<td><?php echo h($itemType['ItemType']['id']); ?>&nbsp;</td>
		<td><?php echo $this->Html->link(__($itemType['ItemType']['name']), array('action' => 'view', $itemType['ItemType']['id'])); ?>&nbsp;</td>
		<td>
			<?php foreach ($itemType["Project"] as $project): ?>
				<?php echo $this->Html->link(__($project['name']), array('controller'=>'projects','action' => 'view', $project['id'])); ?>&nbsp;
			<?php endforeach; ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $itemType['ItemType']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $itemType['ItemType']['id']), null, __('Are you sure you want to delete # %s?', $itemType['ItemType']['id'])); ?>
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
	<h2><?php  echo __('Item Type');?></h2>
	<ul>
		<li class="active last"><?php echo $this->Html->link(__('New Item Type'), array('action' => 'add')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>