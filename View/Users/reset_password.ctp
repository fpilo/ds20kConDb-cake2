<?php
	$this->Html->addCrumb('Users', '/users');
	$this->Html->addCrumb($this->Form->value('User.username'), '/users/view/'.$this->Form->value('User.id'));
	$this->Html->addCrumb('reset password', '/users/resetPassword/'.$this->Form->value('User.id'));
?>

<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
		<legend><?php echo __('Change '. Inflector::humanize($this->Form->value('User.username')) .'\'s password:'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('username',array("type"=>"hidden"));
		echo $this->Form->input('password', array('value' => '',"label"=>"New password"));
		// echo $this->Form->input('password_check', array('type' => 'password'));
	?>
	<div class="input password required">
		<label for="UserPasswordCheck">New password check</label>
		<input name="data[User][password_check]" type="password" value="" id="UserPasswordCheck"/>
	</div>
	</fieldset>
<?php echo $this->Form->end(__('Save changes'));?>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('User: '.$this->Form->value('User.username')); ?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('Cancel'), array('action' => 'view', $this->Form->value('User.id')));?></li>
		<li><?php echo $this->Html->link(__('Back to User List'), array('action' => 'index'));?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>