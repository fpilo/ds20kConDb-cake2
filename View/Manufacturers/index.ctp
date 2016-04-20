<div class="manufacturers index">
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('address');?></th>
			<th><?php echo $this->Paginator->sort('phone_number');?></th>
			<th><?php echo $this->Paginator->sort('email');?></th>
			<th><?php echo $this->Paginator->sort('contact');?></th>
			<th>Project</th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($manufacturers as $manufacturer): ?>
	<tr>
		<td><?php echo h($manufacturer['Manufacturer']['id']); ?>&nbsp;</td>
		<td><?php echo $this->Html->link($manufacturer['Manufacturer']['name'], array('action' => 'view', $manufacturer['Manufacturer']['id'])); ?></td>
		<td><?php echo h($manufacturer['Manufacturer']['address']); ?>&nbsp;</td>
		<td><?php echo h($manufacturer['Manufacturer']['phone_number']); ?>&nbsp;</td>
		<td><?php echo $this->Text->autoLinkEmails($manufacturer['Manufacturer']['email']); ?>&nbsp;</td>
		<td><?php echo h($manufacturer['Manufacturer']['contact']); ?>&nbsp;</td>
		<td>
			<?php foreach($manufacturer['Project'] as $project): ?>
			<?php echo $this->Html->link($project['name'], array('controller' => 'projects', 'action' => 'view', $project['id'])); ?>&nbsp;
			<?php endforeach; ?>
		</td>
		<td class="actions">			
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $manufacturer['Manufacturer']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $manufacturer['Manufacturer']['id']), null, __('Are you sure you want to delete # %s?', $manufacturer['Manufacturer']['id'])); ?>
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
	<h2><?php  echo __('Manufacturers');?></h2>
	<ul>
		<li class="active last"><?php echo $this->Html->link(__('New'), array('action' => 'add')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>