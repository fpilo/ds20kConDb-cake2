<script type="text/javascript">

			$(function(){
				// Accordion
				$("#accordion").accordion({ header: "h3" });
		});
</script>

<div class="items form">
	<h2 class="demoHeaders">Create</h2>
		<div id="accordion">
			<div>
				<h3><a href="#">New Item</a></h3>
				<div>
				<?php echo $this->Form->create('ItemType');?>
					<fieldset>
						<legend><?php echo __('Select Item Type:'); ?></legend>
						<table cellpadding="0" cellspacing="0">
								<tr>
									<td><?php echo $this->Form->input('item_type_id', array('multiple' => false, 'options' => $itemTypes, 'size' => 8)); ?></td>
									</td>
								</tr>
						</table>
					</fieldset>
				<?php echo $this->Form->end(__('Submit'));?>
				</div>
			</div>
			<div>
				<h3><a href="#">New Wafer</a></h3>
				<div>
				<?php echo $this->Form->create('WaferType');?>
					<fieldset>
						<legend><?php echo __('Select Item Type:'); ?></legend>
						<table cellpadding="0" cellspacing="0">
								<tr>
									<td><?php echo $this->Form->input('item_type_id', array('multiple' => false, 'options' => $waferTypes, 'size' => 8)); ?></td>
									</td>
								</tr>
						</table>
					</fieldset>
				<?php echo $this->Form->end(__('Submit'));?>
				</div>
			</div>
		</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List Items'), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('Upload Items'), array('action' => 'upload')); ?> </li>
		<li><?php echo $this->Html->link(__('List Item Types'), array('controller' => 'item_types', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Item Type'), array('controller' => 'item_types', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Locations'), array('controller' => 'locations', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Location'), array('controller' => 'locations', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List States'), array('controller' => 'states', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New State'), array('controller' => 'states', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Manufacturers'), array('controller' => 'manufacturers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Manufacturer'), array('controller' => 'manufacturers', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Projects'), array('controller' => 'projects', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Project'), array('controller' => 'projects', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Items'), array('controller' => 'items', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Parent'), array('controller' => 'items', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Histories'), array('controller' => 'histories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New History'), array('controller' => 'histories', 'action' => 'add')); ?> </li>
	</ul>
</div>