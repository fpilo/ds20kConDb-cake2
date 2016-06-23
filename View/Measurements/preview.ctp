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
	<p class="error-message error"><?php echo $message; ?></p>
<?php endif; ?>

<div>
<?php if (isset($previewData)): ?>
	<?php

		/*
		* Read columns specified in the specified columns interval (lastCol is an optional parameter)
		* TODO METTERE QUESTA FUNZIONE IN UNA LIBRERIA SEPARATA
		*/
		function show_array($array, $hasHeaders = false, $firstCol, $lastCol = -1){
				
			if(is_array($array) == 1){          // check if input is an array
				foreach($array as $key_val => $value) {
					if (is_array($value) == 1){   // array is multidimensional
						if($hasHeaders){
							echo "<thead>\n<tr>";
							show_array($value, $hasHeaders, $firstCol);
							echo "</tr>\n</thead>\n<tbody>\n";
							$hasHeaders = false;
						} else{
							echo "<tr>";
							show_array($value, $hasHeaders, $firstCol);
							echo "</tr>\n";
						}
					}
					else{                        // (sub)array is not multidim
						if($key_val>=$firstCol && ($lastCol<0 || $key_val<$lastCol)){
							if($hasHeaders){
								echo "<th main width=\"120\">".$value."</th>"; 
							} else{ 
								echo "<td main width=\"120\">".$value."</td>"; 
							}
						}
					}
				} //foreach $array
				echo "</tbody>";
			}  
			else{ // argument $array is not an array
				return;
			}
		}

		function html_show_array($array){
			echo "<table id=\"meas_list_table\" cellspacing=\"0\" border=\"2\">\n";
			show_array($array, false, 0); //Set here the column range
			echo "</table>\n";
		}
		
		if(isset($devices))
			if(count($devices)==1){
				$previewData['measurementSetupId'] = 1;
				$previewData['measurementSetupName'] = reset($devices)['name'];
			}
		html_show_array($previewData);
		
	?>
<?php endif; ?>
</div>

<?php $submitAllowed = false; unset($previewData); ?>
<div class="items view">
	<?php if (isset($previewData)): ?>
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
					<?php if($previewData["item_id"] !== false && $previewData["itemCode"] == "MultiDev"): ?>
						Set of measurements referring to many items
						<?php $submitAllowed = true; ?>
					<?php elseif($previewData["item_id"] !== false): ?>
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
				<dt>Recognized start time</dt><dd><?php echo date("d.m.Y H:i:s",$mFile->measurementParameters->startTime); ?></dd>
				<dt>Recognized stop time</dt><dd><?php echo date("d.m.Y H:i:s", $mFile->measurementParameters->stopTime); ?></dd>
			<?php
			foreach($mFile->measurementParameters->getParameters() as $parameter){
				echo "<dt>".$parameter["parameter_name"]."</dt><dd> ".$parameter["parameter_value"]."</dd>";
			}
			echo "</dl>";
			if($mFile->measurementParameters->error){
				$submitAllowed = false;
				if(count($mFile->measurementParameters->errors)>1){
					echo "<p class='error'>The following parameters are not in the database, please add them to the ".$this->Html->link("matching table",array("controller"=>"matchings","action"=>"add"))." to associate them with existing parameters or ask an administrator to add the parameters if they don't exist yet in a different format </p>";
				}else{
					echo "<p class='error'>The following parameter is not in the database, please add it to the ".$this->Html->link("matching table",array("controller"=>"matchings","action"=>"add"))." to associate it with an existing parameter or ask an administrator to add the parameter if it doesn't exist yet in a different format </p>";
				}
				$tmp = array();
				foreach($mFile->measurementParameters->errors as $error){
					$tmp[] = $error["msg"];
				}
				echo "<b>".implode(", ",$tmp)."</b>";
			}
			?>
			
			<h3>Possible Tags:</h3>
			<div class="measurementTags">
			<?php
			//Echo all possible tags with white background and borders, mark set tags .
			$setTagsIds = array_keys($mFile->measurementTags->getTags());
			foreach($measurementTagIds as $tagId=>$tagName){
				//Mark toggle tag as green/white on click
				$selected = "";
				if(in_array($tagId, $setTagsIds)){//Premark tags set in the file
					$selected = " selected";
				}
				echo "<div class='measurementTag".$selected."' value='".$tagId."'>".$tagName."</div>";
			}
			if($mFile->measurementTags->error){
				$submitAllowed = false;
				foreach($mFile->measurementTags->errors as $error){
					echo "<p class='error'>".$error["msg"]."</p><br />";
				}
			}
			//Add hidden field with class "measurementTag" on green and remove it on white
			//Transfer these tags on submit
			?>
			</div>

			<!-- Here a User should be presented with a list of measurement devices that can create this measurement type.-->			
			<h3>Measurement Setup used:</h3>
			<div class="measurementDevices">
				<div class="measurementDeviceError error"></div>
				<?php
					$selected = (count($devices)==1)?" selected":"";
					foreach($devices as $deviceId=>$device){
						echo "<div class='measurementDevice".$selected."' value='".$deviceId."'>".$device["name"]." @ ".$device["Location"]["name"]."</div>";
					}		
				?>
			</div>
			
			<?php
			if($mFile->itemParameters !== null){
				echo "<h3>Item Parameters:</h3>
							<dl>";
				foreach($mFile->itemParameters->getParameters() as $parameter){
					echo "<dt>".$parameter["parameter_name"]."</dt><dd> ".$parameter["parameter_value"]."</dd>";
				}
				echo "</dl>";
				if($mFile->itemParameters->error){
					$submitAllowed = false;
					if(count($mFile->itemParameters->errors)>1){
						echo "<p class='error'>The following parameters are not in the database, please add them to the ".$this->Html->link("matching table",array("controller"=>"matchings","action"=>"add"))." to associate them with existing parameters or ask an administrator to add the parameters if they don't exist yet in a different format </p>";
					}else{
						echo "<p class='error'>The following parameter is not in the database, please add it to the ".$this->Html->link("matching table",array("controller"=>"matchings","action"=>"add"))." to associate it with an existing parameter or ask an administrator to add the parameter if it doesn't exist yet in a different format </p>";
					}
					$tmp = array();
					foreach($mFile->itemParameters->errors as $error){
						$tmp[] = $error["msg"];
					}
					echo "<b>".implode(", ",$tmp)."</b>";
				}
			}
			?>
			<h3>First few rows of each recognized data section. </h3>
			<div class="preview_table_div">
				<?php
					foreach($mFile->getMeasurementSections() as $section):
						$previewData = $mFile->getSectionAsPreview($section["name"])->measurementData;
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

	<!--<input type='button' value='Discard Preview' class='delete_measurement'/>-->

</div>
