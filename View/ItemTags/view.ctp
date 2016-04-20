<div class="itemTags view">
<h2><?php echo __('Item Tag'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($itemTag['ItemTag']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo $itemTag['ItemTag']['name']; ?>
			&nbsp;
		</dd>
	</dl>
	<?php if (!empty($items)): ?>
	<h3><?php echo __('Related Items'); ?></h3>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Code'); ?></th>
		<th><?php echo __('Tags'); ?></th>
		<th><?php echo __('State'); ?></th>
		<th><?php echo __('Item Quality'); ?></th>
		<th><?php echo __('Location'); ?></th>
		<th><?php echo __('Item Type'); ?></th>
		<th><?php echo __('Item Subtype'); ?></th>
		<th><?php echo __('Item Subtype Version'); ?></th>
		<th><?php echo __('Manufacturer'); ?></th>
		<th><?php echo __('Project'); ?></th>
		<!-- <th><?php echo __('Comment'); ?></th> -->
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($items as $item):
			$tmpTags = array();
			foreach($item["ItemTag"] as $iTag){
				$tmpTags[] = $this->Html->tableLink($iTag["ItemTag"]["name"],array("controller"=>"item_tags","action"=>"view",$iTag["ItemTag"]["id"]));
			}
			$tags = implode(", ", $tmpTags);
		?>
		<tr class="items">
			<td><?php echo $this->Form->checkbox('itemId',array("value"=>$item['ItemView']['id'], "class"=>'itemId','hiddenField'=>false)); ?></td>
			<?php
				//If is stock write more data into the value field so the type, quality and tags are displayed as well on top.
				if(strpos($item["ItemView"]["code"], "Stock") === 0){
					$value = $item['ItemView']['code']." of ".$item['ItemView']['item_subtype_name']." v".$item['ItemView']['item_subtype_version']; //." ".implode(" ", $item["ItemTag"]);
				}else{
					$value = $item['ItemView']['code'];
				}

			?>
			<td class='itemCode' value='<?php echo $value; ?>'>
				<?php echo $this->Html->tableLink($item['ItemView']['code'], array('controller' => 'items', 'action' => 'view', $item['ItemView']['id'])); ?>
			</td>
			<td>
				<?php foreach($item["ItemTag"] as $stag): ?>
				<?php echo "<a href='".$this->Html->url(array('controller' => 'item_tags', 'action' => 'view', $stag["ItemTag"]['id']))."'>".$stag["ItemTag"]['name']."</a>"; ?>
				<?php //echo $this->Html->tableLink($tag["ItemTag"]['name'], array('controller' => 'item_tags', 'action' => 'view', $tag["ItemTag"]['id'])); ?>
				<?php endforeach; ?>
			</td>
			<td>
				<?php echo $this->Html->tableLink($item['ItemView']['state_name'], array('controller' => 'states', 'action' => 'view', $item['ItemView']['state_id'])); ?>
			</td>
			<td>
				<?php echo $this->Html->tableLink($item['ItemView']['item_quality_name'], array('controller' => 'item_qualities', 'action' => 'view', $item['ItemView']['item_quality_id'])); ?>
			</td>
			<td class='itemLocation' value='<?php echo $item['ItemView']['location_name']; ?>'>
				<?php echo $this->Html->tableLink($item['ItemView']['location_name'], array('controller' => 'locations', 'action' => 'view', $item['ItemView']['location_id'])); ?>
			</td>
			<td>
				<?php echo $this->Html->tableLink($item['ItemView']['item_type_name'], array('controller' => 'itemTypes', 'action' => 'view', $item['ItemView']['item_type_id'])); ?>
			</td>
			<td>
				<?php echo $this->Html->tableLink($item['ItemView']['item_subtype_name'], array('controller' => 'itemSubtypes', 'action' => 'view', $item['ItemView']['item_subtype_id'])); ?>
			</td>
			<td>
				<?php echo $this->Html->tableLink($item['ItemView']['item_subtype_version'], array('controller' => 'item_subtype_versions', 'action' => 'view', $item['ItemView']['item_subtype_version_id'])); ?>
			</td>
			<td>
				<?php echo $this->Html->tableLink($item['ItemView']['manufacturer_name'], array('controller' => 'manufacturers', 'action' => 'view', $item['ItemView']['manufacturer_id'])); ?>
			</td>
			<td>
				<?php echo $this->Html->tableLink($item['ItemView']['project_name'], array('controller' => 'projects', 'action' => 'view', $item['ItemView']['project_id'])); ?>
			</td>
			<td class="actions">
				<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $item['ItemView']['id'], $item['ItemView']['code']), null, __('Are you sure you want to delete "%s"?', $item['ItemView']['code'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
	<?php endif; ?>
	<?php if (!empty($stocks)): ?>
	<h3><?php echo __('Related Stocks'); ?></h3>
	<table cellpadding = "0" cellspacing = "0">
		<tr>
			<th><?php echo __('Id'); ?></th>
			<th><?php echo __('Tags'); ?></th>
			<th><?php echo __('State'); ?></th>
			<th><?php echo __('Item Quality'); ?></th>
			<th><?php echo __('Locations'); ?></th>
			<th><?php echo __('Item Type'); ?></th>
			<th><?php echo __('Item Subtype'); ?></th>
			<th><?php echo __('Item Subtype Version'); ?></th>
			<th><?php echo __('Manufacturer'); ?></th>
			<th><?php echo __('Projects'); ?></th>
			<th><?php echo __('Comment'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>
		<?php foreach ($stocks as $stock):
			$tmpTags = array();
			foreach($stock["StockTag"] as $stockTag){
				$tmpTags[] = $this->Html->tableLink($stockTag["name"],array("controller"=>"item_tags","action"=>"view",$stockTag["id"]));
			}
			$tags = implode(", ", $tmpTags);

			$tmpLocations = array();
			foreach($stock["Location"] as $location){
				$tmpLocations[] = $this->Html->tableLink($location["name"],array("controller"=>"locations","action"=>"view",$location["id"]));
			}
			$locations = implode(", ", $tmpLocations);

			$tmpProjects = array();
			foreach($stock["Project"] as $project){
				$tmpProjects[] = $this->Html->tableLink($project["name"],array("controller"=>"projects","action"=>"view",$project["id"]));
			}
			$projects = implode(", ", $tmpProjects);
		?>
		<tr>
			<td id='id'><?php echo h($stock['StockView']['id']); ?>&nbsp;</td>
			<td>
				<?php echo $tags; ?>
			</td>
			<td>
				<?php echo $this->Html->tableLink($stock['StockView']['state_name'], array('controller' => 'states', 'action' => 'view', $stock['StockView']['state_id'])); ?>
			</td>
			<td>
				<?php echo $this->Html->tableLink($stock['StockView']['stock_quality_name'], array('controller' => 'item_qualities', 'action' => 'view', $stock['StockView']['stock_quality_id'])); ?>
			</td>
			<td>
				<?php echo $locations; ?>
			</td>
			<td>
				<?php echo $this->Html->tableLink($stock['StockView']['item_type_name'], array('controller' => 'item_types', 'action' => 'view', $stock['StockView']['item_type_id'])); ?>
			</td>
			<td>
				<?php echo $this->Html->tableLink($stock['StockView']['item_subtype_name'], array('controller' => 'item_subtypes', 'action' => 'view', $stock['StockView']['item_subtype_id'])); ?>
			</td>
			<td>
				<?php echo $this->Html->tableLink($stock['StockView']['version'], array('controller' => 'item_subtype_versions', 'action' => 'view', $stock['StockView']['item_subtype_version_id'])); ?>
			</td>
			<td>
				<?php echo $this->Html->tableLink($stock['StockView']['manufacturer_name'], array('controller' => 'manufacturers', 'action' => 'view', $stock['StockView']['manufacturer_id'])); ?>
			</td>
			<td>
				<?php echo $projects; ?>
			</td>
			<td>
				<?php echo $stock['StockView']['comment']; ?>
			</td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'stocks', 'action' => 'view', $stock["StockView"]['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'stocks', 'action' => 'edit', $stock["StockView"]['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'stocks', 'action' => 'delete', $stock["StockView"]['id']), array(), __('Are you sure you want to delete # %s?', $stock["StockView"]['id'])); ?>
			</td>		</tr>

	<?php endforeach; ?>
	</table>
	<?php endif; ?>

</div>
<div id='verticalmenu'>
	<h2><?php echo __('Item Tag'); ?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Item Tag'), array('action' => 'edit', $itemTag['ItemTag']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Item Tag'),  array('action' => 'delete', $itemTag['ItemTag']['id']), array(), __('Are you sure you want to delete # %s?', $itemTag['ItemTag']['id'])); ?></li>
		<li><?php echo $this->Html->link(__('New Item Tag'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Item Tags'), array('action' => 'index')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>

