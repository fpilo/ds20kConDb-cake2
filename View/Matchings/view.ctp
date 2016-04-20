<div class="matchings view">
<h2><?php echo __('Matching'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($matching['Matching']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($matching['Matching']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Parameter'); ?></dt>
		<dd>
			<?php echo $this->Html->link($matching['Parameter']['name'], array('controller' => 'parameters', 'action' => 'view', $matching['Parameter']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div id='verticalmenu'>
	<h2><?php  echo __('Matching');?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Matching'), array('action' => 'edit', $matching['Matching']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Matching.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('Matching.id'))); ?></li>
		<li class="active last"><?php echo $this->Html->link(__('New'), array('action' => 'add')); ?></li>
		<li class="active last"><?php echo $this->Html->link(__('List'), array('action' => 'index')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
