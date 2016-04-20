<?php
	$this->Html->addCrumb('ClTemplates', '/clTemplates');
	$this->Html->addCrumb('Edit', '/clTemplates/edit/'.$this->data['ClTemplate']['id']);
?>

<div class="cltemplates form">
<?php echo $this->Form->create('ClTemplate');?>
	<fieldset>
		<legend><?php echo __('Edit Checklist Template'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('description');
		echo $this->Form->input('item_subtype_id', array(
															'empty' => '(choose one)'
															));
		echo $this->Form->input('default');
	?>	
		
	<?php $hlevels = array('1'=>'1','2'=>'2'); ?>
	<h3>Actions</h3>
	<table id="mytable" style="font-size:75%">
		<tr class="nodrag nodrop">
			<th style="width:3ex">#</th>
			<th style="width:3ex">&nbsp;</th>
			<th style="width:3ex">Hierarchy<br />level</th>
			<th style="width:15em">Name</th>
			<th style="width:25em">Description</th>
			<th style="width:15em">Source state(s)</th>
			<th style="width:15em">Target State</th>
			<th style="width:2em">&nbsp;</th>
		</tr>
		
		<tr id="claction0" style="display:none;">
			<td>
				<?php echo $this->Form->input('unused.list_number',array(
					'label'=>'','div'=>false,'type'=>'text','readonly'=>'readonly', 'style' => 'text-align:right;')); ?>
			</td><td>
				<?php echo $this->Form->input('unused.list_subnumber',array(
					'label'=>'','div'=>false,'type'=>'text','readonly'=>'readonly', 'style' => 'text-align:right;')); ?>
			</td>
			<td><?php echo $this->Form->input('unused.hierarchy_level', array(
																				'label' => '',
																				'div' => false,
																				'type' => 'select',
																				'options' => $hlevels,
																				'onchange' => 'toggleClStatesView(0)',
																				'empty' => true
																				)
												); ?>
			</td>
			<td><?php echo $this->Form->input('unused.name',array('label' => '', 'div' => false, 'type'=> 'text')); ?></td>
			<td><?php echo $this->Form->input('unused.description',array('label' => '', 'div' => false, 'type' => 'text', 'rows' => '3')); ?></td>
			<td><?php echo $this->Form->input('unused.source_state_id', array(
																				'label' => '',
																				'div' => false,
																				'type' => 'select',
																				'size' => 5,
																				'multiple' => true, //if set to true an hidden input is also created (remember to add [] in javascript for multipe choice)
																				'options' => $clSourceStates,
																				'empty' => '(choose at least one)'
																				)
												); ?>
			</td>
			<td><?php echo $this->Form->input('unused.target_state_id', array(
																				'label'=>'',
																				'div'=>false,
																				'type' => 'select',
																				'size' => 5,
																				'multiple' => false,
																				'options' => $clTargetStates,
																				'empty' => '(choose one)'
																				)
												); ?>
			</td>
			<td class="dragHandle"><?php echo $this->Form->button('&nbsp;-&nbsp;',array('type'=>'button','title'=>'Click Here to remove this action')); ?></td>
		</tr>
		
		<?php
			$count=0;
			if(isset($this->data['ClAction'])){
				foreach($this->data['ClAction'] as $claction)
				{
					echo '<tr id="claction'.($count+1).'">';
					echo '<td>';
					echo $this->Form->input('ClAction.' . $count . '.list_number',array(
						'label' => '', 'div' => false, 'type' => 'text', 'readonly' => 'readonly', 'style' => 'text-align:right;'));
					echo '</td><td>';
					if($claction['hierarchy_level']==1) echo $this->Form->input('ClAction.' . $count . '.list_subnumber', array(
						'label' => '','div' => false,'type' => 'text','readonly' => 'readonly','hidden' => true));
					if($claction['hierarchy_level']==2){
						echo $this->Form->input('ClAction.' . $count . '.list_subnumber', array(
						'label' => '', 'div' => false, 'type' => 'text', 'readonly' => 'readonly',  'style' => 'text-align:right;'));
						}
					echo '</td>';
					echo '<td style="display:none; padding-right:2ex">'.$this->Form->input('ClAction.' . $count . '.id').'</td>';				
					echo '<td>'.$this->Form->input('ClAction.' . $count . '.hierarchy_level', array(
																										'label' => '',
																										'div' => false,
																										'type' => 'select',
																										'options' => $hlevels,
																										'onchange' => 'toggleClStatesView('.($count+1).')',
																										'empty' => true
																										)
													).'</td>';
					echo '<td>'.$this->Form->input('ClAction.' . $count . '.name', array('label'=> '', 'div' => false, 'type' => 'text')).'</td>';
					echo '<td>'.$this->Form->input('ClAction.' . $count . '.description', array('label' => '', 'div' => false,  'type' => 'text', 'rows' => '3')).'</td>';
					
					//Source states selection
					unset($selected); $selected=array();
								
					if(isset($claction['source_state_id']))
						foreach($claction['source_state_id'] as $clstateid){
							$selected[$clstateid]=$clstateid;							
						}
					
					$seloptions = array(
											'label' => '',
											'div' => false,
											'type' => 'select',
											'size' => 5,
											'multiple' => true, //if set to true an hidden input is also created (remember to add [] in javascript for multipe choice)
											'options' => $clSourceStates,
											'selected' => $selected,
											'empty' => '(choose at least one)'
										);
					if($claction['hierarchy_level']==2){ $seloption['selected'] = null; $seloptions['hidden']=true; }					
					echo '<td>'.$this->Form->input('ClAction.' . $count . '.source_state_id', $seloptions).'</td>';
													
					//Target states selection	
					unset($selected);$selected=array();		
					
					if(isset($claction['target_state_id'])){
						$clstateid = $claction['target_state_id'];
						$selected[$clstateid]=$claction['target_state_id'];							
					}
					
					unset($seloptions);
					$seloptions = array(
											'label' => '',
											'div' => false,
											'type' => 'select',
											'size' => 5,
											'multiple' => false,
											'options' => $clTargetStates,
											'selected' => $selected,
											'empty' => '(choose one)'
										);
					if($claction['hierarchy_level']==2){ $seloption['selected'] = null; $seloptions['hidden']=true; }		
					echo '<td>'.$this->Form->input('ClAction.' . $count . '.target_state_id', $seloptions ).'</td>';
					
					echo '<td class="dragHandle">'.	$this->Form->button('&nbsp;-&nbsp;',array('type'=>'button','title'=>'Click Here to remove this action','onclick'=>'removeClAction('.($count+1).')')).'</td>';
					echo '</tr>';
					
					$count++;
				}
			}
		?>
		
		<tr id="trAdd" class="nodrag nodrop">				
			<td></td><td></td><td></td><td></td><td></td><td></td><td></td>
			<td class="dragHandle"><?php echo $this->Form->button('+',array('type'=>'button','title'=>'Click Here to add another action','onclick'=>'addClAction()')); ?></td>
		</tr>
	</table>
	
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('clTemplates');?></h2>
	<ul>
		<li class="active"><?php echo $this->Html->link(__('Cancel'), array('action' => 'view', $this->Form->value('ClTemplate.id'))); ?> </li>
		<li class="active"><?php echo $this->Html->link(__('Add new state'), array('controller' => 'clStates', 'action' => 'add')); ?> </li>
		<li class="active last"><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('ClTemplate.id')), null, __('Are you sure you want to delete %s?', $this->Form->value('ClTemplate.name'))); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>

