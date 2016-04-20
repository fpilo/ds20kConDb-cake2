<div class="deliverers form">
<?php echo $this->Form->create('Deliverer');?>
	<fieldset>
		<legend><?php echo __('Edit Deliverer'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('homepage');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
<h2><?php echo __('Deliverer'); ?></h2>
	<ul>
		<li class='active'><?php echo $this->Form->postLink(__('Delete deliverer'), array('action' => 'delete', $this->Form->value('Deliverer.id')), null, __('Are you sure you want to delete %s?', $this->Form->value('Deliverer.name'))); ?></li>
	</ul>
	
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
