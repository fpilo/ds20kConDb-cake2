<!--
	Create a table with all Measurements to be uploaded
	
	FP TODO: inserire lista di strumenti da scegliere, selezionando eventualmente quello scritto nel file
-->

<table id="preview_table">
<tr>
	<th>Tmp Meas Id</th>
	<th>File Name</th>
	<th>Status</th>
	<th>Item Code</th>
	<th>Item Id</th>
	<th>Measurement Setup</th>
</tr>
<?php if(!empty($tmpMeasurements)): ?>
<?php foreach($tmpMeasurements as $dummy => $tmpMeasurement): ?>
<tr>
	<td><?php echo $tmpMeasurement['id']; ?></td>
	<td>
		<?php
			echo $tmpMeasurement['fileName'];
			echo $this->Form->hidden('tmpMeasurements.'.$dummy.'.fileName', array('value' => $tmpMeasurement['fileName'])); 
		?>
	</td>
	<td>
		<?php 
			echo $tmpMeasurement['status']; 
			echo $this->Form->hidden('tmpMeasurements.'.$dummy.'.status', array('value' => $tmpMeasurement['status']));
		?>
	</td>
	<td><?php echo $tmpMeasurement['itemCode']; ?></td>
	<td><?php echo $tmpMeasurement['itemId']; ?></td>
	<td>
		<?php 
			echo $tmpMeasurement['measurementSetup']['name'];
			echo $this->Form->hidden('tmpMeasurements.'.$dummy.'.measurementSetup', array('value' => $tmpMeasurement['measurementSetup']['id']));
		?>
	</td>
</tr>
<?php endforeach; ?>
<?php else: ?>
	<tr>
		<td colspan="7">No Measurements</td>
	</tr>
<?php endif; ?>
</table>
