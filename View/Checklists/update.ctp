<?php
	$this->Html->addCrumb($item['Item']['code'], '/items/view/'.$item['Item']['id']);
	$link = '/checklists/update/'.$item['Item']['id']; if(isset($checklist['Checklist']['id'])) $link .='/'.$checklist['Checklist']['id'];
	$this->Html->addCrumb('Modify checklist', $link);
?>

<div class="checklist update">				
		<table cellpadding="0" cellspacing="0">
		<tr>
				<th>id</th>
				<th>name</th>
				<th>description</th>
				<th>template</th>
				<th class="actions"><?php echo __('Actions');?></th>
		</tr>
		<?php if (!empty($checklist)):?>
		<tr>
			<td><?php echo h($checklist['Checklist']['id']); ?>&nbsp;</td>
			<td><?php echo $this->Html->link(__($checklist['Checklist']['name']), array('action' => 'view', $checklist['Checklist']['id'])); ?>&nbsp;</td>
			<td><?php echo h($checklist['Checklist']['description']); ?>&nbsp;</td>
			<td><?php echo $this->Html->link(__($checklist['ClTemplate']['name']), array('controller' => 'clTemplates', 'action' => 'view', $checklist['ClTemplate']['id'])); ?>&nbsp;</td>
			<td class="actions">
				<?php $refStr = 'update_'.$item['Item']['id']; echo $this->Html->link(__('Edit'), array('action' => 'edit', $checklist['Checklist']['id'], $refStr)); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $checklist['Checklist']['id'], $checklist['Checklist']['item_id']), null, __('Are you sure you wish to delete checklist # %s?', $checklist['Checklist']['id'])); ?>
			</td>
		</tr>
		<?php else: ?>
			<td>No checklist found.</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		<?php endif; ?>
		</table>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('checklists');?></h2>
	<ul>
		<li class="active"><?php echo $this->Html->link(__('Cancel'), array('controller' => 'Items', 'action' => 'view', $item['Item']['id'])); ?></li>
		<li class="active"><?php if(!isset($checklist['Checklist']['id'])) echo $this->Html->link(__('Add'), array('action' => 'add', $item['Item']['id'])); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>