<?php
	// $this->Html->addCrumb('Item Types', '/item_types/index/');
?>
<script type="text/javascript">
	function check_projects(){
      if($('#ProjectProject').val()){
         $('#submitform').removeAttr('disabled');
      } else {
         $('#submitform').attr('disabled', 'disabled');
      }
	}
</script>
<div class="itemTypes form">
<?php echo $this->Form->create('ItemType'); ?>
	<fieldset>
		<legend><?php echo __('Add Item Type'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('Project',array("label"=>"Projects","size"=>count($projects),'onchange'=>'check_projects()'));
	?>
	</fieldset>
<?php echo $this->Form->end(array('label'=>__('Submit'),'id'=>'submitform','disabled'=>'disabled')); ?>
</div>
<div id='verticalmenu'>
	<h2><?php  echo __('Item Type');?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('List Item Types'), array('action' => 'index')); ?></li>
		</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
