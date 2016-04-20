<?php //echo $data?>

<table cellpadding="0" cellspacing="0" id="tbl" tabindex="1">
	<tr>
			<th style="height:40px"><?php echo $this->Paginator->sort('id');?></th>
			<th style="height:40px"><?php echo $this->Paginator->sort('code');?></th>
			<th style="height:40px"><?php echo $this->Paginator->sort('item_subtype_version_id');?></th>
			<th style="height:40px"><?php echo $this->Paginator->sort('location_id');?></th>
			<th style="height:40px"><?php echo $this->Paginator->sort('state_id');?></th>
			<th style="height:40px"><?php echo $this->Paginator->sort('manufacturer');?></th>
			<th style="height:40px"><?php echo $this->Paginator->sort('project_id');?></th>
	</tr>
	<?php
	foreach ($items as $item): ?>
		<?php $selected = false; ?>	
		<?php 
			if(!empty($transfer['Selection']))
			foreach($transfer['Selection'] as $selectedItem) {
				if($selectedItem['Item']['id'] == $item['Item']['id'])
					$selected = true;
			}
		?>
		
		<?php if($selected): ?>
		<tr class="items" style="background: lightgreen">
		<?php else: ?>
		<tr class="items">
		<?php endif; ?>
			
		<td id='id' style="height:30px"><?php echo h($item['Item']['id']); ?>&nbsp;</td>
		<td style="height:30px">
			<?php echo $this->Html->tableLink($item['Item']['code'], array('controller' => 'items', 'action' => 'view', $item['Item']['id'])); ?>
		</td>
		<td style="height:30px">
			<?php echo $this->Html->tableLink($item['ItemType']['name'] .' '. $item['ItemSubtype']['name'] .' v'. $item['ItemSubtypeVersion']['version'], array('controller' => 'item_subtype_versions', 'action' => 'view', $item['ItemSubtypeVersion']['id'])); ?>
		</td>
		<td style="height:30px">
			<?php echo $this->Html->tableLink($item['Location']['name'], array('controller' => 'locations', 'action' => 'view', $item['Location']['id'])); ?>
		</td>
		<td style="height:30px">
			<?php echo $this->Html->tableLink($item['State']['name'], array('controller' => 'states', 'action' => 'view', $item['State']['id'])); ?>
		</td>
		<td style="height:30px">
			<?php echo $this->Html->tableLink($item['Manufacturer']['name'], array('controller' => 'manufacturers', 'action' => 'view', $item['Manufacturer']['id'])); ?>
		</td>
		<td style="height:30px">
			<?php echo $this->Html->tableLink($item['Project']['name'], array('controller' => 'projects', 'action' => 'view', $item['Project']['id'])); ?>
		</td>
	</tr>
	<?php endforeach; ?>
	</table>