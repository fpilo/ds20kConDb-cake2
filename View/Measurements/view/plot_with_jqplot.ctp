<?php echo $this->Html->script("jqplot/jquery.jqplot.js"); ?>
<?php echo $this->Html->script("jqplot/plugins/jqplot.canvasTextRenderer.js"); ?>
<?php echo $this->Html->script("jqplot/plugins/jqplot.canvasAxisLabelRenderer.js"); ?>
<?php echo $this->Html->css("jquery.jqplot.css"); ?>


<script type="text/javascript">
	var options = {
	      series:[{showMarker:false}],
	      axes:{
	        xaxis:{
	          label:'Angle (radians)'
	        },
	        yaxis:{
	          label:'Cosine'
	        }
	      }
	}
	$(function(){
		$("input[name=yCalc]:radio").change(function(){
			setPlotType($(this).val());
		});

		var cosPoints = [];
		  for (var i=0; i<2*Math.PI; i+=0.1){
		     cosPoints.push([i, Math.cos(i)]);
		  }
		$("#measurement_header").append($("#plot_output").fadeIn(500));
		$("#measurement_header").append($("#plot_control").fadeIn(500));

		var plot1 = $.jqplot('plot_output', [cosPoints], {
	      series:[{showMarker:false}],
	      axes:{
	        xaxis:{
	          label:'Angle (radians)'
	        },
	        yaxis:{
	          label:'Cosine'
	        }
	      }
	});
		console.log(plot1);


		//Make parameters clickable
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
		//Make standard plots depending on the measurement type
		<?php echo $measurementStandardPlot; ?>



		$(".tag").click(function(){
			var tag = $(this);
			var tag_id = tag.attr("id");
			tag.hide();
			if($(this).parent().attr("id") == "aviable_tags"){
				//Tag is an aviable tag set it as new tag and on success move it over to the other display
				$.ajax({
					url: "<?php echo $this->Html->url(array("controller"=>"measurements","action"=>"addTag")); ?>/"+$("#measurementId").attr("value")+"/"+tag_id,
					type: "GET",
					dataType: "json"
				}).done(function(result){
					if(result.success){
						tag.detach().appendTo("#set_tags").fadeIn(200);
						var url = '<?php echo $this->Html->url(array("controller"=>"measurement_tags", 'action' => 'view',)); ?>/'+tag.attr("id").substr(4);
						$("#display_tags").append("<a href='"+url+"' id='display_"+tag.attr("id")+"'>"+tag.html()+"</a> ");
					}else{
						tag.fadeIn(200);
						alert(result.message);
					}
				});
			}else if($(this).parent().attr("id") == "set_tags"){
				//Tag is an aviable tag set it as new tag and on success move it over to the other display

				$.ajax({
					url: "<?php echo $this->Html->url(array("controller"=>"measurements","action"=>"removeTag")); ?>/"+$("#measurementId").attr("value")+"/"+tag_id,
					type: "GET",
					dataType: "json"
				}).done(function(result){
					if(result.success){
						$("#display_"+tag_id).remove();
						tag.detach().appendTo("#aviable_tags").fadeIn(200);
					}else{
						tag.fadeIn(200);
						alert(result.message);
					}
				});
			}
		});


	});

		function createPlotButton(){
				console.log("bla");
			}

</script>

<div id="plot_output" style="width:700px; height:400px; float:right; display:none;">

</div>
<div id="plot_control" style="width:250px; min-height:200px; margin:10px; float:left; display:none;">
		<table>
			<tr><td><?php echo __('X-Axis (Right click)'); ?></td><td id="x_axis_selection">none</td></tr>
			<tr><td><?php echo __('Y-Axis (Left click)'); ?></td><td id="y_axis_selection">none</td></tr>
		</table>
	<input type='radio' value="nothing" name="yCalc" id="calcNothing"/><label for="calcNothing">y = y</label><br />
	<input type='radio' value="inverseSquare" name="yCalc" id="calcInverseSquare"/><label for="calcInverseSquare">y = 1/y^2</label><br />
<!--	<input type='radio' value="square" name="yCalc" id="calcSquare"/><label for="calcSquare">y = y^2</label><br />
		<input type='radio' value="inverse" name="yCalc" id="calcInverse"/><label for="calcInverse">y = 1/y</label><br />
		<input type='radio' value="invert" name="yCalc" id="calcInvert"/><label for="calcInvert">y = -y</label><br />-->
	<input type='radio' value="abs" name="yCalc" id="calcAbs"/><label for="calcAbs">y = abs(y)</label><br />
	<label for="plotLogarithmic"><input type="checkbox" value="logarithmic" name="logarithmic" id="plotLogarithmic" onclick="toggleLog();"/> Log y</label>
	<label for="plotMax">Plot Maximum: <input type="number" step="0.0001" value="0.0000" name="plotMax" id="plotMax" onchange='triggerMinMaxChange($("#plotMin").val(),$(this).val())' /></label>
	<label for="plotMax">Plot Minimum: <input type="number" step="0.0001" value="0.0000" name="plotMin" id="plotMin" onchange='triggerMinMaxChange($(this).val(),$("#plotMax").val())' /></label>
</div>
