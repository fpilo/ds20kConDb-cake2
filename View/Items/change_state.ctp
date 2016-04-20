<div class="items form">
<?php echo $this->Form->create('Item');?>
	<fieldset>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('state_id');
		echo $this->Form->input('History.comment');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Edit '.$this->Form->value('Item.code')). ':'; ?></h2>
	<ul>
		<li class="active last"><?php echo $this->Html->link(__('Cancel'), array('controller' => 'items', 'action' => 'view', $this->Form->value('Item.id'))); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
