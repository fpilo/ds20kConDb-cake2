<?php
	$slide = 	'$("div.files").slideToggle("slow");
					if(sessionStorage.getItem("AddMeasurementFilterVisability") == 1)
						sessionStorage.setItem("AddMeasurementFilterVisability", 0);
					else
						sessionStorage.setItem("AddMeasurementFilterVisability", 1);
						
					 event.preventDefault();';
?>
<script type="text/javascript">
	$(document).ready(function() {
	  
		if(sessionStorage.getItem("AddMeasurementFilterVisability") == 1)
			$("div.files").show();
//	});

//	$(function(){
		restoreTabs('measurementsViewFileTabIndex');
	});
</script>

<?php $this->Js->get('#search')->event('click', $slide); ?>

<div class="measurements index">
<!--
	<?php echo $this->Form->create('Measurement');?>
	<fieldset>
		<legend><?php echo __('Add Measurement'); ?></legend>
		<?php
			echo $this->Form->input('measurement_name');
		?>
	</fieldset>
	<?php echo $this->Form->end(__('Submit'));?>
-->
	
	<div class="input-message" id="search">Show files...</div>

	<div class="files"> <!-- style="display: none"> -->
		<div id="tabs" class='related'>
			<ul>
				<?php foreach($result['files'] as $name => $file): ?>
					<li><a href="#<?php echo $file['fixed_name']; ?>"><?php echo $name; ?></a></li>
				<?php endforeach; ?>
			</ul>
			
			<?php foreach($result['files'] as $name => $file): ?>
			<div id="<?php echo $file['fixed_name']?>" >
				<table cellpadding="0" cellspacing="0">
					<?php echo $this->Html->tableHeaders($file['header']); ?>
					<?php echo $this->Html->tableCells($file['data']); ?>
				</table>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
	
	<?php //debug($result); ?>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Add Measurement'); ?></h2>
	<ul>
		<li class='active last'><?php echo $this->Html->link(__('Download table as .csv'), array('action' => 'generateCsv')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
