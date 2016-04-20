<div class="locationsUsers view">
<h2><?php  echo __('Locations User');?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($locationsUser['LocationsUser']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Location'); ?></dt>
		<dd>
			<?php echo $this->Html->link($locationsUser['Location']['name'], array('controller' => 'locations', 'action' => 'view', $locationsUser['Location']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($locationsUser['User']['username'], array('controller' => 'users', 'action' => 'view', $locationsUser['User']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Locations User'), array('action' => 'edit', $locationsUser['LocationsUser']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Locations User'), array('action' => 'delete', $locationsUser['LocationsUser']['id']), null, __('Are you sure you want to delete # %s?', $locationsUser['LocationsUser']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Locations Users'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Locations User'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Locations'), array('controller' => 'locations', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Location'), array('controller' => 'locations', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
