<?php
	$this->Html->addCrumb($item['Item']['code'], '/items/view/'.$item['Item']['id']);
	$this->Html->addCrumb('Add checklist', '/checklists/add/'.$item['Item']['id']);
?>

<div class="checklists form">

<?php echo $this->Form->create('Checklist');?>
	<fieldset>
		<legend><?php echo __('Add Checklist'); ?></legend>
	<?php if(!empty($clTemplates)): ?>
	<?php
		echo $this->Form->input('cl_template_id', array(
													'empty' => '(choose one)'
													));
	?>
	</fieldset>
	<?php echo $this->Form->end(__('Submit'));?>
	<?php else: ?>
		No checklist template found for the current item subtype.
	</fieldset>
	<?php endif; ?>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('Actions');?></h2>
	<ul>
		<li class="active"><?php echo $this->Html->link(__('Cancel'), array('controller' => 'Checklists', 'action' => 'update', $item['Item']['id'])); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>