<script type="text/javascript">
			$(function(){
				restoreTabs('LocationViewTabIndex');
			});
</script>

<div class="locations view">	
	<div id="tabs" class='related'>
		<ul>
			<li><a href="#information"><?php echo __('Information'); ?></a></li>
			<li><a href="#users"><?php echo __('Users'); ?></a></li>
		</ul>
		
		<div id="users">
			<?php if (!empty($location['User'])):?>
			<table cellpadding = "0" cellspacing = "0">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Username'); ?></th>
				<th><?php echo __('First name'); ?></th>
				<th><?php echo __('Last name'); ?></th>
				<th><?php echo __('Email'); ?></th>
				<th><?php echo __('Group'); ?></th>
			</tr>
			<?php
				foreach ($location['User'] as $user): ?>
				<tr>
					<td><?php echo $user['id'];?></td>
					<td><?php echo $this->Html->link($user['username'], array('controller' => 'users', 'action' => 'view', $user['id']));?></td>
					<td><?php echo $user['first_name'];?></td>
					<td><?php echo $user['last_name'];?></td>
					<td><?php echo $this->Text->autoLinkEmails($user['email']); ?></td>
					<td><?php echo $this->Html->link($user['Group']['name'], array('controller' => 'groups', 'action' => 'view', $user['Group']['id']));?></td>
				</tr>
				<?php endforeach; ?>
				</table>
			<?php endif; ?>
		</div>
		
		<div id="information">
			<dl>
				<dt><?php echo __('Id'); ?></dt>
				<dd>
					<?php echo h($location['Location']['id']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Name'); ?></dt>
				<dd>
					<?php echo h($location['Location']['name']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Full name'); ?></dt>
				<dd>
					<?php echo h($location['Location']['full_name']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Address'); ?></dt>
				<dd>
					<?php echo h($location['Location']['address']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Phone Number'); ?></dt>
				<dd>
					<?php echo h($location['Location']['phone_number']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Email'); ?></dt>
				<dd>
					<?php echo h($location['Location']['email']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Contact'); ?></dt>
				<dd>
					<?php echo h($location['Location']['contact']); ?>
					&nbsp;
				</dd>
			</dl>
		</div>
	</div>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('Locations');?></h2>
	<ul>
		<li class="active"><?php echo $this->Html->link(__('Edit Location'), array('action' => 'edit', $location['Location']['id'])); ?> </li>
		<li class="active"><?php echo $this->Form->postLink(__('Delete Location'), array('action' => 'delete', $location['Location']['id']), null, __('Are you sure you want to delete # %s?', $location['Location']['id'])); ?> </li>
		<li class="active last"><?php echo $this->Html->link(__('New Location'), array('action' => 'add')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>