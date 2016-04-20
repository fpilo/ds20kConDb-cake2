<?php
$this->Html->addCrumb('Measurements', '/measurements');
$this->Html->addCrumb('View', '/measurements/view/'.$measurement['Measurement']['id']);
?>
<div class="measurements form">
	<h3>No file found to be downloaded</h3>
</div>
<div id='verticalmenu'>
	<h2><?php echo __('Actions'); ?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('Back'), array('action' => 'view', $measurement['Measurement']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Edit Measurement'), array('action' => 'edit', $measurement['Measurement']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Measurement'), array('action' => 'delete', $measurement['Measurement']['id']), null, __('Are you sure you want to delete # %s?', $measurement['Measurement']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('New Measurement'), array('action' => 'add')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
