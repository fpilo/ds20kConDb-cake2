<?php require(dirname(__FILE__).'/header.ctp'); ?>
<?php echo $this->Html->script("flot/jquery.flot.js"); ?>
<?php echo $this->Html->script("flot/base64.js"); ?>
<?php echo $this->Html->script("flot/canvas2image.js"); ?>
<?php echo $this->Html->script("flot/jquery.flot.saveAsImage.js"); ?>
<?php echo $this->Html->script("flot/jquery.flot.selection.js"); ?>
<?php echo $this->Html->script("flot/jquery.flot.axislabels.js"); ?>
<?php echo $this->Html->script("flot/jquery.flot.resize.js"); ?>
<script type="text/javascript">

	var overwritePlots = true;
	var plotChips = true;
	var localDataStorage = {};
	var loadingGif = '<?php echo $this->Html->image("loading.gif",array("width"=>"100"));?>';

	function plotStrip(chip,strip){
		options["legend"] = {show:true};
		if(typeof localDataStorage[chip] === 'undefined' || typeof localDataStorage[chip][strip] === 'undefined') { //Check for the existance of the section in the local storage variable (should always exist this is just a backup)
			$.ajax({
				url: '<?php echo $this->Html->url(array("controller"=>"measurements","action"=>"view",$measurement['Measurement']['id']));?>',
				type: "GET",
				data : {"chip": chip, "strip": strip,"xParam":$("input[name=xParam]:checked").val(),"yParam":$("input[name=yParam]:checked").val()},
				dataType : "json",
				success: function(newPlotData){
					if(typeof localDataStorage[chip] === 'undefined'){ //check if the chip has been set before and if not create it
						localDataStorage[chip] = {};
					}
					localDataStorage[chip][strip] = newPlotData[0];
					// console.log(newPlotData);
					onDataReceivedReplace(localDataStorage[chip][strip],true);
				}
			});
		}else{
			// console.log([localDataStorage[chip][strip]]);
			onDataReceivedReplace([localDataStorage[chip][strip]],true);
		}
	}
	function plotStrips(chip){
		if(typeof localDataStorage[chip] === 'undefined'){ //check if the chip has been set before and if not create it
			$.ajax({
				url: '<?php echo $this->Html->url(array("controller"=>"measurements","action"=>"view",$measurement['Measurement']['id']));?>',
				type: "GET",
				data : {"chip": chip,"allstrips":true,"xParam":$("input[name=xParam]:checked").val(),"yParam":$("input[name=yParam]:checked").val()},
				dataType : "json",
				success: function(newPlotData){
					localDataStorage[chip] = newPlotData;
					onDataReceivedReplace(localDataStorage[chip]);
				}
			});
		}else{
			onDataReceivedReplace(localDataStorage[chip]);
		}
	}
	function plotStripAdd(chip,strip){
		$.ajax({
			url: '<?php echo $this->Html->url(array("controller"=>"measurements","action"=>"view",$measurement['Measurement']['id']));?>',
			type: "GET",
			data : {"chip": chip, "strip": strip,"xParam":$("input[name=xParam]:checked").val(),"yParam":$("input[name=yParam]:checked").val()},
			dataType : "json",
			success: onDataReceivedAdd
		});
	}

	function displayStripTable(chip,strip){
		$.get( '<?php echo $this->Html->url(array("controller"=>"measurements","action"=>"view",$measurement['Measurement']['id']));?>',
				{"chip": chip, "strip": strip,"table":true,"xParam":$("input[name=xParam]:checked").val(),"yParam":$("input[name=yParam]:checked").val()},
				function( data ) {
					$("#measurementDataTable").html( data ).hide().fadeIn(200);
				}
		);
	}

	//UNUSED
	function plotRecursive(chip,strip,remaining){
		if(remaining>0){
			$.ajax({
				url: '<?php echo $this->Html->url(array("controller"=>"measurements","action"=>"view",$measurement['Measurement']['id']));?>',
				type: "POST",
				data : {"chip": chip, "strip": strip, "strip_range": $("#strip_range").val(),"xParam":$("input[name=xParam]:checked").val(),"yParam":$("input[name=yParam]:checked").val()},
				dataType : "json",
				success: function(data){
					onDataReceivedAdd(data);
					remaining--;
					strip++;
					plotRecursive(chip,strip,remaining);
				}
			});
		}
	}

	$(function() {
		//After Page Load
		$("#overwritePlots").change(function(){
			overwritePlots = $(this).attr("checked") ? true : false;
		});
		$("#plotChips").change(function(){
			plotChips = $(this).attr("checked") ? true : false;
			if(plotChips){
				$("#overwritePlots").attr("checked","checked");
				overwritePlots = plotChips;
			}
		});
		
		//if a user changes the selection of the x or y axis delete all local cache for the plots
		$('input[name=xParam]:radio').change(function(){
			localDataStorage = {};
		});
		$('input[name=yParam]:radio').change(function(){
			localDataStorage = {};
		});
		$.ajax({
			url: '<?php echo $this->Html->url(array("controller"=>"measurements","action"=>"view",$measurement['Measurement']['id']));?>',
			type: "GET",
			success: function(data){
				$("#apvdaq_plot").html(data);
				$("#chip").change(function(){
					console.log("chip changed");
					if(plotChips){
						resetPlot();
					}
					var chip = $(this).val();
					if($("#strips").length){
						//Exists, needs to be removed first
						//				$("#strips").parent().fadeOut(200,function(){
						$("#strips").parent().remove();
						//				})
					}
					setTimeout(200);
					$("#intcal_selector").append(loadingGif);
					$.ajax({
						url: '<?php echo $this->Html->url(array("controller"=>"measurements","action"=>"view",$measurement['Measurement']['id']));?>',
						type: "GET",
						data : {"chip": chip,"xParam":$("input[name=xParam]:checked").val(),"yParam":$("input[name=yParam]:checked").val()},
						success: function(data){
							$("#intcal_selector img").replaceWith(data);
							$("#strips").hide().fadeIn(200);
							hideLegend = true;
							if(plotChips){
								//Plot all strips at the beginning
								options["legend"] = {show:false};
								plotStrips(chip);
							}else{
								options["legend"] = {show:true};
							}
							$("#strips").change(function(){
								var strip = $(this).val();
								if(overwritePlots){
									// console.log("overwriting");
									plotStrip(chip,strip);
								}else{
									// console.log("not overwriting");
									plotStripAdd(chip,strip);
								}
								$("#x_axis_selection").html($("input[name=xParam]:checked").val());
								$("#y_axis_selection").html($("input[name=yParam]:checked").val());
								//Only display the strip table if the user requests it
								//displayStripTable(chip,strip);
							});
						},
					});
				});
				//add bind event to enable displaying of said tooltip
				$("#placeholder").bind("plothover", function (event, pos, item) {
					if (item) {
						var x = item.datapoint[0].toFixed(2),
							y = item.datapoint[1].toFixed(2);
						$("#tooltip").html(item.series.label)//  + " of " + x + " = " + y)
							.css({top: item.pageY+5, left: item.pageX+5})
							.fadeIn(200);
					} else {
						$("#tooltip").hide();
					}
				});
				$("#plot_output").resizable({
					maxWidth: 900,
					maxHeight: 500,
					minWidth: 450,
					minHeight: 250
				});
			},
		});
		//Add tooltip to display on hover of line to identify strip
		$("<div id='tooltip'></div>").css({
			position: "absolute",
			display: "none",
			border: "1px solid #fdd",
			padding: "2px",
			"background-color": "#fee",
			opacity: 0.80
		}).appendTo("body");
	});

</script>
<div id="apvdaq_plot">
	<?php echo $this->Html->image("loading.gif");?>
</div>

</div>

<?php require(dirname(__FILE__).'/menu.ctp'); ?>
<div class="related view" id="measurementDataTable">
</div>
