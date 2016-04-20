<?php
	$this->Html->addCrumb('ClStates', '/clStates');
	$this->Html->addCrumb('Add', '/clStates/add/');
?>

<?php $this->Js->get('#advopt')->event('click', '$("div.advopt").toggle();'); ?>

<div class="clStates form">
<?php echo $this->Form->create('ClState');?>
	<fieldset>
	<legend><?php echo __('Add ClState'); ?></legend>
	<?php
		echo $this->Form->input('referer', array('type'=>'hidden'));
		echo $this->Form->input('name');
		echo $this->Form->input('description', array(
														'after' => 	'<div class="input-message" id="advopt">Advanced options...</div>'
														));		
	?>
	<div class='advopt' style='display: none'>
	<?php	
		echo $this->Form->input('type', array('type' => 'select','options'=>$clStateTypes, 'selected' => $defaultTypes, 'div' => false));
	?>
	</div>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('ClStates');?></h2>
	<ul>
		<li class="active last"><?php echo $this->Html->link(__('Cancel'), $this->request->data['ClState']['referer']); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>