<?php
	$this->Html->addCrumb('ClStates', '/clStates');
	$this->Html->addCrumb('View', '/clStates/view/'.$clState['ClState']['id']);
?>

<div class="clStates view">
	<fieldset>
	<legend>Information</legend>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($clState['ClState']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($clState['ClState']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($clState['ClState']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Type'); ?></dt>
		<dd>
			<?php echo h($clState['ClState']['type']); ?>
			&nbsp;
		</dd>
	</dl>
	</fieldset>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('States');?></h2>
	<ul>
		<li class="active"><?php echo $this->Html->link(__('Cancel'), array('action' => 'index')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
