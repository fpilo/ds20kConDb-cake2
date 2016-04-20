<div class="items form">
<?php echo $this->Form->create('Data', array('type' => 'file'));?>
	<fieldset>
		<legend><?php echo __('Choose measurement file (*.csv):'); ?></legend>
		<?php
			echo $this->Form->input('Files.', array('type' => 'file', 'multiple' => 'multiple'));
		?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Measurements'); ?></h2>
	<ul>
		<li class='active'><?php echo $this->Html->link(__('Cancel'), array('controller' => 'measurements', 'action' => 'index')); ?></li>		
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>