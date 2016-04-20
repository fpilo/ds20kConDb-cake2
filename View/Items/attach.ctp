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

<?php
	$slide = 	'$("div.search").slideToggle("slow");
					if(sessionStorage.getItem("ItemSelectFromInventoryFilterVisability") == 1)
						sessionStorage.setItem("ItemSelectFromInventoryFilterVisability", 0);
					else
						sessionStorage.setItem("ItemSelectFromInventoryFilterVisability", 1);

					 event.preventDefault();';
?>

<?php $this->Js->get('#search')->event('click', $slide); ?>

<div class="items index">

		<?php echo $this->Form->create('Item');?>
			<fieldset>
				<div style="display: inline">
				<?php
					if(empty($filter['code'])) {
						echo $this->Form->input('code', array(
								'div' => false,
								'label' => false,
								'placeholder' => 'Search items by code ...',
								'after' => '<div class="input-message" id="search">Extended search...</div>'));
					} else {
						echo $this->Form->input('code', array(
								'div' => false,
								'label' => false,
								'default' => $filter['code'],
								'after' => '<div class="input-message" id="search">Extended search...</div>'));
					}
				?>
				</div>

				<div class="search" id="searchDIV" style="display: none">
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td><?php
									if(empty($filter['state_id'])) {
										$filter['state_id'] = '';
									}

									echo $this->Form->input('state_id', array(
													'div' => false,
													'size' => 8,
													'multiple' => true,
													'options' => $states,
													'default' => $filter['state_id'],
													'onchange' => 'SelectAll(this)',
													'empty' => '(Select all)')); ?>
							</td>
						</tr>
						<tr>
							<td>
								<?php
									if(empty($filter['limit'])) {
										$filter['limit'] = 50;
									}

									$limits = array(25 => '25', 50 => '50', 100 => '100', 200 => '200', 500 => '500');
									echo $this->Form->input('limit', array(
															'options' => $limits,
															'div' => false,
															'selected' => $filter['limit'],
															'label' => 'Results/page'));
								?>
							</td>
						</tr>
					</table>
				</div>
				
				<?php echo $this->Form->submit(__('Search', true), array('div' => false)); ?>
			</fieldset>
		<?php echo $this->Form->end(); ?>

	<table cellpadding="0" cellspacing="0" id="tbl" tabindex="1">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('code');?></th>
			<th>Tags</th>
			<th><?php echo $this->Paginator->sort('state_id');?></th>
			<th><?php echo $this->Paginator->sort('ItemQuality.name', 'Item Quality');?></th>
			<th><?php echo $this->Paginator->sort('location_id');?></th>
			<th><?php echo $this->Paginator->sort('item_type_id');?></th>
			<th><?php echo $this->Paginator->sort('item_subtype_id');?></th>
			<th><?php echo $this->Paginator->sort('item_subtype_version_id');?></th>
			<th><?php echo $this->Paginator->sort('manufacturer');?></th>
			<th><?php echo $this->Paginator->sort('project_id');?></th>
			<th>Actions</th>
	</tr>
	<?php
	foreach ($items as $item):
		$tmpTags = array();
		foreach($item["ItemTag"] as $itemTag){
			$tmpTags[] = $this->Html->tableLink($itemTag["name"],array("controller"=>"item_tags","action"=>"view",$itemTag["id"]));
		}
		$tags = implode(", ", $tmpTags);
	?>

	<tr class="items">
		<td id='id'><?php echo h($item['Item']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->tableLink($item['Item']['code'], array('controller' => 'items', 'action' => 'view', $item['Item']['id'])); ?>
		</td>
		<td>
			<?php echo $tags; ?>
		</td>
		<td>
			<?php echo $this->Html->tableLink($item['State']['name'], array('controller' => 'states', 'action' => 'view', $item['State']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->tableLink($item['ItemQuality']['name'], array('controller' => 'states', 'action' => 'view', $item['ItemQuality']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->tableLink($item['Location']['name'], array('controller' => 'locations', 'action' => 'view', $item['Location']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->tableLink($item['ItemType']['name'], array('controller' => 'item_types', 'action' => 'view', $item['ItemType']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->tableLink($item['ItemSubtype']['name'], array('controller' => 'item_subtypes', 'action' => 'view', $item['ItemSubtype']['id'])); ?>
	        </td>
		<td>
			<?php echo $this->Html->tableLink($item['ItemSubtypeVersion']['version'], array('controller' => 'item_subtype_versions', 'action' => 'view', $item['ItemSubtypeVersion']['id'])); ?>		
		<td>
			<?php echo $this->Html->tableLink($item['Manufacturer']['name'], array('controller' => 'manufacturers', 'action' => 'view', $item['Manufacturer']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->tableLink($item['Project']['name'], array('controller' => 'projects', 'action' => 'view', $item['Project']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Form->create(__('Selection'), array('url' => array('controller' => 'items','action' => 'attach', $position, $item_id)));?>
			<?php echo $this->Form->hidden('id', array('value' => $item['Item']['id'])); ?>
			<?php echo $this->Form->hidden('model', array('value' => 'Item')); ?>
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
		<li class='active'><?php echo $this->Html->link('Cancel',array('action' => 'view', $item_id));?></li>
	</ul>

	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?></div>
</div>
