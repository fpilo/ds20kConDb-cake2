<!--
	Create a table with all Components
-->

<table id="component_table">
<tr>
	<th>Project</th>
	<th>Manufacturer</th>
	<th>Type</th>
	<th>Subtype</th>
	<th>Version</th>
	<th>Comment</th>
	<th>PosName</th>
	<th>Position</th>
	<th>Attached at delivery</th>
	<th>Is a stock item</th>
	<th>Allow other versions</th>
	<th>Actions</th>
</tr>
<?php if(!empty($myComponents)): ?>
<?php //debug($myComponents); ?>
<?php foreach($myComponents as $dummy => $component): ?>
<tr>
	<td>
		<?php echo $this->Form->hidden('SubtypeComponent.'.$dummy.'.project_id', array('value' => $component['ItemSubtypeVersionsComposition']['project_id'])) ?>
		<?php echo $component['ItemSubtypeVersionsComposition']['project_name']; ?>
	</td>
	<td><?php echo $component['Manufacturer']['name']; ?></td>
	<td><?php echo $component['ItemSubtype']['ItemType']['name']; ?></td>
	<td>
      <?php
         echo $component['ItemSubtype']['name'];
         echo $this->Form->hidden('SubtypeComponent.'.$dummy.'.item_subtype_id',array('value'=>$component['ItemSubtype']['id']));
      ?>
   </td>
	<td><?php echo $component['ItemSubtypeVersion']['version']; ?></td>
	<td><?php echo $component['ItemSubtypeVersion']['comment']; ?></td>
	<td>
		<?php echo $this->Form->hidden('SubtypeComponent.'.$dummy.'.position_name', array('value' => $component['ItemSubtypeVersionsComposition']['position_name'])) ?>
		<?php echo $component['ItemSubtypeVersionsComposition']['position_name']; ?>
	</td>
	<td>
		<?php
         if(!empty($component['ItemSubtypeVersionsComposition']['position'])) {
            $value = $component['ItemSubtypeVersionsComposition']['position'];
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
		<?php
		if( (isset($component['ItemSubtypeVersionsComposition']['attached'])) && ($component['ItemSubtypeVersionsComposition']['attached'] == 0) )
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
        if( (isset($component['ItemSubtypeVersionsComposition']['is_stock'])) && ($component['ItemSubtypeVersionsComposition']['is_stock'] == 1) )
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
        if( (isset($component['ItemSubtypeVersionsComposition']['all_versions'])) && ($component['ItemSubtypeVersionsComposition']['all_versions'] == 1) )
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
		<?php if(isset($component["New"]) || !$editWithAttached): ?>
			<input type="button" name="RemoveButton" value="Remove" class="RemoveButton" onclick="RemoveComponent(<?php echo $dummy;?>)" style="width: 100px"/>
		<?php endif; ?>
	</td>
</tr>
<?php endforeach; ?>
<?php else: ?>
	<tr>
		<td colspan="7">No Components</td>
	</tr>
<?php endif; ?>
</table>
