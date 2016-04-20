<?php echo $this->My->toJavascript($itemSubtypeVersions); ?>
<?php echo $this->Html->script('searchfilter'); ?>

<div class="items index">
	<?php echo $this->Form->create('Stock'); ?>
	<fieldset>
		<legend><?php echo __('Add Stock'); ?></legend>
		<div class="search">
			<table class="search" cellpadding="0" cellspacing="0" style="width: 100%">
			<tr>
				<!--<td>
				<table><tr>-->
				<td><?php
						if(empty($filter['location_id'])) $filter['location_id'] = '';
						echo $this->Form->input('location_id', array(
									'div' => false,
									'size' => 18,
									'multiple' => true,
									'options' => $locations,
									'onchange' => 'SubSelectOnChange(this)',
									'style' => 'width: 100%',
									'default' => $filter['location_id'],
									'id' => 'location_id',
									'empty' => '(Select all)')); ?>
				</td>
				<td><?php echo $this->Form->input('project_id', array(
									'div' => false,
									'size' => 18,
									'multiple' => true,
									//'options' => array(),
									'onchange' => 'SubSelectOnChange(this)',
									'style' => 'width: 100%',
									//'default' => $filter['project_id'],
									'id' => 'project_id')); ?>
				</td>
				<td><?php echo $this->Form->input('manufacturer_id', array(
									'div' => false,
									'size' => 18,
									'multiple' => false,
									//'options' => $manufacturers,
									'onchange' => 'SubSelectOnChange(this)',
									'style' => 'width: 100%',
									//'default' => $filter['manufacturer_id'],
									'id' => 'manufacturer_id')); ?>
				</td>
				<td><?php echo $this->Form->input('item_type_id', array(
									'div' => false, '
									size' => 18,
									'multiple' => false,
									//'options' => $itemTypes,
									'onchange' => 'SubSelectOnChange(this)',
									'style' => 'width: 100%',
									//'default' => $filter['item_type_id'],
									'id' => 'item_type_id')); ?>
				</td>
				<td><?php echo $this->Form->input('item_subtype_id', array(
									'div' => false,
									'size' => 18,
									'multiple' => false,
									//'options' => $itemSubtypes,
									'onchange' => 'SubSelectOnChange(this)',
									'style' => 'width: 100%',
									//'default' => $filter['item_subtype_id'],
									'id' => 'item_subtype_id')); ?>
				</td>
				<td><?php echo $this->Form->input('item_subtype_version_id', array(
									'div' => false,
									'size' => 18,
									'multiple' => false,
									'options' => array(),
									'onchange' => 'SubSelectOnChange(this)',
									'style' => 'width: 100%',
									//'default' => $filter['item_subtype_version_id'],
									'id' => 'item_subtype_version_id'
									)); ?>
					<?php echo $this->Html->link(__('Show Changelog'), array('controller' => 'item_subtypes', 'action' => 'changelog'), array('id' => 'show_changelog', 'target' => '_blank')); ?>
				</td>
				<td><?php
						if(empty($filter['state_id']))
							$filter['state_id'] = '';

						echo $this->Form->input('state_id', array(
									'div' => false,
									'size' => 18,
									'multiple' => false,
									'options' => $states,
									'onchange' => 'SubSelectOnChange(this)',
									'style' => 'width: 100%',
									'id' => 'state_id',
									'default' => $filter['state_id'])); ?>
				</td>
			</tr>
			<tr>
				<td><?php
						if(empty($filter['stock_quality_id']))
							$filter['stock_quality_id'] = '6';

						echo $this->Form->input('stock_quality_id', array(
									'div' => false,
									'size' => 4,
									'multiple' => false,
									'options' => $stockQualities,
									'onchange' => 'SubSelectOnChange(this)',
									'style' => 'width: 100%',
									'id' => 'stock_quality_id',
									'default' => $filter['stock_quality_id'])); ?>
				</td>
				<td><?php
						if(empty($filter['stock_tag_id']))
							$filter['stock_tag_id'] = '';

						echo $this->Form->input('stock_tag_id', array(
									'div' => false,
									'size' => 4,
									'multiple' => true,
									'options' => $stockTags,
#									'onchange' => 'SelectAll(this)',
									'default' => $filter['stock_tag_id'],
									'id' =>'stock_tag_id',
#									'empty' => '(Select all)'
									)); ?>
				</td>

				<!--</tr>
				</table></td>-->
				<td colspan="5">
					<?php echo $this->Form->input('amount', array('placeholder' => 'Please set amount of items in stock...',)); ?>
				</td>
			</tr>
			</table>
		</div>
	</fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('Stock');?></h2>
	<ul>
		<li class="active last"><?php echo $this->Html->link(__('Cancel'), array('action' => 'index')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
