<!--
	Create a table with all Components
-->

<div id="component_table_div">
	<?php echo $this->Session->flash('tableForm') ?>
	<table id="component_table">
	<tr>
		<!--<th><?php //echo __('Id'); ?></th>-->
		<th><?php echo $this->Paginator->sort('Component.position_numeric','Position');?></th>
		<th><?php echo $this->Paginator->sort('Component.position_name','PosName');?></th>
		<th><?php echo __('Type'); ?></th>
		<th><?php echo __('Subtype'); ?></th>
		<th><?php echo __('Version'); ?></th>
		<th><?php echo __('Project'); ?></th>
		<th><?php echo __('Manufacturer'); ?></th>
		<th><?php echo __('Comment'); ?></th>
		<th><?php echo __('Has Components'); ?></th>
		<th><?php echo __('Attached at delivery'); ?></th>
		<th><?php echo __('Is a stock item'); ?></th>
		<th><?php echo __('Allow other versions'); ?></th>
		<th><?php echo __('Actions'); ?></th>
	</tr>

	<?php if(!empty($components)): ?>
	<?php foreach($components as $dummy => $component): ?>
	<?php //debug($component); ?>
	<tr>
		<td>
			<?php echo $this->Form->hidden('SubtypeComponent.'.$dummy.'.status_code', array('value' => $component['Component']['status_code'])) ?>	
			<?php
					 if(!empty($component['Component']['position'])) {
							$value = $component['Component']['position'];
					 } else {	
							// Todo: throw a 'component has no position' error
							$value = $dummy+1;
					 }
			?>

			<?php echo $this->Form->input('SubtypeComponent.'.$dummy.'.position', array(
																						'label' => false,
																						'value' => $value,
																						'div' => false,
																						'onchange' => 'PositionChanged(this)')); ?>
			
			<?php echo $this->Form->hidden('SubtypeComponent.'.$dummy.'.component_id', array('value' => $component['ItemSubtypeVersion']['id'])) ?>
		</td>
		<td>
			<?php echo $this->Form->hidden('SubtypeComponent.'.$dummy.'.position_name', array('value' => $component['Component']['position_name'])) ?>
			<?php echo $component['Component']['position_name']; ?>
		</td>
		<td><?php echo $component['ItemSubtypeVersion']['ItemSubtype']['ItemType']['name']; ?></td>
		<td>
				<?php
					 echo $component['ItemSubtypeVersion']['ItemSubtype']['name'];
					 echo $this->Form->hidden('SubtypeComponent.'.$dummy.'.item_subtype_id',array('value'=>$component['ItemSubtypeVersion']['ItemSubtype']['id']));
				?>
		 </td>
		<td><?php echo $component['ItemSubtypeVersion']['version']; ?></td>
		<td>
			<?php echo $this->Form->hidden('SubtypeComponent.'.$dummy.'.project_id', array('value' => $component['Project']['id'])) ?>
			<?php echo $component['Project']['name']; ?>
		</td>
		<td><?php echo $component['ItemSubtypeVersion']['Manufacturer']['name']; ?></td>
		<td><?php echo $component['ItemSubtypeVersion']['comment']; ?></td>
		<td>&nbsp;</td>
		<td>
			<?php
			if( (isset($component['Component']['attached'])) && ($component['Component']['attached'] == 0) )
				echo $this->Form->input('SubtypeComponent.'.$dummy.'.attached', array(
																						'type' => 'checkbox',
																						'label' => false,
																						'onchange' => 'AttachedChanged(this)'));
			else
				echo $this->Form->input('SubtypeComponent.'.$dummy.'.attached', array(
																						'type' => 'checkbox',
																						'label' => false,
																						'checked' => 'checked',
																						'onchange' => 'AttachedChanged(this)'));
			?>
		</td>
		<td>
					<?php
					if( (isset($component['Component']['is_stock'])) && ($component['Component']['is_stock'] == 1) )
							echo $this->Form->input('SubtypeComponent.'.$dummy.'.is_stock', array(
																																											'type' => 'checkbox',
																																											'label' => false,
																																											'checked' => 'checked',
																																											'onchange' => 'IsStockChanged(this)'));
					else
							echo $this->Form->input('SubtypeComponent.'.$dummy.'.is_stock', array(
																																											'type' => 'checkbox',
																																											'label' => false,
																																											'onchange' => 'IsStockChanged(this)'));
					?>
			</td>
			<td>
					<?php
					if( (isset($component['Component']['all_versions'])) && ($component['Component']['all_versions'] == 1) )
							echo $this->Form->input('SubtypeComponent.'.$dummy.'.all_versions', array(
																																											'type' => 'checkbox',
																																											'label' => false,
																																											'checked' => 'checked',
																																											'onchange' => 'AllVersionsChanged(this)'));
					else
							echo $this->Form->input('SubtypeComponent.'.$dummy.'.all_versions', array(
																																											'type' => 'checkbox',
																																											'label' => false,
																																											'onchange' => 'AllVersionsChanged(this)'));
					?>
			</td>
		<td>
			<?php if(isset($component['Component']['is_new']) || !$editWithAttached): ?>
				<input type="button" name="RemoveButton" value="Remove" class="RemoveButton" onclick="RemoveComponent(<?php echo $component['Component']['id']; ?>)" style="width: 100px"/>
			<?php endif; ?>
		</td>
	</tr>
	<?php endforeach; ?>
	</table>
	<p id="paging_counter">
		<?php
			echo $this->Paginator->counter(array(
			'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
			));
		?>	
	</p>
	<div id="paging_links" class="paging">
		<?php
			echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
			echo $this->Paginator->numbers(array('separator' => ''));
			echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
		?>
	</div>
	<?php else: ?>
		<tr>
			<td colspan="7">No Components found.</td>
		</tr>
	</table>
	<?php endif; ?>
</div>