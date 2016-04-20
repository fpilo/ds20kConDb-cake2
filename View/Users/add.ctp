<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
		<legend><?php echo __('Add user'); ?></legend>
	<table>
	<tr><td colspan="2"><?php echo $this->Form->input('first_name'); ?></td></tr>
	<tr><td colspan="2"><?php echo $this->Form->input('last_name'); ?></td></tr>
	<tr><td colspan="2"><?php echo $this->Form->input('email'); ?></td></tr>
	<tr><td colspan="2"><?php echo $this->Form->input('username'); ?></td></tr>
	<tr><td colspan="2"><?php echo $this->Form->input('password'); ?></td></tr>
	<tr><td colspan="2"><?php echo $this->Form->input('password_check', array('type' => 'password')); ?></td></tr>
	<tr><td colspan="2"><?php echo $this->Form->input('group_id'); ?></td></tr>
	<tr><td colspan="2"><?php echo $this->Form->input('standard_location_id',array("options"=>$locations)); ?></td></tr>
	<tr><td><?php
				$after = $this->Form->input('add_projects', array('type' => 'checkbox', 'checked' => $this->Form->value('User.add_projects'), 'before' => 'Add new projects automatically ', 'label' => false, 'disabled' => false));
				echo $this->Form->input('Project', array('after' => $after, 'size' => 10)); ?>
		</td>
		<td><?php
				$after = $this->Form->input('add_locations', array('type' => 'checkbox', 'checked' => $this->Form->value('User.add_locations'), 'before' => 'Add new locations automatically ', 'label' => false, 'disabled' => false));
				echo $this->Form->input('Location', array('after' => $after, 'size' => 10)); ?>
	</td></tr>
	<tr><td colspan="2"><?php echo $this->Form->input('comment', array('type' => 'textarea')); ?></td></tr>
	</table>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Users'); ?></h2>
	<ul>
		<li class='active last'><?php echo $this->Html->link(__('Cancel'), array('action' => 'index')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>