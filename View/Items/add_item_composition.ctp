<script type="text/javascript">
	$(function(){
		// Tabs
		$('#tabs').tabs();
	});

	function ShowComponents(absPosition)
    {
		//alert("tbody."+absPosition);
		$("tbody."+absPosition).slideToggle("fast");
    }

    function checkComponents(elem)
    {
		$('input[parent='+elem.id+']').each(function() {
			this.checked = elem.checked;
			checkComponents(this);
		});
    }
</script>

<?php
/**
 * 	For Loadtime measurement
 *
	$timeAll = array();
	$timeAll = microtime();
	$timeAll = explode(' ', $timeAll);
	$timeAll = $timeAll[1] + $timeAll[0];
	$startAll = $timeAll;
 *
 */
?>

<div class="items form">
	<?php
	$formName = 'ItemComposition';
		echo $this->Form->create($formName);
	?>
	<div id="tabs">
	<ul>
		<?php foreach ($itemCodes as $key => $itemCode): ?>
			<li><a href=<?php echo '"#' . $key . '"';?> ><?php echo __($itemCode); ?></a></li>
		<?php endforeach; ?>
	</ul>

	<?php

		foreach ($itemCodes as $key => $itemCode):

			$tabId= '"' . $key . '"'; ?>
			<div id=<?php echo $tabId;?> >

				<dl>
					<dt><?php echo __('Code'); ?></dt>
					<dd>
						<?php echo __($itemCode); ?>

						<?php echo $this->Form->hidden($formName.'.'.$itemCode.'.code', array('value' => $itemCode)); ?>
						<?php echo $this->Form->hidden($formName.'.'.$itemCode.'.location_id', array('value' => $item['Item']['location_id'])); ?>
						<?php echo $this->Form->hidden($formName.'.'.$itemCode.'.item_subtype_version_id', array('value' => $item['Item']['item_subtype_version_id'])); ?>
						<?php echo $this->Form->hidden($formName.'.'.$itemCode.'.project_id', array('value' => $item['Item']['project_id'])); ?>
						<?php echo $this->Form->hidden($formName.'.'.$itemCode.'.item_quality_id', array('value' => $item['Item']['item_quality_id'])); ?>
						<?php
						if(is_array($item["Item"]["item_tag"])){
							foreach($item["Item"]["item_tag"] as $num=>$tag){
								echo $this->Form->hidden($formName.'.'.$itemCode.'.item_tag.', array('value' => $tag));
							}
						}
						?>
						<?php echo $this->Form->hidden($formName.'.'.$itemCode.'.comment', array('value' => $item['Item']['comment'])); ?>
						&nbsp;
					</dd>
					<dt><?php echo __('Item Type'); ?></dt>
					<dd><?php
							echo $this->Html->link($item['ItemSubtype']['ItemType']['name'], array('controller' => 'item_types', 'action' => 'view', $item['ItemSubtype']['ItemType']['id']));
						?>
						&nbsp;
					</dd>
					<dt><?php echo __('Manufacturer'); ?></dt>
					<dd>
						<?php echo $this->Html->link($item['Manufacturer']['name'], array('controller' => 'manufacturers', 'action' => 'view', $item['Manufacturer']['id'])); ?>
						&nbsp;
					</dd>
					<dt><?php echo __('Item Tags'); ?></dt>
					<dd>
						<?php
							if(is_array($item["Item"]["item_tag"])){
								foreach($item["Item"]["item_tag"] as $tag){
									if(isset($itemTags[$tag]))
										echo $this->Html->link($itemTags[$tag],array("controller"=>"itemTags","action"=>"view",$tag))." ";
								}
							}
						?>
						&nbsp;
					</dd>
				</dl>
				<br>
				<h3><?php echo __('Components:'); ?></h3>
				<?php
						/**
						 * 	For Loadtime measurement
						 *
						$time = array();
						$time = microtime();
						$time = explode(' ', $time);
						$time = $time[1] + $time[0];
						$start = $time;
						 */

						//debug($components);

						//$new_components = $this->My->inputComponents($components, $itemCode, $formName);
						$componentList = $this->My->listItems($components, $formName, $itemCode);

						//debug($componentList);

						/**
						 * 	For Loadtime measurement
						 *
						$time = microtime();
						$time = explode(' ', $time);
						$time = $time[1] + $time[0];
						$finish = $time;
						$total_time = round(($finish - $start), 4);
						echo 'MyHelper loaded in '.$total_time.' seconds.';
						echo '<br>'.count($new_components).' components.';
						 *
						 */
				?>
				<?php
				/*
						$time = array();
						$time = microtime();
						$time = explode(' ', $time);
						$time = $time[1] + $time[0];
						$start = $time;
				 */
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
						<th>add to stock</th>
					</tr>
					<?php
						$depth = 0;
						reset($componentList);
						foreach($componentList as $component) {
							//debug($component);

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

								echo '<input type="hidden" name="'.$component['field_name']['valid'].'" id="testU_" value="0"/>';
								echo '<input type="checkbox" name="'.$component['field_name']['valid'].'" parent="create_'.str_replace(".", "DOT", $parent).'" id="create_'.str_replace(".", "DOT", $pos).'" checked="checked" onclick="checkComponents(this)" value="1"/>';
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
								if($component['attached' ] == 1) {
									echo '<input type="checkbox" name="'.$component['field_name']['attached'].'"  parent="attached_'.str_replace(".", "DOT", $parent).'" id="attached_'.str_replace(".", "DOT", $pos).'" checked="checked" onclick="checkComponents(this)" value="1" />';
								} else {
									echo '<input type="checkbox" name="'.$component['field_name']['attached'].'"  parent="attached_'.str_replace(".", "DOT", $parent).'" id="attached_'.str_replace(".", "DOT", $pos).'" onclick="checkComponents(this)" value="1" />';
								}
							echo "</td>\n";
                            echo "<td>\n";
                                echo "\n";
                                if($component['is_stock' ] == 1) {
                                    echo '<input type="checkbox" name="'.$component['field_name']['is_stock'].'"  parent="is_stock_'.str_replace(".", "DOT", $parent).'" id="attached_'.str_replace(".", "DOT", $pos).'" checked="checked" onclick="checkComponents(this)" value="1" disabled="disabled"/>';
                                } else {
                                    echo '<input type="checkbox" name="'.$component['field_name']['is_stock'].'"  parent="is_stock_'.str_replace(".", "DOT", $parent).'" id="attached_'.str_replace(".", "DOT", $pos).'" onclick="checkComponents(this)" value="1" disabled="disabled"/>';
                                }
                                echo '<input type="hidden" name="'.$component['field_name']['is_stock'].'" value="'.$component['is_stock'].'" id="ItemCompositionProjectField"/>';
                                echo "\n";
                            echo "</td>\n";
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
						/**
						 * 	For Loadtime measurement
						 *
						$time = array();
				 		$time = microtime();
						$time = explode(' ', $time);
						$time = $time[1] + $time[0];
						$finish = $time;
						$total_time = round(($finish - $start), 4);
						echo 'Table loaded in '.$total_time.' seconds.';
						 *
						 */
				 ?>
			</div>
		<?php endforeach ?>
	</div>

	<center>
		<?php echo $this->Form->submit('Register');?>
	</center>
	<?php echo $this->Form->end();?>
</div>

<?php
	/**
	 * 	For Loadtime measurement
	 *
	$timeAll = array();
	$timeAll = microtime();
	$timeAll = explode(' ', $timeAll);
	$timeAll = $timeAll[1] + $timeAll[0];
	$finishAll = $timeAll;
	$total_timeAll = round(($finishAll - $startAll), 4);
	echo 'Site loaded in '.$total_timeAll.' seconds.';
	 *
	 */
 ?>

<div id='verticalmenu'>
	<h2><?php echo __('Register composite item'); ?></h2>
	<ul>
	    <?php $url = (empty($url) ? array('controller' => 'items', 'action' => 'index') : $url); ?>
		<li class="active last"><?php echo $this->Html->link(__('Cancel'), $url); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>