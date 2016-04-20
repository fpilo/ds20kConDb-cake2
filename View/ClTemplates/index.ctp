<?php
	$this->Html->addCrumb('ClTemplates', '/clTemplates');
?>

<div class="ClTemplates index">
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('description');?></th>
			<th><?php echo $this->Paginator->sort('item subtype');?></th>
			<th><?php echo $this->Paginator->sort('default');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($clTemplates as $clTemplate): ?>
	<tr>
		<td><?php echo h($clTemplate['ClTemplate']['id']); ?>&nbsp;</td>
		<td><?php echo $this->Html->link(__($clTemplate['ClTemplate']['name']), array('action' => 'view', $clTemplate['ClTemplate']['id'])); ?>&nbsp;</td>
		<td><?php echo h($clTemplate['ClTemplate']['description']); ?>&nbsp;</td>
		<td><?php echo h($clTemplate['ItemSubtype']['name']); ?>&nbsp;</td>
		<td><?php echo $clTemplate['ClTemplate']['default']?$this->Html->image('/img/test-pass-icon.png', array('alt' => 'yes')):''; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $clTemplate['ClTemplate']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $clTemplate['ClTemplate']['id']), null, __('Are you sure you want to delete # %s?', $clTemplate['ClTemplate']['id'])); ?>
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

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('Templates');?></h2>
	<ul>
		<li class="active"><?php echo $this->Html->link(__('Add'), array('action' => 'add')); ?></li>
		<li class="active last"><?php echo $this->Html->link(__('Clone'), array('action' => 'myClone')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>