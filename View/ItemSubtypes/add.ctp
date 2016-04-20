<?php
	echo "ciao";
	// $this->Html->addCrumb('Item Types', '/item_types/index/');
	// $this->Html->addCrumb('Item Subtypes', '/item_subtypes/index/');
?>
<script type="text/javascript">var itemSubtypesBaseUrl = '<?php echo $this->Html->url(array("controller"=>"ItemSubtypes"))?>';</script>

<?php echo $this->My->toJavascript($itemSubtypeVersions); ?>
<?php echo $this->Html->script('filter'); ?>

<script type="text/javascript">
	<?php
		foreach($manufacturers as $project_id => $project) {
			echo "versionManufacturer[".$project_id."] = new Array();\n";
			foreach($project as $manufacturer_id => $manufacturer) {
				echo "versionManufacturer[".$project_id."][".$manufacturer_id."] = new Option(\"".$manufacturer."\", ".$manufacturer_id.");\n";
			}
		}
	?>
	var projectOptions = null;
	$(document).ready(function(){
	    projectOptions = $("#ProjectProject option");
        //Reset the Project selector to show an unselectable information
        var elem = document.getElementById('ProjectProject');
        ResetSubSelect(elem);
        elem = document.getElementById('ProjectProject');
        DisableSubSelect(elem);
        var node=document.createElement("option");
		var textnode=document.createTextNode("Select an item type");
		node.appendChild(textnode);
        elem.appendChild(node);

		restoreTabs('itemSubtypesAddTabIndex');


		project_ids = GetArray('ItemSubtypeVersionAddProjectIds');

		/*
		 * INIT Project SubSelect
		 */
		if(isset(project_ids)) {
			var elem = document.getElementById('ProjectProject');

			// mark values from initValues as selected
			SetSelectedValues('ProjectProject', project_ids);
			RefreshSubSelect(elem);

		}

		$('#AddButton').click(function(){

			if($('#item_subtype_version_id').val() != null) {
				$.post(
					'<?php echo $this->Html->url(array('controller' => 'itemSubtypeVersions', 'action' => 'addComponent')); ?>/',
					{projectId: $('#project_id').val(), projectName: $('#project_id option:selected').text(), itemSubtypeVersionId: $('#item_subtype_version_id').val(), session: 'addItemSubtype' },
					UpdateComponents
				);
			} else  {
				$("#dialog").dialog({
		            modal: true,
		            buttons: {
		                Ok: function() {
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

	function RemoveComponent(dummy) {
		$.post(
			'<?php echo $this->Html->url(array('controller' => 'itemSubtypeVersions', 'action' => 'removeComponent')); ?>/',
			{dummy: dummy, session: 'addItemSubtype' },
			UpdateComponents
		);
	}

    function PositionChanged(elem) {
    	var pos = elem.name.split("][");
    	var dummy = pos[1];
    	var position = elem.value;

    	$.post(
			'<?php echo $this->Html->url(array('controller' => 'itemSubtypeVersions', 'action' => 'changeComponent')); ?>/',
			{dummy: dummy, field: 'position', value: position, session: 'addItemSubtype' }
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
			{dummy: dummy, field: 'attached', value: value, session: 'addItemSubtype' }
		);
    }

    function IsStockChanged(elem) {
    	var pos = elem.name.split("][");
    	var dummy = pos[1];
    	var isStock = elem.checked;

    	if(isStock)
    		var value = 1;
    	else
    		var value = 0;

    	$.post(
			'<?php echo $this->Html->url(array('controller' => 'itemSubtypeVersions', 'action' => 'changeComponent')); ?>/',
			{dummy: dummy, field: 'is_stock', value: value, session: 'addItemSubtype' }
		);
    }

    function AllVersionsChanged(elem) {
        var pos = elem.name.split("][");
        var dummy = pos[1];
        var allVersions = elem.checked;

        if(allVersions)
            var value = 1;
        else
            var value = 0;

        $.post(
            '<?php echo $this->Html->url(array('controller' => 'itemSubtypeVersions', 'action' => 'changeComponent')); ?>/',
            {dummy: dummy, field: 'all_versions', value: value, session: 'addItemSubtype' }
        );
    }

</script>

<div id="dialog" title="Missing Version" style="display: none;">
    <p>Please select the version of the component.</p>
</div>

<div class="itemSubtypeVersions form">
<?php echo $this->Form->create('ItemSubtype', array('type' => 'file'));?>
	<fieldset>

		<legend><?php echo __('Add Item Subtype'); ?></legend>

	<!-- Accordion -->
		<div id="tabs" class='related'>
			<ul>
				<li><a href="#common"><?php echo __('Common Data'); ?></a></li>
				<li><a href="#components"><?php echo __('Components'); ?></a></li>
			</ul>

			<div id="common">
				<b>
					<?php
						echo 'Version Number: 1';
						echo $this->Form->hidden('ItemSubtypeVersion.version', array('value' => 1));
					?>
				</b>
			<table>
				<tr>
					<td colspan="3">
						<?php echo $this->Form->input('name'); ?>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<?php echo $this->Form->input('shortname'); ?>
					</td>
				</tr>
				<tr>
					<td style="width: 33%">
						<?php echo $this->Form->input('item_type_id', array(
																'size' => 8,
																'style' => 'width: 100%',
																'onchange' => 'RefreshSubSelect(this)',
																)); ?>
					</td>
					<td style="width: 33%">
						<?php echo $this->Form->input('Project.Project', array(
																'size' => 8,
																'multiple' => true,
																'style' => 'width: 100%',
																'onchange' => 'RefreshSubSelect(this)'
																));
						?>
					</td>
					<td style="width: 33%">
						<?php echo $this->Form->input('ItemSubtypeVersion.manufacturer_id', array(
																'size' => 8,
																'disabled' => true,
																'options' => array('' => 'Select a project'),
																'style' => 'width: 100%',
																'onchange' => 'RefreshSubSelect(this)')); ?>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<table>
						<tr>
						<td>
						<?php echo $this->Form->input('comment', array(
																'type' => 'textarea',
																'label' => 'Comment for the Subtype'))?>
						</td>
						<td>
						<?php echo $this->Form->input('ItemSubtypeVersion.comment', array(
																'type' => 'textarea',
																'label' => 'Specific comment for the 1. Version'))?>
						</td>
						</tr>
						</table>
					</td>
				</tr>
			</table>
			</div>

			<div id="components">
				<h3>Select Components</h3>
				<table cellpadding="0" cellspacing="0" id="table_component_subtypes">
				<tr>
					<td style = "width: 18%">
						<?php
							echo $this->Form->input('component_project_id', array(
														'style' => 'width: 90%',
														'onchange' => 'RefreshSubSelect(this)',
														'size' => 8,
														'label' => 'Project',
														'id' => 'project_id'));
						?>
					</td>
					<td style = "width: 18%">
						<?php
							echo $this->Form->input('component_manufacturer_id', array(
														'disabled' => 'disabled',
														'options' => array('Select a project'),
														'style' => 'width: 90%',
														'onchange' => 'RefreshSubSelect(this)',
														'size' => 8,
														'label' => 'Manufacturer',
														'id' => 'manufacturer_id'));
						?>
					</td>
					<td style = "width: 18%">
						<?php
							echo $this->Form->input('component_item_type_id', array(
														'disabled' => 'disabled',
														'style' => 'width: 90%',
														'onchange' => 'RefreshSubSelect(this)',
														'size' => 8,
														'options' => array('Please select a manufacturer'),
														'label' => 'Type',
														'id' => 'item_type_id'));
						?>
					</td>
					<td style = "width: 18%">
						<?php
							echo $this->Form->input('component_item_subtype_id', array(
														'disabled' => 'disabled',
														'style' => 'width: 90%',
														'onchange' => 'RefreshSubSelect(this)',
														'size' => 8,
														'options' => array('Please select a item type'),
														'label' => 'Subtype',
														'id' => 'item_subtype_id'));
						?>
					</td>
					<td style = "width: 18%">
						<?php
							echo $this->Form->input('component_item_subtype_version_id', array(
														'disabled' => 'disabled',
														'style' => 'width: 90%',
														'onchange' => 'RefreshSubSelect(this)',
														'size' => 8, 'options' => array('Please select a subtype'),
														'label' => 'Version',
														'id' => 'item_subtype_version_id'));
						?>
						<?php echo $this->Html->link(__('Show Changelog'), array('controller' => 'itemSubtypes', 'action' => 'changelog'), array('id' => 'show_changelog', 'target' => '_blank')); ?>
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

			<table id="component_table">
				<tr>
					<th>Project</th>
					<th>Manufacturer</th>
					<th>Type</th>
					<th>Subtype</th>
					<th>Version</th>
					<th>Comment</th>
					<th>Position</th>
					<th>Attached at delivery</th>
					<th>Is a stock item</th>
					<th>Allow other versions</th>
					<th>Actions</th>
				</tr>
			<?php if(!empty($components)): ?>

			<?php foreach($components as $dummy => $component): ?>
				<tr>
					<td>
						<?php echo $component['ItemSubtypeVersionsComposition']['project_name']; ?>
						<?php echo $this->Form->hidden('SubtypeComponent.'.$dummy.'.project_id', array('value' => $component['ItemSubtypeVersionsComposition']['project_id'])) ?>
					</td>
					<td><?php echo $component['Manufacturer']['name']; ?></td>
					<td><?php echo $component['ItemSubtype']['ItemType']['name']; ?></td>
					<td><?php echo $component['ItemSubtype']['name']; ?></td>
					<td><?php echo $component['ItemSubtypeVersion']['version']; ?></td>
					<td><?php echo $component['ItemSubtypeVersion']['comment']; ?></td>
					<td>
						<?php
						if(!empty($component['ItemSubtypeVersionsComposition']['position']))
							$value =  $component['ItemSubtypeVersionsComposition']['position'];
						else $value = $dummy+1;
						?>

						<?php echo $this->Form->input('SubtypeComponent.'.$dummy.'.position', array(
																									'label' => false,
																									'value' => $value,
																									'div' => false,
																									'onchange' => 'PositionChanged(this)')); ?>
						<?php echo $this->Form->hidden('SubtypeComponent.'.$dummy.'.component_id', array('value' => $component['ItemSubtypeVersion']['id'])) ?>
					</td>
					<td>
						<?php
						if( (isset($component['ItemSubtypeVersionsComposition']['attached'])) && ($component['ItemSubtypeVersionsComposition']['attached'] == 0) )
							echo $this->Form->input('SubtypeComponent.'.$dummy.'.attached', array(
																									'type' => 'checkbox',
																									'label' => false,
																									'onchange' => 'AttachedChanged(this)'));
						else
							echo $this->Form->input('SubtypeComponent.'.$dummy.'.attached', array(
																									'type' => 'checkbox',
																									'label' => false,
																									'checked' => 'checked',
																									'onchange' => 'AttachedChanged(this)'));
						?>
					</td>
					<td>
						<?php
						if( (isset($component['ItemSubtypeVersionsComposition']['is_stock'])) && ($component['ItemSubtypeVersionsComposition']['is_stock'] == 1) )
							echo $this->Form->input('SubtypeComponent.'.$dummy.'.is_stock', array(
																									'type' => 'checkbox',
																									'label' => false,
																									'checked' => 'checked',
																									'onchange' => 'IsStockChanged(this)'));
						else
							echo $this->Form->input('SubtypeComponent.'.$dummy.'.is_stock', array(
																									'type' => 'checkbox',
																									'label' => false,
																									'onchange' => 'IsStockChanged(this)'));
						?>
					</td>
					<td>
                        <?php
                        if( (isset($component['ItemSubtypeVersionsComposition']['all_versions'])) && ($component['ItemSubtypeVersionsComposition']['all_versions'] == 1) )
                            echo $this->Form->input('SubtypeComponent.'.$dummy.'.all_versions', array(
                                                                                                    'type' => 'checkbox',
                                                                                                    'label' => false,
                                                                                                    'checked' => 'checked',
                                                                                                    'onchange' => 'AllVersionsChanged(this)'));
                        else
                            echo $this->Form->input('SubtypeComponent.'.$dummy.'.all_versions', array(
                                                                                                    'type' => 'checkbox',
                                                                                                    'label' => false,
                                                                                                    'onchange' => 'AllVersionsChanged(this)'));
                        ?>
                    </td>
					<td><input type="button" name="RemoveButton" value="Remove" id="RemoveButton" onclick="RemoveComponent(<?php echo $dummy;?>)" style="width: 100px"/></td>
				</tr>
			<?php endforeach; ?>
			<?php else: ?>
				<tr>
					<td colspan="7">No Components</td>
				</tr>
			<?php endif; ?>
			</table>

			<div class="actions"  style="width: 420px; float: right">
				<?php echo $this->Html->link(__('Remove all Components'), array('controller' => 'itemSubtypes', 'action' => 'resetAdd'), array('style' => 'float: right')); ?>
			</div>

			<br>
		</div>
		</div>
	</fieldset>

<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Subtypes'); ?></h2>
	<ul>
		<li class='active last'><?php echo $this->Html->link(__('Cancel'), array('action' => 'index')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>