<div class="itemTags form">
<?php echo $this->Form->create('ItemTag'); ?>
	<fieldset>
		<legend><?php echo __('Add Item Tag'); ?></legend>
	<?php
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div id='verticalmenu'>
	<h2><?php echo __('Item Tag'); ?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('List Item Tags'), array('action' => 'index')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
