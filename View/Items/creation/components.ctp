<fieldset>
	<table>
<table><tr><td>

<?php
	/*
	 * Convert actions array into links.
	 * Not possible to include it into the Controller because you need to use HtmlHelper to create links.
	 */
	foreach($assemble['Selection'] as $position => $selection) {
		if(is_array($selection["actions"]))
			$assemble['Selection'][$position]['actions'] = array($this->Html->link(
																	array_shift($selection['actions']),
																	array_shift($selection['actions']),
																	array("class"=>"equipButtons")
																),
																	array('class' => 'actions')
															);
	}
	if(!isset($itemCodes)){

		$itemCodes = array($itemCode);
	}

?>

<div id="tabs" class='related'>
<?php
	echo "<ul>";
	foreach($itemCodes as $itemCode){
		echo "<li><a href='#".$itemCode."'>".$itemCode."</a></li>";
	}
	echo "</ul>";
	foreach($itemCodes as $itemCode){
		echo "<div id='".$itemCode."'>";
		echo $this->Form->create('Item',array("class"=>"components", "default"=>false));
		echo "<input type='hidden' value='$itemCode' name='data[Item][code]'/>";
		echo "<table>";
			echo $this->Html->tableHeaders(array(
				'Position',
				'Type',
				'Subtype',
				'Version(s)',
				'Code',
				'Tags',
				'State',
				'Quality',
				'Manufacturer',
				'Project',
				'Actions'));

			echo $this->Html->tableCells($assemble['Selection']);
		echo "</table>";
		echo "<input type='hidden' value='stepThree' name='data[step]' />";
		echo "</form></div>";
	}
?>
</div>

</td>
	</table>
</fieldset>

<script type='text/javascript'>
var overlayCloseButton = '<div style="float: right;"><?php echo $this->Html->image("ElegantBlueWeb/xmark.png",array("alt"=>"X","width"=>"20","onClick"=>"closeSelectorOverlay();","style"=>"cursor:pointer; z-index:5;")); ?></div>';
var itemViewBaseUrl = '<?php echo $this->Html->url(array("controller"=>"Items","action"=>"view")); ?>/';

function getNewTargetRow(data,componentPosition,itemId){
	var newTargetRow = "<tr><td>"+componentPosition+"</td>";
	newTargetRow += "<td>"+data.ItemType.name+"</td>";
	newTargetRow += "<td>"+data.ItemSubtype.name+"</td>";
	newTargetRow += "<td>"+data.ItemSubtypeVersion.version+"</td>";
	if(data.Item.code.indexOf("Stock_") != -1){
		newTargetRow += "<td>Stock item";
	}else{
		newTargetRow += "<td>"+data.Item.code;
	}
	//add the hidden fields for the component information
	//		name='data[Component][posId][position]' and name='data[Component][posId][component_id]'
	//The separation if it was an stock item is only made in the controller
	newTargetRow += "<input type='hidden' value='"+componentPosition+"' name='data[Component]["+componentPosition+"][position]'>";
	newTargetRow += "<input type='hidden' value='"+itemId+"' name='data[Component]["+componentPosition+"][component_id]'>";
	newTargetRow += "</td><td>";
	//Special treatment for tags, as usual
	$(data.ItemTag).each(function(){
		newTargetRow += this.name+" ";
	});
	newTargetRow += "</td><td>"+data.State.name+"</td>";
	newTargetRow += "<td>"+data.ItemQuality.name+"</td>";
	newTargetRow += "<td>"+data.Manufacturer.name+"</td>";
	newTargetRow += "<td>"+data.Project.name+"</td>";
	newTargetRow += "<td class='actions'><a href='#' onClick='removeItemFromPosition(this,'"+componentPosition+"',"+itemId+")' >Remove</a></td></tr>";
		return newTargetRow;
}

</script>
<?php echo $this->Html->script("component_modification");
