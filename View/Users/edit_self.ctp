<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
		<legend><?php echo __('Edit user: '.$this->Form->value('User.username')); ?></legend>
	<table>
	<tr><td colspan="2"><?php echo $this->Form->input('id'); ?></td></tr>
	<tr><td colspan="2"><?php echo $this->Form->input('first_name'); ?></td></tr>
	<tr><td colspan="2"><?php echo $this->Form->input('last_name'); ?></td></tr>
	<tr><td colspan="2"><?php echo $this->Form->input('email'); ?></td></tr>
	<tr><td colspan="2"><?php echo $this->Form->input('phone'); ?></td></tr>
	<!--tr><td colspan="2"><?php echo $this->Form->input('username'); ?></td></tr-->
	<tr><td colspan="2"><?php echo $this->Form->input('comment', array('type' => 'textarea')); ?></td></tr>
	<tr><td colspan="2"><?php echo $this->Form->input('standard_location_id',array("options"=>$locations)); ?></td></tr>
	</table>
	</fieldset>
<?php echo $this->Form->end(__('Save changes'));?>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Users'); ?></h2>
	<ul>
		<li class='active'><?php echo $this->Html->link(__('Cancel'), array('action' => 'index')); ?> </li>
		<li class='active'><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('User.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('User.id'))); ?></li>
		<li class='active last'><?php echo $this->Html->link(__('Reset Password'), array('action' => 'resetPassword', $this->Form->value('User.id'))); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
