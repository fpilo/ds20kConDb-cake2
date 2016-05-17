<?php
	$this->Html->addCrumb($itemSubtypeVersion['ItemSubtype']["ItemType"]['name'], '/item_types/view/'.$itemSubtypeVersion['ItemSubtype']["ItemType"]['id']);
	$this->Html->addCrumb($itemSubtypeVersion['ItemSubtype']['name'], '/item_subtypes/view/'.$itemSubtypeVersion['ItemSubtype']['id']);
	$stVersionName = ($itemSubtypeVersion['ItemSubtypeVersion']['name'] != "")? $itemSubtypeVersion['ItemSubtypeVersion']['version']." (".$itemSubtypeVersion['ItemSubtypeVersion']['name'].")": "v".$itemSubtypeVersion['ItemSubtypeVersion']['version'];
	$this->Html->addCrumb($stVersionName, '/item_subtype_versions/view/'.$itemSubtypeVersion['ItemSubtypeVersion']['id']);
?>
<script type="text/javascript">
			$(function(){
				restoreTabs('ItemSubtypeVersionsViewTabIndex');
				//Get all used tags
				var tags = new Array();
				$("#notGradedMeasurements td:last-child a").each(function(){
					temp = $(this).html();
					if(tags.indexOf(temp)==-1){
						tags.push(temp);
					}
				});
				//Show clickable links for each tag
				$(tags).each(function(){
					tagName = this;
					$("#tagSelector").append("<input type='button' value='select "+tagName+"' onClick='markFromTag(\""+tagName+"\")' style='margin-left:5px; margin-right:5px;' />");
				});


			});

			//deselect all measurements and only select the ones with the applicable tag
			function markFromTag(tagName){
				$(".displayTag").parent().parent().find("td:first-child input").each(function(){$(this).prop('checked', false)})
				$(".displayTag").each(function(){
					if($(this).html()==tagName){
						$(this).parent().parent().find("td:first-child input").each(function(){$(this).prop('checked', true)});
					}
				});
			}

			function deleteAllGradings(){
				$("#withGrading form").each(function(){
					var row = $(this).parent().parent();
					$.post($(this).attr("action"),function(){
						row.fadeOut(500,function(){
							row.remove();
							document.location.href = document.location.href;
						});
					});
				})
			}
</script>

