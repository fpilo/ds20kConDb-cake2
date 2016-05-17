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
		$('#component_table').remove();
		$('#table_component_subtypes').after(component_table);
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
   
   function RemoveComponent(dummy) {
      $.post(
         '<?php echo $this->Html->url(array('controller' => 'itemSubtypeVersions', 'action' => 'removeComponent')); ?>/',
         {dummy: dummy, session: session_id,editWithAttached: $("#ItemSubtypeVersionEditWithAttached").val() },
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

