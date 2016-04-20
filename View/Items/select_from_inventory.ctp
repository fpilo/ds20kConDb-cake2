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
<script>
$(document).ready(function() {

	if(sessionStorage.getItem("ItemSelectFromInventoryFilterVisability") == 1)
		$("div.search").show();

	$("#search").click(function(){
		<?php echo $slide; ?>
	});
	$("#tbl tr td.id").each(function(){
		if($.inArray($(this).html()*1, selectedItems) != -1){
			$(this).parent().attr("style","opacity:0.5");
			$(this).parent().find(".actions input").attr("disabled", true);
		}
	})

	$("form.itemSearch").submit(function(){
		console.log($(this).attr("action"));
		$(this).parent().load($(this).attr("action"),$(this).serialize(),function(){
			$(this).prepend('<div style="float: right;"><?php echo $this->Html->image("ElegantBlueWeb/xmark.png",array("alt"=>"X","width"=>"20","onClick"=>"closeSelectorOverlay();","style"=>"cursor:pointer; z-index:5;")); ?></div>');
		});
	});
});
</script>


<?php echo $this->Form->create('Item', array('type' => 'get',"default"=>false,'class'=>"itemSearch"));?>
	<fieldset>
		<legend>Select item:</legend>
		<div style="display: inline">
		<?php
			if(empty($filter['code'])) {
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

		?>


		<div class="search" id="searchDIV" style="display: none">
			<table cellpadding="0" cellspacing="0">
			<tr>
				<td><?php
						if(empty($filter['item_quality_id'])) $filter['item_quality_id'] = '';
						echo $this->Form->input('item_quality_id', array(
										'div' => false,
										'size' => 8,
										'multiple' => true,
										'options' => $item_qualities,
										'default' => $filter['item_quality_id'],
										'onchange' => 'SelectAll(this)',
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
			</tr>
			</table>
		</div>
		<?php echo $this->Form->submit(__('Search'), array('div' => false)); ?>
	</fieldset>
<?php
	echo $this->Form->end();
?>

<table cellpadding="0" cellspacing="0" id="tbl" tabindex="1">
<tr>
		<th><?php echo $this->Paginator->sort('id');?></th>
		<th><?php echo $this->Paginator->sort('code');?></th>
		<th><?php echo $this->Paginator->sort('item_subtype_version_id');?></th>
		<th><?php echo $this->Paginator->sort('location_id');?></th>
		<th>Tags</th>
		<th><?php echo $this->Paginator->sort('state_id');?></th>
		<th><?php echo $this->Paginator->sort('item_quality_id');?></th>
		<th><?php echo $this->Paginator->sort('manufacturer');?></th>
		<th><?php echo $this->Paginator->sort('project_id');?></th>
		<th>Actions</th>
</tr>
<?php
foreach ($items as $item): ?>
<tr class="items">
	<td class='id'><?php echo h($item['ItemView']['id']); ?></td>
	<td>
		<?php echo $this->Html->tableLink($item['ItemView']['code'], array('controller' => 'items', 'action' => 'view', $item['ItemView']['id'])); ?>
	</td>
	<td>
		<?php echo $this->Html->tableLink($item['ItemView']['item_type_name'], array('controller' => 'itemTypes', 'action' => 'view', $item['ItemView']['item_type_id'])); ?>
	</td>
	<td>
		<?php echo $this->Html->tableLink($item['ItemView']['location_name'], array('controller' => 'locations', 'action' => 'view', $item['ItemView']['location_id'])); ?>
	</td>
	<td>
		<?php
			foreach($item["ItemTag"] as $tag)
				echo $this->Html->tableLink($tag["ItemTag"]['name'], array('controller' => 'item_tags', 'action' => 'view', $tag["ItemTag"]['id']));
		?>
	</td>
	<td>
		<?php echo $this->Html->tableLink($item['ItemView']['state_name'], array('controller' => 'states', 'action' => 'view', $item['ItemView']['state_id'])); ?>
	</td>
	<td>
		<?php echo $this->Html->tableLink($item['ItemView']['item_quality_name'], array('controller' => 'item_qualities', 'action' => 'view', $item['ItemView']['item_quality_id'])); ?>
	</td>
	<td>
		<?php echo $this->Html->tableLink($item['ItemView']['manufacturer_name'], array('controller' => 'manufacturers', 'action' => 'view', $item['ItemView']['manufacturer_id'])); ?>
	</td>
	<td>
		<?php echo $this->Html->tableLink($item['ItemView']['project_name'], array('controller' => 'projects', 'action' => 'view', $item['ItemView']['project_id'])); ?>
	</td>
	<td class="actions">
		<input type='button' value='Select' onClick="selectItemForPosition(this,<?php echo $item["ItemView"]["id"]; ?>,'<?php echo $position; ?>')" />
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

