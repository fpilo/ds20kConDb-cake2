<div class="stocks view">
<h2><?php echo __('Stock'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($stock['Stock']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Item Subtype Version'); ?></dt>
		<dd>
			<?php echo $this->Html->link($stock['ItemSubtypeVersion']['version'], array('controller' => 'item_subtype_versions', 'action' => 'view', $stock['ItemSubtypeVersion']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Amount'); ?></dt>
		<dd>
			<?php echo h($stock['Stock']['amount']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Location'); ?></dt>
		<dd>
			<?php if (!empty($stock['Location'])): ?>
				<?php foreach ($stock['Location'] as $location): ?>
					<?php echo $this->Html->link(__($location['name']), array('controller' => 'locations', 'action' => 'view', $location['id']))." "; ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</dd>
		<dt><?php echo __('Project'); ?></dt>
		<dd>
			<?php if (!empty($stock['Project'])): ?>
				<?php foreach ($stock['Project'] as $project): ?>
					<?php echo $this->Html->link(__($project['name']), array('controller' => 'projects', 'action' => 'view', $project['id']))." "; ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</dd>
	</dl>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('Stock');?></h2>
	<ul>
		<li class="active"><?php echo $this->Html->link(__('New'), array('action' => 'add')); ?></li>
		<li class="active"><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $stock['Stock']['id'])); ?> </li>
		<li class="active last"><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $stock['Stock']['id']), null, __('Are you sure you want to delete this stock?')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>