<?php //debug($assemble['Item']); ?>
<?php //echo $this->Html->script('saveForm');?>

<script type="text/javascript">
	$(document).ready(function(){
		$('#ItemCode').blur(function(){
			$.post(
				'<?php echo $this->Html->url(array("controller" => "items", "action" => "saveForm")); ?>/',
				{field:  'code', value: $('#ItemCode').val(), formName: 'AssembleItemComposition' },
				false
			);
		});

		$('#ItemComment').blur(function(){
			$.post(
				'<?php echo $this->Html->url(array("controller" => "items", "action" => "saveForm")); ?>/',
				{field:  'comment', value: $('#ItemComment').val(), formName: 'AssembleItemComposition' },
				false
			);
		});

		/*
		$('#ItemStateId').blur(function(){
			$.post(
				'<?php echo $this->Html->url(array("controller" => "items", "action" => "saveForm")); ?>/',
				{field:  'state_id', value: $('#ItemStateId').val(), formName: 'AssembleItemComposition' },
				false
			);
		});*/

		$('#ItemItemQualityId').blur(function(){
			$.post(
				'<?php echo $this->Html->url(array("controller" => "items", "action" => "saveForm")); ?>/',
				{field:  'item_quality_id', value: $(this).val(), formName: 'AssembleItemComposition' },
				false
			);
		});

		$('#ItemItemTagsId').blur(function(){
			$.post(
				'<?php echo $this->Html->url(array("controller" => "items", "action" => "saveForm")); ?>/',
				{field:  'item_tags_id', value: $(this).val(), formName: 'AssembleItemComposition' },
				false
			);
		});

		/*
		function handleCodeValidation(error){
			$('#code-notEmpty').remove();
			if(error.length > 0){
				$('#ItemCode').after('<div id="code-notEmpty" class="error-message">'+ error +'</div>');
			}
		}
		//*/
	});


</script>

<div class="items form">

	<?php echo $this->Form->create('Item'); ?>
	<fieldset>
		<legend><?php  echo __('Create new '.$assemble['ItemSubtypeVersion']['ItemSubtype']['name'].' v'.$assemble['ItemSubtypeVersion']['ItemSubtypeVersion']['version'] .': '); ?></legend>
		<table>
		<?php
			$code = null;
			if(!empty($assemble['Item']['code']))
				$code = $assemble['Item']['code'];

			$comment = null;
			if(!empty($assemble['Item']['comment']))
				$comment = $assemble['Item']['comment'];

			/*
			$state_id = null;
			if(!empty($assemble['Item']['state_id']))
				$state_id = $assemble['Item']['state_id'];
			*/
			
			$item_quality_id = null;
			if(!empty($assemble['Item']['item_quality_id']))
				$item_quality_id = $assemble['Item']['item_quality_id'];

			$item_tags_id = null;
			if(!empty($assemble['Item']['item_tags_id']))
				$item_tags_id = $assemble['Item']['item_tags_id'];

			echo $this->Html->tableCells(
				array(
					//Row1
					array(
						// Cell1 of Row1
						array(
							$this->Form->input('code', array('default' => $code)),
							array('colspan' => '3')
						)),
					//Row2
					array(
						// Cell1 of Row2
						array(
							$this->Form->input('comment', array('label' => 'Comment', 'type' => 'textarea', 'default' => $comment)),
							array('colspan' => '3')
						)
					),
					//Row3
					array(
						// Cell1 of Row3
						//$this->Form->input('state_id', array('size' => 4, 'style' => 'width: 250px', 'default' => $state_id)),
						$this->Form->input('item_quality_id', array('size' => 4, 'style' => 'width: 250px', 'default' => $item_quality_id)),
						$this->Form->input('item_tags_id', array('size' => 4, 'style' => 'width: 250px','multiple'=>true,'default' => $item_tags_id)),
					)
				)
			);
		?>
	<td colspan="3">
	<h3 class="actions"><?php  echo __('Components: '); ?></h3>

	<?php
		/*
		 * Convert actions array into links.
		 * Not possible to include it into the Controller because you need to use HtmlHelper to create links.
		 */
		foreach($assemble['Selection'] as $position => $selection) {
			$assemble['Selection'][$position]['actions'] = array($this->Html->link(
																	array_shift($selection['actions']),
																	array_shift($selection['actions'])),
																 array('class' => 'actions'));
		}
	?>
		<table>
		<?php
			echo $this->Html->tableHeaders(array(
				'Position',
				'Type',
				'Subtype',
				'Version',
				'Code',
				'Tags',
				'State',
				'Quality',
				'Manufacturer',
				'Project',
				'Actions'));

			echo $this->Html->tableCells($assemble['Selection']);
		?>
		</table>

	</td>
		</table>
	</fieldset>

	<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Assemble'); ?></h2>
	<ul>
		<li class='active last'><?php echo $this->Html->link(__('Cancel'), array('controller'=>'items', 'action' => 'cancelAssemble')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>