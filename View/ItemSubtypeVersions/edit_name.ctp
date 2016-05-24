<?php
	$itemSubtypeVersionName = ($this->data['ItemSubtypeVersion']['name'] != "")? $this->data['ItemSubtypeVersion']['version']." (".$this->data['ItemSubtypeVersion']['name'].")": "v".$this->data['ItemSubtypeVersion']['version'];
	$this->Html->addCrumb($itemSubtypeVersionName, '/item_subtype_versions/view/'.$this->data['ItemSubtypeVersion']['id']);
?>

<div class="itemSubtypeVersions form">
<?php echo $this->Form->create('ItemSubtypeVersion');?>
	<fieldset>
		<legend><?php echo __('Edit Version Comment'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div id='verticalmenu'>
	<h2><?php  echo __('Edit Version Name');?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('Cancel'), array('action' => 'view', $this->Form->value('ItemSubtypeVersion.id')));?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>