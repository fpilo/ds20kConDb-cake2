<?php
	$this->Html->addCrumb('Events', '/events');
	$this->Html->addCrumb('add', '/events/add');
?>

<div class="events form">
<?php echo $this->Form->create('Event');?>
	<fieldset>
		<legend><?php echo __('Add Event'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('description');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Events'); ?></h2>
	<ul>
		<li class='active last'><?php echo $this->Html->link(__('Cancel'), array('action' => 'index')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>