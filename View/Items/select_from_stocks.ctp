<h3>Select item:</h3>
<br />


	<table cellpadding="0" cellspacing="0" id="tbl" tabindex="1">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<!-- <th><?php echo $this->Paginator->sort('code');?></th> -->
			<th><?php echo $this->Paginator->sort('item_subtype_version_id');?></th>
			<th><?php echo $this->Paginator->sort('location_id');?></th>
			<th>Tags</th>
			<th><?php echo $this->Paginator->sort('item_quality_id');?></th>
			<th><?php echo $this->Paginator->sort('manufacturer');?></th>
			<th><?php echo $this->Paginator->sort('project_id');?></th>
			<th><?php echo $this->Paginator->sort('amount');?></th>
			<th>Actions</th>
	</tr>
	<?php
	foreach ($items as $item): ?>
	<tr class="items">
		<td id='id'><?php echo h($item['StockView']['item_id']); ?>&nbsp;</td>
		<!-- <td>
			<?php echo $this->Html->tableLink($item['StockView']['stock_item_code'], array('controller' => 'items', 'action' => 'view', $item['StockView']['item_id'])); ?>
		</td> -->
		<td>
			<?php
				$stVersionName = ($item['StockView']['item_subtype_version_name'] != "")? " (".$item['StockView']['item_subtype_version_name'].")":"";
				echo $this->Html->tableLink($item['StockView']['item_type_name'] .' '. $item['StockView']['item_subtype_name'] .' v'. $item['StockView']['version'].$stVersionName, array('controller' => 'item_subtype_versions', 'action' => 'view', $item['StockView']['item_subtype_version_id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->tableLink($item['StockView']['location_name'], array('controller' => 'locations', 'action' => 'view', $item['StockView']['location_id'])); ?>
		</td>
		<td>
			<?php
				if($item["StockView"]["item_tags_ids"] !== null){
					$itemTags = explode(",",$item["StockView"]["item_tags_ids"]);
					foreach($itemTags as $tag)
						echo $this->Html->tableLink($tags[$tag], array('controller' => 'item_tags', 'action' => 'view', $tag));
				}
			?>
		</td>
		<td>
			<?php echo $this->Html->tableLink($item['StockView']['item_quality_name'], array('controller' => 'item_qualities', 'action' => 'view', $item['StockView']['item_quality_id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->tableLink($item['StockView']['manufacturer_name'], array('controller' => 'manufacturers', 'action' => 'view', $item['StockView']['manufacturer_id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->tableLink($item['StockView']['project_name'], array('controller' => 'projects', 'action' => 'view', $item['StockView']['project_id'])); ?>
		</td>
		<td>
			<?php echo $item['StockView']['amount']; ?>
		</td>
		<td class="actions">
			<?php if($item["StockView"]["amount"] >0 ): ?>
				<input type='button' value='Select' onClick="selectItemForPosition(this,<?php echo $item["StockView"]["item_id"]; ?>,'<?php echo $position; ?>')" />
			<?php else: ?>
				Stock is 0, cannot attach!
			<?php endif; ?>
		</td>
	</tr>
	<?php endforeach; ?>
	</table>

	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging" id="Navigator">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>

