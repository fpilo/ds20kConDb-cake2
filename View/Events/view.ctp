<?php
	$this->Html->addCrumb('Events', '/events');
	$this->Html->addCrumb($event['Event']['name'], '/events/view/'.$event['Event']['id']);
?>

<div class="events view">
	<h1><?php  echo __('Event information');?></h1>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($event['Event']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Type'); ?></dt>
		<dd>
			<?php echo h($event['Event']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($event['Event']['description']); ?>
			&nbsp;
		</dd>
	</dl>
	<br>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Events'); ?></h2>
	<ul>
		<li class='active'><?php echo $this->Html->link(__('List Events'), array('action' => 'index')); ?> </li>
		<li class='active'><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $event['Event']['id'])); ?> </li>
		<li class='active'><?php echo $this->Form->postLink(__('Delete Event'), array('action' => 'delete', $event['Event']['id']), null, __('Are you sure you want to delete "%s"?', $event['Event']['name'])); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>