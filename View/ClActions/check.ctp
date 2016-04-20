<?php
	$this->Html->addCrumb('ClActions', '/clActions');
	$this->Html->addCrumb('Check', '/clActions/check/'.$this->data['ClAction']['id']);
?>

<div class="clActions form">
<?php echo $this->Form->create('ClAction');?>
	<fieldset>
		<legend><?php echo __('Complete Check list Action'); ?></legend>
	<?php
		echo $this->Form->input('referer', array('type'=>'hidden'));
		echo $this->Form->input('id',  array('type'=>'hidden'));
		echo $this->Form->input('name', array('readonly' => true));
		$states = array('1'=>'fail','3'=>'pass');
		echo $this->Form->input('status', array(
												'type' => 'select',
												'options' => $states, 
												'default' => '3', 
												)
								);
		echo $this->Form->input('status_code', array('type'=>'hidden'));
		echo $this->Form->input('updated_by', array('required' => true, 'readonly' => true));
		echo $this->Form->input('notes');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('clActions');?></h2>
	<ul>
		<li class="active"><?php echo $this->Html->link(__('Cancel'), $this->request->data['ClAction']['referer']); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>

<?php echo $this->Html->script(array('jquery-1.6.4.min'));?>
<script type="text/javascript" charset="utf-8">

	$(document).ready(function() {		
		$(document).on('keyup keypress', 'form input[type="text"]', function(e) {
			if(e.keyCode == 13) { //enter
			e.preventDefault();
			return false;
		  }
		});
	});
	
</script>