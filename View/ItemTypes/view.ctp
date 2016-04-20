<?php
	// $this->Html->addCrumb('Item Types', '/item_types/index/');
	$this->Html->addCrumb($itemType['ItemType']['name'], '/item_types/view/'.$itemType['ItemType']['id']);
?>
<script type="text/javascript">
			$(function(){
				restoreTabs('itemTypesTabIndex');
			});
</script>

<div class="itemTypes view">
		<div id="tabs" class='related'>
		<ul>
			<li><a href="#information"><?php echo __('Information'); ?></a></li>
			<li><a href="#itemSubtypes"><?php echo __('Item Subtypes'); ?></a></li>
		</ul>

		<div id="information">
			<dl>
				<dt><?php echo __('Id'); ?></dt>
				<dd>
					<?php echo h($itemType['ItemType']['id']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Name'); ?></dt>
				<dd>
					<?php echo h($itemType['ItemType']['name']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Projects'); ?></dt>
				<dd>
					<?php foreach ($itemType["Project"] as $project): ?>
						<?php echo $this->Html->link(__($project['name']), array('controller'=>'projects','action' => 'view', $project['id'])); ?>&nbsp;
					<?php endforeach; ?>
					&nbsp;
				</dd>
			</dl>
		</div>

		<div id="itemSubtypes">
			TODO: Show the corresponding project to each Subtype
			<?php if (!empty($itemType['ItemSubtype'])):?>
				<table cellpadding = "0" cellspacing = "0">
				<tr>
					<th><?php echo __('Id'); ?></th>
					<th><?php echo __('Name'); ?></th>
					<th><?php echo __('Item Type Id'); ?></th>
				</tr>
				<?php
					$i = 0;
					foreach ($itemType['ItemSubtype'] as $itemSubtype): ?>
					<tr>
						<td><?php echo $itemSubtype['id'];?></td>
						<td><?php echo $this->Html->link($itemSubtype['name'], array('controller' => 'item_subtypes', 'action' => 'view', $itemSubtype['id'])); ?></td>
						<td><?php echo $itemSubtype['item_type_id'];?></td>
					</tr>
				<?php endforeach; ?>
				</table>
			<?php endif; ?>
		</div>
	</div>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('Item Type: '.$itemType['ItemType']['name']);?></h2>
	<ul>
		<li class='active'><?php echo $this->Html->link(__('New'), array('action' => 'add')); ?> </li>
		<li class='active'><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $itemType['ItemType']['id'])); ?> </li>
		<li class='active last'><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $itemType['ItemType']['id']), null, __('Are you sure you want to delete # %s?', $itemType['ItemType']['id'])); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
