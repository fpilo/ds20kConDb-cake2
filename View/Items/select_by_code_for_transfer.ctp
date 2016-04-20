<div class="items form">	
	<fieldset>
	<legend>Select items by code:</legend>
	<?php 
		echo $this->Form->create('Item');
		if(!empty($codes))
			echo $this->Form->input('codes', array('type' => 'textarea', 'value' => $codes));
		else
			echo $this->Form->input('codes', array('type' => 'textarea'));
		echo $this->Form->submit(__('Search'), array('div' => false));
		echo $this->Form->end();
	?>
	</fieldset>
</div>
	
<div id='verticalmenu'>
	<h2><?php echo __('Transfers'); ?></h2>
	<ul>
		<li class='active last'><?php echo $this->Html->link('Cancel',array('controller' => 'transfers', 'action' => 'add'));?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>