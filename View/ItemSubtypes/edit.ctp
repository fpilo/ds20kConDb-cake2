<?php
	// $this->Html->addCrumb('Item Types', '/item_types/index/');
	$this->Html->addCrumb($this->data['ItemType']['name'], '/item_types/view/'.$this->data['ItemType']['id']);
	// $this->Html->addCrumb('Item Subtypes', '/item_subtypes/index/');
	$this->Html->addCrumb($this->data['ItemSubtype']['name'], '/item_subtypes/view/'.$this->data['ItemSubtype']['id']);
?>
<div class="itemSubtypes form">
<?php echo $this->Form->create('ItemSubtype');?>
	<fieldset>
		<legend><?php echo __('Edit Item Subtype'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('shortname');
		echo $this->Form->input('comment', array('type' => 'textarea'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('Item Subtype');?></h2>
	<ul>
		<li class='active'><?php echo $this->Html->link(__('Cancel'), array('action' => 'view', $this->Form->value('ItemSubtype.id'))); ?> </li>
		<li class='active'><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('ItemSubtype.id')), null, __('Are you sure you want to delete %s?', $this->Form->value('ItemSubtype.name'))); ?></li>
	</ul>
	<ul>
		<li class='active last'><?php echo $this->Html->link(__('Item Types'), array('controller' => 'item_types', 'action' => 'index')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>