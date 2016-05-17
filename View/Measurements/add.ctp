<?php
	if($itemId !== null) $this->Html->addCrumb($itemCode, array("controller"=>"items","action"=>"view",$itemId,"#"=>"measurements"));
	else $this->Html->addCrumb('Measurements', '/measurements');
	$this->Html->addCrumb('Add Measurement', array("controller"=>"measurements","action"=>"add",$itemId));
?>


<div class="measurements form">
	<h1>Save a Measurement in the Database. </h1>
	<?php if ($itemId != null): echo "<h3>Measurements will be added to item: ";
	echo $this->Html->link($itemCode,array('controller'=>"items",'action'=>"view",$itemId,"#"=>"information"));
	echo "</h3>";
	endif;
	?>
	<h3>The system can currently handle:
		<ul>
			<li>CVI and STR files created by LabView at HEPHY</li>
			<li>CSV files created by APVDAQ Version V0.93 7 July 2014 and later</li>
			<li>It and IV files of measurements in the climate chamber at HEPHY</li>
		</ul>
		<br />
	To add new file layouts to the system a file sample is required. <br />
	Multiple Files can be selected and uploaded at once. <br />
	It is also possible to upload multiple Files in a .zip Archive at once (Useful for slow connections). <br />
	<br />
	Each file generates a preview that then needs to be confirmed. <br />
	Measurements are automatically attached to Items if the item code can be recognized and found in the database<br />
	If an item Code is passed the code is compared to the one in the File and the result is displayed. 
	</h3>
	<?php echo $this->Plupload->loadWidget('jqueryui', array('height' => '330px')); ?>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Measurement'); ?></h2>
	<ul>
	<?php	if($itemId !== null): ?>
		<li><?php echo $this->Html->link(__('Return to Item'), array('controller'=>"items",'action' => 'view',$itemId,"#"=>"measurements")); ?></li>
	<?php endif; ?>
		<li><?php echo $this->Html->link(__('List Measurements'), array('action' => 'index')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>

<div class="measurements form" id="preview"></div>
<div class="measurements" id="debug_output"></div>
<script type="text/javascript">
var num = 1;
function preview(info){

	$.ajax('<?php echo Router::url(array('controller' => 'measurements', 'action' => 'preview')); ?>/'+num,{
		data: info,
		type: "POST",
		success: function(data,textStatus){
			if(data.substr(0,1)!="<"){
				$.each($.parseJSON(data),function(id,file){
					preview({local:file},info,num);
					num += 1;
				})
			}else{
				//Normal preview, echo data to div and activate buttons
				previewDiv(data,info,num);
				num += 1;
			}
		},
		error:function(exception){alert('Exeption:'+exception);}

	});

}

function previewDiv(data,info,num){

	$("#preview").append("<div class='preview' id='preview_area_"+num+"'></div>");
	var preview_area = $('#preview_area_'+num);
	preview_area.hide()
	preview_area.html(data);
	preview_area.fadeIn(500);

	preview_area.find(".delete_measurement").each(function(){
		$(this).click(function(){
			$(this).parent().parent().remove();
		});
	});

	preview_area.find(".measurementTag, .measurementDevice").each(function(){
		$(this).click(function(){
			if($(this).hasClass("selected")){
				$(this).removeClass('selected');
			}else{
				$(this).addClass('selected');
			}
		})
	});

	preview_area.find(".confirm_measurement").each(function(){
		$(this).click(function(){
			data = new Array();
			preview_area.find("th.emptyCol").each(function(){
				data.push($(this).attr("id")); //push cols marked as empty into array
			});
			measurementTags = new Array();
			preview_area.find(".measurementTag.selected").each(function(){
				measurementTags.push($(this).attr("value"));
			});
			measurementDevice = new Array();
			if(preview_area.find(".measurementDevice.selected").length !=1){
				preview_area.find(".measurementDeviceError").html("You have to select the Measurement Setup this measurement was taken with. ").focus();
				preview_area.find(".submitError").html("You have to select the Measurement Setup this measurement was taken with. ");
				return false;
			}
			preview_area.find(".measurementDevice.selected").each(function(){
				measurementDevice = $(this).attr("value");
			});
			preview_area.html('<?php echo $this->Html->image("loading.gif",array("width"=>100)); ?>');
//				console.log(info.local);
			//Save the measurement with the parameters set
			$.post("<?php echo Router::url(array('controller' => 'measurements', 'action' => 'saveData')); ?>",
			{"emptyCols":data,
			"measurementTags":measurementTags,
			"measurementSetup":measurementDevice,
			 "fileName":info.local,
			 "itemId":info.itemId},
			function(result){
				preview_area.html(result);
				preview_area.find(".delete_measurement").each(function(){
					$(this).click(function(){
						$(this).parent().parent().remove();
					});
				});

			});
		});
	});

	var allCells = $(this).find("td, th");
	var className = "emptyCol";

	allCells
	  .on("click", function() {
	    var el = $(this),
	        pos = el.index();
	//    el.parent().find("th, td").addClass("hover");
		allCells.filter(":nth-child(" + (pos+1) + ")").each(function(){
	    if($(this).hasClass(className)){
		    $(this).removeClass(className);
	    }else{
		    $(this).addClass(className);
	    }

		})
	});

}
</script>
