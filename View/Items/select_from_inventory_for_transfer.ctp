<?php echo $this->My->toJavascript($itemSubtypeVersions); ?>
<?php echo $this->Html->script('searchfilter'); ?>
    
<?php
	$slide = 	'$("div.search").slideToggle("slow");
					if(sessionStorage.getItem("ItemSelectFromInventoryFilterVisability") == 1)
						sessionStorage.setItem("ItemSelectFromInventoryFilterVisability", 0);
					else
						sessionStorage.setItem("ItemSelectFromInventoryFilterVisability", 1);
						
					 event.preventDefault();';
?>
<script>
$(document).ready(function() {
  
	if(sessionStorage.getItem("ItemSelectFromInventoryFilterVisability") == 1)
		$("div.search").show();
});
</script>


<?php $this->Js->get('#search')->event('click', $slide); ?>

<div class="items index">
		<?php echo $this->Form->create('Item');?>
			<fieldset>
				<Legend>Available Items for Transfer:</legend>
				<?php 
					if(empty($filter['code'])){
						echo $this->Form->input('code', array(
									'div' => false,
									'label' => false,
									'placeholder' => 'Search item by code ...',
									'after' => '<div class="input-message" id="search">Extended search...</div>'));
					} else {
						echo $this->Form->input('code', array(
									'div' => false,
									'label' => false,
									'default' => $filter['code'],
									'after' => '<div class="input-message" id="search">Extended search...</div>'));
					}
				
					echo $this->Form->submit(__('Search'), array('div' => false)); 
				?>
							
							
				<div class="search" id="searchDIV" style="display: none">
				<table cellpadding="0" cellspacing="0" style="width: 100%">
				<tr>
					<td>					
					<table><tr>
					<td><?php echo $this->Form->input('project_id', array(
										'div' => false, 
										'size' => 18, 
										'multiple' => true, 
										//'options' => array(), 
										'empty' => '(Select all)',
										'onchange' => 'SubSelectOnChange(this)',
										//'default' => $filter['project_id'],
										'id' => 'project_id')); ?>
					</td>
					<td><?php echo $this->Form->input('manufacturer_id', array(
										'div' => false, 
										'size' => 18, 
										'multiple' => true, 
										//'options' => $manufacturers, 
										'empty' => '(Select all)',
										'onchange' => 'SubSelectOnChange(this)',
										//'default' => $filter['manufacturer_id'],
										'id' => 'manufacturer_id')); ?>
					</td>
					<td><?php echo $this->Form->input('item_type_id', array(
										'div' => false, '
										size' => 18, 
										'multiple' => true, 
										//'options' => $itemTypes, 
										'empty' => '(Select all)',
										'onchange' => 'SubSelectOnChange(this)',
										//'default' => $filter['item_type_id'],
										'id' => 'item_type_id')); ?>
					</td>
					<td><?php echo $this->Form->input('item_subtype_id', array(
										'div' => false,
										'size' => 18, 
										'multiple' => true, 
										//'options' => $itemSubtypes, 
										'empty' => '(Select all)',
										'onchange' => 'SubSelectOnChange(this)',
										//'default' => $filter['item_subtype_id'],
										'id' => 'item_subtype_id')); ?>
					</td>
					<td><?php echo $this->Form->input('item_subtype_version_id', array(
										'div' => false, 
										'size' => 18, 
										'multiple' => true, 
										'options' => array(), 
										'empty' => '(Select all)',
										'onchange' => 'SubSelectOnChange(this)',
										//'default' => $filter['item_subtype_version_id'],
										'id' => 'item_subtype_version_id')); ?>
						<?php echo $this->Html->link(__('Show Changelog'), array('controller' => 'itemSubtypes', 'action' => 'changelog'), array('id' => 'show_changelog', 'target' => '_blank')); ?>
					</td>
					<td><?php 
							if(empty($filter['location_id'])) $filter['location_id'] = '';
							echo $this->Form->input('location_id', array(
										'div' => false, 
										'size' => 18, 
										'multiple' => true,										 
										'options' => $locations,
										'onchange' => 'SelectAll(this)',
										'default' => $filter['location_id'],
										'id' => 'location_id',
										'empty' => '(Select all)')); ?>
					</td>
					<td><?php 
							if(empty($filter['state_id'])) $filter['state_id'] = '';
							echo $this->Form->input('state_id', array(
										'div' => false, 
										'size' => 18, 
										'multiple' => true, 
										'options' => $states,
										'onchange' => 'SelectAll(this)',
										'default' => $filter['state_id'],
										'id' => 'state_id',
										'empty' => '(Select all)')); ?>
					</td>
					</tr>
					<tr>
					<td>
						<?php 
							if(empty($filter['limit']))
								$filter['limit'] = 50;
								
							$limits = array(25 => '25', 50 => '50', 100 => '100', 200 => '200', 500 => '500');
							echo $this->Form->input('limit', array(
													'options' => $limits,
													'div' => false,
													'selected' => $filter['limit'],
													'label' => 'Results/page'));
						?>
					</td>
					</tr></table></td>
				</tr>
				</table>
			</div>
			</fieldset>
		<?php echo $this->Form->end(); ?>
	
	<table>
	<tr>
		<th>Items</th>	
		<th></th>
	</tr>
	<tr><td>
		
	<table cellpadding="0" cellspacing="0" id="tbl">
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
	
	</td>
	<td>
	
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th style="height:40px">Actions</th>
		</tr>
		<?php
		foreach ($items as $item): ?>
		<tr>
			<td style="height:30px">
					<?php echo $this->Form->create(__('Selection'));?>
					<?php echo $this->Form->hidden('Selection.Item.id', array('value' => $item['Item']['id'])); ?>
					<?php echo $this->Form->hidden('Selection.Item.code', array('value' => $item['Item']['code'])); ?>
					<?php echo $this->Form->hidden('Selection.ItemType.name', array('value' => $item['ItemType']['name'])); ?>
					<?php echo $this->Form->hidden('Selection.ItemSubtype.name', array('value' => $item['ItemSubtype']['name'])); ?>
					<?php echo $this->Form->hidden('Selection.ItemSubtypeVersion.version', array('value' => $item['ItemSubtypeVersion']['version'])); ?>
					<?php echo $this->Form->hidden('Selection.Location.name', array('value' => $item['Location']['name'])); ?>
					<?php echo $this->Form->hidden('Selection.Location.id', array('value' => $item['Location']['id'])); ?>
					<?php echo $this->Form->hidden('Selection.State.name', array('value' => $item['State']['name'])); ?>
					<?php echo $this->Form->hidden('Selection.Manufacturer.name', array('value' => $item['Manufacturer']['name'])); ?>
					<?php echo $this->Form->hidden('Selection.Project.name', array('value' => $item['Project']['name'])); ?>
					<?php echo $this->Js->submit('Select/Unselect', array(
								//'url'=> array('controller'=>'transfers', 'action'=>'addToCart'),
								'update'=>'#tbl', 
								'div' => false
							));
					?>
					<?php echo $this->Form->end();?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
	
	</td>
	</tr>
	
	<tr>
	<td colspan="2">
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
	<br>
	<div class="actions" style="width: 100%">
		<?php echo $this->Html->link('Save Selection',array('controller' => 'items', 'action' => 'addSelectFromInventoryToTransfer'));?>
	</div>
	</td>
	</tr>
	</table>
	
	
</div>
	

<div id='verticalmenu'>
	<h2><?php echo __('Transfer'); ?></h2>
	<ul>
		<li class='active'><?php echo $this->Html->link('Save Selection',array('controller' => 'items', 'action' => 'addSelectFromInventoryToTransfer'));?></li>
		<li class='active last'><?php echo $this->Html->link('Cancel',array('controller' => 'items', 'action' => 'cancelSelectFromInventoryForTransfer'));?></li>		
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>

