<?php

// debug($data);
// debug($itemSubtypeVersion);
echo $this->Form->create('Item',array("class"=>"stepTwo", "default"=>false)); ?>
<fieldset>
	<legend><?php  echo __('Create new '.$itemSubtypeVersion['ItemSubtype']['name'].' v'.$itemSubtypeVersion['ItemSubtypeVersion']['version'] .': '); ?></legend>
	<table>
	<?php
		if($this->Session->check('Auth.User.Permissions.controllers/AclManager/Acl/index')){
			//Is admin, show checkbox
			$checkbox = "Don't attach existing components but create them as new (previously known as 'register')".$this->Form->checkbox("create_components",array('hiddenField' => false))."<br />";
			$checkbox .= "Add shortname in the middle of the generated name (only applicable if registering)".$this->Form->checkbox("show_shortName",array());
		}else{
			//is not admin, hidden input depending on itemType
			if($create){
				$checkbox = $this->Form->hidden("create_components",array('value' => "1"));
				$checkbox .= "Add shortname in the middle of the generated name ".$this->Form->checkbox("show_shortName",array());
			}else{
				//isn't wafer, don't set
				$checkbox = "";
			}
		}
		echo $this->Html->tableCells(
			array(
				//Row1
				array(
					// Cell1 of Row1
					array(
						$this->Form->input('code',array('type'=>'textarea','class'=>'codeOrAmount','placeholder'=>'Enter either one or multiple item codes'))."Separate multiple item codes with space or semikolon",
						array("style"=>"border-bottom:0px;")
					),
					// Cell2 of Row1
					array(
						$this->Form->input('comment', array('label' => 'Comment', 'type' => 'textarea')),
						array('rowspan' => '2', "colspan"=>"3", "style"=>"width:75%")
					)
				),
				//Row2
				array(
					// Cell1 of Row2
					array(
						$this->Form->input('amount',array('type'=>"number","min"=>"1",'class'=>'codeOrAmount','placeholder'=>'or enter an amount to create a stock of items'))."This will create an indistinguishable stock of items",
						array("style"=>"background-color:white;")
					)
				),
				//Row3
				array(
					array(
						$this->Form->input('item_quality_id', array('size' => 1, 'style' => 'width: 250px','default' => '6')), //Set default to "not classified"
						array( "style"=>"width:50%")
					),
					array(
						$this->Form->input('item_tags_id', array('size' => 4, 'style' => 'width: 250px','multiple'=>true)),
						array("colspan"=>"1", "style"=>"width:20%")
					),
					array(
						$checkbox,
						array("style"=>"width: 250px;","colspan"=>"1")
					),
				)
			)
		);
	?>
	</table>
</fieldset>

<?php echo $this->Form->end(__('Continue'));?>
<script type='text/javascript'>
	$(function(){
		$(".codeOrAmount").change(function(){ //if in either of these fields a value is changed deactivate all the remaining fields.
			var trigger = $(this);
			if(trigger.val() != ""){
				$(".codeOrAmount").each(function(){
					if($(this).attr("id") != trigger.attr("id")){
						$(this).attr("disabled",true);
					}
				});
			}else{
				//Reactivate all input fields again
				$(".codeOrAmount").each(function(){
					$(this).attr("disabled",false);
				});
			}

		});
		//Bind the action for the step two form
		$("form.stepTwo").bind("submit", function (event) {
			$("#ItemCreateStepThree").html("");
			$("#ItemCreateStepFour").animate({height:"0px"},100,function(){$(this).html("");$(this).height("auto");});
			//Check if all mandatory fields have values before submitting
			var allRequiredSelected = true;

			$("#ItemCode").parent().removeClass("error");
			$("#ItemAmount").parent().removeClass("error");
			$("#ItemItemQualityId").parent().removeClass("error");

			if($("#ItemCode").val() == "" && $("#ItemAmount").val() == ""){
				allRequiredSelected = false;
				$("#ItemCode").parent().addClass("error");
				$("#ItemCode").focus();
				$("#ItemAmount").parent().addClass("error");
			}
			if($("#ItemItemQualityId").val() == ""){
				allRequiredSelected = false;
				$("#ItemItemQualityId").parent().addClass("error");
				$("#ItemItemQualityId").focus();

			}
 			dataFromStepTwo = $("form.stepTwo").serialize();

			if(allRequiredSelected){
				$.ajax(
					{
						async: true,
						data: dataFromStepOne+"&"+dataFromStepTwo,
						dataType: "html",
						success:function (data, textStatus) {
								$("#ItemCreateStepThree").html(data);
								var heightWithText = $("#ItemCreateStepThree").height();
								$("#ItemCreateStepThree").height("0px").animate({height:heightWithText+"px"},300,function(){$(this).css("height","auto");})
							},
						type:"POST",
						url:"<?php echo $this->Html->url(array("controller"=>"Items","action"=>"create"))?>"
					}
				);
				//Get the data from the fields, store the current data and replace it with only displaying values

				$("form.stepTwo .submit").html('<input type="button" onClick="resetToStepTwo();" value="Return to step two">');
				//Deactivate all form fields in the top
			}


		});
		return false;
	});
</script>
