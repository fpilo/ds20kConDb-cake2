<?php
	$this->Html->addCrumb('ClStates', '/clStates');
	$this->Html->addCrumb('Edit', '/clStates/edit/'.$this->data['ClState']['id']);
?>

<?php $this->Js->get('#advopt')->event('click', '$("div.advopt").toggle();'); ?>

<div class="clStates form">
<?php echo $this->Form->create('ClState');?>
	<fieldset>
		<legend><?php echo __('Edit ClState'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('description', array(
														'after' => 	'<div class="input-message" id="advopt">Advanced options...</div>'
													));
		//ClState type editing
		/*
		$selected = array(0 => array_search($this->form->data['ClState']['type'], $clStateTypes));
		$seloptions = array('label' => 'Type',
					'div' => true,
					'type' => 'select',
					'size' => 1,
					'multiple' => false,
					'options' => $clStateTypes,
					'selected' => $selected,
					'empty' => '(choose at least one)',
					);
		echo $this->Form->input('type', $seloptions);
		*/
	?>
	<div class='advopt' style='display: none'>
		<?php
			echo $this->Form->input('saveAll', array('label' => 'Apply the changes to all the related ClStates in the db', 'type' => 'checkbox'));
		?>
	</div>
	</fieldset>
	<?php
		$options = array
		(
			'label' => 'Submit',
			'value' => 'Submit',
			'type' => 'submit',
			'id' => 'submit',
			'div' => true
		);
		echo $this->Form->end($options);
	?>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('ClStates');?></h2>
	<ul>
		<li class="active"><?php echo $this->Html->link(__('Cancel'), array('action' => 'index')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>

<?php echo $this->Html->script(array('jquery-1.6.4.min'));?>
<script type="text/javascript" charset="utf-8">
    
	$(document).ready(function() {
		$('#submit').click(function() {
			if ($('#ClStateSaveAll').is(':checked')) {
			  return confirm('The current changes will be applied to all the related ClStates in the db.\nAre you sure that you want to perform the action?');
			 }
		});
	});
	
</script>