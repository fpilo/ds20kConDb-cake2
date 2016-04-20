<script type="text/javascript">
	$(function(){
		
		$(".mm_param").each(function() {
			$(this).click(function () {
				//Just display that this parameter is the x-Axis one and don't allow change of it
				if($(this).hasClass("x_param")){
					return false;
				}
				//Mark/Unmark this parameter for usage as one of the two y-axis. 
				$(this).parent().find("mm_param").removeClass("y_param");
				$(this).addClass("y_param");
				
			});
			//Mark this parameter as the one used by the x-Axis
			if($(this).attr("value") == xAxis){
				$(this).addClass("x_param");
			}
		});
		$(".addToPlot").each(function(){
			//Check if a x_param was found in this section and if not remove the whole section from the display
			if($(this).prev().find(".x_param")[0] == undefined){
				//Didn't find x-parameter, removing whole block and returning
				$(this).parent().parent().hide();
				return true;
			}
			$(this).click(function(){
				addPlot($(this).attr("measurementId"),$(this).parent().find(".x_param").attr("value"),$(this).parent().find(".y_param").attr("value"));
			});
		});
	});
</script>
<style type="text/css">
	.mm_param {
		cursor: pointer;
		border: solid 1px black;
		border-radius: 4px;
		margin: 3px;
		padding: 2px;
	}
	.mm_param.x_param {
		background: blue;
		color: white;
		cursor: default;
		float: left;
	}

	.mm_param.y_param {
		background: #8ae234;
		float:right;
	}
	
	
</style>
<?php

$blockNames = array(
	0=>"Same Item and Measurement Type, different Time",
	1=>"Same Item, different Measurement Type",
	2=>"Same Measurement Type, parent or child Item",
	3=>"Same Measurement Type, different Item"
);

foreach($measurementBlocks as $blockId=>$block){
	echo "<h2 style='color:darkblue;'>".$blockNames[$blockId]."</h2>";
	echo "<table>";
	foreach($block as $measurement){
		echo "<tr>";
		echo "<td width='15%'>".$measurement["Item"]["code"]."</td>";
		echo "<td>".$measurement["Measurement"]["start"]."</td>";
		$mTags = array();
		foreach($measurement["MeasurementTag"] as $mTag){
			$mTags[] = $mTag["name"];
		}
		echo "<td style='width:50px;'>".implode(" ",$mTags)."</td>";
		echo "<td>".$measurement["MeasurementType"]["name"]."</td>";
		echo "<td>".$measurement["Device"]["name"]."@".$measurement["Device"]["Location"]["name"]."</td>";
		echo "<td>".$measurement["Item"]["ItemSubtype"]["name"]."</td>";
		echo "<td style='width:200px;' class='action'>";
		echo "<div class='mm_subselect'>";
		foreach($measurement["Measurement"]["header"] as $paramId=>$param){
			echo "<span class='mm_param' value='".$paramId."'>".$param."</span>";
		}
		echo "</div>";
		echo "<input type='button' value='Add to Plot' measurementId='".$measurement["Measurement"]["id"]."' class='addToPlot' /></div>";
		echo "</td>";
		echo "</tr>";
	}
	echo "</table>";
//	debug($block);
}

?>

