<?php
	$this->Html->addCrumb('Users', '/users');
	$this->Html->addCrumb($user['User']['username'], '/users/view/'.$user['User']['id']);
?>

<div class="users view">
	<fieldset>
		<legend>
			Information
		</legend>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($user['User']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Username'); ?></dt>
			<dd>
				<?php echo h($user['User']['username']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('First Name'); ?></dt>
			<dd>
				<?php echo h($user['User']['first_name']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Last Name'); ?></dt>
			<dd>
				<?php echo h($user['User']['last_name']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('email'); ?></dt>
			<dd>
				<?php echo $this->Text->autoLinkEmails($user['User']['email']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Group'); ?></dt>
			<dd>
				<?php echo $this->Html->link($user['Group']['name'], array('controller' => 'groups', 'action' => 'view', $user['Group']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Locations'); ?></dt>
			<dd>
			<?php
			foreach ($user['Location'] as $location): ?>
					<?php echo $this->Html->link($location['name'], array('controller' => 'locations', 'action' => 'view', $location['id'])); ?>				
			<?php endforeach?>
			&nbsp;
			</dd>
			<dt><?php echo __('Projects'); ?></dt>
			<dd>
			<?php
			foreach ($user['Project'] as $project): ?>
					<?php echo $this->Html->link($project['name'], array('controller' => 'projects', 'action' => 'view', $project['id'])); ?>
			<?php endforeach?>
			&nbsp;
			</dd>
			<dt><?php echo __('Comment'); ?></dt>
			<dd>
				<?php echo h($user['User']['comment']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Created'); ?></dt>
			<dd>
				<?php echo h($user['User']['created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modified'); ?></dt>
			<dd>
				<?php echo h($user['User']['modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</fieldset>
	
	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link('Show Logs', array('controller' => 'users', 'action' => 'viewLog', $user['User']['id'])); ?></li>
		</ul>
	</div>
	
	<div class="related">
		<h3><?php echo __('History');?></h3>
		<?php if (!empty($history)):?>
		<table cellpadding = "0" cellspacing = "0">
		<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('Event.name', 'Log Event');?></th>
			<th><?php echo $this->Paginator->sort('comment');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
		</tr>
		<?php
			$i = 0;
			foreach ($history as $h): ?>
			<tr>
				<td><?php echo $h['History']['id'];?></td>
				<td><?php echo $h['Event']['name'];?></td>
				<td><?php echo $h['History']['comment'];?></td>
				<td><?php echo $h['History']['created'];?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'histories', 'action' => 'view', $h['History']['id'])); ?>					
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
	<?php endif; ?>
	</div>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('User: '.$user['User']['username']); ?></h2>
	<ul>
		<li class='active'><?php echo $this->Html->link(__('Edit User'), array('action' => 'edit', $user['User']['id'])); ?> </li>
		<li class='active'><?php echo $this->Form->postLink(__('Delete User'), array('action' => 'delete', $user['User']['id']), null, __('Are you sure you want to delete # %s?', $user['User']['id'])); ?> </li>
		<li class='active'><?php echo $this->Html->link(__('Reset Password'), array('action' => 'resetPassword', $user['User']['id'])); ?> </li>
		<li class='active last'><?php echo $this->Html->link(__('Add User'), array('action' => 'add')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>