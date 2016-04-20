<div class="states index">
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('description');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($states as $state): ?>
	<tr>
		<td><?php echo h($state['State']['id']); ?>&nbsp;</td>
		<td><?php echo $this->Html->link(__($state['State']['name']), array('action' => 'view', $state['State']['id'])); ?>&nbsp;</td>
		<td><?php echo h($state['State']['description']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $state['State']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $state['State']['id']), null, __('Are you sure you want to delete # %s?', $state['State']['id'])); ?>
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
	<h2><?php  echo __('States');?></h2>
	<ul>
		<li class="active last"><?php echo $this->Html->link(__('New'), array('action' => 'add')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>