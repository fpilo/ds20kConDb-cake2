<?php echo $this->My->toJavascript($itemSubtypeVersions); ?>
<script type='text/javascript'>
var getAvailableUrl = "<?php echo $this->Html->url(array('controller'=>'items','action'=>'getAvailable')); ?>";
</script>
<?php echo $this->Html->script('filter_new'); ?>

<style type="text/css">

	#selector div{
		float:left;
		clear:none;
	}
</style>


<?php
	if(!isset($hideLocation)){
		$hideLocation = false;
	}
   if(!isset($multiple)){
      $multiple = false;
   }
?>

<div id='selector'>
	<div>
      <?php 
		   if(!$hideLocation){
			   echo $this->Form->input('location_id', array(
										'size' => 8,
										'style' => 'width: 200px',
                              //'onchange' => 'RefreshSubSelect(this)',
										'id' => 'location_id'));
			}
		?>
	</div>
	<div>
		<?php
			echo $this->Form->input('project_id', array(
										'style' => 'width: 200px',
										'onchange' => 'RefreshSubSelect(this)',
										'size' => 8,
										'class' => 'dependent',
										'id' => 'project_id'));
		?>
	</div>
	<div>
		<?php
			echo $this->Form->input('item_type_id', array(
										'disabled' => 'disabled',
										'style' => 'width: 200px',
										'onchange' => 'RefreshSubSelect(this)',
										'size' => 8,
										'class' => 'dependent',
										'options' => array('Please select a Project'),
										'id' => 'item_type_id'));
		?>
	</div>
	<div>
		<?php
			echo $this->Form->input('item_subtype_id', array(
										'disabled' => 'disabled',
										'style' => 'width: 200px',
										'onchange' => 'RefreshSubSelect(this)',
										'size' => 8,
										'class' => 'dependent',
										'options' => array('Please select an Item Type'),
										'id' => 'item_subtype_id'));
		?>
	</div>
	<div>
		<?php
         $version_options = array(
										'disabled' => 'disabled',
										'style' => 'width: 400px',
										'onchange' => 'RefreshSubSelect(this)',
										'size' => 8,
										'class' => 'dependent',
										'options' => array('Please select an Item Subtype'),
										'id' => 'item_subtype_version_id');
         if($multiple) {
            $version_options['multiple'] = 'multiple';
            $version_options['label'] = 'Item Subtype Version (ctrl-click to select multiple)';
         }
         if(isset($custom_version_label)) {
            $version_options['label'] = $custom_version_label;
         }
			echo $this->Form->input('item_subtype_version_id',$version_options);
		?>
		<?php #echo $this->Html->link(__('Show Changelog'), array('controller' => 'itemSubtypes', 'action' => 'changelog'), array('id' => 'show_changelog', 'target' => '_blank',"style"=>"padding-left: 0.5em;")); ?>
		<input type='hidden' value='' name='data[Item][manufacturer_id]' id='manufacturer_id' />
	</div>
</div>
