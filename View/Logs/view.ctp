<div class="logs view">
	<h3><?php echo __('Log data'); ?></h3>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($log['Log']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($log['User']['username'], array('controller' => 'users', 'action' => 'view', $log['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Log Event'); ?></dt>
		<dd>
			<?php echo $this->Html->link($log['LogEvent']['name'], array('controller' => 'log_events', 'action' => 'view', $log['LogEvent']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Comment'); ?></dt>
		<dd>
			<?php echo h($log['Log']['comment']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($log['Log']['created']); ?>
			&nbsp;
		</dd>
	</dl>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Log'); ?></h2>
	<ul>
		<li class="active last"><?php echo $this->Html->link(__('Log Events'), array('controller' => 'log_events', 'action' => 'index')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
