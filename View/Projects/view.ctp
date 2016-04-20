<script type="text/javascript">
			$(function(){
				restoreTabs('projectsTabIndex');
			});
</script>

<div class="projects view">
	
	<div id="tabs" class='related'>
		<ul>
			<li><a href="#informations"><?php echo __('Informations'); ?></a></li>
			<li><a href="#manufacturers"><?php echo __('Manufacturers'); ?></a></li>
			<li><a href="#files"><?php echo __('Files');?></a></li>
			<li><a href="#users"><?php echo __('Users'); ?></a></li>
		</ul>
		
		<div id="informations">
			<dl>
				<dt><?php echo __('Id'); ?></dt>
				<dd>
					<?php echo h($project['Project']['id']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Name'); ?></dt>
				<dd>
					<?php echo h($project['Project']['name']); ?>
					&nbsp;
				</dd>
			</dl>
		</div>
		
		<div id="manufacturers">
			<?php if (!empty($project['Manufacturer'])):?>
			<table cellpadding = "0" cellspacing = "0">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Name'); ?></th>
				<th><?php echo __('Address'); ?></th>
				<th><?php echo __('Phone Number'); ?></th>
				<th><?php echo __('Email'); ?></th>
				<th><?php echo __('Contact'); ?></th>
			</tr>
			<?php
				foreach ($project['Manufacturer'] as $manufacturer): ?>
				<tr>
					<td><?php echo $manufacturer['id'];?></td>
					<td><?php echo $this->Html->link($manufacturer['name'], array('controller' => 'manufacturers', 'action' => 'view', $manufacturer['id']));?></td>
					<td><?php echo $manufacturer['address'];?></td>
					<td><?php echo $manufacturer['phone_number'];?></td>
					<td><?php echo $this->Text->autoLinkEmails($manufacturer['email']); ?></td>
					<td><?php echo $manufacturer['contact'];?></td>
				</tr>
				<?php endforeach; ?>
				</table>
			<?php endif; ?>
		</div>
		
		<div id="files">
			<table class="file">
				<tr>
					<th><?php echo __('Name'); ?></th>
					<th><?php echo __('Size'); ?></th>
					<th><?php echo __('Type'); ?></th>
					<th><?php echo __('Comment'); ?></th>
					<th><?php echo __('User'); ?></th>
					<th class="actions"><?php echo __('Actions');?></th>
				</tr>
				<?php if (!empty($project['DbFile'])):?>
				<?php foreach ($project['DbFile'] as $f): ?>
				<tr>
					<td>
						<?php echo $this->Html->tableLink($f['name'], array('controller' => 'db_files', 'action' => 'view', $f['id'], 'Project', $project['Project']['id'])); ?>
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
																				'width'=> '16',
																				'height'=> '16',
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
																	$project['Project']['id'],
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
														'url' => array('controller' => 'db_files','action' => 'add', 'Project', $project['Project']['id']))); ?>	
					</td>						
				</tr>
			</table>
		</div>
		
		<div id="users">
			<?php if (!empty($project['User'])):?>
			<table cellpadding = "0" cellspacing = "0">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Username'); ?></th>
				<th><?php echo __('First name'); ?></th>
				<th><?php echo __('Last name'); ?></th>
				<th><?php echo __('Email'); ?></th>
				<th><?php echo __('Group'); ?></th>
			</tr>
			<?php
				foreach ($project['User'] as $user): ?>
				<tr>
					<td><?php echo $user['id'];?></td>
					<td><?php echo $this->Html->link($user['username'], array('controller' => 'users', 'action' => 'view', $user['id']));?></td>
					<td><?php echo $user['first_name'];?></td>
					<td><?php echo $user['last_name'];?></td>
					<td><?php echo $this->Text->autoLinkEmails($user['email']); ?></td>
					<td><?php echo $this->Html->link($user['Group']['name'], array('controller' => 'groups', 'action' => 'view', $user['Group']['id']));?></td>
				</tr>
				<?php endforeach; ?>
				</table>
			<?php endif; ?>
		</div>
	</div>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('Projects');?></h2>
	<ul>
		<li class="active"><?php echo $this->Html->link(__('New'), array('action' => 'add')); ?></li>
		<li class="active"><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $project['Project']['id'])); ?> </li>
		<li class="active last"><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $project['Project']['id']), null, __('Are you sure you want to delete # %s?', $project['Project']['id'])); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>