<?php

	$formName = "ItemComposition";

	if(!isset($itemCodes)){

		$itemCodes = array($itemCode);
	}


	// debug($componentList);
?>
<div id="tabs" class='related'>
<?php
	echo "<ul>";
	foreach($itemCodes as $itemCode){
		echo "<li><a href='#".$itemCode."'>".$itemCode."</a></li>";
	}
	echo "</ul>";
	foreach($itemCodes as $itemCode):
		echo "<div id='".$itemCode."'>";
		echo $this->Form->create('Item',array("class"=>"components", "default"=>false));
			?>
			<table>
				<tr>
					<th width="10px"></th>
					<th width="10px">Create</th>
					<th>Code</th>
					<th>Project</th>
					<th>Item Type</th>
					<th>Position</th>
					<th>is attached</th>
					<!-- <th>add to stock</th> -->
				</tr>
				<?php
					$this->My->showShortName = $showShortName;
					$componentList = $this->My->listItems($components, $formName, $itemCode);

					$depth = 0;
					reset($componentList);
					foreach($componentList as $component) {
			#							debug($component);

						//echo $component['depth']."::".$depth;
						//echo "<br>";

						// create classes for the table rows, based on the component position, for javascript "ShowComponents"
						$classes = array("component");
						$pos = $component['abs_position'];
						$end = strrpos($pos, '.');
						if($end !== false) {
							$length = strlen($pos);
							$pos = substr($pos, 0, $end);
							//replace the "." because javascript dont like it
							$classes[] = str_replace(".", "DOT", $pos);
						}

						if ($component['depth'] > $depth) {
							for($i=0; $i < ($component['depth']-$depth); $i++) {
								echo "<tbody class='".implode(' ', $classes)."' style='display: none'>\n";
							}
							$depth = $component['depth'];
						} else if ($component['depth'] < $depth) {
							for($i=0; $i < ($depth-$component['depth']); $i++) {

								echo "</tbody>\n";
							}
							$depth = $component['depth'];
						}

						echo "<tr>\n";
						echo "<td width='10px'>\n";
							if($component['has_components'] > 0) {
								echo $this->Html->image('Plus.png', array(
																	'alt' => __('Show Search'),
																	'border' => '0',
																	'id'=>'search',
																	'width'=>'20',
																	'height'=>'20',
																	'onclick' => 'ShowComponents("'.str_replace(".", "DOT", $component['abs_position']).'")'));
							}
						echo "</td>\n";
						echo "<td>\n";
							//echo $this->Form->checkbox('valid', array('name' => $component['valid_field'], 'id' => 'testU','hiddenField' => true, 'checked' => true, 'onclick' => 'add()'));
							//echo "\n";

							$pos = $component['abs_position'];
							$parent = substr($pos, 0, strrpos($pos, "."));
							//TODO: Don't show the checkbox for stock items!!!!
							echo '<input type="hidden" name="'.$component['field_name']['valid'].'" id="testU_" value="0"/>';
							if($component['is_stock'] == 1){
								echo '<input type="hidden" name="'.$component['field_name']['valid'].'" parent="create_'.str_replace(".", "DOT", $parent).'" id="create_'.str_replace(".", "DOT", $pos).'" value="0"/>stock';
							}else{
								echo '<input type="checkbox" name="'.$component['field_name']['valid'].'" parent="create_'.str_replace(".", "DOT", $parent).'" id="create_'.str_replace(".", "DOT", $pos).'" checked="checked" onclick="checkComponents(this)" value="1"/>';
							}
							echo "\n";
						echo "</td>\n";
						echo "<td>\n";
							//echo $this->Form->input('code_field', array('name' => $component['code_field'], 'value' => $component['code'],'label' => false, 'type' => 'text'));
							//echo "\n";
							//$component['code'] = str_replace('"', '&quot;', $component['code']);
							echo '<div class="input text" style="height: 5px"><input name="'.$component['field_name']['code'].'" value="'.$component['code'].'" type="text" id="ItemCompositionCodeField"/></div>';
							echo "\n";

							//echo $this->Form->hidden('version_field', array('name' => $component['version_field'], 'value' => $component['item_subtype_version_id']));
							//echo "\n";
							echo '<input type="hidden" name="'.$component['field_name']['version'].'" value="'.$component['item_subtype_version_id'].'" id="ItemCompositionVersionField"/></td>';
							echo "\n";
						echo "</td>\n";
						echo "<td>\n";
							echo $componentProjects[$component['project_id']];
						echo "</td>\n";
						echo "<td>\n";
							if($component['has_components'] > 0) {
								echo "<b>". $component['description']."</b>";
							} else {
								echo $component['description'];
							}
							echo "\n";
						echo "</td>\n";
						echo "<td>\n";
							echo $component['abs_position'];
							echo "\n";
							//echo $this->Form->hidden('position_field', array('name' => $component['position_field'], 'value' => $component['position']));
							//echo "\n";
							echo '<input type="hidden" name="'.$component['field_name']['position'].'" value="'.$component['position'].'" id="ItemCompositionPositionField"/>';
							echo "\n";
							//echo $this->Form->hidden('location_field', array('name' => $component['location_field'], 'value' => $component['location_id']));
							//echo "\n";
							echo '<input type="hidden" name="'.$component['field_name']['location'].'" value="'.$component['location_id'].'" id="ItemCompositionLocationField"/>';
							echo "\n";
							//echo $this->Form->hidden('state_field', array('name' => $component['state_field'], 'value' => $component['state_id']));
							//echo "\n";
							echo '<input type="hidden" name="'.$component['field_name']['state'].'" value="'.$component['state_id'].'" id="ItemCompositionStateField"/>';
							echo "\n";
							//echo $this->Form->hidden('project_field', array('name' => $component['project_field'], 'value' => $component['project_id']));
							//echo "\n";
							echo '<input type="hidden" name="'.$component['field_name']['project'].'" value="'.$component['project_id'].'" id="ItemCompositionProjectField"/>';
							echo "\n";

						echo "</td>\n";
						echo "<td>\n";
							echo "\n";
							//echo $this->Form->checkbox('attached_field', array('name' => $component['attached_field'], 'hiddenField' => true, 'checked' =>  $component['attached' ], 'onclick' => 'add()'));
							//echo "\n";

							//id="ItemCompositionAttachedField"
							echo '<input type="hidden" name="'.$component['field_name']['attached'].'" id="ItemCompositionAttachedField_" value="0"/>';
							if($component['is_stock'] == 1){
								echo '<input type="hidden" name="'.$component['field_name']['attached'].'"  parent="attached_'.str_replace(".", "DOT", $parent).'" id="attached_'.str_replace(".", "DOT", $pos).'" value="0" />';
							}else{
								if($component['attached' ] == 1) {
									echo '<input type="checkbox" name="'.$component['field_name']['attached'].'"  parent="attached_'.str_replace(".", "DOT", $parent).'" id="attached_'.str_replace(".", "DOT", $pos).'" checked="checked" onclick="checkComponents(this)" value="1" />';
								} else {
									echo '<input type="checkbox" name="'.$component['field_name']['attached'].'"  parent="attached_'.str_replace(".", "DOT", $parent).'" id="attached_'.str_replace(".", "DOT", $pos).'" onclick="checkComponents(this)" value="1" />';
								}
							}
						echo "</td>\n";
			            // echo "<td>\n";
			                // echo "\n";
			                // if($component['is_stock' ] == 1) {
			                    // echo '<input type="checkbox" name="'.$component['field_name']['is_stock'].'"  parent="is_stock_'.str_replace(".", "DOT", $parent).'" id="attached_'.str_replace(".", "DOT", $pos).'" checked="checked" onclick="checkComponents(this)" value="1" disabled="disabled"/>';
			                // } else {
			                    // echo '<input type="checkbox" name="'.$component['field_name']['is_stock'].'"  parent="is_stock_'.str_replace(".", "DOT", $parent).'" id="attached_'.str_replace(".", "DOT", $pos).'" onclick="checkComponents(this)" value="1" disabled="disabled"/>';
			                // }
			                // echo '<input type="hidden" name="'.$component['field_name']['is_stock'].'" value="'.$component['is_stock'].'" id="ItemCompositionProjectField"/>';
			                // echo "\n";
			            // echo "</td>\n";
						echo "</tr>\n";

					}
					if ($depth > 0) {
						for($i=0; $i < $depth; $i++) {
							echo "</tbody>\n";
						}
						$depth = 0;
					}
				?>
			</table>
		<?php
		echo "<input type='hidden' value='stepThree' name='data[step]' />";
		echo "</form></div>";
	endforeach;
	echo $this->Form->create('Item',array("class"=>"stepThree", "default"=>false));
	echo $this->Form->end(__('Create Item'));


	echo $this->element("step_three_javascript");
	?>


<script type='text/javascript'>
	function ShowComponents(absPosition)
    {
		//alert("tbody."+absPosition);
		$("tbody."+absPosition).slideToggle("fast");
    }

    function checkComponents(elem)
    {
    	var parentDivId = $(elem).closest("div").attr("id");

		$('#'+parentDivId+' input[parent='+elem.id+']').each(function() {
			this.checked = elem.checked;
			checkComponents(this);
		});
    }

</script>