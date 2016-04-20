<?php echo $this->My->toJavascript($itemSubtypeVersions); ?>
<?php echo $this->Html->script('filter'); ?>
<script type="text/javascript">
	var itemTagsBaseUrl = '<?php echo $this->Html->url(array("controller"=>"ItemTags"))?>';
</script>
<div class="items form">
<?php echo $this->Form->create('Item', array('type' => 'file'));?>
	<fieldset>
		<table class="searcho" cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="1"><?php echo $this->Form->input('code', array('type' => 'textarea', 'style' => 'width: 90%',
															//'before' => '<li class="bigfield"><div class="input text required error">',
															'after' => '<div class="input-message">Seperate multiple Items with ";" or " "</div>')); ?>
					</td>
					<td><?php echo $this->Form->input('location_id', array(
														'size' => 8,
														'style' => 'width: 200px',
														'onchange' => 'RefreshSubSelect(this)',
														'id' => 'location_id'));
						?>
					</td>
					<td><?php echo $this->Form->input('item_quality_id', array(
														'size' => 8,
														'style' => 'width: 200px',
														'onchange' => 'RefreshSubSelect(this)',
														'id' => 'item_quality_id'));
						?>
					</td>
					<td><?php echo $this->Form->input('item_tag', array(
														'size' => 8,
														'options' => array(''=>''),
														'style' => 'width: 200px',
														'onchange' => 'RefreshSubSelect(this)',
														'multiple' => true));
						?>
					</td>
				</tr>
				<tr>
					<td>
						<?php
							echo $this->Form->input('project_id', array(
														'style' => 'width: 200px',
														'onchange' => 'RefreshSubSelect(this)',
														'size' => 8,
														'id' => 'project_id'));
						?>
					</td>
					<td>
						<?php
							echo $this->Form->input('manufacturer_id', array(
														'disabled' => 'disabled',
														'options' => array('Select a project'),
														'style' => 'width: 200px',
														'onchange' => 'RefreshSubSelect(this)',
														'size' => 8,
														'id' => 'manufacturer_id'));
						?>
					</td>
					<td>
						<?php
							echo $this->Form->input('item_type_id', array(
														'disabled' => 'disabled',
														'style' => 'width: 200px',
														'onchange' => 'RefreshSubSelect(this)',
														'size' => 8,
														'options' => array('Please select a manufacturer'),
														'id' => 'item_type_id'));
						?>
					</td>
					<td>
						<?php
							echo $this->Form->input('item_subtype_id', array(
														'disabled' => 'disabled',
														'style' => 'width: 200px',
														'onchange' => 'RefreshSubSelect(this)',
														'size' => 8,
														'options' => array('Please select a item type'),
														'id' => 'item_subtype_id'));
						?>
					</td>
					<td>
						<?php echo $this->Html->link(__('Show Changelog'), array('controller' => 'itemSubtypes', 'action' => 'changelog'), array('id' => 'show_changelog', 'target' => '_blank')); ?>
						<?php
							echo $this->Form->input('item_subtype_version_id', array(
														'disabled' => 'disabled',
														'style' => 'width: 200px',
														'onchange' => 'RefreshSubSelect(this)',
														'size' => 8, 'options' => array('Please select a subtype'),
														'id' => 'item_subtype_version_id'));
						?>
					</td>
				</tr>
				<tr>
					<td colspan="5">
						<?php echo $this->Form->input('comment', array(
														'label' => 'Comment',
														'type' => 'textarea',
														'onchange' => 'RefreshSubSelect(this)',
														'id' => 'comment'));
						?>
					</td>
				</tr>
		</table>
		<?php //echo $this->Form->input('Files.', array('type' => 'file', 'multiple' => 'multiple', 'label' => false)); ?>
		<?php //echo $this->Form->input('fileComment', array('label' => 'File Description')); ?>

	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Register'); ?></h2>
	<ul>
		<li class="active last"><?php echo $this->Html->link(__('Cancel'), array('controller' => 'items', 'action' => 'index')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
