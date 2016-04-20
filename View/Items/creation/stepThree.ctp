<style type="text/css">
	#selectorOverlay{
		background-color:white;
		position: absolute;
		display: inline-block;
		overflow:auto;
		box-shadow: 0px 0px 20px #888888;
		padding: 5px 0px;
	}
</style>
<?php

// debug($data);
// debug($itemSubtypeVersion);

//Echo a text describing what will happen if the submit button is pressed
echo $description;
if(count($assemble["ItemSubtypeVersion"]["Component"])>0)
	require("components.ctp");

//Adapt the button text according to the selections
if ($submit){
	echo $this->Form->create('Item',array("class"=>"stepThree", "default"=>false));
	if(count($assemble["ItemSubtypeVersion"]["Component"])==0){
		if(!isset($itemCodes)){
			$itemCodes = array($itemCode);
		}
		foreach($itemCodes as $itemCode){
			echo "<input type='hidden' value='$itemCode' name='data[Item][code][]'/>";
		}
	}
	echo $this->Form->end(__('Create Item'));
}

echo $this->element("step_three_javascript");

?>

