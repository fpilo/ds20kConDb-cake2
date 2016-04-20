<?php
	$this->Html->addCrumb('User info', '/users/settings/'.$this->Session->read('User.User.id'));
	$this->Html->addCrumb('change password', '/users/changePassword');
?>

<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
		<legend><?php echo __('Change '. Inflector::humanize($this->Session->read('User.User.username')) .'\'s password'); ?></legend>
	<?php
		echo $this->Form->input('id', array('value' => $this->Session->read('User.User.id')));
		echo $this->Form->input('password',array("label"=>"New password"));
	?>
	<div class="input password required">
		<label for="UserPasswordCheck">New password check</label>
		<input name="data[User][password_check]" type="password" value="" id="UserPasswordCheck"/>
	</div>
	</fieldset>
<?php echo $this->Form->end(__('Save changes'));?>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Change password'); ?></h2>
	<ul>
		<li class="active last"><?php echo $this->Html->link(__('Cancel'), array('action' => 'settings'));?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>