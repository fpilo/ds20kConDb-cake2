<?php
	$this->Html->addCrumb('Transfers', '/transfers/index/');
	$this->Html->addCrumb('edit', '/transfers/edit/'.$transfer['Transfer']['id']);
?>

<div class="transfers form">
<?php echo $this->Form->create('Transfer');?>
	<fieldset>
		<legend><?php echo __('Edit Transfer'); ?></legend>

		<table border="0" cellspacing="0" cellpadding="0">
			<?php
				echo $this->Form->input('id', array('default' => $transfer['Transfer']['id']));
				echo $this->Html->tableCells(array(
					    array(array('Shipping Date', array('class' => 'first-column')), $this->Form->input('shipping_date', array(
					    							'div' => false,
					    							'label' => false,
													'dateFormat' => 'MDY',
													'type' => 'date',
													'style' => 'width: 20%',
													'default' => $transfer['Transfer']['shipping_date'],
													'separator' => ' - '))),
					    array(array('To', array('class' => 'first-column')), $this->Form->input('to_location_id', array(
					    							'options' => $to_locations,
					    							'div' => false,
					    							'label' => false,
													'default' => $transfer['To']['id'],
					    							'style' => 'width: 20%'))),
					    array(array('Deliverer', array('class' => 'first-column')), $this->Form->input('deliverer_id', array(
					    							'div' => false,
					    							'label' => false,
					    							'default' => $transfer['Deliverer']['id'],
					    							'style' => 'width: 20%'))),
					    array(array('Comment', array('class' => 'first-column')),
						$this->Form->input('comment', array(
					    							'div' => false,
					    							'label' => false,
					    							'default' => $transfer['Transfer']['comment'],
					    							'type' => 'textarea'))),
					    array(array('Tracking Number', array('class' => 'first-column')), $this->Form->input('tracking_number', array(
					    							'div' => false,
					    							'label' => false,
					    							'default' => $transfer['Transfer']['tracking_number']))),
					    array(array('Link', array('class' => 'first-column')), $this->Form->input('link', array(
					    							'div' => false,
					    							'label' => false,
					    							'default' => $transfer['Transfer']['link'])))
					));
			?>
		</table>

		<legend><?php echo __('Transfered Items'); ?></legend>
		<table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Code'); ?></th>
				<th><?php echo __('Type'); ?></th>
				<th><?php echo __('Subtype'); ?></th>
				<th><?php echo __('Version'); ?></th>
				<th><?php echo __('Location'); ?></th>
				<th><?php echo __('Project'); ?></th>
				<th><?php echo __('State'); ?></th>
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
					<td class="<?php echo $class?>"><?php echo $item['id'];?></td>
					<td class="<?php echo $class?>"><?php echo $this->Html->link($item['code'], array('controller' => 'items', 'action' => 'view', $item['id'])); ?></td>
					<td class="<?php echo $class?>"><?php echo $this->Html->link($item['ItemType']['name'], array('controller' => 'item_types', 'action' => 'view', $item['ItemType']['id'])); ?></td>
					<td class="<?php echo $class?>"><?php echo $this->Html->link($item['ItemSubtype']['name'], array('controller' => 'item_subtypes', 'action' => 'view', $item['ItemSubtype']['id'])); ?></td>
					<td class="<?php echo $class?>"><?php echo $this->Html->link($item['ItemSubtypeVersion']['version'], array('controller' => 'item_subtype_versions', 'action' => 'view', $item['ItemSubtypeVersion']['id'])); ?></td>
					<td class="<?php echo $class?>"><?php echo $this->Html->link($item['Location']['name'], array('controller' => 'locations', 'action' => 'view', $item['Location']['id'])); ?></td>
					<td class="<?php echo $class?>"><?php echo $this->Html->link($item['Project']['name'], array('controller' => 'projects', 'action' => 'view', $item['Project']['id'])); ?></td>
					<td class="<?php echo $class?>"><?php echo $this->Html->link($item['State']['name'], array('controller' => 'states', 'action' => 'view', $item['State']['id'])); ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Transfer'); ?></h2>
	<ul>
		<li class='active last'><?php echo $this->Html->link(__('Cancel'), array('action' => 'view', $transfer['Transfer']['id'])); ?> </li>
		<!--<li><?php //echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Transfer.id')), null, __('Are you sure you want to delete this transfer?')); ?></li>-->
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
