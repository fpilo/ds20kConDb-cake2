<?php
	$this->Html->addCrumb('ClStates', '/clStates');
?>

<div class="ClStates index">
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('description');?></th>
			<th><?php echo $this->Paginator->sort('type');?></th>
			<th><?php echo $this->Paginator->sort('clActionID');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($clStates as $clState): ?>
	<tr>
		<?php if($clState[0]['count']==2) $clState['ClState']['type'] = 'source&target'; ?>
		<td><?php echo h($clState['ClState']['id']); ?>&nbsp;</td>
		<td><?php echo $this->Html->link(__($clState['ClState']['name']), array('action' => 'view', $clState['ClState']['id'])); ?>&nbsp;</td>
		<td><?php echo h($clState['ClState']['description']); ?>&nbsp;</td>
		<td><?php echo h($clState['ClState']['type']); ?>&nbsp;</td>
		<td><?php echo h($clState['ClState']['cl_action_id']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $clState['ClState']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $clState['ClState']['id']), null, __('Are you sure you want to delete # %s?', $clState['ClState']['id'])); ?>
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
	<h2><?php  echo __('ClStates');?></h2>
	<ul>
		<li class="active last"><?php echo $this->Html->link(__('Add'), array('action' => 'add')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>