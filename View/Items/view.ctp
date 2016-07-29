<?php
	foreach($item["CompositeItemChain"] as $crumb){
		$this->Html->addCrumb($crumb['code'], '/items/view/'.$crumb['id']);
	}
	$this->Html->addCrumb('Item '.$item['Item']['code'].' View', '/items/view/'.$item['Item']['id']);

?>

<script type="text/javascript">
   $(function(){
      restoreTabs('itemTabIndex');
      $('#top-menu').smartmenus({hideTimeout:1000});
   });
   function addToTransfer(transferId){
      if(typeof transferId == 'undefined') transferId = "";
      //Change target of form
      $("#TransfersAddForm").attr("action","<?php echo $this->Html->url(array("controller"=>"Transfers","action"=>"add"))?>/"+transferId)
      //submit form
      $("#TransfersAddForm").submit();
   }
   function change_notes(aid){
      $("#notes_"+aid+" .note_text").hide();
      $("#notes_"+aid+" .note_form").show();
   }
   function cancel_change_notes(aid){
      $("#notes_"+aid+" .note_form").hide();
      $("#notes_"+aid+" .note_text").show();
   }
</script>

<div class="items view">
<?php
   echo $this->Form->create('Transfers',array('action'=>'add'));
   echo '<input type="hidden" name="transferFromId" value="'.$item['Location']['id'].'">';
   echo '<input type="hidden" name="data[selectedItems][]" value="'.$item['Item']['id'].'">';