<div class="itemSubtypeVersions view">
	<div id="tabs" class='related'>
		<ul>
			<li><a href="#information"><?php echo __('Version Information');?></a></li>
			<li><a href="#items"><?php echo __('Items');?></a></li>
			<li><a href="#components"><?php echo __('Components');?></a></li>
			<li><a href="#files"><?php echo __('Files');?></a></li>
			<?php if($measurements !== null && $this->Session->check('Auth.User.Permissions.controllers/Measurements/gradeSubtypeVersion')): ?>
				<li><a href="#measurements"><?php echo __('Measurement Grading');?></a></li>
			<?php endif;?>
		</ul>

		<div id="information">
			<dl>
				<dt><?php echo __('Id'); ?></dt>
				<dd>
					<?php echo h($itemSubtypeVersion['ItemSubtypeVersion']['id']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Version'); ?></dt>
				<dd>
					<?php echo h($itemSubtypeVersion['ItemSubtypeVersion']['version']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Name'); ?></dt>
				<dd>
					<?php echo h($itemSubtypeVersion['ItemSubtypeVersion']['name']); ?>
					&nbsp;
					<?php echo $this->Html->image('ElegantBlueWeb/settings.png', array(
																				'alt' => __('edit'),
																				'border' => '0',
																				'width'=>'16',
																				'height'=>'16',
																				'url' => array('controller' => 'item_subtype_versions', 'action' => 'editName', $itemSubtypeVersion['ItemSubtypeVersion']['id'])));
					?>
				</dd>
				<dt><?php echo __('Manufacturer'); ?></dt>
				<dd>
					<?php echo $this->Html->link($itemSubtypeVersion['Manufacturer']['name'], array('controller' => 'manufacturers', 'action' => 'view', $itemSubtypeVersion['Manufacturer']['id'])); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Project'); ?></dt>
				<dd>
					<?php foreach($itemSubtypeVersion['Project'] as $project): ?>
						<?php echo $this->Html->link($project['name'], array('controller' => 'projects', 'action' => 'view', $project['id'])); ?>
						&nbsp;
					<?php endforeach; ?>
				</dd>
				<dt><?php echo __('Comment'); ?></dt>
				<dd>
					<?php echo $itemSubtypeVersion['ItemSubtypeVersion']['comment']; ?>
					&nbsp;
					<?php echo $this->Html->image('ElegantBlueWeb/settings.png', array(
																				'alt' => __('edit'),
																				'border' => '0',
																				'width'=>'16',
																				'height'=>'16',
																				'url' => array('controller' => 'item_subtype_versions', 'action' => 'editComment', $itemSubtypeVersion['ItemSubtypeVersion']['id'])));
					?>
				</dd>
				<dt><?php echo __('Item Subtype'); ?></dt>
				<dd>
					<?php echo $this->Html->link($itemSubtypeVersion['ItemSubtype']['name'], array('controller' => 'item_subtypes', 'action' => 'view', $itemSubtypeVersion['ItemSubtype']['id'])); ?>
					&nbsp;
				</dd>
			</dl>
		</div>

		<div id="items">
			<?php if (!empty($itemSubtypeVersion['Item'])):?>
			<table cellpadding = "0" cellspacing = "0">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Code'); ?></th>
			</tr>
			<?php
				$i = 0;
				foreach ($itemSubtypeVersion['Item'] as $item): ?>
				<tr>
					<td><?php echo $item['id'];?></td>
					<td><?php echo $this->Html->link($item['code'], array('controller' => 'items', 'action' => 'view', $item['id'])); ?></td>
				</tr>
			<?php endforeach; ?>
			</table>

			<?php else: ?>
				No items found.
			<?php endif; ?>

		</div>

		<div id="files">
			<h3>Version files: <?php echo $itemSubtypeVersion['ItemSubtype']['name'].' v'.$itemSubtypeVersion['ItemSubtypeVersion']['version'].$stVersionName; ?></h3>
			<table class="file">
				<tr>
					<th><?php echo __('Name'); ?></th>
					<th><?php echo __('Size'); ?></th>
					<th><?php echo __('Type'); ?></th>
					<th><?php echo __('Comment'); ?></th>
					<th><?php echo __('User'); ?></th>
					<th class="actions"><?php echo __('Actions');?></th>
				</tr>
				<?php if (!empty($itemSubtypeVersion['DbFile'])):?>
					<?php foreach ($itemSubtypeVersion['DbFile'] as $f): ?>
						<tr>
							<td>
								<?php echo $this->Html->tableLink($f['name'], array('controller' => 'db_files', 'action' => 'view', $f['id'])); ?>
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
																		'url' => array('controller' => 'db_files','action' => 'download', $f['id']))); ?>
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
																			$itemSubtypeVersion['ItemSubtypeVersion']['id'],
																			$f['id']),
																		array('escape' => false),
																		__('Are you sure you want to delete %s?', $f['name'])); ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php else: ?>
				<tr><td colspan="6">No files found.</td></tr>
			<?php endif; ?>
				<tr>
					<td colspan="6">
					<?php echo $this->Html->image('upload.png', array(
														'title' => 'Upload new file',
														'alt' => __('Upload file'),
														'border' => '0',
														'width'=>'34',
														'height'=>'34',
														'url' => array('controller' => 'db_files','action' => 'add', 'ItemSubtypeVersion', $itemSubtypeVersion['ItemSubtypeVersion']['id']))); ?>
					</td>

				</tr>
			</table>

			<h3>Subtype files: <?php echo $itemSubtypeVersion['ItemSubtype']['name']; ?></h3>
			<table class="file">
				<tr>
					<th><?php echo __('Name'); ?></th>
					<th><?php echo __('Size'); ?></th>
					<th><?php echo __('Type'); ?></th>
					<th><?php echo __('Comment'); ?></th>
					<th><?php echo __('User'); ?></th>
					<th class="actions"><?php echo __('Actions');?></th>
				</tr>
				<?php if (!empty($itemSubtypeVersion['ItemSubtype']['DbFile'])):?>
					<?php foreach ($itemSubtypeVersion['ItemSubtype']['DbFile'] as $f): ?>
						<tr>
							<td>
								<?php echo $this->Html->tableLink($f['name'], array('controller' => 'db_files', 'action' => 'view', $f['id'])); ?>
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
																		'url' => array('controller' => 'db_files','action' => 'download', $f['id']))); ?>
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
																			$itemSubtypeVersion['ItemSubtype']['id'],
																			$f['id']),
																		array('escape' => false),
																		__('Are you sure you want to delete %s?', $f['name'])); ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php else: ?>
				<tr><td colspan="6">No files found.</td></tr>
			<?php endif; ?>
				<tr>
					<td colspan="6">
					<?php echo $this->Html->image('upload.png', array(
														'title' => 'Upload new file',
														'alt' => __('Upload file'),
														'border' => '0',
														'width'=>'34',
														'height'=>'34',
														'url' => array('controller' => 'db_files','action' => 'add', 'ItemSubtype', $itemSubtypeVersion['ItemSubtype']['id']))); ?>
					</td>

				</tr>
			</table>
		</div>

		<div id="components">
			<?php if (!empty($itemSubtypeVersion['Component'])):?>
			<table cellpadding = "0" cellspacing = "0">
			<tr>
				<!--<th><?php //echo __('Id'); ?></th>-->
				<th><?php echo __('Position'); ?></th>
				<th><?php echo __('PosName'); ?></th>
				<th><?php echo __('Type'); ?></th>
				<th><?php echo __('Subtype'); ?></th>
				<th><?php echo __('Version'); ?></th>
				<th><?php echo __('Project'); ?></th>
				<th><?php echo __('Manufacturer'); ?></th>
				<th><?php echo __('Comment'); ?></th>
				<th><?php echo __('Has Components'); ?></th>
				<th><?php echo __('Attached at delivery'); ?></th>
				<th><?php echo __('Is a stock item'); ?></th>
				<th><?php echo __('Allow other versions'); ?></th>
			</tr>
			<?php
				$i = 0;
				foreach ($itemSubtypeVersion['Component'] as $component): ?>
				<?php $version = ($component['name']!= "")?$component['version']." (".$component['name'].")":$component['version']; ?>
				<tr>
					<!--<td><?php //echo $component['id'];?></td>-->
					<td><?php echo $component['ItemSubtypeVersionsComposition']['position'];?></td>
					<td><?php echo $component['ItemSubtypeVersionsComposition']['position_name'];?></td>
					<td><?php echo $this->Html->link($component['ItemSubtype']['ItemType']['name'], array('controller' => 'item_types', 'action' => 'view', $component['ItemSubtype']['ItemType']['id'])); ?>
					<td><?php echo $this->Html->link($component['ItemSubtype']['name'], array('controller' => 'item_subtypes', 'action' => 'view', $component['ItemSubtype']['id'])); ?></td>
					<td><?php echo $this->Html->link($version, array('controller' => 'item_subtype_versions', 'action' => 'view', $component['id'])); ?></td>
					<td><?php echo $this->Html->link($component['ItemSubtypeVersionsComposition']['project_name'], array('controller' => 'projects', 'action' => 'view', $component['ItemSubtypeVersionsComposition']['project_id'])); ?></td>
					<td><?php echo $this->Html->link($component['Manufacturer']['name'], array('controller' => 'manufacturers', 'action' => 'view', $component['Manufacturer']['id'])); ?></td>
					<td><?php echo $component['comment'];?></td>
					<td><?php echo $this->Form->input('has_components', array('type' => 'checkbox', 'checked' => $component['has_components'], 'label' => false, 'disabled' => true)) ;?></td>
					<td><?php echo $this->Form->input('attached', array('type' => 'checkbox', 'checked' => $component['ItemSubtypeVersionsComposition']['attached'], 'label' => false, 'disabled' => true)) ;?></td>
					<td><?php echo $this->Form->input('is_stock', array('type' => 'checkbox', 'checked' => $component['ItemSubtypeVersionsComposition']['is_stock'], 'label' => false, 'disabled' => true)) ;?></td>
					<td><?php echo $this->Form->input('all_versions', array('type' => 'checkbox', 'checked' => $component['ItemSubtypeVersionsComposition']['all_versions'], 'label' => false, 'disabled' => true)) ;?></td>
				</tr>
			<?php endforeach; ?>
			</table>
			<?php else: ?>
				No components found.
			<?php endif; ?>
		</div>
		<?php if($measurements !== null && $this->Session->check('Auth.User.Permissions.controllers/Measurements/gradeSubtypeVersion')): ?>
			<div id="measurements">
				<?php
