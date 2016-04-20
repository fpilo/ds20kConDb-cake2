<?php
	// $this->Html->addCrumb('Item Types', '/item_types/index/');
	$this->Html->addCrumb($this->data['ItemType']['name'], '/item_types/view/'.$this->data['ItemType']['id']);
	// $this->Html->addCrumb('Item Subtypes', '/item_subtypes/index/');
	$this->Html->addCrumb($this->data['ItemSubtype']['name'], '/item_subtypes/view/'.$this->data['ItemSubtype']['id']);
?>
<div class="itemSubtype form">
<?php echo $this->Form->create('ItemSubtype');?>
	<fieldset>
		<legend><?php echo __('Change Comment'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name', array('type' => 'hidden'));
		echo $this->Form->input('comment', array('type' => 'textarea'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div id="verticalmenu">
	<span><?php  echo __('Item Subtype: '); ?></span><br>
	<span><?php  echo __($this->data['ItemSubtype']['name']);?></span>
	<ul>
		<li><?php echo $this->Html->link(__('Cancel'), array('action' => 'view', $this->Form->value('ItemSubtype.id')));?></li>
	</ul>
</div>
