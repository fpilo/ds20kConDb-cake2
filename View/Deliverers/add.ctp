<div class="deliverers form">
<?php echo $this->Form->create('Deliverer');?>
	<fieldset>
		<legend><?php echo __('Add Deliverer'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('homepage');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
<h2><?php echo __('Deliverer'); ?></h2>
	<ul>
		<li class='active'><?php echo $this->Html->link(__('Cancel'), array('action' => 'index')); ?></li>
	</ul>
	
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
