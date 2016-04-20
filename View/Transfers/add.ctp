<?php
	$this->Html->addCrumb('Transfers', '/transfers/index/');
	$this->Html->addCrumb('add', '/transfers/add/');
?>


<?php
	$id = null;
	if(isset($this->request->data["Transfer"]["id"]))
		$id = $this->request->data["Transfer"]["id"];
	if(isset($transfer["Transfer"]["id"]))
		$id = $transfer["Transfer"]["id"];
?>

<style type="text/css">
	tr.attached{
		opacity: 0.5;
	}
</style>


<script type="text/javascript">
	$(document).ready(function(){
		$(".stockItemAmount").each(function(){
			var element = $(this);
			$(this).change(function(){
				$.getJSON(
					"<?php echo $this->Html->url(array("controller" => "transfers", "action" => "updateStockItemAmountOfTransfer")); ?>/<?php echo $id; ?>/"+element.attr("name").substr(6)+"/"+element.val(),
					function(data){
						if(data.success){
							//Display saved somewhere
							element.parent().animate({"background-color":"#00ff00"},500).animate({"background-color":"inherit"},500);
						}else{
							element.parent().animate({"background-color":"red"},500).animate({"background-color":"inherit"},500);
						}
					}
				);
			});
		});
		$(".removeFromTransfer").each(function(){
			var element = $(this);
			$(this).click(function(e){
				e.preventDefault();
				$.getJSON($(this).attr("href"),function(data){
					if(data.success){
						//Hide the parent row
						element.parent().parent().fadeOut(500);
						//Iterate through the next trs until the class "attached" is not set anymore
						var next = null;
						if(element.parent().parent().next().hasClass("attached")){
							var next = element.parent().parent().next();
						}
						while(next != null){
							next.fadeOut(500);
							if(next.next().hasClass("attached")){
								next = next.next();
							}else{
								next = null;
							}
						}

					}else{
						element.parent().parent().animate({"background-color":"red"},500).animate({"background-color":"inherit"},500);
					}
				});
			});
		});
		$("#re")
	});
</script>

