<?php
	$headers = array();
	$tableData = array();
	$rownum = 0;
	$tableData = $measurementReadings[1];
	// foreach($measurementReadings as $data){
	// 	//Getting table Headers
	// 	if(!isset($headers[$data["Parameter"]["id"]])) //Only set if not yet set
	// 		$headers[$data["Parameter"]["id"]] = array($data["Parameter"]["name"]=>array("id"=>"parameter_".$data["Parameter"]["id"], "class"=>"parameter"));
	// 	//Getting table Data by associating rows via the MeasuringPoint.id
	//
	// 	$tableData[$data["MeasuringPoint"]["id"]][] = $data["Reading"]["value"];
	//
	// }

	foreach($measurementReadings[0] as $id=>$parameter){
		$headers[$parameter["id"]] = array($parameter["name"]=>array("id"=>"parameter_".$parameter["id"],"class"=>"parameter"));
	}

	if($measurementQueueStatus<3):
		echo "Measurement is not yet completely uploaded.";
	else:
?>

<table cellpadding="0" cellspacing="0">
	<?php echo $this->Html->tableHeaders($headers); ?>
	<?php echo $this->Html->tableCells(array_values($tableData)); ?>
</table>

<?php endif; ?>