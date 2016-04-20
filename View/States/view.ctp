<div class="states view">
	<fieldset>
	<legend>Information</legend>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($state['State']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($state['State']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($state['State']['description']); ?>
			&nbsp;
		</dd>
	</dl>
	</fieldset>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('States');?></h2>
	<ul>
		<li class="active"><?php echo $this->Html->link(__('New'), array('action' => 'add')); ?></li>
		<li class="active"><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $state['State']['id'])); ?> </li>
		<li class="active last"><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $state['State']['id']), null, __('Are you sure you want to delete %s?', $state['State']['name'])); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
