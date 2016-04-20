<div class="matchings index">
	<h2><?php echo __('Matchings'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('parameter_id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($matchings as $matching): ?>
	<tr>
		<td><?php echo h($matching['Matching']['id']); ?>&nbsp;</td>
		<td><?php echo h($matching['Matching']['name']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($matching['Parameter']['name'], array('controller' => 'parameters', 'action' => 'view', $matching['Parameter']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $matching['Matching']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $matching['Matching']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $matching['Matching']['id']), array(), __('Are you sure you want to delete # %s?', $matching['Matching']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
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
	<h2><?php  echo __('Matching');?></h2>
	<ul>
		<li class="active last"><?php echo $this->Html->link(__('New'), array('action' => 'add')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
