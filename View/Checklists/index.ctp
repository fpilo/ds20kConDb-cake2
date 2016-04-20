<?php
	$this->Html->addCrumb('Checklists', '/checklists');
?>

<div class="Checklist index">
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('description');?></th>
			<th><?php echo $this->Paginator->sort('associated item');?></th>
			<th><?php echo $this->Paginator->sort('template');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($checklists as $checklist): ?>
	<tr>
		<?php $refStr = 'index_'.$checklist['Item']['id']; ?>
		
		<td><?php echo h($checklist['Checklist']['id']); ?>&nbsp;</td>
		<td><?php echo $this->Html->link(__($checklist['Checklist']['name']), array('action' => 'view', $checklist['Checklist']['id'])); ?>&nbsp;</td>
		<td><?php echo h($checklist['Checklist']['description']); ?>&nbsp;</td>
		<td><?php echo $this->Html->link(__($checklist['Item']['code']), array('controller' => 'items', 'action' => 'view', $checklist['Item']['id'])); ?>&nbsp;</td>
		<td><?php echo $this->Html->link(__($checklist['ClTemplate']['name']), array('controller' => 'clTemplates', 'action' => 'view', $checklist['ClTemplate']['id'])); ?>&nbsp;</td>
		<td class="actions">
			<?php if(isset($checklist['Item']['id'])): ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $checklist['Checklist']['id'], $refStr)); ?>
			<?php endif; ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $checklist['Checklist']['id']), null, __('Are you sure you want to delete # %s?', $checklist['Checklist']['id'])); ?>
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
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>