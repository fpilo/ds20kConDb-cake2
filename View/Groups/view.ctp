<div class="groups view">
	<fieldset>
		<legend><?php echo "Group: ".$group['Group']['name']; ?></legend>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($group['Group']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Name'); ?></dt>
			<dd>
				<?php echo h($group['Group']['name']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Created'); ?></dt>
			<dd>
				<?php echo h($group['Group']['created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modified'); ?></dt>
			<dd>
				<?php echo h($group['Group']['modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</fieldset>
	
	<div class="related">
		<h3><?php echo __('Related Users');?></h3>
		<?php if (!empty($group['User'])):?>
		<table cellpadding = "0" cellspacing = "0">
		<tr>
			<th><?php echo __('Id'); ?></th>
			<th><?php echo __('Username'); ?></th>
			<th><?php echo __('Group Id'); ?></th>
			<th><?php echo __('Created'); ?></th>
			<th><?php echo __('Modified'); ?></th>
			<th class="actions"><?php echo __('Actions');?></th>
		</tr>
		<?php
			$i = 0;
			foreach ($group['User'] as $user): ?>
			<tr>
				<td><?php echo $user['id'];?></td>
				<td><?php echo $user['username'];?></td>
				<td><?php echo $user['group_id'];?></td>
				<td><?php echo $user['created'];?></td>
				<td><?php echo $user['modified'];?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'users', 'action' => 'view', $user['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'users', 'action' => 'edit', $user['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'users', 'action' => 'delete', $user['id']), null, __('Are you sure you want to delete # %s?', $user['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
		<?php endif; ?>
	</div>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Groups'); ?></h2>
	<ul>
		<li class='active'><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $group['Group']['id'])); ?> </li>
		<li class='active'><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $group['Group']['id']), null, __('Are you sure you want to delete %s?', $group['Group']['name'])); ?> </li>
		<li class='active last'><?php echo $this->Html->link(__('New Group'), array('action' => 'add')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>