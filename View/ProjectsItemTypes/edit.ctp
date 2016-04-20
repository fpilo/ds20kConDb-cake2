<div class="projectsItemTypes form">
<?php echo $this->Form->create('ProjectsItemType'); ?>
	<fieldset>
		<legend><?php echo __('Edit Projects Item Type'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('project_id');
		echo $this->Form->input('item_type_id');
		echo $this->Form->select("ItemTag",$itemTags,array('multiple' => true,'size'=>10));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div id='verticalmenu'>
	<h2><?php  echo __('Projects Item Types');?></h2>
	<ul>
		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('ProjectsItemType.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('ProjectsItemType.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Projects Item Types'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('New Projects Item Type'), array('action' => 'add')); ?></li>
		<li class="active last"><?php echo $this->Html->link(__('Cancel'), array('action' => 'index')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
