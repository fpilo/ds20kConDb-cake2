<?php
	$this->Html->addCrumb('Transfers', '/transfers/index/');
?>
<script type="text/javascript">
	var overlayCloseButton = '<div style="float: right;"><?php echo $this->Html->image("ElegantBlueWeb/xmark.png",array("alt"=>"X","width"=>"20","onClick"=>"closeOverlay();","style"=>"cursor:pointer; z-index:5;")); ?></div>';
	function openOverlay(elem,url,pos){
		//position contains object with top and left of the button that has been clicked
		var size = {width:1000,height:400}; //Define the default size of the overlay

		//Setting position of overlay
		pos.left = $(".transfers:first").offset().left;
		pos.top = pos.top+$(elem).height(); //adding height of element to top position so it is displayed below

		//Overwriting width of overlay to match position of table cell
		size.width = $(".transfers:first").width()-(window.innerWidth-$(elem).parent().next().offset().left);

		var targetPos = {width:size.width, height:size.height, left:pos.left, top:pos.top};
		//Create the overlay and attach it to the body
		if($("#overlay").attr("id") == undefined){
			$("#content").append("<div id='overlay'></div>");
		}
		$("#overlay").attr("style","top:"+pos.top+"px; left:"+pos.left+"px; width:0px; height:0px;");
		console.log(url);
		$("#overlay").load(url,function(){
			$("#overlay").prepend(overlayCloseButton);
		});
		$("#overlay").animate(targetPos,300);
	}
	function closeOverlay(){
		targetPos = $("#overlay").offset();
		targetPos.left += $("#overlay").width();
		targetPos.top += "0";
		targetPos.left += "px";
		targetPos.top += "px";
		targetPos.width = "0px";
		targetPos.height = "0px";
		$("#overlay").html("").animate(targetPos,300,function(){
			$("#overlay").remove();
		});

	}
$(function(){
		$(".transfer_items").click(function(event){
			event.preventDefault();
			var url = "transfers/transferItems/"+$(this).attr("value");
			var pos = $(this).offset();
			openOverlay(this,url,pos);
			targetRow = $(this).parent().parent();
		});
	});
</script>
<?php
	$header = array("Shipping Date","From","To","Deliverer",array("Items"=>array("style"=>"width:300px;")),"Comment",array("Actions"=>array("class"=>"actions")));

	function getTransferItems($arr){
		$items = array();
		foreach($arr as $item){
			if($item["ItemsTransfer"]["is_part_of"] != null) continue;
			$items[] = (strpos($item["code"], "Stock") !== false)?"Stock Item":$item["code"];
		}
		return $items;
	}

