<div class="items index">
	<h1>Select stock:</h1>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th>Tags</th>
			<th><?php echo $this->Paginator->sort('state_name', 'State'); ?></th>
			<th><?php echo $this->Paginator->sort('stock_quality_name' ,'Stock Quality'); ?></th>
			<th>Locations</th>
			<th><?php echo $this->Paginator->sort('item_type_name' ,'Type'); ?></th>
			<th><?php echo $this->Paginator->sort('item_subtype_name' ,'Subtype'); ?></th>
			<th><?php echo $this->Paginator->sort('version', 'Version'); ?></th>
			<th><?php echo $this->Paginator->sort('manufacturer' ,'Manufacturer'); ?></th>
			<th>Projects</th>
			<th><?php echo $this->Paginator->sort('amount'); ?></th>
			<th><?php echo $this->Paginator->sort('parent_item', 'Parent Item'); ?></th>
			<th class="actions">Actions</th>
	</tr>
	<?php foreach ($stocks as $stock): ?>
	<tr>
		<td>
			<?php
				foreach($stock['StockTag'] as $tag) {
					echo $this->Html->link(__($tag['name']), array('controller' => 'item_tags', 'action' => 'view', $tag['id']))." ";
				}
			?>
		</td>
		<td><?php
                  if(!empty($stock['StockView']['state_name'])) {
                      echo $this->Html->link(__($stock['StockView']['state_name']), array('controller' => 'states', 'action' => 'view', $stock['StockView']['state_id']))." ";
                  } else {
                      echo "-";
                  }
            ?>&nbsp;
        </td>
		<td><?php echo $this->Html->link(__($stock['StockView']['stock_quality_name']), array('controller'=> 'manufacturers', 'action' => 'view', $stock['StockView']['manufacturer_id'])); ?></td>
		<td>
			<?php
				foreach($stock['Location'] as $location) {
					echo $this->Html->link(__($location['name']), array('controller' => 'locations', 'action' => 'view', $location['id']))." ";
				}
			?>
		</td>
		<td><?php echo $this->Html->link(__($stock['StockView']['item_type_name']), array('controller'=> 'item_types', 'action' => 'view', $stock['StockView']['item_type_id'])); ?></td>
		<td><?php echo $this->Html->link(__($stock['StockView']['item_subtype_name']), array('controller'=> 'item_subtypes', 'action' => 'view', $stock['StockView']['item_subtype_id'])); ?></td>
		<td><?php echo $this->Html->link(__($stock['StockView']['version']), array('controller'=>'item_subtype_versions','action' => 'view', $stock['StockView']['item_subtype_version_id'])); ?></td>
		<td><?php echo $this->Html->link(__($stock['StockView']['manufacturer_name']), array('controller'=> 'manufacturers', 'action' => 'view', $stock['StockView']['manufacturer_id'])); ?></td>
		<td>
			<?php
				foreach($stock['Project'] as $project) {
					echo $this->Html->link(__($project['name']), array('controller' => 'projects', 'action' => 'view', $project['id']))." ";
				}
			?>
		</td>
		<td><?php echo h($stock['StockView']['amount']); ?>&nbsp;</td>
		<td><?php
		          if(!empty($stock['StockView']['parent_item_code'])) {
		              echo $this->Html->link(__($stock['StockView']['parent_item_code']), array('controller' => 'items', 'action' => 'view', $stock['StockView']['parent_item_id']))." ";
		          } else {
		              echo "-";
		          }
            ?>&nbsp;
        </td>
		<td class="actions">
			<?php echo $this->Form->create(__('Selection'));?>
			<?php echo $this->Form->hidden('id', array('value' => $stock['StockView']['id'])); ?>
			<?php echo $this->Form->submit(__('Select'), array('div' => false)); ?>
			<?php echo $this->Form->end();?>
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
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Assemble'); ?></h2>
	<ul>
		<li class='active last'><?php echo $this->Html->link('<< Back',array('controller' => 'items', 'action' => 'assembleItemComposition'));?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>


