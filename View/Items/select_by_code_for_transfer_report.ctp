<div class="items form">	
		<div id="SelectedId">
			<?php if(!empty($found)): ?>
			<fieldset>			
			<legend>Found items</legend>
			<?php echo $this->Form->create(__('Selection'));?>
			<table>
				<tr>
					<th>Id</th>
					<th>Code</th>
					<th>Type</th>
					<th>Subtype</th>
					<th>Version</th>
					<th>Project</th>
					<th>Manufacturer</th>
					<th>Current Location</th>
				</tr>
				<?php foreach($found as $i => $item): ?>
				<tr>
					<td><?php echo $item['Item']['id'];?></td>
					<td><?php echo $item['Item']['code'];?></td>
					<td><?php echo $item['ItemType']['name']; ?></td>
					<td><?php echo $item['ItemSubtype']['name']; ?></td>
					<td><?php echo $item['ItemSubtypeVersion']['version']; ?></td>
					<td><?php echo $item['Project']['name']; ?></td>
					<td><?php echo $item['Manufacturer']['name']; ?></td>
					<td><?php echo $item['Location']['name']; ?></td>
					
					
					<?php echo $this->Form->hidden('Selection.'.$i.'.Item.id', array('value' => $item['Item']['id'])); ?>
					<?php echo $this->Form->hidden('Selection.'.$i.'.Item.code', array('value' => $item['Item']['code'])); ?>
					<?php echo $this->Form->hidden('Selection.'.$i.'.ItemType.name', array('value' => $item['ItemType']['name'])); ?>
					<?php echo $this->Form->hidden('Selection.'.$i.'.ItemSubtype.name', array('value' => $item['ItemSubtype']['name'])); ?>
					<?php echo $this->Form->hidden('Selection.'.$i.'.ItemSubtypeVersion.version', array('value' => $item['ItemSubtypeVersion']['version'])); ?>
					<?php echo $this->Form->hidden('Selection.'.$i.'.Location.name', array('value' => $item['Location']['name'])); ?>
					<?php echo $this->Form->hidden('Selection.'.$i.'.Location.id', array('value' => $item['Location']['id'])); ?>
					<?php echo $this->Form->hidden('Selection.'.$i.'.State.name', array('value' => $item['State']['name'])); ?>
					<?php echo $this->Form->hidden('Selection.'.$i.'.Manufacturer.name', array('value' => $item['Manufacturer']['name'])); ?>
					<?php echo $this->Form->hidden('Selection.'.$i.'.Project.name', array('value' => $item['Project']['name'])); ?>
				</tr>
				<?php endforeach; ?>								
			</table>
			<?php echo $this->Form->submit('Add to transfer', array('div' => false)); ?>
			<?php echo $this->Form->end();?>
			</fieldset>
			<?php endif; ?>
			
			<?php if(!empty($missing)): ?>
			<fieldset>
			<legend>
				Missing items
			</legend>
				<ul>
				<?php foreach($missing as $item): ?>				
					<li><b><?php echo $item;?></b></li>				
				<?php endforeach; ?>
				</ul>
			</fieldset>
			<?php endif; ?>
			
		</div>
</div>
	
<div id='verticalmenu'>
	<h2><?php echo __('Transfers'); ?></h2>
	<ul>
		<li class='active last'><?php echo $this->Html->link('<< Back',array('controller' => 'items', 'action' => 'selectByCodeForTransfer', $codes));?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>