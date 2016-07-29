<div id='verticalmenu'>
	<h2><?php echo __('Actions'); ?></h2>
	<ul>
		<li class='active'><?php echo $this->Html->link(__('Edit Measurement'), array('action' => 'edit', $measurement['Measurement']['id'])); ?> </li>
		<li class='active last'><?php echo $this->Form->postLink(__('Delete Measurement'), array('action' => 'delete', $measurement['Measurement']['id']), null, __('Are you sure you want to delete # %s?', $measurement['Measurement']['id'])); ?> </li>
		<!--<li><?php echo $this->Html->link(__('New Measurement'), array('action' => 'add')); ?> </li>-->
	</ul>
	<?php require(dirname(__FILE__).'/../../Layouts/menu.ctp'); ?>
</div>
