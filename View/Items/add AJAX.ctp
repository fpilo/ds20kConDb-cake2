<?php $this->Html->script('validation', FALSE);?>

<div id="success"></div>
<div class="items form">
<?php echo $this->Form->create('Item', array('type' => 'file'));?>
	<fieldset>
		<legend><?php echo __('Add Item'); ?></legend>
				<?php echo $this->Form->input('code', array('type' => 'textarea',
															'id' => 'code',
															//'before' => '<li class="bigfield"><div class="input text required error">',
															'after' => '<div class="input-message">Seperate multiple Items with ";" or " "</div>')); ?>
				<?php echo $this->Form->input('comment', array('label' => 'Comment')); ?>
		<table cellpadding="0" cellspacing="0">
				<tr>				
					<td>
						<?php echo $this->Form->input('project_id', array('style' => 'width: 250px', 'size' => 8)); ?>
					</td>
					<td>
						<?php echo $this->Form->input('manufacturer_id', array('style' => 'width: 250px', 'size' => 8)); ?>
					</td>
					<td>
						<?php echo $this->Form->input('itemSubtype_id', array('style' => 'width: 250px', 'size' => 8)); ?>
					</td>
					<td><?php echo $this->Form->input('location_id', array('size' => 8)); ?></td>
					<td><?php echo $this->Form->input('state_id', array('size' => 8)); ?></td>
				</tr>
		</table>
	</fieldset>
<?php 
		echo $this->Form->end(__('Submit'));
		echo $this->Js->submit('Ajax Submit', array(
			'before'=>$this->Js->get('#sending')->effect('fadeIn'),
			'success'=>$this->Js->get('#sending')->effect('fadeOut'),
			'update'=>'#success'
		));
		echo debug($manufacturers);
		echo debug($results);
		echo debug($test);
?>
</div>
<div id="sending" style="display: none; background color: lightgreen;">Sending...</div>

<div class="actions">
	<h3><?php echo __('List'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Items'), array('action' => 'index'));?></li>
	</ul>
</div>

<?php
$this->Js->get('#ItemProjectId')->event('change', 
	$this->Js->request(array(
		'controller'=>'items',
		'action'=>'getManufacturerByProject',
		), array(
		'update'=>'#ItemManufacturerId',
		'async' => true,
		'method' => 'post',
		'dataExpression'=>true,
		'data'=> $this->Js->serializeForm(array(
			'isForm' => true,
			'inline' => true
			))
		))
	);
?>

<?php
$this->Js->get('#ItemManufacturerId')->event('change', 
	$this->Js->request(array(
		'controller'=>'items',
		'action'=>'getItemSubtypeByManufacturer'
		), array(
		'update'=>'#ItemItemSubtypeId',
		'async' => true,
		'method' => 'post',
		'dataExpression'=>true,
		'data'=> $this->Js->serializeForm(array(
			'isForm' => true,
			'inline' => true
			))
		))
	);
?>