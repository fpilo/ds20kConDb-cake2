<div class="locationsUsers form">
<?php echo $this->Form->create('LocationsUser');?>
	<fieldset>
		<legend><?php echo __('Edit Locations User'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('location_id');
		echo $this->Form->input('user_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('LocationsUser.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('LocationsUser.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Locations Users'), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Locations'), array('controller' => 'locations', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Location'), array('controller' => 'locations', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
