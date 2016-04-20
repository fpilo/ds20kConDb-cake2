<?php
	$this->Html->addCrumb('User info', '/users/settings/'.$this->Session->read('User.User.id'));
	$this->Html->addCrumb('Change standard location', '/users/changeStandardLocation');
?>

<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
		<legend><?php echo __('Change '. Inflector::humanize($this->Session->read('User.User.username')) .'\'s standard location'); ?></legend>
		This is the location that is selected as the default location in the search selector. You may change this for yourself if you are now at a different location.<br />
		This location also influences which transfers you're allowed to receive but not which are visible to you.
	<?php
		echo $this->Form->input('id', array('value' => $this->Session->read('User.User.id')));
		echo $this->Form->input('standard_location_id',array("options"=>$locations));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Save changes'));?>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Change standard location'); ?></h2>
	<ul>
		<li class="active last"><?php echo $this->Html->link(__('Cancel'), array('action' => 'settings'));?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>