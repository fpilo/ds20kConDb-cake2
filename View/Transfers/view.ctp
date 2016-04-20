<?php
	$this->Html->addCrumb('Transfers', '/transfers/index/');
	$this->Html->addCrumb('view', '/transfers/view/'.$transfer['Transfer']['id']);
?>


<div class="transfers view">
	<h1><?php echo __('Transfer Information');?></h1>
	<br>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($transfer['Transfer']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('From'); ?></dt>
		<dd>
			<?php echo $this->Html->link($transfer['From']['name'], array('controller' => 'locations', 'action' => 'view', $transfer['From']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('To'); ?></dt>
		<dd>
			<?php echo $this->Html->link($transfer['To']['name'], array('controller' => 'locations', 'action' => 'view', $transfer['To']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Recipient'); ?></dt>
		<dd>
			<?php echo $this->Html->link($transfer['Recipient']['first_name']." ".$transfer['Recipient']['last_name'], array('controller' => 'users', 'action' => 'view', $transfer['Recipient']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Deliverer'); ?></dt>
		<dd>
			<?php echo $this->Html->link($transfer['Deliverer']['name'], array('controller' => 'deliverers', 'action' => 'view', $transfer['Deliverer']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tracking Number'); ?></dt>
		<dd>
			<?php echo h($transfer['Transfer']['tracking_number']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Link'); ?></dt>
		<dd>
			<?php echo $this->Html->link($transfer['Transfer']['link'], $transfer['Transfer']['link'], array('external' => true)); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Shipping Date'); ?></dt>
		<dd>
			<?php echo h($transfer['Transfer']['shipping_date']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Resp. User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($transfer['User']['first_name']." ".$transfer['User']['last_name'], array('controller' => 'users', 'action' => 'view', $transfer['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('created'); ?></dt>
		<dd>
			<?php echo $transfer['Transfer']['created']; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('last modified'); ?></dt>
		<dd>
			<?php echo $transfer['Transfer']['modified']; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Comment'); ?></dt>
		<dd>
			<?php echo $transfer['Transfer']['comment']; ?>
			&nbsp;
		</dd>
		<dt>
			&nbsp;
		</dt>
		<dd>
			<?php echo $this->Html->link(__('Download Table'),array("controller"=>"Transfers","action"=>"generateCSV",$transfer["Transfer"]["id"]));?>
		</dd>
	</dl>

	<br>

	<div class="related">
		<h3><?php echo __('Related Items');?> </h3>
		<?php if (!empty($transfer['Transfer'])):?>
		<table cellpadding = "0" cellspacing = "0">
		<tr>
			<th><?php echo __('Id'); ?></th>
			<th><?php echo __('Code'); ?></th>
			<th><?php echo __('Amount'); ?></th>
			<th><?php echo __('Tags'); ?></th>
			<th><?php echo __('State'); ?></th>
			<th><?php echo __('Quality'); ?></th>
			<th><?php echo __('Type'); ?></th>
			<th><?php echo __('Subtype'); ?></th>
			<th><?php echo __('Version'); ?></th>
			<th><?php echo __('Current Location'); ?></th>
			<th><?php echo __('Project'); ?></th>
			<!--<th class="actions"><?php echo __('Actions');?></th>-->
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
				<?php if(count($item["ItemStocks"])>0): ?>
					<td class="<?php echo $class?>">&nbsp;</td>
					<td class="<?php echo $class?>"><?php echo $this->Html->link("Stock Item", array('controller' => 'items', 'action' => 'view', $item['id'])); ?></td>
				<?php else: ?>
					<td class="<?php echo $class?>"><?php echo $item['id'];?></td>
					<td class="<?php echo $class?>"><?php echo $this->Html->link($item['code'], array('controller' => 'items', 'action' => 'view', $item['id'])); ?></td>
				<?php endif; ?>
				<td class="<?php echo $class?>"><?php echo $item["ItemsTransfer"]["amount"];?></td>
				<?php foreach($item["ItemTags"] as $id=>$itemTag) $item["ItemTags"][$id] = $itemTag["ItemTag"]["name"]; ?>
				<td class="<?php echo $class?>"><?php echo implode(", ",$item['ItemTags']);?></td>
				<td class="<?php echo $class?>"><?php echo $item["State"]["name"];?></td>
				<td class="<?php echo $class?>"><?php echo $item["ItemQuality"]["name"];?></td>
				<td class="<?php echo $class?>"><?php echo $this->Html->link($item['ItemType']['name'], array('controller' => 'item_types', 'action' => 'view', $item['ItemType']['id'])); ?></td>
				<td class="<?php echo $class?>"><?php echo $this->Html->link($item['ItemSubtype']['name'], array('controller' => 'item_subtypes', 'action' => 'view', $item['ItemSubtype']['id'])); ?></td>
				<td class="<?php echo $class?>"><?php echo $this->Html->link($item['ItemSubtypeVersion']['version'], array('controller' => 'item_subtype_versions', 'action' => 'view', $item['ItemSubtypeVersion']['id'])); ?></td>
				<td class="<?php echo $class?>"><?php echo $this->Html->link($item['Location']['name'], array('controller' => 'locations', 'action' => 'view', $item['Location']['id'])); ?></td>
				<td class="<?php echo $class?>"><?php echo $this->Html->link($item['Project']['name'], array('controller' => 'projects', 'action' => 'view', $item['Project']['id'])); ?></td>
				<!-- <td class="<?php echo $class?>"><?php echo $this->Html->link($item['State']['name'], array('controller' => 'states', 'action' => 'view', $item['State']['id'])); ?></td> -->
				<!--<td class="actions <?php echo $class?>">
					<?php
						if($item['ItemsTransfer']['is_part_of'] == null): ?>
							<?php //echo $this->Html->link(__('Remove from Transfer'), array('controller' => 'transfers', 'action' => 'remove', $transfer['Transfer']['id'], $item['id'])); ?>
					<?php endif; ?>
			</td>-->
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>
	</div>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Transfer'); ?></h2>
	<ul>
		<?php
			if($transfer["To"]["id"] == $standardLocation["Location"]["id"] && $transfer["Transfer"]["status"] == 2)
				$receiveLink = $this->Form->postLink("Receive",array("controller"=>"transfers","action"=>"receive",$transfer["Transfer"]["id"]),array("class"=>"button","confirm"=>"This will move all the Items in this Transfer to the Location ".$transfer["To"]['name']." and mark this transfer as completed. "));
			else
				$receiveLink = "";
		?>
		<!-- <li class='active last'><?php echo $this->Html->link(__('Overview'), array('action' => 'index')); ?> </li> -->
		<!-- <li class='active last'><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $transfer['Transfer']['id'])); ?> </li> -->
		<!--<li><?php //echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Transfer.id')), null, __('Are you sure you want to delete this transfer?')); ?></li>-->
		<li class='active'><?php echo $receiveLink;?> </li>
	</ul>	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>