?>
</form>
	<div id="tabs" class='related'>
		<ul>
			<li><a href="#information"><?php echo __('Item Information'); ?></a></li>
			<li><a href="#checklist"><?php echo __('Check list'); ?></a></li>
			<li><a href="#histories"><?php echo __('History'); ?></a></li>
			<?php if (!empty($item['ItemSubtypeVersion']['Components'])):?>
				<li><a href="#components"><?php echo __('Components');?></a></li>
			<?php endif; ?>
			<li><a href="#files"><?php echo __('Files');?></a></li>
			<li><a href="#measurements"><?php echo __('Measurements');?></a></li>
			<li><a href="#transfers"><?php echo __('Transfers');?></a></li>
		</ul>

		<div id="information">
			<dl>
				<dt><?php echo __('Id'); ?></dt>
				<dd>
					<?php echo h($item['Item']['id']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Code'); ?></dt>
				<dd>
					<?php echo h($item['Item']['code']); ?>
					&nbsp;
					<?php echo $this->Html->image('ElegantBlueWeb/settings.png', array(
																				'title' => 'Change code',
																				'alt' => __('edit'),
																				'border' => '0',
																				'width'=>'16',
																				'height'=>'16',
																				'url' => array('controller' => 'items', 'action' => 'changeCode', $item['Item']['id'])));
					?>
					&nbsp;
				</dd>
				<dt><?php echo __('Item Type'); ?></dt>
				<dd>
					<?php echo $this->Html->link($item['ItemType']['name'], array('controller' => 'item_types', 'action' => 'view', $item['ItemType']['id'])); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Item Subtype'); ?></dt>
				<dd>
					<?php echo $this->Html->link($item['ItemSubtype']['name'], array('controller' => 'item_subtypes', 'action' => 'view', $item['ItemSubtype']['id'])); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Version'); ?></dt>
				<?php
					//Defining the name displayed for a subtype version
					$sVName = $item['ItemSubtypeVersion']['version'];
					if($item['ItemSubtypeVersion']['name'] != ""){
						$sVName = $item['ItemSubtypeVersion']['version']." (".$item['ItemSubtypeVersion']['name'].")";
					}
				?>
				<dd>
					<?php echo $this->Html->link($sVName, array('controller' => 'item_subtype_versions', 'action' => 'view', $item['ItemSubtypeVersion']['id'])); ?>
					&nbsp;
					<?php echo $this->Html->image('ElegantBlueWeb/settings.png', array(
																				'title' => 'Change version',
																				'alt' => __('edit'),
																				'border' => '0',
																				'width'=>'16',
																				'height'=>'16',
																				'url' => array('controller' => 'items', 'action' => 'changeItemSubtypeVersion', $item['Item']['id'])));
					?>
					&nbsp;
				</dd>
				<dt><?php echo __('Tags'); ?></dt>
				<dd>
					<?php foreach($item["ItemTag"] as $tag): ?>
					<?php echo "<a href='".$this->Html->url(array('controller' => 'item_tags', 'action' => 'view', $tag["id"]))."'>".$tag['name']."</a>"; ?>
					<?php endforeach; ?>
					<?php echo $this->Html->image('ElegantBlueWeb/settings.png', array(
																				'title' => 'Change tags',
																				'alt' => __('edit'),
																				'border' => '0',
																				'width'=>'16',
																				'height'=>'16',
																				'url' => array('controller' => 'items', 'action' => 'changeTags', $item['Item']['id'])));
					?>&nbsp;
				</dd>
				<?php if (!empty($item['ItemSubtypeVersion']['Components'])): ?>
				<dt><?php echo __('Components Tags'); ?></dt>
				<?php
					$tmpTags = array();
					foreach($item["Component"] as $component){
						if(($component['ItemComposition']['valid'] == TRUE) && $component['ItemComposition']['component_id'] != null){
							if(isset($component["ItemTag"]))
								foreach($component["ItemTag"] as $itemTag){
									$tmpTags[] = $itemTag["name"];
									//$tmpTags[] = $this->Html->tableLink($itemTag["name"],array("controller"=>"item_tags","action"=>"view",$itemTag["id"]));
								}
						}elseif(($component['ItemComposition']['valid'] == TRUE) && $component['ItemComposition']['component_id'] == null) {
							foreach($component["Stock"]["StockTag"] as $itemTag){
								$tmpTags[] = $itemTag["name"];
								//$tmpTags[] = $this->Html->tableLink($itemTag["name"],array("controller"=>"item_tags","action"=>"view",$itemTag["id"]));
							}
						}
					}
					$tmpTags = array_unique($tmpTags);
					$tags = implode(", ", $tmpTags);
				?>
				<dd>
					<?php echo $tags; ?>&nbsp;
				</dd>
				<?php endif; ?>
				<?php
				if(isset($allComponents)):
					$itemViewUrl = $this->Html->url(array('controller'=>"Item","Action"=>"view")); 
					function displayItem($item,$level=0){
						global $itemViewUrl;
						$tmp = "";
						for($i=0;$i<$level;$i++){
							$tmp .= "&nbsp;&nbsp;";
						}
						$tmp .= "<a href='".$itemViewUrl.$item["id"]."'>".$item["code"]."</a><br />";
						return $tmp;
					}
					function displayComponentsRecursively($input,&$output="",$level=0){
						if(isset($input["id"])){
							displayComponentsRecursively($input["Component"],$output,$level+1);
							$output = displayItem($input,$level).$output;
						}elseif(is_array($input) && !isset($input["id"])){
							foreach($input as $tmp){
								displayComponentsRecursively($tmp,$output,$level+1);
							}
						}
						return $output;
					}
					?>
					<dt id="itemHierachyButton">Full item hierachy <br />(<?php echo sizeof($allComponents); ?> components)</dt>
					<dd id="itemHierachy" style="display:inline-block; overflow:hidden; ">
						<?php
						$compCounter = 0;
						foreach($allComponents as $component){
							if($compCounter > 5){ echo "... (full components list tmp. disabled)"; break; }
							echo displayComponentsRecursively($component);
							$compCounter++;
						}
						?>
					</dd>
				<?php endif; ?>
				<dt><?php echo __('Quality'); ?></dt>
				<dd>
					<?php echo $this->Html->link($item['ItemQuality']['name'], array('controller' => 'item_tags', 'action' => 'view', $item['ItemQuality']["id"])); ?>
					<?php echo $this->Html->image('ElegantBlueWeb/settings.png', array(
																				'title' => 'Change tags',
																				'alt' => __('edit'),
																				'border' => '0',
																				'width'=>'16',
																				'height'=>'16',
																				'url' => array('controller' => 'items', 'action' => 'changeQuality', $item['Item']['id'])));
					?>
					&nbsp;
				</dd>
				<dt><?php echo __('Location'); ?></dt>
				<dd>
					<?php echo $this->Html->link($item['Location']['name'], array('controller' => 'locations', 'action' => 'view', $item['Location']['id'])); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('State'); ?></dt>
				<dd>
					<?php echo $this->Html->link($item['State']['name'], array('controller' => 'states', 'action' => 'view', $item['State']['id'])); ?>
					&nbsp;
					<?php
							if (empty($checklist) || empty($checklist['ClAction'])){
								$url = array('controller' => 'items', 'action' => 'changeState', $item['Item']['id']);
							//else
							//$url = array('controller' => 'items', 'action' => 'view', $item['Item']['id'], '#' => 'checklist', 'fullBase' => true);

								echo $this->Html->image('ElegantBlueWeb/settings.png', array(
																							'title' => 'Change state',
																							'alt' => __('edit'),
																							'border' => '0',
																							'width'=>'16',
																							'height'=>'16',
																							'url' => $url
																						));
							}
					?>

					&nbsp;
				</dd>
				<dt><?php echo __('Manufacturer'); ?></dt>
				<dd>
					<?php echo $this->Html->link($item['Manufacturer']['name'], array('controller' => 'manufacturers', 'action' => 'view', $item['Manufacturer']['id'])); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Project'); ?></dt>
				<dd>
					<?php echo $this->Html->link($item['Project']['name'], array('controller' => 'projects', 'action' => 'view', $item['Project']['id'])); ?>
					&nbsp;
					<?php echo $this->Html->image('ElegantBlueWeb/settings.png', array(
																				'title' => 'Change project',
																				'alt' => __('edit'),
																				'border' => '0',
																				'width'=>'16',
																				'height'=>'16',
																				'url' => array('controller' => 'items', 'action' => 'changeProject', $item['Item']['id'])));
					?>
					&nbsp;
				</dd>
				<dt><?php echo __('Is part of'); ?></dt>
				<dd>
				<?php
				foreach ($item['CompositeItem'] as $compositeItem):
					if ($compositeItem['ItemComposition']['valid'] != 0): ?>
						<?php echo $this->Html->link($compositeItem['code'], array('controller' => 'items', 'action' => 'view', $compositeItem['id'])); ?>
						<?php echo $this->Form->postLink(__('Detach'), array('controller' => 'items', 'action' => 'detach',  $compositeItem['ItemComposition']['id']), null, __('Are you sure you want to detach %s from %s?', $item['Item']['code'], $compositeItem['code'])); ?>
					<?php endif?>
				<?php endforeach?>
				&nbsp;
				</dd>
				<dt><?php echo __('Was part of'); ?></dt>
				<dd>
				<?php
				foreach ($item['CompositeItem'] as $compositeItem):
					if ($compositeItem['ItemComposition']['valid'] == 0): ?>
						<strike>
						<?php echo $this->Html->link($compositeItem['code'], array('controller' => 'items', 'action' => 'view', $compositeItem['id'])); ?>
						</strike>
					<?php endif?>
				<?php endforeach?>
				&nbsp;
				</dd>
				<dt><?php echo __('Comment'); ?></dt>
				<dd>
					<?php echo $item['Item']['comment'];
					echo $this->Html->image('ElegantBlueWeb/settings.png', array(
																				'title' => 'Edit comment',
																				'alt' => __('edit'),
																				'border' => '0',
																				'width'=>'16',
																				'height'=>'16',
																				'url' => array('controller' => 'items', 'action' => 'changeComment', $item['Item']['id'])));
			 		?>
				&nbsp;
				</dd>
			</dl>
			<?php if (!empty($limitedHistory)):?>

					<table cellpadding = "0" cellspacing = "0">
					<tr>
						<th><?php echo __('Additional comments'); ?></th>
						<th><?php echo __('Date'); ?></th>
						<th><?php echo __('Responsible User'); ?></th>
					</tr>
					<?php
						$i = 0;
						foreach ($limitedHistory as $h): ?>
						<tr>
							<td>
								<?php echo $this->Html->link($h['Event']['name'], array('controller' => 'events', 'action' => 'view', $h['Event']['id'])); ?>:&nbsp;
								<?php echo $h['History']['comment'];?>
							</td>
							<td><?php echo $h['History']['created'];?></td>
							<td>
								<?php 	if(isset($h['User']['username']))
											echo $this->Html->link($h['User']['username'], array('controller' => 'users', 'action' => 'view', $h['User']['id']));
										else
											echo 'Unknown';
								?>
							</td>
						</tr>
					<?php endforeach; ?>
					</table>
			<?php endif; ?>
			<table style='width:auto;'>
					<tr>
						<th>
							Parameter
						</th>
						<th>
							Value
						</th>
						<th>
							Comment
						</th>
						<th>
							Date and Time
						</th>
					</tr>
				<?php foreach($item["ItemsParameters"] as $itemParameter): ?>
					<tr>
						<td>
							<?php echo $itemParameter["Parameter"]["name"]; ?>
						</td>
						<td>
							<?php echo $itemParameter["value"]; ?>
						</td>
						<td>
							<?php echo $itemParameter["comment"]; ?>
						</td>
						<td>
							<?php echo $itemParameter["timestamp"]; ?>
							<?php //if($this->Session->check('Auth.User.Permissions.controllers/AclManager/Acl/index')) : ?>
								<input type='button' value='delete' parameterId='<?php echo $itemParameter['id'] ?>' class='removeItemParameter'>
							<?php //endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				<tr id='newItemParameter'>
					<td style="min-width:200px;">
						<div class="ui-widget">
						<select name='data[parameter]' id='combobox'>
							<option disabled selected>--select an option--</option>
						<?php foreach($parameters as $pId=>$pName): ?>
							<option value="<?php echo $pId; ?>" ><?php echo h($pName); ?></option>
						<?php endforeach; ?>
						</select>
						</div>
					</td>
					<td>
						<input type='text' value='' placeholder="Value" id='newValue'/>
					</td>
					<td>
						<input type='text' value='' placeholder="Comment" id='newComment'/>
					</td>
					<td>
						<input type='button' value='Save' id='saveItemParameter'/>
					</td>
				</tr>
			</table>
			<?php #echo $this->Form->input("parameter_id",array( "class"=>'ui-widget',"id"=>"combobox"),$parameters); ?>
		</div>

		<div id="checklist">
			<?php if (!empty($checklist)):?>
				<?php if (!empty($checklist['ClAction'])):?>
					<table cellpadding = "0" cellspacing = "0">
						<tr>
							<th>&nbsp;</th>
							<th width="60"><?php echo __('#'); ?></th>
							<th><?php echo __('Action'); ?></th>
							<th><?php echo __('Description'); ?></th>
							<th><?php echo __('Status'); ?></th>
							<th><?php echo __('Last update'); ?></th>
							<th><?php echo __('Updated by'); ?></th>
							<th><?php echo __('Notes (click on text to edit)'); ?></th>
						</tr>

						<?php
							$iclAction = 0;
							$clAction_fail = false;

							foreach ($checklist['ClAction'] as $a):
								$iclAction++;
								$clAction_level = $a['hierarchy_level'];
								$clAction_status = 0;
                        $clAction_status = ($a['status_code'] >> 12 & 0x3);
								if($clAction_status == 1) $clAction_fail = true;
								$clAction_isNext =($a['status_code'] >> 15 & 0x1);

								if ($clAction_status !== 0) $cla_class = "close";
								else {
									if(($clAction_isNext == 1) && !$clAction_fail) $cla_class = "next";
									else $cla_class = "open";
								}

								?>
                     <tr class="clAction <?php echo $cla_class; ?>">
							<td>
								<?php if ($clAction_status !== 0 && $clAction_level == 1) echo $this->Html->image('ElegantBlueWeb/repeat.png', array(
																							'title' => 'Repeat ClAction',
																							'alt' => __('repeat'),
																							'border' => '0',
																							'width'=>'25',
																							'height'=>'25',
																							'style' => 'float: right; padding: 0px 5px;',
																							'url' => array('controller' => 'clActions', 'action' => 'repeat', $a['id'])
																							));
										else echo("&nbsp;");
								?>

								<?php
										
									if ($clAction_isNext == 1 && !$clAction_fail) {
											echo $this->Form->postLink(
												$this->Html->image('checklist-skip.png', array(
													'title' => 'Skip ClAction',
													'alt' => __('skip'),
													'border' => '0',
													'width'=>'25',
													'height'=>'25',
													'style' => 'float: right; padding: 0px 5px;'
												)),
												array(
													'controller' => 'clActions',
													'action' => 'skip',
													$a['id']
												),
												array(															
													'escape' => false,
													'confirm' => 'Are you sure you wish to skip this action?'
												)
											);
											echo $this->Html->image('checklist-check.png', array(
																								'title' => 'Check ClAction',
																								'alt' => __('check'),
																								'border' => '0',
																								'width'=>'25',
																								'height'=>'25',
																								'style' => 'float: right; padding: 0px 5px;',
																								'url' => array('controller' => 'clActions', 'action' => 'check', $a['id'])
																								));
																			}
									else echo("&nbsp;");
								?>

							</td>

							<?php if($clAction_level == 2) : ?>
							<td style="text-align:right;">
							<?php echo $a['list_number'].'.'.$a['list_subnumber'];	?>
							</td>
							<?php endif; ?>
							<?php if($clAction_level == 1) : ?>
							<td>
							<?php echo $a['list_number']; ?>
							</td>
							<?php endif; ?>

							<td><?php echo $a['name']; ?></td>
							<td><?php echo $a['description']; ?></td>
							<td>
								<?php if (($a['status_code'] >> 12 & 0x7) == 7) echo $this->Html->image('test-skip-icon.png', array(
																								'title' => 'ClAction skipped',));
									  else echo("&nbsp;"); ?>
								<?php if (($a['status_code'] >> 12 & 0x3) == 3) echo $this->Html->image('test-pass-icon.png', array(
																								'title' => 'ClAction completed, pass status',));
									  else echo("&nbsp;"); ?>
								<?php if (($a['status_code'] >> 12 & 0x3) == 1) echo $this->Html->image('test-fail-icon.png', array(
																								'title' => 'ClAction completed, fail status',));
									  else echo("&nbsp;"); ?>
								<?php #echo dechex($a['status_code']); ?>
							</td>
							<td><?php if(!empty($a['last_update'])) echo $a['last_update']; else echo("&nbsp;"); ?></td>
							<td><?php if(!empty($a['updated_by'])) echo $a['updated_by']; else echo("&nbsp;"); ?></td>
							<td id="notes_<?php echo $a['id']; ?>">
                        <div class="note_text" onclick="change_notes(<?php echo $a['id']; ?>)"><?php if(!empty($a['notes'])) echo $a['notes']; else echo("&nbsp;"); ?></div>
                        <div class="note_form" style="display:none">
                        <?php
                           echo $this->Form->create('ClAction',array('action'=>'edit_notes'));
                           echo $this->Form->input('id', array('type' => 'hidden', 'value'=>$a['id']));
                           echo $this->Form->input('item_id', array('type' => 'hidden', 'value'=>$item['Item']['id']));
                           echo $this->Form->textarea('notes', array('value'=> $a['notes'], 'label'=>'', 'cols'=>200, 'rows'=>7));
                           echo $this->Form->end('Save');
                           echo '<button onclick="cancel_change_notes('.$a['id'].')">Back</button>';
                        ?>
                        </div>
                     </td>
						</tr>
						<?php endforeach; ?>
					</table>
				<?php else: ?>
					No actions found in the checklist.
				<?php endif; ?>
			<?php else: ?>
				No checklist found.
			<?php endif; ?>
		</div>

	   <?php require(dirname(__FILE__).'/view_history_tab.ctp'); ?>

		<?php $rowBackups = ""; ?>
		<?php if (!empty($item['ItemSubtypeVersion']['Components'])):?>
		<div id="components">
					<h3 class="actions"><?php  echo __('Currently attached Components: '); ?></h3>

					<table>
					<?php

						echo $this->Html->tableHeaders(array(
									'Position',
									'Status',
									'Code',
									'Tags',
									'State',
									'Quality',
									'Location',
									'Type',
									'Subtype',
									'Version(s)',
									'Manufacturer',
									'Project',
									'Actions'));

						$cells = array();
						//Store the components of this subtypeversion in an array ready to be displayed as a table
						foreach($item['ItemSubtypeVersion']['Components'] as $componentSlot) {
                     // do position and version first in case there is multiple options on this slot
							$position = $componentSlot['Component']['position'];
							//Display version name if it exists
							$version = ($componentSlot['ItemSubtypeVersion']['name'] != "")?$componentSlot['ItemSubtypeVersion']['version']." (".$componentSlot['ItemSubtypeVersion']['name'].")":$componentSlot['ItemSubtypeVersion']['version'];
                     if(!empty($cells[$position])) {
                        $cells[$position][9].='<br>'.$version;
                        continue;
                     }
							$status = $this->Html->image('Error.png', array(
										//'alt' => __('edit'),
										'border' => '0',
										'width'=>'16',
										'height'=>'16',
										//'url' => array('controller' => 'items', 'action' => 'changeItemSubtypeVersion', $item['Item']['id'])
										));

							$type = $componentSlot['ItemSubtypeVersion']['ItemSubtype']['ItemType']['name'];
							$subtype = $componentSlot['ItemSubtypeVersion']['ItemSubtype']['name'];
							$code = '<b>No item attached</b>';
							$state = '<b>-</b>';
							$location = '<b>-</b>';
							$manufacturer = '<b>-</b>';
							$project = '<b>-</b>';
							$tags = '<b>-</b>';
							$quality = '<b>-</b>';

							if($componentSlot['Component']['is_stock'] == 0) {
								$actions = $this->Html->link(__('Attach'), array('action' => 'selectFromInventory', $componentSlot['Component']['position'], $item['Item']['item_subtype_version_id'],0),array("class"=>"equipButtons"));
							} else {
								$actions = $this->Html->link(__('Attach Stock'), array('action' => 'selectFromInventory', $componentSlot['Component']['position'], $item['Item']['item_subtype_version_id'],1),array("class"=>"equipButtons"));
							}
							$cells[$position] = array($position, $status, $code, $tags, $state, $quality, $location, $type, $subtype, $version, $manufacturer, $project, array($actions, array('class'=> 'actions')));

						}

						//Iterate over the set components and replace the corresponding row in the table array
						foreach($item['Component'] as $component) {

							/* Add unique components with valid composition to currently attached items table */
							if(($component['ItemComposition']['valid'] == TRUE)) {
								// debug($component);
								$tmpTags = array();
								//Display version name if it exists
								$version = ($component['ItemSubtypeVersion']["name"] != "")?$component['ItemSubtypeVersion']['version']." (".$component['ItemSubtypeVersion']["name"].")":$component['ItemSubtypeVersion']['version'];

								$status = $this->Html->image('Tick.png', array('border' => '0','width'=>'16','height'=>'16')); //Status icon
								$position = $component['ItemComposition']['position'];
								if(!empty($component['ItemQuality'])) $quality = $this->Html->tableLink($component['ItemQuality']['name'], array("controller"=>"item_qualities","action"=>"view",$component['ItemQuality']['id']));
								$type = $this->Html->tableLink($component['ItemType']['name'], array('controller' => 'item_types', 'action' => 'view', $component['ItemType']['id']));
								$subtype = $this->Html->tableLink($component['ItemSubtype']['name'], array('controller' => 'item_subtypes', 'action' => 'view', $component['ItemSubtype']['id']));
								$version = $this->Html->tableLink($version, array('controller' => 'item_subtype_versions', 'action' => 'view', $component['ItemSubtypeVersion']['id']));
								$state = $this->Html->tableLink($component['State']['name'], array('controller' => 'states', 'action' => 'view', $component['State']['id']));
								$manufacturer = $this->Html->tableLink($component['Manufacturer']['name'], array('controller' => 'manufacturers', 'action' => 'view', $component['Manufacturer']['id']));
								$project = $this->Html->tableLink($component['Project']['name'], array('controller' => 'projects', 'action' => 'view', $component['Project']['id']));
								$actions = '<a href="#" onClick="removeItemFromPosition(this,event,\''.$position.'\','.$component["id"].')" >Detach</a></td>';


								//Section for special treatment if stock item. Only applicable fields are assigned here the rest ist handled for all at once above
								if(isset($component["ItemStocks"]) && count($component["ItemStocks"])>0){
									//There is a stock item attached at this position, replace applicable fields
									//Find the stock item configuration for this location
									foreach($component["ItemStocks"] as $stockNum=>$stockitem)
										$correctStockNum = $stockNum;
									$code = "Stock item";
									$location = $this->Html->tableLink($item["Location"]["name"], array('controller' => 'locations', 'action' => 'view', $item['Location']['id']));
									foreach($component["ItemStocks"][$correctStockNum]["Item"]["ItemTag"] as $itemTag){
										$tmpTags[] = $itemTag["name"];
										//$tmpTags[] = $this->Html->tableLink($itemTag["name"],array("controller"=>"item_tags","action"=>"view",$itemTag["id"]));
									}
								}else{
									//No stock item at this position, set standard values from the Item
									$location = $this->Html->tableLink($component['Location']['name'], array('controller' => 'locations', 'action' => 'view', $component['Location']['id']));
									$code = $this->Html->tableLink($component['code'], array('controller' => 'items', 'action' => 'view', $component['id']));
									if(isset($component["ItemTag"]))
										foreach($component["ItemTag"] as $itemTag){
											$tmpTags[] = $itemTag["name"];
											//$tmpTags[] = $this->Html->tableLink($itemTag["name"],array("controller"=>"item_tags","action"=>"view",$itemTag["id"]));
										}
								}

								$tags = implode(", ", $tmpTags);


								//Old action button with check if one really wants to detach the item
								// $actions = $this->Form->postLink(
									// __('Detach'),
									// array(
										// 'controller' => 'items',
										// 'action' => 'detach',
										// $component['ItemComposition']['id']),
									// null,
									// __('Are you sure you want to detach %s from %s?', $component['code'], $item['Item']['code'])
								// );
								$rowBackups .=  "rowBackups['".$position."'] = '".$this->Html->tableCells($cells[$position])."';\n";
								$cells[$position] = array($position, $status, $code, $tags, $state, $quality, $location, $type, $subtype, $version, $manufacturer, $project, array($actions, array('class'=> 'actions')));
							}
						}
						//debug($item['Component']);

						//Store the empty row in the rowBackups array in javascript so on removal something can displayed as expected
						echo $this->Html->tableCells($cells);
					?>
					</table>
					<div id='debug'></div>

					<?php $out = ''; ?>
					<?php foreach($item['Component'] as $component):  //Create table of all components previously attached?>
						<?php if($component['ItemComposition']['valid'] == FALSE): ?>
							<?php
								$tmpTags = array();
								foreach($component["ItemTag"] as $itemTag){
									$tmpTags[] = $itemTag["name"];
								}
								$tags = implode(", ", $tmpTags);
								$code = $this->Html->tableLink($component['code'], array('controller' => 'items', 'action' => 'view', $component['id']));
								if(count($component["ItemStocks"])>0){
									$code = "Stock&nbsp;item";
								}

							?>
							<?php $out .= '<tr>'?>
							<?php $out .= '<td>' .$component['ItemComposition']['position']. '</td>'?>
							<?php $out .= '<td>' .$code. '</td>'?>
							<?php $out .= '<td>' .$tags. '</td>'?>
							<?php $out .= '<td>' .$component['State']['name']. '</td>'?>
							<?php $out .= '<td>' .$component['ItemQuality']['name']. '</td>'?>
							<?php $out .= '<td>' .$component['Location']['name']. '</td>'?>
							<?php $out .= '<td>' .$component['ItemType']['name']. '</td>'?>
							<?php $out .= '<td>' .$component['ItemSubtype']['name']. '</td>'?>
							<?php $out .= '<td>' .$component['ItemSubtypeVersion']['version']. '</td>'?>
							<?php $out .= '<td>' .$component['Manufacturer']['name']. '</td>'?>
							<?php $out .= '<td>' .$component['Project']['name']. '</td>'?>
							<?php $out .= '<td>' .$component['ItemComposition']['created']. '</td>'?>
							<?php $out .= '<td>' .$component['ItemComposition']['modified']. '</td>'?>
							<?php $out .= '</tr>'?>
						<?php endif; ?>
					<?php endforeach; ?>

					<?php if(strlen($out) > 0): ?>
					<h3 class="actions"><?php  echo __('Detached Components: '); ?></h3>

					<table>
						<tr>
							<th>Position</th>
							<th>Code</th>
							<th>Tags</th>
							<th>State</th>
							<th>Quality</th>
							<th>Location</th>
							<th>Type</th>
							<th>Subtype</th>
							<th>Version</th>
							<th>Manufacturer</th>
							<th>Project</th>
							<th>Attached on</th>
							<th>Detached on</th>
						</tr>
						<?php echo $out; ?>
					</table>
					<?php endif; ?>
		</div>
		<?php endif; ?>


		<div id="files">
			<h3>Item files: <?php echo $item['Item']['code']; ?></h3>
			<table class="file">
				<tr>
					<th><?php echo __('Name'); ?></th>
					<th><?php echo __('Size'); ?></th>
					<th><?php echo __('Type'); ?></th>
					<th><?php echo __('Comment'); ?></th>
					<th><?php echo __('User'); ?></th>
					<th class="actions"><?php echo __('Actions');?></th>
				</tr>
				<?php if (!empty($item['DbFile'])):?>
				<?php foreach ($item['DbFile'] as $f): ?>
				<tr>
					<td>
						<?php echo $this->Html->tableLink($f['name'], array('controller' => 'db_files', 'action' => 'view', $f['id'], 'Item', $item['Item']['id'])); ?>
					</td>
					<td>
						<?php echo $f['size']; ?>
					</td>
					<td>
						<?php echo $f['type']; ?>
					</td>
					<td>
						<?php echo $f['comment']; ?>
						&nbsp;
						<?php echo $this->Html->image('ElegantBlueWeb/settings.png', array(
																				'title' => 'Edit comment',
																				'alt' => __('edit'),
																				'border' => '0',
																				'width'=>'16',
																				'height'=>'16',
																				'align' => 'right',
																				'url' => array('controller' => 'db_files', 'action' => 'changeComment', $f['id'])));
						?>
					</td>
					<td>
						<?php echo $f['User']['username']; ?>
					</td>
					<td>
						<?php echo $this->Html->image('download.png', array(
																'title' => 'Download',
																'alt' => __('Download file'),
																'border' => '0',
																'width'=>'24',
																'height'=>'24',
																'url' => array('controller' => 'db_files','action' => 'download', $f['id'], true))); ?>
						&nbsp;
						<?php echo $this->Form->postLink($this->Html->image('delete.png',
																array(
																	'title' => 'Delete',
																	'alt' => __('Delete file'),
																	'border' => '0',
																	'width'=>'24',
																	'height'=>'24')),
																array(
																	'controller' => 'db_files',
																	'action' => 'delete',
																	'Item',
																	$item['Item']['id'],
																	$f['id']),
																array('escape' => false),
																__('Are you sure you want to delete %s?', $f['name'])); ?>
					</td>
				</tr>
				<?php endforeach; ?>
				<?php else: ?>
				<tr>
					<td colspan="6">No files found.</td>
				</tr>
				<?php endif; ?>
				<tr>
					<td colspan="6">
					<?php echo $this->Html->image('upload.png', array(
														'title' => 'Upload new file',
														'alt' => __('Upload file'),
														'border' => '0',
														'width'=>'34',
														'height'=>'34',
														'url' => array('controller' => 'db_files','action' => 'add', 'Item', $item['Item']['id']))); ?>
					</td>
				</tr>
			</table>

			<h3>Subtype files: <?php echo $item['ItemSubtype']['name']; ?></h3>
			<table class="file">
				<tr>
					<th><?php echo __('Name'); ?></th>
					<th><?php echo __('Size'); ?></th>
					<th><?php echo __('Type'); ?></th>
					<th><?php echo __('Comment'); ?></th>
					<th><?php echo __('User'); ?></th>
					<th class="actions"><?php echo __('Actions');?></th>
				</tr>
				<?php if(!empty($item['ItemSubtype']['DbFile'])): ?>
				<?php foreach ($item['ItemSubtype']['DbFile'] as $f): ?>
				<tr>
					<td>
						<?php echo $this->Html->tableLink($f['name'], array('controller' => 'db_files', 'action' => 'view', $f['id'], 'Item', $item['Item']['id'])); ?>
					</td>
					<td>
						<?php echo $f['size']; ?>
					</td>
					<td>
						<?php echo $f['type']; ?>
					</td>
					<td>
						<?php echo $f['comment']; ?>
						<?php echo $this->Html->image('ElegantBlueWeb/settings.png', array(
																				'title' => 'Edit comment',
																				'alt' => __('edit'),
																				'border' => '0',
																				'width'=>'16',
																				'height'=>'16',
                                                                                'align' => 'right',
																				'url' => array('controller' => 'db_files', 'action' => 'changeComment', $f['id'])));
						?>
					</td>
					<td>
						<?php echo $f['User']['username']; ?>
					</td>
					<td>
						<?php echo $this->Html->image('download.png', array(
																'title' => 'Download',
																'alt' => __('Download file'),
																'border' => '0',
																'width'=>'24',
																'height'=>'24',
																'url' => array('controller' => 'db_files','action' => 'download', $f['id'], true))); ?>
						&nbsp;
						<?php echo $this->Form->postLink($this->Html->image('delete.png',
																array(
																	'title' => 'Delete',
																	'alt' => __('Delete file'),
																	'border' => '0',
																	'width'=>'24',
																	'height'=>'24')),
																array(
																	'controller' => 'db_files',
																	'action' => 'delete',
																	'ItemSubtype',
																	$item['ItemSubtype']['id'],
																	$f['id']),
																array('escape' => false),
																__('Are you sure you want to delete %s?', $f['name'])); ?>
					</td>
				</tr>
				<?php endforeach; ?>
				<?php else: ?>
					<tr>
						<td colspan="6">No files found.</td>
					</tr>
				<?php endif; ?>
				<tr>
					<td colspan="6">
						<?php echo $this->Html->image('upload.png', array(
															'title' => 'Upload new file',
															'alt' => __('Upload file'),
															'border' => '0',
															'width'=>'34',
															'height'=>'34',
															'url' => array('controller' => 'db_files','action' => 'add', 'ItemSubtype', $item['ItemSubtype']['id']))); ?>
					</td>
				</tr>
			</table>

		<h3>Version files: <?php echo $item['ItemSubtype']['name'].' v'.$item['ItemSubtypeVersion']['version']; ?></h3>
			<table class="file">
				<tr>
					<th><?php echo __('Name'); ?></th>
					<th><?php echo __('Size'); ?></th>
					<th><?php echo __('Type'); ?></th>
					<th><?php echo __('Comment'); ?></th>
					<th><?php echo __('User'); ?></th>
					<th class="actions"><?php echo __('Actions');?></th>
				</tr>
				<?php if(!empty($item['ItemSubtypeVersion']['DbFile'])): ?>
				<?php foreach ($item['ItemSubtypeVersion']['DbFile'] as $f): ?>
				<tr>
					<td>
						<?php echo $this->Html->tableLink($f['name'], array('controller' => 'db_files', 'action' => 'view', $f['id'], 'Item', $item['Item']['id'])); ?>
					</td>
					<td>
						<?php echo $f['size']; ?>
					</td>
					<td>
						<?php echo $f['type']; ?>
					</td>
					<td>
						<?php echo $f['comment']; ?>
						&nbsp;
						<?php echo $this->Html->image('ElegantBlueWeb/settings.png', array(
																				'title' => 'Edit comment',
																				'alt' => __('edit'),
																				'border' => '0',
																				'width'=>'16',
																				'height'=>'16',
                                                                                'align' => 'right',
																				'url' => array('controller' => 'db_files', 'action' => 'changeComment', $f['id'])));
						?>
					</td>
					<td>
						<?php echo $f['User']['username']; ?>
					</td>
					<td>
						<?php echo $this->Html->image('download.png', array(
																'title' => 'Download',
																'alt' => __('Download file'),
																'border' => '0',
																'width'=>'24',
																'height'=>'24',
																'url' => array('controller' => 'db_files','action' => 'download', $f['id'], true))); ?>
						&nbsp;
						<?php echo $this->Form->postLink($this->Html->image('delete.png',
																array(
																	'title' => 'Delete',
																	'alt' => __('Delete file'),
																	'border' => '0',
																	'width'=>'24',
																	'height'=>'24')),
																array(
																	'controller' => 'db_files',
																	'action' => 'delete',
																	'ItemSubtypeVersion',
																	$item['ItemSubtypeVersion']['id'],
																	$f['id']),
																array('escape' => false),
																__('Are you sure you want to delete %s?', $f['name'])); ?>
					</td>
				</tr>
				<?php endforeach; ?>
				<?php else: ?>
				<tr>
					<td colspan="6">No files found.</td>
				</tr>
				<?php endif; ?>
				<tr>
					<td colspan="6">
						<?php echo $this->Html->image('upload.png', array(
															'title' => 'Upload new file',
															'alt' => __('Upload file'),
															'border' => '0',
															'width'=>'34',
															'height'=>'34',
															'url' => array('controller' => 'db_files','action' => 'add', 'ItemSubtypeVersion', $item['ItemSubtypeVersion']['id']))); ?>
					</td>
				</tr>
			</table>

		<h3>Project files: <?php echo $item['Project']['name']; ?></h3>
			<table class="file">
				<tr>
					<th><?php echo __('Name'); ?></th>
					<th><?php echo __('Size'); ?></th>
					<th><?php echo __('Type'); ?></th>
					<th><?php echo __('Comment'); ?></th>
					<th><?php echo __('User'); ?></th>
					<th class="actions"><?php echo __('Actions');?></th>
				</tr>
				<?php if(!empty($item['Project']['DbFile'])): ?>
				<?php foreach ($item['Project']['DbFile'] as $f): ?>
				<tr>
					<td>
						<?php echo $this->Html->tableLink($f['name'], array('controller' => 'db_files', 'action' => 'view', $f['id'], 'Item', $item['Item']['id'])); ?>
					</td>
					<td>
						<?php echo $f['size']; ?>
					</td>
					<td>
						<?php echo $f['type']; ?>
					</td>
					<td>
						<?php echo $f['comment']; ?>
						&nbsp;
						<?php echo $this->Html->image('ElegantBlueWeb/settings.png', array(
																				'title' => 'Edit comment',
																				'alt' => __('edit'),
																				'border' => '0',
																				'width'=>'16',
																				'height'=>'16',
																				'align' => 'right',
																				'url' => array('controller' => 'db_files', 'action' => 'changeComment', $f['id'])));
						?>
					</td>
					<td>
						<?php echo $f['User']['username']; ?>
					</td>
					<td>
						<?php echo $this->Html->image('download.png', array(
																'title' => 'Download',
																'alt' => __('Download file'),
																'border' => '0',
																'width'=>'24',
																'height'=>'24',
																'url' => array('controller' => 'db_files','action' => 'download', $f['id'], true))); ?>
						&nbsp;
						<?php echo $this->Form->postLink($this->Html->image('delete.png',
																array(
																	'title' => 'Delete',
																	'alt' => __('Delete file'),
																	'border' => '0',
																	'width'=>'24',
																	'height'=>'24')),
																array(
																	'controller' => 'db_files',
																	'action' => 'delete',
																	'Project',
																	$item['Project']['id'],
																	$f['id']),
																array('escape' => false),
																__('Are you sure you want to delete %s?', $f['name'])); ?>
					</td>
				</tr>
				<?php endforeach; ?>
				<?php else: ?>
				<tr>
					<td colspan="6">No files found.</td>
				</tr>
				<?php endif; ?>
				<tr>
					<td colspan="6">
						<?php echo $this->Html->image('upload.png', array(
															'title' => 'Upload new file',
															'alt' => __('Upload file'),
															'border' => '0',
															'width'=>'34',
															'height'=>'34',
															'url' => array('controller' => 'db_files','action' => 'add', 'Project', $item['Project']['id']))); ?>
					</td>
				</tr>
			</table>
		</div>

		<div id="measurements">
			<?php echo $this->Html->link(__('Add Measurement'), array('controller' => 'measurements', 'action' => 'add',$item["Item"]["id"])); ?>
			<br>
			<?php if (!empty($measurements)):?>
			<table cellpadding = "0" cellspacing = "0">
				<tr>
					<th>View</th>
					<th>Type</th>
					<th>Tags</th>
					<th>Uploaded by</th>
					<th>started</th>
					<th>finished</th>
					<th>Saved</th>
				</tr>
				<?php foreach($measurements as $measurement):?>
				<tr>
					<td><?php echo $this->Html->link($measurement["Measurement"]["id"],array("controller"=>"measurements","action"=>"view",$measurement["Measurement"]["id"]));?></td>
					<td><?php echo $this->Html->link($measurement["MeasurementType"]["name"],array("controller"=>"measurements","action"=>"view",$measurement["Measurement"]["id"])); ?></td>
					<td>
						<?php if(count($measurement["MeasurementTag"])>0): ?>
							<?php foreach($measurement["MeasurementTag"] as $tagId=>$tagName): ?>
								<?php echo $this->Html->link($tagName, array('controller' => 'measurement_tags', 'action' => 'view', $tagId)); ?>
							<?php endforeach; ?>
						<?php endif; ?>
					</td>
					<td><?php echo $measurement["User"]["first_name"]." ".$measurement["User"]["last_name"];?></td>
					<td><?php echo $measurement["Measurement"]["start"]?></td>
					<td><?php echo $measurement["Measurement"]["stop"]?></td>
					<td><?php echo $measurement["History"]["created"]?></td>
				</tr>


			<?php endforeach; ?>
			</table>
			<?php else: ?>
				No measurements found.
			<?php endif; ?>
		</div>

		<div id="transfers">
			<?php if (!empty($transfers)):?>
				<table cellpadding = "0" cellspacing = "0">
					<tr>
						<th><?php echo __('Shipping Date'); ?></th>
						<th><?php echo __('From'); ?></th>
						<th><?php echo __('To'); ?></th>
						<th><?php echo __('Tracking Number'); ?></th>
						<th><?php echo __('Deliverer'); ?></th>
						<th><?php echo __('Comment'); ?></th>
						<th><?php echo __('Responsible User'); ?></th>
						<th class="actions"><?php echo __('Actions');?></th>
					</tr>
					<?php
						$i = 0;
						foreach ($transfers as $t): ?>
						<tr>
							<td>
								<?php echo substr($t['shipping_date'],0,10); ?>
							</td>
							<td>
								<?php echo $this->Html->link($t['ItemsTransfer']['From']['name'], array('controller' => 'locations','action' => 'view', $t['ItemsTransfer']['From']['id'])); ?>
							</td>
							<td>
								<?php echo $this->Html->link($t['ItemsTransfer']['To']['name'], array('controller' => 'locations','action' => 'view', $t['ItemsTransfer']['To']['id'])); ?>
							</td>
							<td>
								<?php 	if(!empty($t['link'])) echo $this->Html->link($t['tracking_number'], $t['link']);
										else echo $t['tracking_number']; ?>
							</td>
							<td>
								<?php echo $this->Html->link($t['Deliverer']['name'], array('controller' => 'deliverers','action' => 'view', $t['Deliverer']['id'])); ?>
							</td>
							<td>
								<?php echo $t['comment']; ?>
							</td>
							<td>
								<?php 	if(isset($t['User']['username']))
											echo $this->Html->link($t['User']['username'], array('controller' => 'users', 'action' => 'view', $t['User']['id']));
										else
											echo 'Unknown';
								?>
							</td>
							<td class="actions">
								<?php echo $this->Html->link(__('View'), array('controller' => 'transfers','action' => 'view', $t['id'])); ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php else: ?>
				No transfers found.
			<?php endif; ?>
		</div>
	</div>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('Item:'); ?></h2>
	<span><?php echo $item['Item']['code']; ?></span>
	<ul id="top-menu">
		<li class='active'><?php echo $this->Html->link(
											'Add comment',
											array('controller' => 'histories', 'action' => 'addComment', $item['Item']['id']),
											array('title' => 'Add a comment to the items history'));?> </li>
		<li class='active'><?php echo $this->Html->link(
														'Modify checklist',
														array('controller' => 'checklists', 'action' => 'update', $item['Item']['id'], $item['Checklist']['id']),
														array('title' => 'Modify the item checklist'));?> </li>
      <li class='active has-sub'><?php echo $this->Html->link(__('Transfer'), array('plugin' => null, 'controller' => 'transfers', 'action' => 'index')); ?></a>
         <ul>
         <?php
         if ($item['Location']['id']==$standardLocation['Location']['id']) {
            if(empty($item['CompositeItemChain'])) {
               if($item['TransferPending']!=-1) {
                  echo "<li class='active'>";
                  echo $this->Html->link(__('Warning: item has transfer pending. click to see transfer'),array('controller'=>'transfers','action'=>'view',$item['TransferPending']));
                  echo "</li>";
               }
               echo "<li class='active'><a href='#' onclick='addToTransfer()'>new transfer from ".$item['Location']['name']."</a></li>";
               foreach($pendingTransfers as $fromId=>$transfers){
                  if($fromId==$item['Location']['id']) {
                     foreach($transfers as $transfer) {
                        echo "<li class='active'><a href='#' onclick='addToTransfer(".$transfer['Transfer']['id'].")'>";
                        echo sprintf(__('add to transfer from %s to %s at %s'),
                                          $transfer["From"]["name"],$transfer["To"]["name"],substr($transfer['Transfer']['shipping_date'],0,10));
                        echo '</a></li>';
                     }
                  }
               }
            } else {
               $top_item = $item['CompositeItemChain'][0];
               echo '<li class="active">';
               echo $this->Html->link("item is attached. view ".$top_item['code'],array('action'=>'view',$top_item['id']));
               echo '</li>';
            }
         } else {
            echo '<li class="active">';
            echo $this->Html->link("user location doesn't match item location. change standard location", array('plugin' => null,'controller' => 'users', 'action' => 'changeStandardLocation'));
            echo '</li>';
         }
         ?>
         </ul>
      </li>
		<?php if (!empty($item['ItemSubtypeVersion']['Component'])):?>
		<li class='active'><?php echo $this->Html->link(
											'Post registration',
											array('controller' => 'items', 'action' => 'postRegistration', $item['Item']['id']),
											array('title' => 'Register components of this item'));?> </li>
		<?php endif; ?>
	</ul>

	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?></div>
</div>

<style>
  .custom-combobox {
    position: relative;
    display: inline-block;
  }
  .custom-combobox-toggle {
    position: absolute;
    top: 0;
    bottom: 0;
    margin-left: -1px;
    padding: 0;
    display:none;
  }
  .custom-combobox-input {
    margin: 0;
    padding: 5px 10px;
    cursor:pointer;
  }
  .ui-autocomplete{
  	max-height:200px;
  	overflow-y: auto;
  	overflow-x: hidden;
  }
  </style>

<?php echo $this->Html->script("combobox"); ?>
<script type='text/javascript'>
var overlayCloseButton = '<div style="float: right;"><?php echo $this->Html->image("ElegantBlueWeb/xmark.png",array("alt"=>"X","width"=>"20","onClick"=>"closeSelectorOverlay();","style"=>"cursor:pointer; z-index:5;")); ?></div>';
var itemViewBaseUrl = '<?php echo $this->Html->url(array("controller"=>"Items","action"=>"view")); ?>/';
</script>
<?php echo $this->Html->script("component_modification");
	echo "<script type='text/javascript'>".$rowBackups."</script>";
?>
<script>
var stockItemIds = $.parseJSON('<?php echo $stockItemIds; ?>');
var viewedItemId = <?php echo $item['Item']['id']; ?>;
var viewedLocationName = '<?php echo $item["Location"]['name']; ?>'; //Storing the viewed location name to display the correct location even if a stock item is selected. Fixing a displaying bug, the real check of course takes place on the server side
  $(function() {
    $( "#combobox" ).combobox();
    $( "#toggle" ).click(function() {
      $( "#combobox" ).toggle();
    });
    $("#saveItemParameter").click(function(){
    	var newItemParameter = {}
    	if($("#combobox").val() != null){
	    	newItemParameter.parameter = $("#combobox").val();
	    	// console.log($("#combobox").val());
    	}else{
    		alert("You need to select a parameter");
    		return
    	}
    	if(!isNaN($("#newValue").val()) && $("#newValue").val() != ""){

	    	newItemParameter.value = $("#newValue").val();
    	}else{
    		alert("You need to enter a numeric value for this parameter");
    		$("#newValue").focus();
    		return
    	}
    	newItemParameter.comment = $("#newComment").val();
    	// console.log(newItemParameter);
    	$.ajax('<?php echo $this->Html->url(array("controller"=>"Items","action"=>"addToItem",$item['Item']['id'])); ?>/addItemParameter',{
    		type: "POST",
    		data: newItemParameter,
    		success: function(data){
    			$("#newItemParameter").parent().append(data);
    			//Push input to end again
    			$("#newItemParameter").parent().append($("#newItemParameter"));
    			//Reset the input fields
    			// $("#combobox").prop("selectedIndex", -1);
    			// $("#combobox").parent().find("span input").val("--select an option--");
    			$("#newValue").val("");
    			$("#newComment").val("");
    		}
    	});
    });
    $(".removeItemParameter").each(function(){
    	$(this).click(function(){
    		var elem = $(this);
	    	var newItemParameter = {}
	    	newItemParameter.parameterId = $(this).attr("parameterId");
	    	$.ajax('<?php echo $this->Html->url(array("controller"=>"Items","action"=>"addToItem",$item['Item']['id'])); ?>/removeItemParameter',{
	    		type: "POST",
	    		data: newItemParameter,
	    		success: function(data){
	    			//Hide deleted parameter
	    			data = JSON.parse(data);
	    			console.log(data);
	    			if(data.error){
	    				alert("error during deletion");
	    			}else{
		    			elem.parent().parent().fadeOut(500);
	    			}
	    		}
	    	});
    	});
    });
	  $("#itemHierachyButton").click(function(){
		 $("#itemHierachy").slideToggle("slow"); 
	  });
  });


function storeItemAttachment(itemId,componentPosition){
	$.ajax('<?php echo $this->Html->url(array("controller"=>"Items","action"=>"attach")); ?>/'+viewedItemId+'/'+itemId+'/'+componentPosition,{
		type: "POST",
		data: {},
		success: function(data){
			$("#debug").html(data);
		}
	});

}

function storeItemDetachment(itemId,componentPosition,row,table,successFn){
	console.log("storing detachment");
	var addBackToStock = false;
	var abort = true;
	if(stockItemIds.indexOf(itemId+"")>=0){
		//Ask user if to add back to stock
		$("#content").append("<div id='dialog'>You are detaching a stock item. Do you want to add it back to the stock?</div>");
		$("#dialog").dialog({
			resizable: false,
			height: 140,
			modal: true,
			buttons: {
				"Yes": function() {
					abort = false;
					addBackToStock = true;
					$(this).dialog("close");
				},
				"No": function() {
					abort = false;
					addBackToStock = false;
					$(this).dialog("close");
				},
				"Abort": function(){
					abort = true;
					$(this).dialog("close");
				}
			},
			close: function(){
				if(abort){
					return false;
				}
				$.ajax('<?php echo $this->Html->url(array("controller"=>"Items","action"=>"detach")); ?>/'+viewedItemId+'/'+itemId+'/'+componentPosition+"/"+addBackToStock,{
					type: "POST",
					data: {},
					success: function(data){
						//Add detached item to table (maybe even reload the whole table)
						$("#debug").html(data);
					}
				});
				successFn(componentPosition,row,table,itemId);
			}
		});
	}else{
		//Ask user if to add back to stock
		$("#content").append("<div id='dialog'>Are you sure you want to detach this item?</div>");
		$("#dialog").dialog({
			resizable: false,
			height: 140,
			modal: true,
			buttons: {
				"Yes": function() {
					abort = false;
					addBackToStock = true;
					$(this).dialog("close");
				},
				"Abort": function(){
					abort = true;
					$(this).dialog("close");
				}
			},
			close: function(){
				if(abort){
					return false;
				}
				$.ajax('<?php echo $this->Html->url(array("controller"=>"Items","action"=>"detach")); ?>/'+viewedItemId+'/'+itemId+'/'+componentPosition+"/"+addBackToStock,{
					type: "POST",
					data: {},
					success: function(data){
						//Add detached item to table (maybe even reload the whole table)
						$("#debug").html(data);
					}
				});
				successFn(componentPosition,row,table,itemId);
			}
		});
	}
}
function getNewTargetRow(data,componentPosition,itemId){
	var newTargetRow = "<tr><td>"+componentPosition+"</td>";
	newTargetRow += '<td><?php echo $this->Html->image('Tick.png', array('border' => '0','width'=>'16','height'=>'16',)); ?></td>';
	if(data.Item.code.indexOf("Stock_") != -1){
		newTargetRow += "<td>Stock item";
	}else{
		newTargetRow += "<td>"+data.Item.code;
	}
	newTargetRow += "<input type='hidden' value='"+componentPosition+"' name='data[Component]["+componentPosition+"][position]'>";
	newTargetRow += "<input type='hidden' value='"+itemId+"' name='data[Component]["+componentPosition+"][component_id]'>";
	newTargetRow += "</td><td>";
	//Special treatment for tags, as usual
	$(data.ItemTag).each(function(){
		newTargetRow += this.name+" ";
	});
	newTargetRow += "</td><td>"+data.State.name+"</td>";
	newTargetRow += "<td>"+data.ItemQuality.name+"</td>";
	newTargetRow += "<td>"+viewedLocationName+"</td>";
	newTargetRow += "<td>"+data.ItemType.name+"</td>";
	newTargetRow += "<td>"+data.ItemSubtype.name+"</td>";
	newTargetRow += "<td>"+data.ItemSubtypeVersion.version+"</td>";
	//add the hidden fields for the component information
	newTargetRow += "<td>"+data.Manufacturer.name+"</td>";
	newTargetRow += "<td>"+data.Project.name+"</td>";
	newTargetRow += "<td class='actions'><a href='#' onClick='removeItemFromPosition(this,event,\""+componentPosition+"\","+itemId+");'  >Detach</a></td></tr>";
	return newTargetRow;
}
  </script>
