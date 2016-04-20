<div class="items form">
<?php echo $this->Form->create('Item');?>
	<fieldset>
		<legend><?php echo __('Delete all Components of this item?'); ?></legend>
		This means all currently attached items as well as all previously attached items.
		<table cellpadding="0" cellspacing="0" id="tbl">
		<tr>
				<th>Id</th>
				<th>Currently attached</th>
				<th>Code</th>
				<th>Type</th>
				<th>Subtype</th>
				<th>Version</th>
				<th>Location</th>
				<th>State</th>
				<th>Manufacturer</th>
				<th>Project</th>
		</tr>
		<?php
		foreach ($items as $key => $item): ?>
		<tr class="items">
			<td id='id'><?php echo h($item['id']); ?>&nbsp;</td>
			<td>
				<?php	if($item['ItemComposition']['valid'] == 1)
							echo 'X';
						else
							echo '-';
					?>					 
			</td>
			<td>
				<?php echo $this->Html->tableLink($item['code'], array('controller' => 'items', 'action' => 'view', $item['id'])); ?>
				<?php echo $this->Form->hidden($key.'.id', array('value' => $item['id'])); ?>
			</td>
			<td>
				<?php echo $this->Html->tableLink($item['ItemType']['name'], array('controller' => 'itemTypes', 'action' => 'view', $item['ItemType']['id'])); ?>
			</td>
			<td>
				<?php echo $this->Html->tableLink($item['ItemSubtype']['name'], array('controller' => 'itemSubtypes', 'action' => 'view', $item['ItemSubtype']['id'])); ?>
			</td>
			<td>
				<?php echo $this->Html->tableLink($item['ItemSubtypeVersion']['version'], array('controller' => 'item_subtype_versions', 'action' => 'view', $item['ItemSubtypeVersion']['id'])); ?>
			</td>
			<td>
				<?php echo $this->Html->tableLink($item['Location']['name'], array('controller' => 'locations', 'action' => 'view', $item['Location']['id'])); ?>
			</td>
			<td>
				<?php echo $this->Html->tableLink($item['State']['name'], array('controller' => 'states', 'action' => 'view', $item['State']['id'])); ?>
			</td>
			<td>
				<?php echo $this->Html->tableLink($item['Manufacturer']['name'], array('controller' => 'manufacturers', 'action' => 'view', $item['Manufacturer']['id'])); ?>
			</td>
			<td>
				<?php echo $this->Html->tableLink($item['Project']['name'], array('controller' => 'projects', 'action' => 'view', $item['Project']['id'])); ?>
			</td>
		</tr>
		<?php endforeach; ?>
		</table>
	</fieldset>
<?php echo $this->Form->end(__('Delete All'));?>
</div>
