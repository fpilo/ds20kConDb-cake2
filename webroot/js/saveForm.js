$(document).ready(function(){

	$('#ItemCode').blur(function(){
		$.post(
			'/cakephp/ds20kcondb/items/saveForm/',
			//'<?php echo $this->Html->url(array("controller" => "items", "action" => "saveForm")); ?>/',
			{field:  'code', value: $('#ItemCode').val(), formName: 'AssembleItemComposition' },
			false
		);
	});

	$('#ItemComment').blur(function(){
		$.post(
			'/cakephp/ds20kcondb/items/saveForm/',
			//'<?php echo $this->Html->url(array("controller" => "items", "action" => "saveForm")); ?>/',
			{field:  'comment', value: $('#ItemComment').val(), formName: 'AssembleItemComposition' },
			false
		);
	});

	$('#ItemStateId').blur(function(){
		$.post(
			'/cakephp/ds20kcondb/items/saveForm/',
			//'<?php echo $this->Html->url(array("controller" => "items", "action" => "saveForm")); ?>/',
			{field:  'state_id', value: $('#ItemStateId').val(), formName: 'AssembleItemComposition' },
			false
		);
	});

	//*
	function handleCodeValidation(error){
		$('#code-notEmpty').remove();
		if(error.length > 0){
			$('#ItemCode').after('<div id="code-notEmpty" class="error-message">'+ error +'</div>');
		}
	}
	//*/
});

