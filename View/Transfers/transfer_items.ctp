<?php if (!empty($transfer['Transfer'])):?>
<table cellpadding = "0" cellspacing = "0">
<tr>
	<th><?php echo __('Code'); ?></th>
	<th><?php echo __('Amount'); ?></th>
	<th><?php echo __('Tags'); ?></th>
	<th><?php echo __('State'); ?></th>
	<th><?php echo __('Quality'); ?></th>
	<th><?php echo __('Type'); ?></th>
	<th><?php echo __('Subtype'); ?></th>
	<th><?php echo __('Version'); ?></th>
	<th><?php echo __('Current Location'); ?></th>
	<th><?php echo __('Project'); ?></th>
	<!--<th class="actions"><?php echo __('Actions');?></th>-->
</tr>
<?php
	$i = 0;
	foreach ($transfer['Item'] as $item): ?>
	<tr>
		<?php
			if($item['ItemsTransfer']['is_part_of'] != null)
				$class = 'component';
			else
				$class = '';
		?>
		<?php if(count($item["ItemStocks"])>0): ?>
			<!-- <td class="<?php echo $class?>">&nbsp;</td> -->
			<td class="<?php echo $class?>"><?php echo $this->Html->link("Stock Item", array('controller' => 'items', 'action' => 'view', $item['id'])); ?></td>
		<?php else: ?>
			<!-- <td class="<?php echo $class?>"><?php echo $item['id'];?></td> -->
			<td class="<?php echo $class?>"><?php echo $this->Html->link($item['code'], array('controller' => 'items', 'action' => 'view', $item['id'])); ?></td>
		<?php endif; ?>
		<td class="<?php echo $class?>"><?php echo $item["ItemsTransfer"]["amount"];?></td>
		<?php foreach($item["ItemTags"] as $id=>$itemTag) $item["ItemTags"][$id] = $itemTag["ItemTag"]["name"]; ?>
		<td class="<?php echo $class?>"><?php echo implode(", ",$item['ItemTags']);?></td>
		<td class="<?php echo $class?>"><?php echo $item["State"]["name"];?></td>
		<td class="<?php echo $class?>"><?php echo $item["ItemQuality"]["name"];?></td>
		<td class="<?php echo $class?>"><?php echo $this->Html->link($item['ItemType']['name'], array('controller' => 'item_types', 'action' => 'view', $item['ItemType']['id'])); ?></td>
		<td class="<?php echo $class?>"><?php echo $this->Html->link($item['ItemSubtype']['name'], array('controller' => 'item_subtypes', 'action' => 'view', $item['ItemSubtype']['id'])); ?></td>
		<td class="<?php echo $class?>"><?php echo $this->Html->link($item['ItemSubtypeVersion']['version'], array('controller' => 'item_subtype_versions', 'action' => 'view', $item['ItemSubtypeVersion']['id'])); ?></td>
		<td class="<?php echo $class?>"><?php echo $this->Html->link($item['Location']['name'], array('controller' => 'locations', 'action' => 'view', $item['Location']['id'])); ?></td>
		<td class="<?php echo $class?>"><?php echo $this->Html->link($item['Project']['name'], array('controller' => 'projects', 'action' => 'view', $item['Project']['id'])); ?></td>
		<!-- <td class="<?php echo $class?>"><?php echo $this->Html->link($item['State']['name'], array('controller' => 'states', 'action' => 'view', $item['State']['id'])); ?></td> -->
		<!--<td class="actions <?php echo $class?>">
			<?php
				if($item['ItemsTransfer']['is_part_of'] == null): ?>
					<?php //echo $this->Html->link(__('Remove from Transfer'), array('controller' => 'transfers', 'action' => 'remove', $transfer['Transfer']['id'], $item['id'])); ?>
			<?php endif; ?>
	</td>-->
	</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>