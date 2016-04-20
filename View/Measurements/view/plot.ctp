<?php if(!$plotLoaded): ?>
	<?php echo $this->Html->script("flot/jquery.flot.js"); ?>
	<?php echo $this->Html->script("flot/base64.js"); ?>
	<?php echo $this->Html->script("flot/canvas2image.js"); ?>
	<?php echo $this->Html->script("flot/jquery.flot.saveAsImage.js"); ?>
	<?php echo $this->Html->script("flot/jquery.flot.selection.js"); ?>
	<?php echo $this->Html->script("flot/jquery.flot.axislabels.js"); //Currently breaks the display/hide functionality of the plots ?>
	<?php echo $this->Html->script("flot/jquery.flot.resize.js"); ?>
<?php endif; ?>
<style type="text/css">
.plot-container {
	float:left;
	box-sizing: border-box;
	width: 900px;
	height: 450px;
	padding: 20px 15px 15px 15px;
	margin: 15px auto 30px auto;
	border: 1px solid #ddd;
	background: #fff;
	background: linear-gradient(#f6f6f6 0, #fff 50px);
	background: -o-linear-gradient(#f6f6f6 0, #fff 50px);
	background: -ms-linear-gradient(#f6f6f6 0, #fff 50px);
	background: -moz-linear-gradient(#f6f6f6 0, #fff 50px);
	background: -webkit-linear-gradient(#f6f6f6 0, #fff 50px);
	box-shadow: 0 3px 10px rgba(0,0,0,0.15);
	-o-box-shadow: 0 3px 10px rgba(0,0,0,0.1);
	-ms-box-shadow: 0 3px 10px rgba(0,0,0,0.1);
	-moz-box-shadow: 0 3px 10px rgba(0,0,0,0.1);
	-webkit-box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.plot-placeholder {
	width: 100%;
	height: 100%;
	font-size: 14px;
	line-height: 1.2em;
}
.plotInfo {
	margin:  5px;
	padding: 5px;
	border: solid 1px black;
	border-radius: 4px;
	min-width:200px;
}
.input_axis_limit {
   width: 10ch;
   text-align: right;
   font-size: 100%;
}
</style>

<script type="text/javascript">
	var plot = null;
	var overview = null;
	var plotTypeX  = "nothing";
	var plotTypeY1 = "nothing";
	var plotTypeY2 = "nothing";
	var logarithmicY1 = false;
	var logarithmicY2 = false;
	var hideLegend = false;
	var seriesIndex = 0;
	
	var currentColorNum = 0;
	
	var xAxis = null;
	var yAxis1 = null;
	var yAxis2 = null;
	
	Math.log10 = function(v){
			if(v<0){
				return 0;
			}else{
				return Math.log(v) / Math.LN10;
			}
		}
	// var modReverse = {
		// nothing: function(v){return v;},
		// invert: function(v){ v = parseFloat(v); return -v;},
		// abs: function(v){ v = parseFloat(v); return Math.abs(v);},
		// square: function(v){ v = parseFloat(v); return Math.sqrt(v);},
		// inverse: function(v){ v = parseFloat(v); return v==0 ? null: (1/v);},
		// log: function(v){ v = parseFloat(v); return Math.pow(10,v);},
		// sqroot: function(v){ v = parseFloat(v); return v <0 ? null: Math.pow(v,2)},
		// inverseSquare: function(v){
			// return mod.sqroot(mod.inverse(v));
			// },
	// }

	var mod = {
		nothing: function(v){return v;},
		invert: function(v){ v = parseFloat(v); return -v;},
		abs: function(v){ v = parseFloat(v); return Math.abs(v);},
		square: function(v){ v = parseFloat(v); return Math.pow(v,2);},
		inverse: function(v){ v = parseFloat(v); return v==0 ? null: (1/v);},
		log: function(v){ v = parseFloat(v); return v <= 0 ? null : Math.log10(v);},
		sqroot: function(v){ v = parseFloat(v); return v <0 ? null: Math.sqroot(v)},
		inverseSquare: function(v){
			return mod.inverse(mod.square(v));
			},
	}
	var data = [];
	var options = {
      series: {
         bars: {
            show: false,
            barWidth: 5,
            align: "center",
         },
      },
		lines: {
			show: true
		},
		points: {
			show: false
		},
		xaxis: {
			
		},
		grid:{
			hoverable: true,	
		},
		xaxes: [{
			axisLabel: "",
		}],
		yaxes: [{
			tickFormatter: niceLabelFormat,
		},{
			tickFormatter: niceLabelFormat,
			position: "right"
		}],
		legend: {
			labelFormatter: function(label, series){
				linkHTML = '<a href="#" onClick="reverseTogglePlot('+seriesIndex+'); return false;" onContextMenu="togglePlot('+seriesIndex+'); return false;" style="text-decoration:none;">'+label+'</a>';
				seriesIndex += 1;
            //console.log(series);
				return linkHTML;
			}
		},
		hooks: { processDatapoints: [conversionHook] },
		selection: {
			mode: "xy"
		}
      //yAxis format for logarithmic output, maybe need that some day
      //yaxis: {  ticks: [0.001,0.01,0.1,1,10,100],
      //          tickDecimals: 3 },
	};

	//Helper function to clone objects
	function clone(obj) {
		if (null == obj || "object" != typeof obj) return obj;
		var copy = obj.constructor();
		for (var attr in obj) {
			if (obj.hasOwnProperty(attr)) copy[attr] = obj[attr];
		}
		return copy;
	}
	function conversionHook(plot,series,datapoints){
		if(series.yaxis.n == 1){
			plotType = plotTypeY1;
		}else{
			plotType = plotTypeY2;
		}
		for(var i=0;i<datapoints.points.length;i=i+2){
			datapoints.points[i] = mod[plotTypeX](datapoints.points[i]);
			datapoints.points[i+1] = mod[plotType](datapoints.points[i+1]);
		}
	}

	function niceLabelFormat(v,axis){
		if(v == 0) return 0;
		exponent = Math.floor(Math.log10(Math.abs(v))); //absolute value to make sure that negative values are also correctly interpreted by the log10 function
		quotient = Math.floor(exponent/3);  //get the quotient of a division by three since we want to group the values by a three
		if(quotient>0){ //add an additional + for the exponent in case of it beeing positive
			factor = "e+"+(3*quotient);
		}else if(quotient<0){
			factor = "e"+(3*quotient);
		}else{
			factor = "";
		}
		preValue = v/Math.pow(10,3*quotient);

		condition = (preValue>0)? (preValue.toFixed(1)-Math.floor(preValue))!=0 :(preValue.toFixed(1)-Math.floor(preValue))!=1;

		if(condition){
			return preValue.toFixed(1).toString()+factor; //one number after the point if there is a number after the point
		}else{
			return preValue.toFixed(0).toString()+factor; //no number after the point if there is none because it would be zero
		}
	}

	togglePlot = function(seriesIdx)
		{
			var someData = plot.getData();
         //console.log(someData[seriesIdx].points);
			someData[seriesIdx].lines.show = !someData[seriesIdx].lines.show;
			plotMainPlot(someData,true);
		}

	reverseTogglePlot = function(seriesIdx)
		{
			var someData = plot.getData();
         //console.log(someData[seriesIdx].lines);
			for(var i=0; i<someData.length;i++){
				someData[i].lines.show = i==seriesIdx?true:false;
			}
			plotMainPlot(someData,true);
		}

   function updateXMinMax() {
      min = $("input#xmin").val();
      max = $("input#xmax").val();
      plot.getOptions().xaxes[0].min = min;
      plot.getOptions().xaxes[0].max = max;
      plot.setupGrid();
      plot.draw();
   }
   function updateYMinMax(which) {
      min = $("input#ymin"+which).val();
      max = $("input#ymax"+which).val();
      plot.getOptions().yaxes[which].min = min;
      plot.getOptions().yaxes[which].max = max;
      plot.setupGrid();
      plot.draw();
   }

	function setPlotType(type,axis){
		if(axis == 0){
			plotTypeX = type;
			console.log("set plot type of x-axis to "+plotTypeX);
		}else if(axis == 1){
			plotTypeY1 = type;
			console.log("set plot type of y-axis1 to "+plotTypeY1);
		}else{
			plotTypeY2 = type;
			console.log("set plot type of y-axis2 to "+plotTypeY2);
		}
		if(logarithmicY1)
			yAxisLogOn(0);
		else
			yAxisLogOff(0);
		
		if(logarithmicY2)
			yAxisLogOn(1);
		else
			yAxisLogOff(1);
		onDataReceivedReplace(data);
	}
	//For x-axis absolute plot
	//options["xaxis"].transform = function(v){return Math.abs(v);}
	//Need to take care of the axis descriptions

	function yAxisLogOff(axis){
		options["yaxes"][axis].transform = mod["nothing"];
		options["yaxes"][axis].tickFormatter = niceLabelFormat;
	}


	function yAxisLogOn(axis){
		console.log("activating log");
      options["yaxes"][axis].transform = mod["log"];
      // options["yaxis"].tickFormatter = function(v,axis){
			// return niceLabelFormat(mod["log"](v),axis);
		// }
	}

	function modificationsOff(){
		setPlotType("nothing",0);
		setPlotType("nothing",1);
		setPlotType("nothing",2);
		return 
	}

	function toggleLog(axis){
		if(axis == 1){
			logarithmicY1 = !logarithmicY1;
			setPlotType(plotTypeY1,axis);
		}else{
			logarithmicY2 = !logarithmicY2;
			setPlotType(plotTypeY2,axis);
		}

	}
	
	function toggleLegend(){
		hideLegend = !hideLegend;
		onDataReceivedReplace(data,true);
	}
   function toggleBars() {
      plot.getOptions().series.bars.show = !plot.getOptions().series.bars.show;
      if(plot.getOptions().series.bars.show) {
         tmp = {series:{bars:{show:true}}};
      } else {
         tmp = {series:{lines:{show:true}}};
      }
      $.plot($('#placeholder'), data, tmp);
   }
	function plotMainPlot(data,keepZoomStatus){
		options.legend.show = !hideLegend;
		options.points.radius = 3;
		seriesIndex = 0; //Set the series index to 0 ever render to make sure the label click works
      //console.log("start");
		if(keepZoomStatus){
			ranges = plot.getAxes(); //Get the current axis values to set the min and max of the axis again
         //console.log(ranges);
			plot = $.plot("#placeholder", data,
				$.extend(true, {}, options, {
					xaxis: { min: ranges.xaxis.min, max: ranges.xaxis.max },
					yaxes: [{ min: ranges.yaxis.min, max: ranges.yaxis.max },
						{ min: ranges.y2axis.min, max: ranges.y2axis.max }]
				})
			);
		}else{
			plot = $.plot("#placeholder", data, options);
		}
      //console.log(seriesIndex);
      $("input#xmin").val(plot.getAxes().xaxis.min);
      $("input#xmax").val(plot.getAxes().xaxis.max);
      $("input#ymin0").val(plot.getAxes().yaxis.min);
      $("input#ymax0").val(plot.getAxes().yaxis.max);
      $("input#ymin1").val(plot.getAxes().y2axis.min);
      $("input#ymax1").val(plot.getAxes().y2axis.max);
	}

	function plotData(data,keepAxis){
		if(keepAxis == undefined){
			keepAxis = false;
		}
		options.legend.show = false;
		options.points.radius = 1;
      //console.log(data);
		if(data[0] !== undefined){
			if(typeof data[0].xAxisLabel !== undefined){
				options.xaxes =  [{axisLabel: data[0].xAxisLabel}];
			}
		}
		overview = $.plot("#overview", data, options);

		plotMainPlot(data,keepAxis);
      //console.log(seriesIndex);
		// now connect the two

		$("#placeholder").dblclick(function(){
			plotMainPlot(data,keepAxis);
			overview.clearSelection();
		});

		$("#placeholder").bind("plotselected", function (event, ranges) {

			seriesIndex = 0;
			plot = $.plot("#placeholder", plot.getData(),
				$.extend(true, {}, options, {
					xaxis: { min: ranges.xaxis.from, max: ranges.xaxis.to },
					yaxes: [{ min: ranges.yaxis.from, max: ranges.yaxis.to },
						{ min: ranges.y2axis.from, max: ranges.y2axis.to }]
				})
			);

			// don't fire event on the overview to prevent eternal loop

			overview.setSelection(ranges, true);
		});

		$("#overview").bind("plotselected", function (event, ranges) {
			plot.setSelection(ranges);
		});
	}

	$(function() {
		$("input[name=xCalc]:radio").change(function(){
			setPlotType($(this).val(),0);
		});
		$("input[name=yCalc1]:radio").change(function(){
			setPlotType($(this).val(),1);
		});
		$("input[name=yCalc2]:radio").change(function(){
			setPlotType($(this).val(),2);
		});

		plotData(data);
		$("#plot_output").resizable({
			maxWidth: 900,
			maxHeight: 500,
			minWidth: 450,
			minHeight: 250
		});

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

		$("#measurement_header").append($("#plot_control").fadeIn(500));
		$("#measurement_header").append($("#plot_output").fadeIn(500));
		$("#measurement_header").append($("#plot_selection").fadeIn(500));
	});
	var overlayCloseButton = '<div style="float: right;"><?php echo $this->Html->image("ElegantBlueWeb/xmark.png",array("alt"=>"X","width"=>"20","onClick"=>"closeSelectorOverlay();","style"=>"cursor:pointer; z-index:5;")); ?></div>';

	function createPlotButton(){
		if($("#x_axis_selection").html().indexOf("none") < 0 && $("#y_axis_selection").html().indexOf("none") < 0){
			if($('#update_plot').length<1){ //Only insert update button if it doesn't exist yet
				$("#plot_control").append('<input type="button" value="update Plot" id="update_plot" style="display: none">');
				$("#update_plot").click(function(){
					addPlot(<?php echo $measurementPlotId; ?>,$("#x_axis_selection_id").attr("value"),$("#y_axis_selection_id").attr("value"));
					//xAxis = $("#x_axis_selection_id").attr("value");
					//yAxis1 = $("#y_axis_selection_id").attr("value");
					//dataurl = '<?php //echo $this->Html->url(array("controller"=>"measurements","action"=>"getDataset",$measurementPlotId));?>///'+$("#x_axis_selection_id").attr("value")+'/'+$("#y_axis_selection_id").attr("value");
					//$.ajax({
						//url : dataurl,
						//type: "GET",
						//dataType: "json",
						//success: function(data){
							//data[0].yaxis = 1; //Set target axis as the first one since this is the first plot
							//data[0].xaxisId = xAxis;
							//data[0].yaxisId = yAxis1;
							//onDataReceivedReplace(data);
							//colorOfNewInsert = plot.getData()[plot.getData().length-1].color;
							//itemCode = data[0].item.code;
							//displayField = data[0].yAxisLabel;
							//info = "<span style='width:15px;height:15px; display: inline-block; background-color:"+colorOfNewInsert+"'>&nbsp;</span> "+displayField+" of "+itemCode+"<br />";
							////Set the information of this measurement also in the displaying window for multiple measurements so the data stays available. 
							////Display links to items and measurements for reference. 
							//$("#plot_selection").append("<div class='plotInfo'>"+info+"</div>");
						//}
					//});
				});
			}
			$("#update_plot").click();
			// var someData = somePlot.getData();
			// someData[0].lines.show = false;
			// console.log(someData);
			// $(someData).each(function(){
				// $("#plotsToDisplay").append($(this)[0].label);
			// });
			// somePlot.setData(someData);
			// somePlot.draw();
		}
		return false
	}
	function onDataReceivedReplace(series,keepAxis) {
		if(keepAxis == undefined){
			keepAxis = false;
		}
		if(series[0] == undefined){
			data[0] = series;
		}else{
			data = series;
		}
		plotData(data,keepAxis);
		
		// unsetMinMax();
		// plotWithoutMinMaxCalculation();
		// setMinMaxStepsize(somePlot.getAxes()["yaxis"].datamin,somePlot.getAxes()["yaxis"].datamax);
		// setMinMax(somePlot.getAxes()["yaxis"].datamin,somePlot.getAxes()["yaxis"].datamax);
		// plotWithoutMinMaxCalculation();
	}
	function onDataReceivedAdd(series,keepAxis) {
		if(keepAxis == undefined){
			keepAxis = false;
		}
		data.push(series);
		plotData(data,keepAxis);
		// unsetMinMax();
		// somePlot = $.plot("#placeholder", data, options);
	}
	
	function addPlot(measurementId,x,y){
      //console.log(measurementId,x,y);
		var replacePlot = false;
		if(xAxis == null){
			xAxis = x;
		}else if(xAxis != x){
			//Replace the whole plot since the x-axis is newly defined
         //console.log("replacing plot");
			xAxis = x;
			yAxis1 = null;
			yAxis2 = null;
			replacePlot = true;
		}
		
		if(yAxis1 == null){
			yAxis1 = y;
			yAxis = 1;
		}else if(yAxis1 != y){
			if(yAxis2 == null){
				yAxis2 = y;
				yAxis = 2;
			}else if(yAxis2 != y){
				alert("both yAxis don't match, cannot plot this. ");
				return false;
			}else{
				yAxis = 2;
			}
		}else{
			yAxis = 1;
		}
		
		//Check if Measurement is already plotted
		var abortPlot = false;
		$(data).each(function(a,b){
         //console.log(b.measurementId,measurementId);
         //console.log(b.xaxisId,x);
         //console.log(b.yaxisId,y);
			if(b.measurementId == measurementId && b.xaxisId == x && b.yaxisId == y) abortPlot = true;
		});
		if(abortPlot){
			alert("This Measurement is already in the plot");
			return false;
		} 
		dataurl = '<?php echo $this->Html->url(array("controller"=>"measurements","action"=>"getDataset"));?>/'+measurementId+'/'+x+'/'+y;
		$.ajax({
			url : dataurl,
			type: "GET",
			dataType: "json",
			success: function(data){
				$(data).each(function(count,subdata){
					subdata.yaxis = yAxis; //Set target axis in just received data so it is assigned correctly
					subdata.xaxisId = x;
					subdata.yaxisId = y;
					if(replacePlot){
						currentColorNum = 1;
						subdata.color = 0; //Set starting index for new plot colors to 0
						onDataReceivedReplace(subdata);
						$("#plot_selection").find(".plotInfo").remove();
						replacePlot = false;
					}else{
						subdata.color = currentColorNum; //Set the index of this plot to a color so the color is kept when removing one in between
						currentColorNum += 1;
						onDataReceivedAdd(subdata);
					}
					//Display color for measurement using plot.getData()[i].color
					colorOfNewInsert = plot.getData()[plot.getData().length-1].color;
					itemCode = subdata.item.code;
					displayField = subdata.yAxisLabel;
					info = "<span style='width:17px;height:17px; display: inline-block; background-color:"+colorOfNewInsert+"'>&nbsp;</span> ";
					info += displayField+' of '+itemCode;
					//First generate the image
					img = '<?php echo $this->Html->image('ElegantBlueWeb/xmark.png',array('alt'=>'remove Plot','width'=>'18','onClick'=>'removePlot();','style'=>'cursor:pointer; z-index:5; float:right; margin-left:10px;')); ?>';
					//Then replace the function call with the one containing the javascript parameters
					img = img.replace('removePlot();','removePlot('+measurementId+','+x+','+y+',this)');
					//Put it together
					info += img;
					
					//Add Time the measurement was done
					info += '<br />Date: '+subdata.date;
					//Add Tags if not empty
					if(subdata.tags != ""){ 
						info += '<br />Tags: '+subdata.tags;
					}
					//Set the information of this measurement also in the displaying window for multiple measurements so the data stays available. 
					//Display links to items and measurements for reference. 
					$("#plot_selection").append("<div class='plotInfo'>"+info+"</div>");
				});
				
			}
		});
	}
	
	function removePlot(measurementId,x,y,elem){
		$(data).each(function(a,b){
			if(b.measurementId == measurementId && b.xaxisId == x && b.yaxisId == y){
				axisNum = b.yaxis;
				data.splice(a,1);
			}
		});
		$(elem).parent().fadeOut(200);
		//Reset the yAxis1 or yAxis2 variable to null if all plots from either are removed. 
		resetyAxis = true;
		$(data).each(function(a,b){
			if(b.yaxisId == y){
				resetYAxis = false
			}
		});
		if(resetyAxis){
			if(axisNum==1){
				yAxis1 = null;
			}else{
				yAxis2 = null;
			}
		}
		
		plotData(data);
	}

	function resetPlot(){
		data = [];
		plotData(data);
		// unsetMinMax();
		// somePlot = $.plot("#placeholder", data, options);
	}
	
	function openSelectorOverlay(url,pos){
		//position contains object with top and left of the button that has been clicked
		var size = {width:1000,height:400}; //Define the size of the overlay
		//Special treatment for the left side because it should not cover the main menu, therefore always at least 200px space to the left, if necessary resize width
		if((pos.left-size.width)<230){
			size.width = pos.left-230;
		}
		if((pos.top-size.height)<10){
			pos.top = size.height+10;
		}
		var targetPos = {width:size.width, height:size.height, left:(pos.left-size.width), top:(pos.top-size.height)};
		//Create the overlay and attach it to the body
		if($("#selectorOverlay").attr("id") == undefined){
			$("#container").append("<div id='selectorOverlay'></div>");
			$("#selectorOverlay").attr("style","top:"+pos.top+"px; left:"+pos.left+"px; width:0px; height:0px;");
			overlayCloseButton = '<div style="position:absolute; left:'+(pos.left-10)+'px; top:'+(pos.top)+'px;" id="overlayCloseButton"><?php echo $this->Html->image("ElegantBlueWeb/xmark.png",array("alt"=>"X","width"=>"20","onClick"=>"closeSelectorOverlay();","style"=>"cursor:pointer; z-index:5;")); ?></div>';
			$("#selectorOverlay").parent().append(overlayCloseButton);
		}
		
		loadDataIntoOverlay(url);
		$("#selectorOverlay").animate(targetPos,300);
		$("#overlayCloseButton").animate({left:(pos.left-10),top:(pos.top-size.height-10)},300);
		
	}
	
	function closeSelectorOverlay(){
		targetPos = $("#selectorOverlay").offset();
		targetPos.left += $("#selectorOverlay").width();
		targetPos.top += $("#selectorOverlay").height();
		targetPos.left += "px";
		targetPos.top += "px";
		targetPos.width = "0px";
		targetPos.height = "0px";
		$("#overlayCloseButton").animate({left:targetPos.left,top:targetPos.top},300,function(){
			$("#overlayCloseButton").remove();
		});
		$("#selectorOverlay").html("").animate(targetPos,300,function(){
			$("#selectorOverlay").remove();
		});
		
	}
	
	function loadDataIntoOverlay(url,postData){
		$("#selectorOverlay").load(url,function(){
		});
	}

</script>
<div id="plot_control" style="width:250px; min-height:240px; margin:10px; float:left; display:none;">
		<table>
			<tr><td><?php echo __('X-Axis (Right click)'); ?></td><td colspan="2" id="x_axis_selection">none</td></tr>
			<tr><td><?php echo __('Y-Axis (Left click)'); ?></td><td colspan ="2" id="y_axis_selection">none</td></tr>
			<tr>
				<td>Left y-axis<br />
					<input type='radio' value="nothing" name="yCalc1" id="calcNothing" checked/><label for="calcNothing">y = y</label><br />
					<input type='radio' value="inverseSquare" name="yCalc1" id="calcInverseSquare"/><label for="calcInverseSquare">y = 1/y^2</label><br />
					<input type='radio' value="abs" name="yCalc1" id="calcAbs"/><label for="calcAbs">y = abs(y)</label><br />
					<label for="plotLogarithmic"><input type="checkbox" value="logarithmic" name="logarithmicY1" id="plotLogarithmicY1" onclick="toggleLog(1);"/> Log y</label><br>
				</td>
				<td>
					Right y-axis<br />
					<input type='radio' value="nothing" name="yCalc2" id="calcNothing" checked/><label for="calcNothing">y = y</label><br />
					<input type='radio' value="inverseSquare" name="yCalc2" id="calcInverseSquare"/><label for="calcInverseSquare">y = 1/y^2</label><br />
					<input type='radio' value="abs" name="yCalc2" id="calcAbs"/><label for="calcAbs">y = abs(y)</label><br />
					<label for="plotLogarithmic"><input type="checkbox" value="logarithmic" name="logarithmicY2" id="plotLogarithmicY2" onclick="toggleLog(2);"/> Log y</label>
				</td>
				<td>
					<input type='radio' value="nothing" name="xCalc" id="calcNothing" checked/><label for="calcNothing">x = x</label><br />
					<input type='radio' value="abs" name="xCalc" id="calcAbs"/><label for="calcNothing">x = abs(x)</label><br />
				</td>
			</tr>
         <tr><td>X min</td><td><input type="text" id="xmin" value="" class="input_axis_limit"></td><td rowspan="2"><br><button onclick="updateXMinMax()">redraw</button></td></tr>
         <tr><td>X max</td><td><input type="text" id="xmax" value="" class="input_axis_limit"></td></tr>
         <tr><td>left Y min</td><td><input type="text" id="ymin0" value="" class="input_axis_limit"></td><td rowspan="2"><br><button onclick="updateYMinMax(0)">redraw</button></td></tr>
         <tr><td>left Y max</td><td><input type="text" id="ymax0" value="" class="input_axis_limit"></td></tr>
         <tr><td>right Y min</td><td><input type="text" id="ymin1" value="" class="input_axis_limit"></td><td rowspan="2"><br><button onclick="updateYMinMax(1)">redraw</button></td></tr>
         <tr><td>right Y max</td><td><input type="text" id="ymax1" value="" class="input_axis_limit"></td></tr>
			<tr>
				<td colspan="2">
					<input type="button" value="Show/Hide Legend" onclick="toggleLegend()" />
				</td>
            <td>
					<!--input type="button" value="Bars on/off" onclick="toggleBars()" /-->&nbsp;
				</td>
			</tr>
		</table>
<!--	<input type='radio' value="square" name="yCalc" id="calcSquare"/><label for="calcSquare">y = y^2</label><br />
		<input type='radio' value="inverse" name="yCalc" id="calcInverse"/><label for="calcInverse">y = 1/y</label><br />
		<input type='radio' value="invert" name="yCalc" id="calcInvert"/><label for="calcInvert">y = -y</label><br />-->
	<!-- // <label for="plotMax">Plot Maximum: <input type="number" step="0.0001" value="0.0000" name="plotMax" id="plotMax" onchange='triggerMinMaxChange($("#plotMin").val(),$(this).val())' /></label>
	// <label for="plotMax">Plot Minimum: <input type="number" step="0.0001" value="0.0000" name="plotMin" id="plotMin" onchange='triggerMinMaxChange($(this).val(),$("#plotMax").val())' /></label> -->
</div>
<div id='plot_output' class='plot-container' style='display:none;'>
	<div id="placeholder" class='plot-placeholder' style="width:70%; height:90%; float:left; "></div>
	<div id='overview' class='plot-placeholder' style='float:right; width:30%; height:39%;'></div>
	Drag to select area of interest on either of the plots. <br />Doubleclick on the main plot to reset the selection.
</div>
<div id='plot_selection' style="float: right; ">
	<input type="button" value="Add Measurement" onclick="openSelectorOverlay('<?php echo $this->Html->url(array("controller"=>"measurements","action"=>"getSimilarMeasurements",$measurement["Measurement"]["id"])) ?>',$(this).offset())" /><br />
	
To add another column of the same measurement just left-click on the column name. 
</div>
