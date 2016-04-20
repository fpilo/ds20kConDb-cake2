<?php
$plotLoaded = true;
require(dirname(__FILE__).'/plot.ctp'); ?>
	<div style="float:left; width:320px;margin:10px;" id="intcal_selector">
		<div style="width:100%; float:left;">
			<?php
				//Base definition
				$header = array("Axis");
				$cells = array(array("xParam"),array("yParam"));
				$defaults = array("x"=>array_fill(0,count($possibleParameters),""),"y"=>array_fill(0,count($possibleParameters),""));
				$defaults["x"][2] = "checked";
				$defaults["y"][3] = "checked";
				foreach($possibleParameters as $num=>$parameter){
					$param = array_pop($parameter);
					if(!in_array($param,array("Chip","Strip"))){
						$header[]	= $param;
						$cells[0][] = "<input type='radio' name='xParam' value='$param' ".$defaults["x"][$num]."/>";
						$cells[1][] = "<input type='radio' name='yParam' value='$param' ".$defaults["y"][$num]."/>";
					}
				}


			?>
			<table>
				<?php echo $this->Html->tableHeaders($header); ?>
				<?php echo $this->Html->tableCells($cells); ?>
			</table>
			<?php echo $this->Form->input("overwritePlots", array('hiddenField' => false,"checked","type"=>"checkbox")); ?>
			<?php echo $this->Form->input("plotChips", array('hiddenField' => false,"checked","type"=>"checkbox","label"=>"Plot all Chips on selection")); ?>
			<input type='button' value='Reset Plot' onclick="resetPlot()"/>
		</div>
		<div class="CMSelector">
			<label>Chips</label>
		<?php
			$selectChips = array();
			foreach($chips as $chip){
				$selectChips[$chip] = $chip;
			}
			echo $this->Form->select("chip",$selectChips,array("size"=>count($selectChips),"style"=>"width:150px;","empty"=>false,"label"=>"Chip"));
		?>
		</div>
	</div>