<div class="transfers form">
<?php echo $this->Form->create('Transfer');?>
	<fieldset>

		<legend><?php  echo __('Selected Items: '); ?></legend>
		<div id="SelectedId">
			<?php //debug($transfer); ?>
			<?php if(!empty($transferItems)): ?>
				<?php //Output all the selected Item codes as hidden fields
				foreach($selectedItems as $itemId){
					echo "<input type='hidden' name='data[selectedItems][]' value='".$itemId."'>";
				}

				?>
			<table>
				<tr>
					<!-- <th>Id</th> -->
					<th>Code</th>
					<th>Tags</th>
					<th>State</th>
					<th>Quality</th>
					<th>Type</th>
					<th>Subtype</th>
					<th>Version</th>
					<th>Project</th>
					<th>Manufacturer</th>
					<th>Current Location</th>
					<th>Amount</th>
					<th>Actions</th>
				</tr>
				<?php foreach($transferItems as $item): ?>
				<tr>
					<!-- <?php if(!isset($item["Item"]["maxAmount"])):?>
						<td><?php echo $item['Item']['id'];?></td>
					<?php else: ?>
						<td>&nbsp;</td>
					<?php endif; ?> -->
					<td><?php echo $item['Item']['code'];?></td>
					<?php foreach($item["ItemTags"] as $id=>$itemTag) $item["ItemTags"][$id] = $itemTag["ItemTag"]["name"]; ?>
					<td><?php echo implode(", ",$item['ItemTags']);?></td>
					<td><?php echo $item['State']['name'];?></td>
					<td><?php echo $item['ItemQuality']['name'];?></td>
					<td><?php echo $item['ItemType']['name']; ?></td>
					<td><?php echo $item['ItemSubtype']['name']; ?></td>
					<?php
						//Defining the name displayed for a subtype version
						$sVName = ($item['ItemSubtypeVersion']['name'] != "")? $item['ItemSubtypeVersion']['version']." (".$item['ItemSubtypeVersion']['name'].")":$item['ItemSubtypeVersion']['version'];

					?>
					<td><?php echo $sVName; ?></td>
					<td><?php echo $item['Project']['name']; ?></td>
					<td><?php echo $item['Manufacturer']['name']; ?></td>
					<td><?php echo $item['Location']['name']; ?></td>
					<?php if(!isset($item["Item"]["maxAmount"])):?>
						<td><?php echo $item['Item']['amount']; ?></td>
					<?php else: ?>
						<td>
							<?php
								if(isset($transfer["Transfer"]["id"])):
							?>
							<select name='stock_<?php echo $item["Item"]["id"]; ?>' class='stockItemAmount'>
								<?php
								//Only allow selection of amount after the transfer has been created because otherwise it can't be stored anywhere
									for($i=1; $i<=$item["Item"]["maxAmount"];$i++){
										$selected = ($i==$item["Item"]["amount"])? "selected='selected'":"";
										echo "<option value='$i' $selected>$i</option>";
									}

								?>
							</select>
							<?php
								else:
									echo 1;
								endif;
							?>
							</td>
					<?php endif; ?>
					<td class="actions">
						<?php
							if(isset($transfer["Transfer"]["id"]))
								echo $this->Html->link(__('Remove'), array('action' => 'removeItemFromTransfer', $transfer["Transfer"]["id"], $item['Item']['id']),array("class"=>"removeFromTransfer"));
						?>
					</td>
				</tr>
				<?php if(!empty($item["Components"])) ?>
					<?php foreach($item["Components"] as $component): ?>
					<tr class='attached'>
						<!-- <?php if(strpos($component["Item"]["code"], "Stock") === false):?>
							<td><?php echo $component['Item']['id'];?></td>
						<?php else: ?>
							<td>&nbsp;</td>
						<?php endif; ?> -->
						<?php if(strpos($component["Item"]["code"], "Stock") === false):?>
							<td><?php echo $component['Item']['code'];?></td>
						<?php else: ?>
							<td>Stock Item</td>
						<?php endif; ?>
						<?php foreach($component["ItemTags"] as $id=>$itemTag) $component["ItemTags"][$id] = $itemTag["ItemTag"]["name"]; ?>
						<td><?php echo implode(", ",$component['ItemTags']);?></td>
						<td><?php echo $component['State']['name'];?></td>
						<td><?php echo $component['ItemQuality']['name'];?></td>
						<td><?php echo $component['ItemType']['name']; ?></td>
						<td><?php echo $component['ItemSubtype']['name']; ?></td>
						<?php
							//Defining the name displayed for a subtype version
							$sVName = $component['ItemSubtypeVersion']['version'];
							if($item['ItemSubtypeVersion']['name'] != ""){
								$sVName = $component['ItemSubtypeVersion']['version']."(".$item['ItemSubtypeVersion']['name'].")";
							}
						?>
						<td><?php echo $sVName ?></td>
						<td><?php echo $component['Project']['name']; ?></td>
						<td><?php echo $component['Manufacturer']['name']; ?></td>
						<td><?php echo $component['Location']['name']; ?></td>
						<td><?php echo $item['Item']['amount']; ?></td>
						<td class="actions">
							attached
						</td>
					</tr>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</table>
			<!-- <div class="actions" style="width: 100%; text-align: right">
				<a id="RemoveAllItemsFromTransfer" href="#">Remove all items</a>
			</div> -->
			<?php else: ?>
				<div class="cake-error">No items selected</div>
			<?php endif; ?>
		</div>
	<div class="actions" style="width: 100%">
	<?php
		if(isset($transfer["Transfer"]["id"]))
			echo $this->Html->link('Add Items', array('controller' => 'items', 'action' => 'index'));
		else {
			echo "Changing of stock item amounts and adding of items to the transfer can only be performed once the transfer has been saved.";
		}
		?>
	</div>
		<legend><?php  echo __('Transfer data: '); ?></legend>
	<?php
		$to_location_id = null;
		if(!empty($transfer['Transfer']['to_location_id']))
			$to_location_id = $transfer['Transfer']['to_location_id'];

		$from_location_id = 4;
		if(!empty($transfer['Transfer']['from_location_id']))
			$from_location_id  = $transfer['Transfer']['from_location_id'];
		$recipient = null;
		if(!empty($transfer['Transfer']['recipient_id']))
			$recipient  = $transfer['Transfer']['recipient_id'];

		$tracking_number = null;
		if(!empty($transfer['Transfer']['tracking_number']))
			$tracking_number = $transfer['Transfer']['tracking_number'];

		$link = null;
		if(!empty($transfer['Transfer']['link']))
			$link = $transfer['Transfer']['link'];

		$deliverer_id = null;
		if(!empty($transfer['Transfer']['deliverer_id']))
			$deliverer_id = $transfer['Transfer']['deliverer_id'];

		$comment = null;
		if(!empty($transfer['Transfer']['comment']))
			$comment = $transfer['Transfer']['comment'];

		$shipping_date_month = null;
		if(!empty($transfer['Transfer']['shipping_date'])){
			if(!is_array($transfer["Transfer"]["shipping_date"])){
				$shipping_date_month = date("m",strtotime($transfer['Transfer']['shipping_date']));
				$shipping_date_day = date("d",strtotime($transfer['Transfer']['shipping_date']));
				$shipping_date_year = date("Y",strtotime($transfer['Transfer']['shipping_date']));
			}else{
				$shipping_date_month = $transfer['Transfer']['shipping_date']['month'];
				$shipping_date_day = $transfer['Transfer']['shipping_date']['day'];
				$shipping_date_year = $transfer['Transfer']['shipping_date']['year'];
			}
		}else{
				$shipping_date_month = date("m");
				$shipping_date_day = date("d");
				$shipping_date_year = date("Y");
		}

		if($shipping_date_day!=null && $shipping_date_month!=null && $shipping_date_year!=null)
			$shipping_date = array(	'day'	=> $shipping_date_day,
									'month' => $shipping_date_month,
									'year'	=> $shipping_date_year);
		if(isset($transfer["Transfer"]["id"])){
			echo $this->Form->hidden("id",array("value"=>$transfer["Transfer"]["id"]));
		}
		echo '<table>';
		echo '<tr>';
		echo '<th style = "width: 10%"> </th>';
		echo '<th> </th>';
		echo '</tr>';

		echo $this->Html->tableCells(array(
			array(array('Origin', array('class' => 'highlight')),$toLocations[$from_location_id].$this->Form->hidden('from_location_id', array(
															'value' => $from_location_id))), //Hardcoded unknown location id
			array(array('Destination', array('class' => 'highlight')),$this->Form->input('to_location_id', array(
															'style' => 'width: 32%',
															'label' => false,
															'default' => $to_location_id))),
			array(array('Recipient', array('class' => 'highlight')),$this->Form->input('recipient_id', array(
															'style' => 'width: 32%',
															'label' => "The selected recipient will receive an email once the transfer is sent. Likewise you will receive an email once it is received. ",
															'default' => $recipient))),
			array(array('Tracking Number', array('class' => 'highlight')), $this->Form->input('tracking_number', array(
															'label' => false,
															'default' => $tracking_number))),
			array(array('URL', array('class' => 'highlight')),$this->Form->input('link', array(
															'label' => false,
															'default' => $link))),
			array(array('Deliverer', array('class' => 'highlight')),$this->Form->input('deliverer_id', array(
															'label' => false,
															'style' => 'width: 32%',
															'default' => $deliverer_id))),
			array(array('Comment', array('class' => 'highlight')),$this->Form->input('comment', array(
															'label' => false,
															'type' => 'textarea',
															'default' => $comment))),
			array(array('Shipping date', array('class' => 'highlight')),$this->Form->input('shipping_date', array(
															'label' => false,
															'dateFormat' => 'MDY',
															'selected' => $shipping_date,
															'type' => 'date',
															'style' => 'width: 10%',
															'separator' => ' - ')))
		));
		echo '</table>';
	?>

	</fieldset>
