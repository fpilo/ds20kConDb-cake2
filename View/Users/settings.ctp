<?php
	$this->Html->addCrumb('User info', '/users/settings/'.$user['User']['id']);
?>

<div class="users view">
	<fieldset>
		<legend>
			User info
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
			<dt><?php echo __('Standard Location'); ?></dt>
			<dd>
				<?php echo $this->Html->link($user['StandardLocation']['name'], array('controller' => 'locations', 'action' => 'view', $user['StandardLocation']['id'])); ?>
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
</div>

<div id='verticalmenu'>
	<h2><?php echo __(Inflector::humanize($this->Session->read('User.User.username'))); ?></h2>
	<ul>
		<li class='active last'><?php echo $this->Html->link(__('Change Password'), array('action' => 'changePassword'));?></li>
		<li class='active last'><?php echo $this->Html->link(__('Change Standard Location'), array('action' => 'changeStandardLocation'));?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
