<div class="projectsItemTypes view">
<h2><?php echo __('Projects Item Type'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($projectsItemType['ProjectsItemType']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Project'); ?></dt>
		<dd>
			<?php echo $this->Html->link($projectsItemType['Project']['name'], array('controller' => 'projects', 'action' => 'view', $projectsItemType['Project']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Item Type'); ?></dt>
		<dd>
			<?php echo $this->Html->link($projectsItemType['ItemType']['name'], array('controller' => 'item_types', 'action' => 'view', $projectsItemType['ItemType']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __("Tags"); ?></dt>
		<dd>
			<?php foreach($projectsItemType["ItemTag"] as $itemTags): ?>
				<?php echo $this->Html->link($itemTags['name'], array('controller' => 'item_tags', 'action' => 'view', $itemTags['id'])); ?>
			<?php endforeach; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div id='verticalmenu'>
	<h2><?php  echo __('Projects Item Types');?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Projects Item Type'), array('action' => 'edit', $projectsItemType['ProjectsItemType']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Projects Item Type'), array('action' => 'delete', $projectsItemType['ProjectsItemType']['id']), array(), __('Are you sure you want to delete # %s?', $projectsItemType['ProjectsItemType']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Projects Item Types'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('New Projects Item Type'), array('action' => 'add')); ?> </li>
		<li class="active last"><?php echo $this->Html->link(__('Cancel'), array('action' => 'index')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
