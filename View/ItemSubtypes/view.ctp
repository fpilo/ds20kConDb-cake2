<?php
	// $this->Html->addCrumb('Item Types', '/item_types/index/');
	$this->Html->addCrumb($itemSubtype['ItemType']['name'], '/item_types/view/'.$itemSubtype['ItemType']['id']);
	// $this->Html->addCrumb('Item Subtypes', '/item_subtypes/index/');
	$this->Html->addCrumb($itemSubtype['ItemSubtype']['name'], '/item_subtypes/view/'.$itemSubtype['ItemSubtype']['id']);
?>
<script type="text/javascript">
			$(function(){
				restoreTabs('ItemSubtypesViewTabIndex');
			});
</script>


<div class="itemSubtypes view">
	<div id="tabs" class='related'>
		<ul>
			<li><a href="#overview"><?php echo __('Overview'); ?></a></li>
			<!-- <li><a href="#versions"><?php echo __('Versions'); ?></a></li> -->
			<li><a href="#files"><?php echo __('Files'); ?></a></li>
			<li id="statisticsList"><a href="#statistics"><?php echo __('Statistics'); ?></a></li>
		</ul>

		<div id="overview">
			<dl>
				<dt><?php echo __('Id'); ?></dt>
				<dd>
					<?php echo h($itemSubtype['ItemSubtype']['id']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Name'); ?></dt>
				<dd>
					<?php echo h($itemSubtype['ItemSubtype']['name']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Shortname'); ?></dt>
				<dd>
					<?php echo h($itemSubtype['ItemSubtype']['shortname']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Comment'); ?></dt>
				<dd>
					<?php echo h($itemSubtype['ItemSubtype']['comment']); ?>
					&nbsp;
					<?php echo $this->Html->image('ElegantBlueWeb/settings.png', array(
														'alt' => __('edit'),
														'border' => '0',
														'width'=>'16',
														'height'=>'16',
														'url' => array('controller' => 'item_subtypes', 'action' => 'changeComment', $itemSubtype['ItemSubtype']['id'])));
					?>
				</dd>
				<dt><?php echo __('Item Type'); ?></dt>
				<dd>
					<?php echo $this->Html->link($itemSubtype['ItemType']['name'], array('controller' => 'item_types', 'action' => 'view', $itemSubtype['ItemType']['id'])); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Count (total)'); ?></dt>
				<dd>
					<?php echo $count['total']; ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Count (available)'); ?></dt>
				<dd>
					<?php echo $count['available']; ?>
					&nbsp;
				</dd>
			</dl>
			<h3>Versions</h3>
			<?php if (!empty($itemSubtype['ItemSubtypeVersion'])):?>
			<table cellpadding = "0" cellspacing = "0">
			<tr>
				<th><?php echo __('Version'); ?></th>
				<th><?php echo __('Name'); ?></th>
				<th><?php echo __('Manufacturer Id'); ?></th>
				<th><?php echo __('Project'); ?></th>
				<th><?php echo __('Comment'); ?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
			<?php
				foreach ($itemSubtype['ItemSubtypeVersion'] as $itemSubtypeVersion): ?>
				<tr>
					<td><?php echo $this->Html->link($itemSubtypeVersion['version'], array('controller' => 'itemSubtypeVersions', 'action' => 'view', $itemSubtypeVersion['id'])); ?></td>
					<td><?php echo $this->Html->link($itemSubtypeVersion['name'], array('controller' => 'itemSubtypeVersions', 'action' => 'view', $itemSubtypeVersion['id'])); ?></td>
					<td><?php echo $itemSubtypeVersion['Manufacturer']['name'];?></td>
					<td>
						<?php foreach ($itemSubtypeVersion['Project'] as $project):?>
							<?php echo $project['name']; ?>
							&nbsp;
						<?php endforeach; ?>
					</td>
					<td><?php echo $itemSubtypeVersion['comment'];?></td>
					<td>
						<?php echo $this->Form->postLink(	$this->Html->image(
																			'delete.png',
																			array(
																				'title' => 'Delete',
																				'alt' => __('Delete file'),
																				'border' => '0',
																				'width'=>'24',
																				'height'=>'24')),
															array(
																'controller' => 'itemSubtypeVersions',
																'action' => 'delete',
																$itemSubtypeVersion['id']
															),
															array(
																'data' => array(
																	'controller' => 'itemSubtypes',
																	'action' => 'view',
																	'param' => $itemSubtype['ItemSubtype']['id']),
																'escape' => false),
															__('Are you sure you want to delete version %s?', $itemSubtypeVersion['version'])); ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</table>
		<?php else: ?>
			No versions found.
		<?php endif; ?>
		</div>

		<div id="files">
				<h3>Subtype files: <?php echo $itemSubtype['ItemSubtype']['name']; ?></h3>
				<table class="file">
					<tr>
						<th><?php echo __('Name'); ?></th>
						<th><?php echo __('Size'); ?></th>
						<th><?php echo __('Type'); ?></th>
						<th><?php echo __('Comment'); ?></th>
						<th><?php echo __('User'); ?></th>
						<th class="actions"><?php echo __('Actions');?></th>
					</tr>
					<?php if (!empty($itemSubtype['DbFile'])):?>
						<?php foreach ($itemSubtype['DbFile'] as $f): ?>
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
																				$itemSubtype['ItemSubtype']['id'],
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
															'url' => array('controller' => 'db_files','action' => 'add', 'ItemSubtype', $itemSubtype['ItemSubtype']['id']))); ?>
						</td>

					</tr>
				</table>

			<?php foreach($itemSubtype['ItemSubtypeVersion'] as $version): ?>
				<h3>Version files: <?php echo $itemSubtype['ItemSubtype']['name'].' v'.$version['version']; ?></h3>
				<table class="file">
					<tr>
						<th><?php echo __('Name'); ?></th>
						<th><?php echo __('Size'); ?></th>
						<th><?php echo __('Type'); ?></th>
						<th><?php echo __('Comment'); ?></th>
						<th><?php echo __('User'); ?></th>
						<th class="actions"><?php echo __('Actions');?></th>
					</tr>
				<?php if(!empty($version['DbFile'])): ?>
					<?php
						$i = 0;
						foreach ($version['DbFile'] as $f): ?>
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
																			$itemSubtype['ItemSubtype']['id'],
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
															'url' => array('controller' => 'db_files','action' => 'add', 'ItemSubtypeVersion', $version['id']))); ?>
						</td>

					</tr>
				</table>
			<?php endforeach; ?>
		</div>

		<div id="statistics">
			<?php if(!empty($overview)): ?>
				<div id="tabsStatistics" class='related'>
					<?php foreach($overview as $location_name => $location): ?>
						<h3><?php echo $location_name; ?></h3>
						<div id="<?php echo $location_name; ?>">
							<font size="+1"><?php echo $location_name; ?></font>
							<table border="0" style="width: 30%">
									<tr>
										<th>State</th>
										<th>Total</th>
										<th>Available</th>
									</tr>
									<?php foreach($location as $state_name => $state): ?>
									<tr>
									<td><?php echo $state_name; ?></td>
									<td><?php echo $state['total']; ?></td>
									<td><?php echo $state['available']; ?></td>
									</tr>
									<?php endforeach; ?>
							</table>
						</div>
					<?php endforeach; ?>
				</div>
			<?php else: ?>
				<br>
				No items found.
			<?php endif; ?>
		</div>
	</div>
</div>


<div id='verticalmenu'>
	<span><?php  echo __('Item Subtype: '); ?></span><br>
	<span><?php  echo __($itemSubtype['ItemSubtype']['name']);?></span>
	<ul>
		<li class='active'><?php echo $this->Html->link(__('Back'), array('action' => 'index')); ?> </li>
		<li class='active'><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $itemSubtype['ItemSubtype']['id'])); ?> </li>
		<li class='active'><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $itemSubtype['ItemSubtype']['id']), null, __('Are you sure you want to delete %s?', $itemSubtype['ItemSubtype']['name'])); ?> </li>
		<li class='active last'><?php echo $this->Html->link(__('Add new Version'), array('controller' => 'item_subtype_versions', 'action' => 'add', $itemSubtype['ItemSubtype']['id'])); ?> </li>
	</ul>
	<ul>
		<li class='active last'><?php echo $this->Html->link(__('Item Types'), array('controller' => 'item_types', 'action' => 'index')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
