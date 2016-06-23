$(document).ready(function(){

	restoreTabs('ItemSubtypeVersions'+add_or_edit+'TabIndex');

	//Add new component
	$('#AddButton').click(function(){

		if($('#item_subtype_version_id').val() != null) {
			SendRequest(session_id);
		} else {
			$("#dialog").dialog({
				modal: true,
				buttons: {
					"All Versions": function() {
						SendRequest(session_id);
						$( this ).dialog( "close" );
					},
					Cancel: function() {
						$( this ).dialog( "close" );
					}
				}
			});
		}
	});
		
});
     
		 
function UpdateComponents(component_table){

	$('#component_table_div').remove();
	$('#multiple_comp_add_form').after(component_table);
	$("#accordion").accordion( "refresh" )
	
}

function SendRequest(session_id,edit_with) {
	$.post(
		'<?php echo $this->Html->url(array('controller' => 'itemSubtypeVersions', 'action' => 'addComponent')); ?>/',
		{
			projectId: $('#project_id').val(),
			projectName: $('#project_id option:selected').text(),
			itemSubtypeId: $('#item_subtype_id').val(),
			itemSubtypeVersionId: $('#item_subtype_version_id').val(),
			session: session_id,
			editWithAttached: $("#ItemSubtypeVersionEditWithAttached").val()
		},
		UpdateComponents
	);
}	
 
function RemoveComponent(componentId) {
	$.post(
		 '<?php echo $this->Html->url(array('controller' => 'itemSubtypeVersions', 'action' => 'removeComponent')); ?>/',
		 {componentId: componentId, session: session_id, editWithAttached: $("#ItemSubtypeVersionEditWithAttached").val() },
		 UpdateComponents
	);
}

function RemoveAllComponents(item_subtype_version_id) {
	$.post(
		 '<?php echo $this->Html->url(array('controller' => 'itemSubtypeVersions', 'action' => 'removeAllComponents')); ?>/',
		 {itemSubtypeVersionId: item_subtype_version_id, session: session_id, editWithAttached: $("#ItemSubtypeVersionEditWithAttached").val() },
		 UpdateComponents
	);
}

function PositionChanged(elem) {
	var pos = elem.name.split("][");
	var dummy = pos[1];
	var position = elem.value;

	$.post(
		 '<?php echo $this->Html->url(array('controller' => 'itemSubtypeVersions', 'action' => 'changeComponent')); ?>/',
		 {dummy: dummy, field: 'position', value: position, session: session_id }
	);
}

function AttachedChanged(elem) {
	var pos = elem.name.split("][");
	var dummy = pos[1];
	var attached = elem.checked;

	if(attached)
		 var value = 1;
	else
		 var value = 0;

	$.post(
		 '<?php echo $this->Html->url(array('controller' => 'itemSubtypeVersions', 'action' => 'changeComponent')); ?>/',
		 {dummy: dummy, field: 'attached', value: value, session: session_id }
	);
}


