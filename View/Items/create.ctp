
<div class='items form'>

<script type='text/javascript'>
//Need to set the Prefix for the filter used within the standard_selector
var prefix = "ItemAdd";
var dataFromStepOne = "";
var dataFromStepTwo = "";
var dataFromStepThree = "";

//<![CDATA[
$(document).ready(function () {
	$("form.stepOne").bind("submit", function (event) {
		//Check if all mandatory fields have values before submitting
		var allRequiredSelected = true;
		$(".required select").each(function(){
			$(this).parent().removeClass("error");
			if($(this).val()==null){
				allRequiredSelected = false;
				$(this).parent().addClass("error");
			}
		});
		if(allRequiredSelected){
			$("#ItemCreateStepTwo").css("height","auto");
			dataFromStepOne = $("form.stepOne").serialize();
			$.ajax(
				{
					async:true,
					data: dataFromStepOne,
					dataType:"html",
					success:function (data, textStatus) {
							//Put the data into the step two field and animate it
							$("#ItemCreateStepTwo").html(data);
							var heightWithText = $("#ItemCreateStepTwo").height();
							$("#ItemCreateStepTwo").height("0px").animate({height:heightWithText+"px"},300,function(){$(this).css("height","auto");})
						},
					type:"POST",
					url:"<?php echo $this->Html->url(array("controller"=>"Items","action"=>"create"))?>"
				}
			);
			//Deactivate all form fields in the top
			$(".stepOne select").attr("disabled",true);
			//Need to implement something to check the string length of the selected string and set the target width accordingly
			$(".stepOne select").animate({width:"120px",height:"12pt"},300,function(){$(".stepOne select").attr("size",1);});

			//replace the submit button with a reset button to return to this step
			$("form.stepOne .submit").html('<input type="button" onClick="resetToStepOne();" value="Return to step one">');
		}
	});
	return false;
});
//]]>

function resetToStepOne(){
	$("#ItemCreateStepTwo").animate({height:"0px"},300,function(){$(this).html("");$(this).height("auto");});
	resetToStepTwo();
	$(".stepOne select").attr("disabled",false);
	$(".stepOne select").attr("size",8);
	$(".stepOne select").animate({width:"200px",height:"120pt"},300,function(){$("form.stepOne select").css("height","auto");});
	$(".stepOne .submit").html('<input type="submit" value="Continue">');
}

function resetToStepTwo(){
	$("#ItemCreateStepThree").animate({height:"0px"},300,function(){$(this).html("");$(this).height("auto");});
	//Get back the previously stored data of the second iteration and replace the displaying values with the editable
	$(".stepTwo .submit").html('<input type="submit" value="Continue">');
}

</script>
<?php
echo $this->Form->create('Item',array("default"=>false,"class"=>"stepOne"));
echo $this->element("standard_selector");
echo $this->Form->end(__('Continue'));

?>

<!-- <h3>Second part: Depending on the selection in the first part, show via ajax request</h3>
<ul>
	<li>ItemType != Wafer</li>
	<ul>
		<li>Show Item Quality and Tags field that depend on the first selection and also the field to enter the unique code. </li>
		<li>Present checkbox to set the item as Stock item, visually deactivating the code field and activating a numeric field for the amount</li>
	</ul>
	<li>ItemType == Wafer</li>
	<ul>
		<li>If the ItemType Selected in the first part is a Wafer show the Components with their checkboxes and names</li>
	</ul>
</ul> -->
<div id='ItemCreateStepTwo'>
</div>
<div id='ItemCreateStepThree'>
</div>
<div id='ItemCreateStepFour'>
</div>
</div>
<div id='verticalmenu'>
	<h2><?php echo __('Create'); ?></h2>
	<ul>
		<li class="active last"><?php echo $this->Html->link(__('Cancel'), array('controller' => 'items', 'action' => 'index')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