<?php
	echo $this->Form->end(__('Save'));
	// echo "<div class='actions'>".$this->Form->postLink("Send",array("controller"=>"transfers","action"=>"send",$transfer["Transfer"]["id"]),
			// array(
				// "class"=>"button",
				// "confirm"=>"This will send the selected Items on their way thereby moving them to a different location and preventing the editing of this Transfer. Are you sure you want to do that?"))
	// ."</div>";

?>
</div>

<?php
// $this->Js->get('#RemoveAllItemsFromTransferId')->event('click',
	// $this->Js->request(array(
		// 'controller'=>'transfers',
		// 'action'=>'removeAllItemsFromTransfer',
		// ), array(
		// 'update'=>'#SelectedId',
		// 'async' => true,
		// 'method' => 'post',
		// 'dataExpression'=>true,
		// 'data'=> $this->Js->serializeForm(array(
			// 'isForm' => true,
			// 'inline' => true
			// ))
		// ))
	// );
?>


<div id='verticalmenu'>
	<h2><?php echo __('Pending Transfer'); ?></h2>
	<?php if(isset($transfer["Transfer"]["id"])): ?>
	<ul>
		<li class='active'><?php echo $this->Form->postLink("Send",array("controller"=>"transfers","action"=>"send",$transfer["Transfer"]["id"]),
			array(
				"class"=>"button",
				"confirm"=>"This will send the selected Items on their way thereby moving them to a different location and preventing the editing of this Transfer. Are you sure you want to do that?"));?> </li>
	</ul>
	<?php endif; ?>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
