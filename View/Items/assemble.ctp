<?php echo $this->My->toJavascript($itemSubtypeVersions); ?>
<?php echo $this->Html->script('filter'); ?>

<div class="items form">
<?php echo $this->Form->create('Item');?>
	<fieldset>
		<table cellpadding="0" cellspacing="0">
				<tr>
					<td width="30%">
						<?php echo $this->Form->input('location_id', array(
														'size' => 8,
														'style' => 'width: 100%',
														'id' => 'location_id'));
						?>
					</td>
					<td width="30%">
						<?php
							echo $this->Form->input('project_id', array(
														'style' => 'width: 100%',
														'onchange' => 'RefreshSubSelect(this)',
														'size' => 8,
														'id' => 'project_id'));
						?>
					</td>
					<td width="30%">
						<?php
							echo $this->Form->input('manufacturer_id', array(
														'disabled' => 'disabled',
														'options' => array('Select a project'),
														'style' => 'width: 100%',
														'onchange' => 'RefreshSubSelect(this)',
														'size' => 8,
														'id' => 'manufacturer_id'));
						?>
					</td>
				</tr>
				<tr>
					<td width="30%">
						<?php
							echo $this->Form->input('item_type_id', array(
														'disabled' => 'disabled',
														'style' => 'width: 100%',
														'onchange' => 'RefreshSubSelect(this)',
														'size' => 8,
														'options' => array('Please select a manufacturer'),
														'id' => 'item_type_id'));
						?>
					</td>
					<td width="30%">
						<?php
							echo $this->Form->input('item_subtype_id', array(
														'disabled' => 'disabled',
														'style' => 'width: 100%',
														'onchange' => 'RefreshSubSelect(this)',
														'size' => 8,
														'options' => array('Please select a item type'),
														'id' => 'item_subtype_id'));
						?>
					</td>
					<td width="30%">
						<?php
							echo $this->Form->input('item_subtype_version_id', array(
														'disabled' => 'disabled',
														'style' => 'width: 100%',
														'onchange' => 'RefreshSubSelect(this)',
														'size' => 8, 'options' => array('Please select a subtype'),
														'id' => 'item_subtype_version_id'));
						?>
						<?php echo $this->Html->link(__('Show Changelog'), array('controller' => 'itemSubtypes', 'action' => 'changelog'), array('id' => 'show_changelog', 'target' => '_blank')); ?>
					</td>
				</tr>
		</table>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Assemble'); ?></h2>
	<ul>
		<li class='active last'><?php echo $this->Html->link(__('Cancel'), array('controller'=>'items', 'action' => 'index')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
