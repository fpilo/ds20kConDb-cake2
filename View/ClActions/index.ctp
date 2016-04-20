<?php
	$this->Html->addCrumb('ClActions', '/clActions');
?>

<div class="ClActions index">
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('description');?></th>
			<th><?php echo $this->Paginator->sort('hierarchy_level'); ?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($clActions as $clAction): ?>
	<tr>
		<td><?php echo h($clAction['ClAction']['id']); ?>&nbsp;</td>
		<td><?php echo $this->Html->link(__($clAction['ClAction']['name']), array('action' => 'view', $clAction['ClAction']['id'])); ?>&nbsp;</td>
		<td><?php echo h($clAction['ClAction']['description']); ?>&nbsp;</td>
		<td><?php echo h($clAction['ClAction']['hierarchy_level']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $clAction['ClAction']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $clAction['ClAction']['id']), null, __('Are you sure you want to delete # %s?', $clAction['ClAction']['id'])); ?>
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
	<h2><?php  echo __('Actions');?></h2>
	<ul>
		<li class="active last"><?php echo $this->Html->link(__('Add'), array('action' => 'add')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>