<div class="histories index">
	
	<div class="search">
		<?php echo $this->Form->create('History');?>
			<fieldset>
				<?php echo $this->Form->input('search_term', array('div' => false, 'label' => 'Search comment')); ?>
				<div>
					<?php echo $this->Form->submit(__('Search'), array('div' => false)); ?>
				</div>
			</fieldset>
		<?php echo $this->Form->end(); ?>
	</div>
	
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('item_id');?></th>
			<th><?php echo $this->Paginator->sort('event_id');?></th>
			<th><?php echo $this->Paginator->sort('user_id');?></th>
			<th><?php echo $this->Paginator->sort('comment');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($histories as $history): ?>
	<tr>
		<td><?php echo h($history['History']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($history['Item']['code'], array('controller' => 'items', 'action' => 'view', $history['Item']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($history['Event']['name'], array('controller' => 'events', 'action' => 'view', $history['Event']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($history['User']['username'], array('controller' => 'users', 'action' => 'view', $history['User']['id'])); ?>
		</td>
		<td><?php echo $history['History']['comment']; ?>&nbsp;</td>
		<td><?php echo h($history['History']['created']); ?>&nbsp;</td>
		<td><?php echo h($history['History']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $history['History']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $history['History']['id']), null, __('Are you sure you want to delete # %s?', $history['History']['id'])); ?>
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
	<h2><?php echo __('History'); ?></h2>
	<ul>
		<li class='active'><?php echo $this->Html->link(__('Events'), array('controller' => 'events', 'action' => 'index')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
