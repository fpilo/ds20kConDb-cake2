<script type='text/javascript'>
//This is javascript required by both the third step in the item assembly as well as the item registring
$(function(){
	$('#tabs').tabs();
	//Trigger on form submit
	$("form.stepThree").submit(function(){
		$("#ItemCreateStepFour").html("");
        $(this).find(".submit input").attr("disabled",true);
        dataFromStepThree = $(this).serialize();
		//Iterate through all the forms in all the tabs for components
		if($("#tabs").length == 0){ //Special treatement for items without components so they also submit
			$.ajax(
				{
					async:true,
					data: dataFromStepOne+"&"+dataFromStepTwo+"&"+dataFromStepThree+"&step=three",
					dataType: "html",
					success:function (data, textStatus) {
							$("#ItemCreateStepFour").html(data);
							var heightWithText = $("#ItemCreateStepFour").height();
							$("#ItemCreateStepFour").height("0px").animate({height:heightWithText+"px"},300,function(){$(this).css("height","auto");})
						},
					type:"POST",
					url:"<?php echo $this->Html->url(array("controller"=>"Items","action"=>"create"))?>"
				}
			);
		}else{
			$("#tabs div form").each(function(){
				dataFromStepThree = $(this).serialize();
				$.ajax(
					{
						async:true,
						data: dataFromStepOne+"&"+dataFromStepTwo+"&"+dataFromStepThree,
						dataType: "html",
						success:function (data, textStatus) {
							$("#ItemCreateStepFour").append(data);
							},
						type:"POST",
						url:"<?php echo $this->Html->url(array("controller"=>"Items","action"=>"create"))?>"
					}
				);
			});
		}
	});
});
</script>
