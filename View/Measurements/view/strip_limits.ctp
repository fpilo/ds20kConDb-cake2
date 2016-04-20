<div id="limits" style="float:left; width:500px; display:none;">
	<form type='post' target='?' id='applyLimits'>
	<table>
		<tr>
			<th>use</th>
			<th>lower Limit</th>
			<th colspan="3">Col Name</th>
			<th>upper Limit</th>
			<th>median</th>
		</tr>
	<?php foreach ($standardLimits as $name=>$limit): ?>
			<tr>
				<td>
					<input type='checkbox' value="1" class='deactivateLimit' id='use_<?php echo $limit["id"]; ?>' checked='checked' />
				</td>
				<td>
					<input type='number' value='<?php echo sprintf("%1.2e",$limit["lValue"]); ?>' name='lValue_<?php echo $limit["id"]; ?>' style='width:100px;' />
				</td>
				<td>
					&lt;
				</td>
				<td style="text-align: center;">
					<?php echo $name; ?>
				</td>
				<td>
					&lt;
				</td>
				<td>
					<input type='number' value='<?php echo sprintf("%1.2e",$limit["uValue"]); ?>' name='uValue_<?php echo $limit["id"]; ?>' style='width:100px;' />
				</td>
				<td>
					<?php echo sprintf("%1.2e",$limit["median"]); ?>
				</td>
			</tr>
	<?php endforeach; ?>
	</table>
	<input type="submit" value="Apply limits" id="applyLimits" />
	<div>
		This Action will create Strip Error Measurements of the Strip Measurements selected from the following Items using the parameters selected above:<br />
		By clicking on the itemCode below one can generate a preview of this measurement
		<table id='gradingStatus'>
		<?php
			foreach($selectedMeasurements as $measurementId=>$item){
				echo "<tr><td><a onClick='applyLimitToMeasurementForPreview(".$measurementId.",\"".$item["Item"]["code"]."\")' style='cursor:pointer;'>".$item["Item"]["code"]."</a>";
				echo "<input type='hidden' name='selectedMeasurements[]' value='$measurementId' />";
				echo "</td><td id='mm_$measurementId'>";
				echo "&nbsp;";
				echo "</td></tr>";
			}
		?>
		</table>
	</div>
	<input type="button" value="Apply limits to all" id="applyLimitsToAll" />
	</form>
</div>
<script type="text/javascript">
	$(function(){
		$("#applyLimits").submit(function(e){
			e.preventDefault();
			applyLimitToMeasurementForPreview(<?php echo $measurement["Measurement"]["id"]; ?>,"<?php echo $measurement["Item"]["code"]; ?>");
		});
		$("#applyLimitsToAll").click(function(){
			$(this).attr("disabled", true).fadeOut(1000);
			var newLimits = getLimits();
			//Loop over all the hidden fields as they contain the measurements where the limits will be applied
			var targetUrls = new Array();
			$("#limits [type=hidden]").each(function(){
				var measurementId = $(this).val();
				var targetUrl = '<?php echo $this->Html->url(array("controller"=>"Measurements","action"=>"gradeSubtypeVersion",$subtypeVersionId));?>/'+measurementId;
				console.log(targetUrl);
				targetUrls.push(targetUrl);
				storeOneMeasurement(targetUrls,newLimits);
			});
		});
		$(".deactivateLimit").each(function(){
			$(this).change(function(){
				if($(this).is(":checked")){
					$(this).parent().parent().attr("style","opacity:1");
				}else{
					$(this).parent().parent().attr("style","opacity:0.3");
				}
			});
		})

		$("#measurement_header").append($("#limits").fadeIn(500));
	});

	function storeOneMeasurement(targetUrls,newLimits){
		targetUrl = targetUrls.pop();
		if(targetUrl == undefined){
			return;
		}
		$("#gradingStatus td").each(function(){
			if($(this).html() == "&nbsp;"){
				$(this).html("processing...");
			}
		})
		$.post(targetUrl+"?store=true",newLimits,function(data){ //Set store to false for debugging purposes to not always create new measurements
			updateProgress(data);
			storeOneMeasurement(targetUrls,newLimits);
		},"json");
	}

	function updateProgress(data){
		$("#"+data.id).html(data.status);
	}

	function getLimits(){
		var newLimits = Array();
		$("#limits input[type='number']").each(function(){
			id = $(this).attr("name").substr(7);
			if(!$("#use_"+id).is(":checked")) return;
			newLimits.push({name:$(this).attr("name"),value:$(this).val()});
		});
		return newLimits;
	}

	function applyLimitToMeasurementForPreview(measurementId,itemCode){
		$("#itemCode").html(itemCode); //Update the item code displayed at the top

		var targetUrl = "<?php echo $this->Html->url(array("controller"=>"Measurements","action"=>"gradeSubtypeVersion",$subtypeVersionId))."/";?>"+measurementId;
		console.log(targetUrl);
		var newLimits = getLimits();
		$.post(targetUrl+"?store=false",newLimits,function(data){
			$("#tabs").replaceWith(data);
			//Make the inserted table of values respond to clicks for the plotting function
			$(".parameter").each(function(){
				$(this).click(function(){
					$("#y_axis_selection").html($(this).html()+"<input type='hidden' id='y_axis_selection_id' value='"+$(this).attr("id").substring(10)+"'>");
					createPlotButton();
				});
				$(this).bind("contextmenu",function(e){
					e.preventDefault();
					$("#x_axis_selection").html($(this).html()+"<input type='hidden' id='x_axis_selection_id' value='"+$(this).attr("id").substring(10)+"'>");
					createPlotButton();
				});
			});
		},"html");
	}

</script>
