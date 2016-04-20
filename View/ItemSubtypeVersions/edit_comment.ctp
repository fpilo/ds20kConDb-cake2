<?php
	// $this->Html->addCrumb('Item Types', '/item_types/index/');
	$this->Html->addCrumb($this->data['ItemSubtype']["ItemType"]['name'], '/item_types/view/'.$this->data['ItemSubtype']["ItemType"]['id']);
	// $this->Html->addCrumb('Item Subtypes', '/item_subtypes/index/');
	$this->Html->addCrumb($this->data['ItemSubtype']['name'], '/item_subtypes/view/'.$this->data['ItemSubtype']['id']);
?>
<div class="itemSubtypeVersions form">
<?php echo $this->Form->create('ItemSubtypeVersion');?>
	<fieldset>
		<legend><?php echo __('Edit Version Comment'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('comment', array('type' => 'textarea'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div id='verticalmenu'>
	<h2><?php  echo __('Edit Version Comment');?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('Cancel'), array('action' => 'view', $this->Form->value('ItemSubtypeVersion.id')));?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>