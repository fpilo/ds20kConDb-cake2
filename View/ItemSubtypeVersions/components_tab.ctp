<div id="multiple_comp_add_form">
	<?php echo $this->Plupload->loadWidget('jqueryui', array('height' => '330px')); ?>
	<div id="get_components_div"><?php //debug($this->request->data); ?></div>
</div>

<script type="text/javascript">

var num = 1;
function saveData(info){

	$.ajax('<?php echo Router::url(array('controller' => 'item_subtype_versions', 'action' => 'getComponents')); ?>/',{
		data: info,
		type: "POST",
		success: function(data,textStatus){
			if(data.substr(0,1)!="<"){
				$.each($.parseJSON(data),function(id,file){
			//		preview({local:file},info,num);
					num += 1;
				})
			}else{
				addComponents(data,info,num);
				num += 1;
			}
		},
		error: function(exception){alert('Exception: ' + exception.textStatus);}
	});

}

function addComponents(data,info,num){

	var tableArray = [];
	
	var $div = $("<div>", {id: "tmpDiv"});
	$("#get_components_div").append($div);
	$div.hide();
	$div.html(data);
	//$div.fadeIn(500); //Enable to debug intermediate stages
	
	$("#comp_list_table tr").each(function() {
		var arrayOfThisRow = [];
		var tableData = $(this).find('td');
		if (tableData.length > 0) {
			tableData.each(function() { arrayOfThisRow.push($(this).text()); });
			tableArray.push(arrayOfThisRow);
		}
	});

	if(tableArray.length > 0) addSingleComp(0,tableArray);
	
}

function addSingleComp(index,tableArray){
	
		if(index < (tableArray.length - 1)){
			$.post('<?php echo $this->Html->url(array('controller' => 'itemSubtypeVersions', 'action' => 'addComponent')); ?>/',
				{
					projectId: tableArray[index][0],
					projectName: tableArray[index][1],
					itemSubtypeId: tableArray[index][2],
					itemSubtypeVersionId: tableArray[index][3],
					positionName: tableArray[index][4],
					position: tableArray[index][5],
					session: session_id,
					attached: tableArray[index][6],
					editWithAttached: $("#ItemSubtypeVersionEditWithAttached").val(),
					isLastComp: false
				},
				function(response){
					addSingleComp(index+1,tableArray);
				}
			).fail(function(response) {
				alert('Found an Error with index ' + index + ' : ' + response.responseText);
			});
		}
		else{
			$.post('<?php echo $this->Html->url(array('controller' => 'itemSubtypeVersions', 'action' => 'addComponent')); ?>/',
				{
					projectId: tableArray[index][0],
					projectName: tableArray[index][1],
					itemSubtypeId: tableArray[index][2],
					itemSubtypeVersionId: tableArray[index][3],
					positionName: tableArray[index][4],
					position: tableArray[index][5],
					session: session_id,
					attached: tableArray[index][6],
					editWithAttached: $("#ItemSubtypeVersionEditWithAttached").val(),
					isLastComp: true
				},
				UpdateComponents
			).fail(function(response) {
				alert('Found an Error with index ' + index + ' : ' + response.responseText);
			});
			$("#multiple_comp_add_form").hide();
		}
	
}

</script>

<div id="single_comp_add_form">
	<table class="DefaultSelectors" cellpadding="0" cellspacing="0" id="table_component_subtypes">
			<tr>
			<td colspan="5">
				<?php
					echo $this->element("standard_selector",array(
						"hideLocation" => true,
						"multiple" => true
					));
					echo $this->element("state_selector",array(
						"multiple" => true,
						"label" => 'Required state(s). Select none to allow all.'
					));
				?>
			</td>
		</tr>
		
		<tr>
			<td></td>
			<td></td>
			<td><input type="button" name="AddButton" value="Add Component" id="AddButton" style="width: 150px"/></td>
			<td></td>
			<td></td>
		</tr>
		
	</table>

	<?php
		if($editWithAttached) {
			echo $this->Form->hidden("manufacturer_id");
			echo $this->Form->hidden("ItemSubtypeVersion.version");
		}
	?>
	<?php echo $this->Form->hidden("editWithAttached",array("value"=>$editWithAttached)); ?>
</div>

<?php require(dirname(__FILE__).'/update_components.ctp'); ?>

<br>
<br>
