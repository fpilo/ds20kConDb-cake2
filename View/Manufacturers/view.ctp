<script type="text/javascript">
			$(function(){
				restoreTabs('ManufacturerViewTabIndex');
			});
</script>

<div class="manufacturers view">
	<div id="tabs" class='related'>
		<ul>
			<li><a href="#informations"><?php echo __('Informations'); ?></a></li>
			<li><a href="#itemSubtypeVersions"><?php echo __('Item Subtype Versions'); ?></a></li>
		</ul>
		
		<div id="informations">
			<dl>
				<dt><?php echo __('Id'); ?></dt>
				<dd>
					<?php echo h($manufacturer['Manufacturer']['id']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Name'); ?></dt>
				<dd>
					<?php echo h($manufacturer['Manufacturer']['name']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Address'); ?></dt>
				<dd>
					<?php echo h($manufacturer['Manufacturer']['address']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Phone Number'); ?></dt>
				<dd>
					<?php echo h($manufacturer['Manufacturer']['phone_number']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Email'); ?></dt>
				<dd>
					<?php echo $this->Text->autoLinkEmails($manufacturer['Manufacturer']['email']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Contact'); ?></dt>
				<dd>
					<?php echo h($manufacturer['Manufacturer']['contact']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Projects'); ?></dt>
				<dd>
					<?php foreach($manufacturer['Project'] as $project): ?>
					<?php echo $this->Html->link($project['name'], array('controller' => 'projects', 'action' => 'view', $project['id'])); ?>&nbsp;
					<?php endforeach; ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Comment'); ?></dt>
				<dd>
					<?php echo h($manufacturer['Manufacturer']['comment']); ?>
					&nbsp;
				</dd>
			</dl>			
		</div>
		
		<div id="itemSubtypeVersions">
			<?php if (!empty($manufacturer['ItemSubtypeVersion'])):?>			
				<table cellpadding = "0" cellspacing = "0">
					<tr>
						<th><?php echo __('Type'); ?></th>
						<th><?php echo __('Subtype'); ?></th>
						<th><?php echo __('Version'); ?></th>
						<th><?php echo __('Projects'); ?></th>
					</tr>
					<?php
						$i = 0;
						foreach ($manufacturer['ItemSubtypeVersion'] as $itemSubtypeVersion): ?>
						<tr>
							<td><?php echo $this->Html->link($itemSubtypeVersion['ItemSubtype']['ItemType']['name'], array('controller' => 'itemTypes', 'action' => 'view', $itemSubtypeVersion['ItemSubtype']['ItemType']['id'])); ?></td>
							<td><?php echo $this->Html->link($itemSubtypeVersion['ItemSubtype']['name'], array('controller' => 'itemSubtypes', 'action' => 'view', $itemSubtypeVersion['ItemSubtype']['id'])); ?></td>
							<td><?php echo $this->Html->link($itemSubtypeVersion['version'], array('controller' => 'itemSubtypeVersions', 'action' => 'view', $itemSubtypeVersion['id'])); ?></td>
							<td>
							<?php foreach($itemSubtypeVersion['Project'] as $project): ?>
								<?php echo $this->Html->link($project['name'], array('controller' => 'projects', 'action' => 'view', $project['id'])); ?>
								&nbsp;
							<?php endforeach; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php else: ?>
				No Subtype Versions found.
			<?php endif; ?>
		</div>
	</div>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('Manufacturer: '.$manufacturer['Manufacturer']['name']);?></h2>
	<ul>
		<li class="active"><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $manufacturer['Manufacturer']['id'])); ?> </li>
		<li class="active last"><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $manufacturer['Manufacturer']['id']), null, __('Are you sure you want to delete %s?', $manufacturer['Manufacturer']['name'])); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>