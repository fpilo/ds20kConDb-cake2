<script type="text/javascript">
	$(function(){
		restoreTabs('previousTabIndexcheckListTemplateView');
	});
</script>

<?php
	$this->Html->addCrumb($checklist['Item']['code'], '/items/view/'.$checklist['Item']['id']);
	$this->Html->addCrumb('View checklist', '/checklists/view/'.$checklist['Checklist']['id']);
?>

<div class="checklists view">

	<div id="tabs" class='related'>
		<ul>
			<li><a href="#informations"><?php echo __('Informations'); ?></a></li>
			<li><a href="#clactions"><?php echo __('Actions'); ?></a></li>
		</ul>
		
		<div id="informations">
			<dl>
				<dt><?php echo __('Id'); ?></dt>
				<dd>
					<?php echo h($checklist['Checklist']['id']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Name'); ?></dt>
				<dd>
					<?php echo h($checklist['Checklist']['name']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Description'); ?></dt>
				<dd>
					<?php echo h($checklist['Checklist']['description']); ?>
					&nbsp;
				</dd>
			</dl>
		</div>
			
		<div id="clactions">
			<?php if(!empty($checklist['ClAction'])):?>
				<table cellpadding = "0" cellspacing = "0">
					<tr>
						<th><?php echo __('Id'); ?></th>
						<th><?php echo __('Name'); ?></th>
						<th><?php echo __('Description'); ?></th>
					</tr>
					<?php
						foreach ($checklist['ClAction'] as $claction): ?>
						<tr>
							<td><?php echo $claction['id'];?></td>
							<td><?php echo $claction['name'];?></td>
							<td><?php echo $claction['description'];?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php else: ?>
				No actions found in the checklist.
			<?php endif; ?>
		</div>
	</div>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('Checklists');?></h2>
	<ul>
		<li class="active last"><?php echo $this->Html->link(__('Cancel'), $this->request->referer()); ?></li>
<!--	<li class="active"><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $checklist['Checklist']['id'], 'view_'.$checklist['Item']['id'])); ?> </li>
		<li class="active last"><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $checklist['Checklist']['id']), null, __('Are you sure you want to delete %s?', $checklist['Checklist']['name'])); ?> </li> -->
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>

