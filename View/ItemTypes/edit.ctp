<?php
	// $this->Html->addCrumb('Item Types', '/item_types/index/');
	$this->Html->addCrumb($this->data['ItemType']['name'], '/item_types/view/'.$this->data['ItemType']['id']);
?>
<div class="itemTypes form">
<?php echo $this->Form->create('ItemType'); ?>
	<fieldset>
		<legend><?php echo __('Edit Item Type'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('Project',array("label"=>"Projects","size"=>count($projects)));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div id='verticalmenu'>
	<h2><?php  echo __('Item Type');?></h2>
	<ul>
		<li class="active"><?php echo $this->Html->link(__('Cancel'), array('action' => 'view', $this->Form->value('ItemType.id'))); ?></li>
		<li class="active"><?php echo $this->Html->link(__('List Item Types'), array('action' => 'index')); ?></li>
		<li class="active last"><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('ItemType.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('ItemType.id'))); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>