#				debug($measurements);
				#Because otherweise Aptana is not happy and I don't want a false positive error message here -- Benni
				$firstMeasurementId = array_values($measurements);
				if(count($firstMeasurementId)>0):
					$firstMeasurementId = $firstMeasurementId[0]["measurement_id"];
				?>
				<form method='post' name='measurementsToGrade' action='<?php
				echo $this->Html->url(array("controller"=>"Measurements","action"=>"gradeSubtypeVersion",$itemSubtypeVersion["ItemSubtypeVersion"]["id"],$firstMeasurementId));
				?>'>
					<h3>No parameters applied yet</h3>
					<div id='tagSelector'></div>
					<input type="submit" value="Create 'bad strip map' by applying value limits" />
					<br />
					<table id='notGradedMeasurements'>
						<tr>
							<th>&nbsp;</th>
							<th>Item</th>
							<th>Measurement</th>
							<th>Measurement Type</th>
							<th>Measurement Setup</th>
							<th>Measurement Tags</th>
						</tr>
						<?php foreach($measurements as $id=>$measurement): ?>
							<tr>
								<td><input type='checkbox' value='<?php echo $measurement["measurement_id"];?>' name='selected[]'></td>
								<td><?php echo $this->Html->link($measurement["code"],array("controller"=>"Items","action"=>"view",$measurement["id"]));?></td>
								<td><?php echo $this->Html->link($measurement["measurement_id"],array("controller"=>"Measurements","action"=>"view",$measurement["measurement_id"]));?></td>
								<td><?php echo $this->Html->link($measurement["measurement_type"],array("controller"=>"Measurements","action"=>"view",$measurement["measurement_type_id"]));?></td>
								<td><?php echo $this->Html->link($measurement["measurement_device"],array("controller"=>"Measurements","action"=>"view",$measurement["measurement_device_id"]));?></td>
								<td><?php foreach($measurement["measurement_tags"] as $tagId=>$tagName)
										echo $this->Html->link($tagName,array("controller"=>"MeasurementTags","action"=>"view",$tagId),array("class"=>"displayTag"))." ";
									?>
								</td>
							</tr>
						<?php endforeach; ?>
					</table>
				</form>
				<?php else: ?>
					<h3>There are no Measurements of this Subtype Version that haven't had limits applied to them.</h3> <br /><br />
				<?php endif;?>
				<h3>List of measurements where parameters have been applied</h3>
				<table id='withGrading'>
					<tr>
						<th class="actions">Measurement</th>
						<th>Item</th>
						<th>Measurement Type</th>
						<th>Measurement Setup</th>
						<th>Measurement Tags</th>
						<th>Delete Strip error Measurement <input type='button' value='delete All' onClick="deleteAllGradings()" /></th>
					</tr>
					<?php foreach($previousBatch as $id=>$measurement): ?>
						<tr>
							<td class="actions"><?php echo $this->Html->link("View",array("controller"=>"Measurements","action"=>"view",$measurement["measurement_id"]));?></td>
							<td><?php echo $this->Html->link($measurement["code"],array("controller"=>"Items","action"=>"view",$measurement["id"]));?></td>
							<td><?php echo $this->Html->link($measurement["measurement_type"],array("controller"=>"Measurements","action"=>"view",$measurement["measurement_type_id"]));?></td>
							<td><?php echo $this->Html->link($measurement["measurement_device"],array("controller"=>"Measurements","action"=>"view",$measurement["measurement_device_id"]));?></td>
							<td><?php foreach($measurement["measurement_tags"] as $tagId=>$tagName)
									echo $this->Html->link($tagName,array("controller"=>"MeasurementTags","action"=>"view",$tagId))." ";
								?>
							</td>
							<td class="actions"><?php echo $this->Form->postLink(__('Delete'), array('controller'=>"Measurements",'action' => 'delete', $measurement["strip_error_measurement_id"]), null, __('Are you sure you want to delete this strip error measurement?'));?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			</div>
		<?php endif;?>
	</div>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('Item Subtype Versions');?></h2>
	<ul>
		<li class='active' ><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $itemSubtypeVersion['ItemSubtypeVersion']['id'])); ?> </li>
		<li class='active last'><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $itemSubtypeVersion['ItemSubtypeVersion']['id']), null, __('Are you sure you want to delete Version %s?', $itemSubtypeVersion['ItemSubtypeVersion']['version'])); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