?>
<div class="transfers index">
	<h3>Pending (from your current standard location)</h3>
	<table>
		<?php echo $this->Html->tableHeaders($header);
		foreach ($pending_transfers as $transfer){
//			debug($transfer);
			$sendLink = $this->Form->postLink("Send",array("controller"=>"transfers","action"=>"send",$transfer["Transfer"]["id"]),array("class"=>"button","confirm"=>"This will send the selected Items on their way thereby moving them to a different location and preventing the editing of this Transfer. Are you sure you want to do that?"));
			$editLink = $this->Html->link(__('Edit or Add Items'), array('action' => 'add', $transfer['Transfer']['id']));
			echo $this->Html->tableCells(array(
				h(substr($transfer['Transfer']['shipping_date'],0,10)),
				$this->Html->tableLink($transfer["From"]['name']." (".$transfer["User"]['first_name']." ".$transfer["User"]['last_name'].")", array('controller' => 'locations', 'action' => 'view', $transfer["From"]['id'])),
				$this->Html->tableLink($transfer["To"]['name']." (".$transfer["Recipient"]['first_name']." ".$transfer["Recipient"]['last_name'].")", array('controller' => 'locations', 'action' => 'view', $transfer["To"]['id'])),
				$this->Html->tableLink($transfer['Deliverer']['name'], array('controller' => 'deliverers', 'action' => 'view', $transfer['Transfer']['deliverer_id'])),
				"<div class='transfer_items' value='".$transfer["Transfer"]["id"]."'>".implode(", ", getTransferItems($transfer["Item"]))."</div>",
//				h($transfer['Transfer']['tracking_number']),
				h($transfer['Transfer']['comment']),
				array($editLink.$sendLink,array("class"=>"actions"))
			));
		}
		?>
	</table>

	<?php if(count($in_transit_standard_transfers)>0):?>
		<h3>Transfers currently in transit to your standard location</h3>
		<table>
			<?php echo $this->Html->tableHeaders($header);
			foreach ($in_transit_standard_transfers as $transfer){
				$viewLink = $this->Html->link(__('View'), array('action' => 'view', $transfer['Transfer']['id']));
				if(in_array($transfer["To"]["id"],$usersLocations))
					$receiveLink = $this->Form->postLink("Receive",array("controller"=>"transfers","action"=>"receive",$transfer["Transfer"]["id"]),array("class"=>"button","confirm"=>"This will move all the Items in this Transfer to the Location ".$transfer["To"]['name']." and mark this transfer as completed. "));
				else
					$receiveLink = "";
				echo $this->Html->tableCells(array(
					h(substr($transfer['Transfer']['shipping_date'],0,10)),
					$this->Html->tableLink($transfer["From"]['name']." (".$transfer["User"]['first_name']." ".$transfer["User"]['last_name'].")", array('controller' => 'locations', 'action' => 'view', $transfer["From"]['id'])),
					$this->Html->tableLink($transfer["To"]['name']." (".$transfer["Recipient"]['first_name']." ".$transfer["Recipient"]['last_name'].")", array('controller' => 'locations', 'action' => 'view', $transfer["To"]['id'])),
					$this->Html->tableLink($transfer['Deliverer']['name'], array('controller' => 'deliverers', 'action' => 'view', $transfer['Transfer']['deliverer_id'])),
					"<div class='transfer_items' value='".$transfer["Transfer"]["id"]."'>".implode(", ", getTransferItems($transfer["Item"]))."</div>",
//					h($transfer['Transfer']['tracking_number']),
					h($transfer['Transfer']['comment']),
					array($viewLink.$receiveLink,array("class"=>"actions"))
				));
			}
			?>
		</table>


	<?php endif; ?>
	<h3>Other transfers currently in transit</h3>
	<table>
		<?php echo $this->Html->tableHeaders($header);
		foreach ($in_transit_transfers as $transfer){
			$viewLink = $this->Html->link(__('View'), array('action' => 'view', $transfer['Transfer']['id']));
			echo $this->Html->tableCells(array(
				h(substr($transfer['Transfer']['shipping_date'],0,10)),
				$this->Html->tableLink($transfer["From"]['name'], array('controller' => 'locations', 'action' => 'view', $transfer["From"]['id'])),
				$this->Html->tableLink($transfer["To"]['name'], array('controller' => 'locations', 'action' => 'view', $transfer["To"]['id'])),
				$this->Html->tableLink($transfer['Deliverer']['name'], array('controller' => 'deliverers', 'action' => 'view', $transfer['Transfer']['deliverer_id'])),
				"<div class='transfer_items' value='".$transfer["Transfer"]["id"]."'>".implode(", ", getTransferItems($transfer["Item"]))."</div>",
//				h($transfer['Transfer']['tracking_number']),
				h($transfer['Transfer']['comment']),
				array($viewLink,array("class"=>"actions"))
			));
		}
		?>
	</table>
	<h3>Completed</h3>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('shipping_date');?></th>
			<th><?php echo $this->Paginator->sort('from_location_id');?></th>
			<th><?php echo $this->Paginator->sort('to_location_id');?></th>
			<th><?php echo $this->Paginator->sort('deliverer_id');?></th>
			<th style='width:300px;'>Items</th>
			<th>Comment</th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	//echo debug($transfers);
	foreach ($completed_transfers as $transfer): ?>
	<tr>
		<td><?php echo h(substr($transfer['Transfer']['shipping_date'],0,10)); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->tableLink($transfer["From"]['name'], array('controller' => 'locations', 'action' => 'view', $transfer["From"]['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->tableLink($transfer['To']['name'], array('controller' => 'locations', 'action' => 'view', $transfer['Transfer']['to_location_id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->tableLink($transfer['Deliverer']['name'], array('controller' => 'deliverers', 'action' => 'view', $transfer['Transfer']['deliverer_id'])); ?>
		</td>

		<td><?php echo "<div class='transfer_items' value='".$transfer["Transfer"]["id"]."'>".implode(", ", getTransferItems($transfer["Item"]))."</div>"; ?>&nbsp;</td>
		<td><?php echo h($transfer['Transfer']['comment']); ?>&nbsp;</td>
		<td class="actions">
			<?php //echo $this->Html->link(__('Recieved'), array('action' => 'recieved', $transfer['Transfer']['id'])); ?>
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $transfer['Transfer']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $transfer['Transfer']['id'])); ?>
			<?php //echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $transfer['Transfer']['id']), null, __('Are you sure you want to delete # %s?', $transfer['Transfer']['id'])); ?>
		</td>
	</tr>
	<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Transfers'); ?></h2>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>