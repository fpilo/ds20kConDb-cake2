<div class="stocks form">
<?php echo $this->Form->create('Stock'); ?>
	<fieldset>
		<legend><?php echo __('Edit Stock'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('item_subtype_version_id');
		echo $this->Form->input('amount');
		echo $this->Form->input('state_id');
		echo $this->Form->input('stock_quality_id');
		echo $this->Form->input('Project');
		echo $this->Form->input('Location');
		echo $this->Form->input('StockTag');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('Stock');?></h2>
	<ul>
		<li class="active"><?php echo $this->Html->link(__('Cancel'), array('action' => 'index')); ?> </li>
		<li class="active last"><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Stock.id')), null, __('Are you sure you want to delete this stock?')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
