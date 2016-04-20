<script type="text/javascript">
	$(function(){
		restoreTabs('previousTabIndexcheckListTemplateView');
	});
</script>

<?php
	$this->Html->addCrumb('ClTemplates', '/clTemplates');
	$this->Html->addCrumb('View', '/clTemplates/view/'.$clTemplate['ClTemplate']['id']);
?>

<div class="cltemplates view">

	<div id="tabs" class='related'>
		<ul>
			<li><a href="#informations"><?php echo __('Informations'); ?></a></li>
			<li><a href="#clactions"><?php echo __('Actions'); ?></a></li>
		</ul>
		
		<div id="informations">
			<dl>
				<dt><?php echo __('Id'); ?></dt>
				<dd>
					<?php echo h($clTemplate['ClTemplate']['id']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Name'); ?></dt>
				<dd>
					<?php echo h($clTemplate['ClTemplate']['name']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Description'); ?></dt>
				<dd>
					<?php echo h($clTemplate['ClTemplate']['description']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Item Subtype Id'); ?></dt>
				<dd>
					<?php echo h($clTemplate['ItemSubtype']['name']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Default'); ?></dt>
				<dd>
					<?php echo h($clTemplate['ClTemplate']['default']?'Yes':'No'); ?>
					&nbsp;
				</dd>
			</dl>
		</div>
			
		<div id="clactions">
			<?php if (!empty($clTemplate['ClAction'])):?>
			<table cellpadding = "0" cellspacing = "0">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Name'); ?></th>
				<th><?php echo __('Description'); ?></th>
			</tr>
			<?php
				foreach ($clTemplate['ClAction'] as $claction): ?>
				<tr>
					<td><?php echo $claction['id'];?></td>
					<td><?php echo $claction['name'];?></td>
					<td><?php echo $claction['description'];?></td>
				</tr>
				<?php endforeach; ?>
				</table>
			<?php endif; ?>
		</div>
	</div>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('ClTemplates');?></h2>
	<ul>
			<li class="active last"><?php echo $this->Html->link(__('Cancel'), $this->request->referer()); ?></li>
<!--		<li class="active"><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $clTemplate['ClTemplate']['id'])); ?> </li>
		<li class="active last"><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $clTemplate['ClTemplate']['id']), null, __('Are you sure you want to delete %s?', $clTemplate['ClTemplate']['name'])); ?> </li>
-->	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
