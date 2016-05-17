<style type="text/css">
	div.error{
		color:red;
		margin:10px 2px;
	}
	input.confirm_measurement{
		background-image: -webkit-gradient(linear, left top, left bottom, from(#76BF6B), to(#3B8230));
		background-image: -webkit-linear-gradient(top, #76BF6B, #3B8230);
		background-image: -moz-linear-gradient(top, #76BF6B, #3B8230);
		border-color: #2d6324;
		color: #fff;
		text-shadow: rgba(0, 0, 0, 0.5) 0px -1px 0px;
		padding: 8px 10px;
	}
	input[type=button].confirm_measurement:hover{
		background: #5BA150;
	}
</style>

<?php if (isset($message)): ?>
	<p class="error-message error"><?php echo $message ?></p>
<?php endif;//debug($filedata->getPreviewColnames($previewId)); ?>
<?php $submitAllowed = false; ?>
<div class="items view">
	<?php if (isset($previewData)): ?>
	<?php debug($previewData); ?>
		<?php if (is_array($previewData)): ?>
			<h3>
				<?php	if($previewData["passedCode"]): //Filecode was passed, check if there is a recognized code ?>
					<?php 	if($previewData["recognizedCode"] === false || $previewData["recognizedCode"] == ""): ?>
						Item code: <?php echo $previewData["itemCode"]; ?>
						for item ID:
						<?php echo $this->Html->link($previewData["item_id"],array('controller'=>"items",'action'=>"view",$previewData["item_id"],"#"=>"information")); ?>
						was passed as a Parameter.
						<?php $submitAllowed = true; ?>
					<?php	elseif($previewData["recognizedCode"] != $previewData["itemCode"]): ?>
				 			The item Code in the measurement file
				 			(<?php echo $this->Html->link($previewData["recognizedCode"],array('controller'=>"items",'action'=>"view",$previewData["recognizedId"],"#"=>"information")); ?>)
				 			 and the Item selected
				 			(<?php echo $this->Html->link($previewData["itemCode"],array('controller'=>"items",'action'=>"view",$previewData["item_id"],"#"=>"information")); ?>)
				 			 do not match. Aborting.
					<?php	elseif($previewData["recognizedCode"] == $previewData["itemCode"]): ?>
				 			The item Code in the measurement file
				 			(<?php echo $this->Html->link($previewData["recognizedCode"],array('controller'=>"items",'action'=>"view",$previewData["recognizedId"],"#"=>"information")); ?>)
				 			 and the Item selected
				 			(<?php echo $this->Html->link($previewData["itemCode"],array('controller'=>"items",'action'=>"view",$previewData["item_id"],"#"=>"information")); ?>)
				 			match.
						<?php $submitAllowed = true; ?>
					<?php	endif; ?>
				<?php 	else: ?>
					<?php if($previewData["item_id"] !== false): ?>
						Item code: <?php echo $previewData["itemCode"]; ?>
						with item ID:
						<?php echo $this->Html->link($previewData["item_id"],array('controller'=>"items",'action'=>"view",$previewData["item_id"],"#"=>"information")); ?>
						<?php $submitAllowed = true; ?>
					<?php else: ?>
						Couldn't find item Code <?php echo $previewData["itemCode"]; ?> in database, please
						<?php echo $this->Html->link("create the item",array("controller"=>"items","action"=>"register")); ?>
						before adding a measurement to it. If the measurement file doesn't contain an item code start the measurement from the item to attach it.
					<?php endif; ?>
				<?php	endif;?>



			</h3>
			<dl>
				<dt>Recognized start time</dt><dd><?php echo date("d.m.Y H:i:s",$cmpListFile->measurementParameters->startTime); ?></dd>
				<dt>Recognized stop time</dt><dd><?php echo date("d.m.Y H:i:s", $cmpListFile->measurementParameters->stopTime); ?></dd>
			<?php
			foreach($cmpListFile->measurementParameters->getParameters() as $parameter){
					echo "<dt>".$parameter["parameter_name"]."</dt><dd> ".$parameter["parameter_value"]."</dd>";
			}
			echo "</dl>";
			if($cmpListFile->measurementParameters->error){
				$submitAllowed = false;
				if(count($cmpListFile->measurementParameters->errors)>1){
					echo "<p class='error'>The following parameters are not in the database, please add them to the ".$this->Html->link("matching table",array("controller"=>"matchings","action"=>"add"))." to associate them with existing parameters or ask an administrator to add the parameters if they don't exist yet in a different format </p>";
				}else{
					echo "<p class='error'>The following parameter is not in the database, please add it to the ".$this->Html->link("matching table",array("controller"=>"matchings","action"=>"add"))." to associate it with an existing parameter or ask an administrator to add the parameter if it doesn't exist yet in a different format </p>";
				}
				$tmp = array();
				foreach($cmpListFile->measurementParameters->errors as $error){
					$tmp[] = $error["msg"];
				}
				echo "<b>".implode(", ",$tmp)."</b>";
			}
			?>

			<h3>Measurement Setup used:</h3>
<!--			Here a User should be presented with a list of measurement devices that can create this measurement type.-->
			<div class="measurementDevices">
				<div class="measurementDeviceError error"></div>
			<?php
				$selected = (count($devices)==1)?" selected":"";
				foreach($devices as $deviceId=>$device){
					echo "<div class='measurementDevice".$selected."' value='".$deviceId."'>".$device["name"]." @ ".$device["Location"]["name"]."</div>";
				}
			
			?>
			</div>

			<h3>First few rows of each recognized data section. </h3>
			<div class="preview_table_div">
				<?php
					foreach($cmpListFile->getMeasurementSections() as $section):
						$previewData = $cmpListFile->getSectionAsPreview($section["name"])->measurementData;
						$tmp = array();
						echo "<h3>[".$section["name"]."]</h3>";
						if($previewData->error){
							$submitAllowed = false;
							foreach($previewData->errors as $error)
								echo '<p class="error-message error">'.$error["msg"].'</p>';
						}else{
				?>
						<table cellpadding="0" cellspacing="0" id="preview_<?php echo $previewId; ?>" class="preview_table">
							<?php
								foreach($previewData->cols as $num=>$col){
									$tmp[$num] = array_pop($col);
								}
								echo $this->Html->tableHeaders($tmp);
								echo $this->Html->tableCells($previewData->rows);
							?>
						</table>
				<?php
						}
					endforeach;
				?>
			</div>
			<?php if($submitAllowed): ?>
				<input type='button' value='Confirm and Save' class='confirm_measurement'/>
				<div class="error submitError"></div>
			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>
	<?php
	?>
<input type="button" value='Confirm and Save' class='confirm_measurement' id="AddButton2"/>
<input type='button' value='Discard Preview' class='delete_measurement'/>
</div>