<?php echo $this->Html->script(array('jquery-1.6.4.min'));?>
<?php echo $this->Html->script(array('jquery.tablednd'));?>
<script type="text/javascript" charset="utf-8">
    
	$(document).ready(function() {
        // Initialise the table
        //$("#mytable").tableDnD();
        // Make a nice striped effect on the table
        //$("#mytable tr:even").addClass("alt");

		$('#mytable').tableDnD({
			onDrop: function(table, row) {
				//alert($.tableDnD.serialize());
				
				assignListNumber();
			
			},
			dragHandle: ".dragHandle"
		});
		$("#mytable tr").hover(function() {
				$(this).find("td:last").addClass('showDragHandle');
			}, function() {
			$(this).find("td:last").removeClass('showDragHandle');
		});
		
	});

	var lastRow=0;
	
	function addClAction() {
		var rowCount = $('#mytable tr').length;
		lastRow=rowCount-2;
		lastRow++;
		$("#trAdd").before($("#claction0").clone(true).attr('id','claction'+lastRow).removeAttr('style'));
		$("#claction"+lastRow+" input:first").attr('name','data[ClAction]['+lastRow+'][list_number]').attr('id','clactionName'+lastRow);
		$("#claction"+lastRow+" input:eq(1)").attr('name','data[ClAction]['+lastRow+'][list_subnumber]').attr('id','clactionName'+lastRow);
		$("#claction"+lastRow+" select:first").attr('name','data[ClAction]['+lastRow+'][hierarchy_level]').attr('id','clactionHierarchyLevel'+lastRow).attr('onchange','toggleClStatesView('+lastRow+')');	
		$("#claction"+lastRow+" input:eq(2)").attr('name','data[ClAction]['+lastRow+'][name]').attr('id','clactionName'+lastRow);
		$("#claction"+lastRow+" input:eq(3)").attr('name','data[ClAction]['+lastRow+'][description]').attr('id','clactionDescription'+lastRow);
		$("#claction"+lastRow+" select:eq(1)").attr('name','data[ClAction]['+lastRow+'][source_state_id][]').attr('id','clactionSourceStateId'+lastRow);
		$("#claction"+lastRow+" select:eq(2)").attr('name','data[ClAction]['+lastRow+'][target_state_id]').attr('id','clactionFinalStateId'+lastRow);
		$("#claction"+lastRow+" button").attr('onclick','removeClAction('+lastRow+')');
		
		assignListNumber();
	}
	
	function removeClAction(x) {
		$("#claction"+x).remove();
		
		assignListNumber();
	}
	
	function toggleClStatesView(x) {
		var hlevel = parseInt($("#claction"+x+" select:first").val());
		switch(hlevel){
			case 1:
				$("#claction"+x+" input:eq(1)").hide();
				$("#claction"+x+" select:eq(1)").show();
				$("#claction"+x+" select:eq(2)").show();
				break;
			case 2:
				$("#claction"+x+" input:eq(1)").show();
				$("#claction"+x+" select:eq(1)").hide();
				$("#claction"+x+" select:eq(2)").hide();
				break;
			default:
				break;
		}
		
		assignListNumber();

	}
	
	function assignListNumber(){
	
		var list_number=0;
		var list_subnumber=0;
		$('#mytable tr:gt(1):not(:last-child)').each(function(){
			hlevel = parseInt($(this).find("select:first").val());
			switch(hlevel){
						case 1:
							$(this).find("input:first").val(++list_number);
							list_subnumber=0;
							break;
						case 2:
							$(this).find("input:first").val(list_number);
							$(this).find("input:eq(1)").val(++list_subnumber);
							break;
					}
		});
		
	}
	
</script>
