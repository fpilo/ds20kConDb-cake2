<div class="itemQualities view">
<h2><?php echo __('Item Quality'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($itemQuality['ItemQuality']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($itemQuality['ItemQuality']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Comment'); ?></dt>
		<dd>
			<?php echo h($itemQuality['ItemQuality']['comment']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div id='verticalmenu'>
	<h2><?php echo __('Item Quality'); ?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Item Quality'), array('action' => 'edit', $itemQuality['ItemQuality']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Item Quality'), array('action' => 'delete', $itemQuality['ItemQuality']['id']), array(), __('Are you sure you want to delete # %s?', $itemQuality['ItemQuality']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Item Qualities'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Item Quality'), array('action' => 'add')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
<div class="related view">
	<h3><?php echo __('Related Items'); ?></h3>
	<?php if (!empty($itemQuality['Item'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Code'); ?></th>
		<th><?php echo __('Item Subtype Version Id'); ?></th>
		<th><?php echo __('Location Id'); ?></th>
		<th><?php echo __('State Id'); ?></th>
		<th><?php echo __('Project Id'); ?></th>
		<th><?php echo __('Manufacturer Id'); ?></th>
		<th><?php echo __('Item Type Id'); ?></th>
		<th><?php echo __('Item Subtype Id'); ?></th>
		<th><?php echo __('Item Quality Id'); ?></th>
		<th><?php echo __('Comment'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($itemQuality['Item'] as $item): ?>
		<tr>
			<td><?php echo $item['id']; ?></td>
			<td><?php echo $item['code']; ?></td>
			<td><?php echo $item['item_subtype_version_id']; ?></td>
			<td><?php echo $item['location_id']; ?></td>
			<td><?php echo $item['state_id']; ?></td>
			<td><?php echo $item['project_id']; ?></td>
			<td><?php echo $item['manufacturer_id']; ?></td>
			<td><?php echo $item['item_type_id']; ?></td>
			<td><?php echo $item['item_subtype_id']; ?></td>
			<td><?php echo $item['item_quality_id']; ?></td>
			<td><?php echo $item['comment']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'items', 'action' => 'view', $item['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'items', 'action' => 'edit', $item['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'items', 'action' => 'delete', $item['id']), array(), __('Are you sure you want to delete # %s?', $item['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

</div>
