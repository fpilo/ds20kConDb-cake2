<script type="text/javascript">	
	function SelectAll(elem) {	
		if(elem.options[0].selected) {
			for (j=1; j<elem.options.length; j++) {
				elem.options[j].selected = true;
			}
			elem.options[0].selected = false;
		}			
    }	
</script>

<div class="items index">	
	<table cellpadding="0" cellspacing="0" id="tbl" tabindex="1">
	<tr>
			<th><?php echo $this->Paginator->sort('manufacturer' ,'Manufacturer'); ?></th>
			<th><?php echo $this->Paginator->sort('item_type_name' ,'Type'); ?></th>
			<th><?php echo $this->Paginator->sort('item_subtype_name' ,'Subtype'); ?></th>
			<th><?php echo $this->Paginator->sort('version', 'Version'); ?></th>
			<th>Projects</th>
			<th>Locations</th>
			<th><?php echo $this->Paginator->sort('amount'); ?></th>
			<th><?php echo $this->Paginator->sort('state_name', 'State'); ?></th>
			<th class="actions">Actions</th>
	</tr>
	<?php foreach ($stocks as $stock): ?>
	<tr>
		<td><?php echo $this->Html->link(__($stock['StockView']['manufacturer_name']), array('controller'=> 'manufacturers', 'action' => 'view', $stock['StockView']['manufacturer_id'])); ?></td>
		<td><?php echo $this->Html->link(__($stock['StockView']['item_type_name']), array('controller'=> 'item_types', 'action' => 'view', $stock['StockView']['item_type_id'])); ?></td>
		<td><?php echo $this->Html->link(__($stock['StockView']['item_subtype_name']), array('controller'=> 'item_subtypes', 'action' => 'view', $stock['StockView']['item_subtype_id'])); ?></td>
		<td><?php echo $this->Html->link(__($stock['StockView']['version']), array('action' => 'view', $stock['StockView']['item_subtype_version_id'])); ?></td>
		<td>
			<?php 
				foreach($stock['Project'] as $project) {
					echo $this->Html->link(__($project['name']), array('controller' => 'projects', 'action' => 'view', $project['id']))." ";
				}
			?>
		</td>
		<td>
			<?php 
				foreach($stock['Location'] as $location) {
					echo $this->Html->link(__($location['name']), array('controller' => 'locations', 'action' => 'view', $location['id']))." ";
				}
			?>
		</td>
		<td><?php echo h($stock['StockView']['amount']); ?>&nbsp;</td>
		<td><?php echo h($stock['StockView']['state_name']); ?>&nbsp;</td>
		
		<td class="actions">
			<?php echo $this->Form->create(__('Selection'), array('url' => array('controller' => 'items','action' => 'attach', $position, $item_id)));?>
			<?php echo $this->Form->hidden('id', array('value' => $stock['StockView']['id'])); ?>
			<?php echo $this->Form->hidden('model', array('value' => 'Stock')); ?>
			<?php echo $this->Form->end('Select');?>
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
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('Attach'); ?></h2>
	<ul>
		<li class='active'><?php echo $this->Html->link('Cancel',array('controller' => 'items', 'action' => 'view', $item_id));?></li>
	</ul>
	
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?></div>
</div>