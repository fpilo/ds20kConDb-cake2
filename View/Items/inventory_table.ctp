<!--
	Script to allow selection of multiple rows, storing the selection in the session and moving on to a second page where actions can be performed
	*) assigning tags to multiple items at once
-->
<script type="text/javascript">
       
	var locationIds = JSON.parse('<?php echo json_encode($locations); ?> ');
	$(function(){
		initializeItemSelector();
      bindColumnChangeFunction();
		// $("#TransferAddForm").submit(function(){
			// $("#selectedItems").appendTo("#TransferAddForm");
			// // $("#TransferAddForm").submit();
			// // $("#selectedItems").appendTo("#ItemsChangeMultipleForm");
		// })
	});

	function initializeItemSelector(){
		var data = getArrayFromSessionStorage("selectedItems");
		$(".itemId").each(function(){
			var element = $(this);
			element.click(function(){
				toggleSelected(element);
			});
			//mark if set in sessionStorage
			$(data).each(function(id,value){
				if(value.id == element.attr("value") && value.location == element.parent().parent().find(".itemLocation").attr("value")){
					if(element.is(':checked')){
						element.prop("checked",false);
					}
					element.click();
				}
			});
		});
		updateSelectedItemsDisplay();
	}

	function toggleVisibleSelection(){
		$(".itemId").click();
	}

	function toggleSelected (element) {
      //console.log($(element).attr("value"),$(element).parent().parent().find(".itemCode").attr("value"));
      var epp = $(element).parent().parent();
      var item_location = epp.find(".itemLocation").attr("value");
      var item_id = $(element).attr("value");
		if($(element).parent().hasClass("selected")){
			removeMarkFromSession(item_id,item_location);
			epp.find("td").each(function(){
				$(this).removeClass("selected");
			})
		}else{
         var item_code = epp.find(".itemCode").attr("value");
			saveMarkToSession(item_id,item_code,item_location,epp.hasClass("attached"),epp.hasClass("transfer_pending"));
			epp.find("td").each(function(){
				$(this).addClass("selected");
			})
		}
		updateSelectedItemsDisplay();
	}

	function updateSelectedItemsDisplay(){
		var content = "";
		$(getArrayFromSessionStorage("selectedItems")).each(function(id,value){
			content += "<div class='selected' value='"+value.id+"' location='"+value.location+"'>"+value.code+" at "+value.location+"<input type='hidden' value='"+value.id+"' name='data[selectedItems][]'></div>";
		})
		$("#selectedItems").html(content);
		$("#selectedItems .selected").each(function(){
			var id = $(this)
			$(this).click(function(){
				var found = false;
				$(".items td.selected input").each(function(){
					if($(this).attr("value") == id.attr("value") && $(this).parent().parent().find(".itemLocation").attr("value") == id.attr("location")){
						$(this).click();
						found = true;
					}
				});
				if(!found){
					removeMarkFromSession(id.attr("value"),id.attr("location"));
					id.remove();
					updateSelectedItemsDisplay();
				}
			})
		});
		if(content != ""){
			$("#ItemsChangeMultipleTags").show();
			$("#ItemsChangeMultipleChecklists").show();
			$("#ItemsAddMultipleToTransfer").show();
			$("#ItemsChangeMultipleReset").show();
		}else{
			$("#ItemsChangeMultipleTags").hide();
			$("#ItemsChangeMultipleChecklists").hide();
			$("#ItemsAddMultipleToTransfer").hide();
			$("#ItemsChangeMultipleReset").hide();
		}
		changeVisibilityOfTransferButtons();
	}

	function saveMarkToSession(id,code,location,attached,transfer_pending){
		//get currently set session storage
		var data = getArrayFromSessionStorage("selectedItems");
		if(data == null){
			//No array yet set, create new one
			data = new Array();
		}
		var pos = -1;
		$(data).each(function(id2,value){
			if(value.id == id && value.code == code && value.location == location){
				pos = id2;
			}
		});
		if(pos==-1){
			data.push({"id":id,"code":code,"location":location,"attached":attached,"transfer_pending":transfer_pending});
		}
		saveArraytoSessionStorage("selectedItems",data);
	}

	function removeMarkFromSession(id,location){
		var data = getArrayFromSessionStorage("selectedItems");
		if(data == null){
			//No array yet set therefore nothing can be removed
			return
		}
		//get element position
		var pos = -1;
		$(data).each(function(id2,value){
			if(value.id == id && value.location == location){
				pos = id2;
			}
		});
      //console.log(id,location,pos,data);
		if(~pos){ //~ is bitwise NOT
			data.splice(pos,1);
		}
		saveArraytoSessionStorage("selectedItems",data);
	}

	function getArrayFromSessionStorage(key){
		return JSON.parse( sessionStorage.getItem(key) ); //Gets an item from the sessionStorage and returns it as an javascript array
	}
	function saveArraytoSessionStorage(key,array){
		return sessionStorage.setItem(key,JSON.stringify(array)); //Saves an item as an Array to the sessionStorage
	}
	function removeAllSelected(){
		do{
			$("#selectedItems .selected").first().click();
		}while($("#selectedItems .selected").length != 0)
	}

	function changeVisibilityOfTransferButtons(){
		var data = getArrayFromSessionStorage("selectedItems");
		if(data == null){
			//No array yet set therefore nothing can be removed
			return
		}
		//get element position
		var pos = -1;
		var locations = new Array();
		var containsAttached = false;
      var containsPendingTransfer = false;
      var pt = '';
		$(data).each(function(id2,value){
			if(locations.indexOf(value.location)==-1){
				locations.push(value.location);
			}
			if(value.attached){ //If there is one attached item within the array set the defining variable to true
				containsAttached = true;
			}
         if(value.transfer_pending){
            containsPendingTransfer = true;
            pt+= value.code+" ";
         }
		});
      if(containsPendingTransfer) {
         $('#transfer_pending_warning').show();
         $('#transfer_pending_warning span').text(pt);
      }else{
         $('#transfer_pending_warning').hide();
      }
		if(locations.length!= 1 || containsAttached){
			//No unique location
			// console.log("no unique location");
			$("#ItemsAddMultipleToTransfer .transferFrom").each(function(){
				if($(this).attr("value") != null){
					$(this).hide();
				}else{
					$(this).show(); //Shows the error message
				}
			});
		}else{
			//Only show transfers from this location and hide the rest
			// console.log("location is: "+locations[0]);
			$("#ItemsAddMultipleToTransfer .transferFrom").each(function(){
				if(locationIds[$(this).attr("value")] != locations[0]){
					$(this).hide();
				}else{
					$("#transferFromId").val($(this).attr("value"));
					$(this).show();
				}
			});
		}

	}

	function addToNewTransfer(transferId){
		if(typeof transferId == 'undefined') transferId = "";
		//Change target of form
		$("#ItemsChangeMultipleForm").attr("action","<?php echo $this->Html->url(array("controller"=>"Transfers","action"=>"add"))?>/"+transferId)
		//submit form
		$("#ItemsChangeMultipleForm").submit();
		//Unset the stored values in the session variable so nothing is selected anymore in the inventory
		saveArraytoSessionStorage("selectedItems",new Array());
	}

	function changeTagsForItems(){
		//Change target of form
		$("#ItemsChangeMultipleForm").attr("action","<?php echo $this->Html->url(array("controller"=>"Items","action"=>"changeMultiple"))?>")
		//submit form
		$("#ItemsChangeMultipleForm").submit();
	}
	function changeChecklistsForItems(){
		//Change target of form
		$("#ItemsChangeMultipleForm").attr("action","<?php echo $this->Html->url(array("controller"=>"Items","action"=>"changeMultipleChecklists"))?>")
		//submit form
		$("#ItemsChangeMultipleForm").submit();
	}
   function bindColumnChangeFunction() {
				$("#ItemIndexColForm input").bind("click", function (event) {
					$("#results").slideUp(300);
					$.ajax({data:$("#ItemIndexColForm, #ItemIndexForm").serialize(),
							dataType:"html",
							success:function (data, textStatus) {
								$("#results").html(data).slideDown(300);
							},
							type:"post",
							url:document.location.href}
					);
					return false;
				});
   }
</script>
<style type="text/css">
	#results .selected{
		background-color:lightgreen;
	}

	#selectedItems{
		display: inline-block;
	}
	#selectedItems .submit{
		float:right;
	}
	#selectedItems div{
		clear: none;
	}
	#selectedItems .selected{
		float: left;
		padding:5px;
		margin:5px;
		border-radius:5px;
		cursor: pointer;
	}

	#tbl .itemId{
		cursor: pointer;
	}
	.inventoryButton{
		margin:5px;
	}
	.attached{
		opacity: 0.5;
		/*background-color:#cccccc !important;*/
	}
	#itemSelection, #itemActions, #ItemsAddMultipleToTransfer {
		float:left;
		margin: 5px;
		/*border: solid 1px black;*/
	}
	#itemSelection{

	}
	#itemActions{

	}
	#ItemsAddMultipleToTransfer input[type=button]{
		margin: 5px;
	}
   #transfer_pending_warning {
      margin: 5px;
      padding: 5px;
      color: red;
      font-size: larger;
      border: 2px red solid;
      border-radius: 5px;
      display: none; /* so it doesn't flash when loading the page */
   }
   #columns_table td, #columns_table th {
      padding: 0;
      text-align: center;
   }
   #columns_table td input {
      margin: 0;
   }
</style>
<div id="results">
	To select multiple items click the corresponding check box. Click again to deselect. <br />
	Selections are kept when the page is changed.
	<?php echo $this->Form->create("Items",array("action"=>"changeMultiple","class"=>"continueWithItems")); ?>
	<div id="selectedItems" style="margin-bottom:0px;">
		&nbsp;
	</div>
	<input type='hidden' name='transferFromId' value='' id='transferFromId' />
	</form>
	<?php
		echo "<div id='itemSelection'>";
		echo "<input type='button' value='".__('Deselect All')."' onClick='removeAllSelected()' id='ItemsChangeMultipleReset' class='inventoryButton' ><br />";
		echo "<input type='button' value='".__('Toggle visible selection')."' onClick='toggleVisibleSelection()' id='ItemToggleVisible' class='inventoryButton'>";
		echo "</div>";
		echo "<div id='itemActions'>";
		echo "<input type='button' value='".__('change Tags for these items')."' onClick='changeTagsForItems();' id='ItemsChangeMultipleTags' class='inventoryButton'><br />";
		echo "<input type='button' value='".__('change Checklists for these items')."' onClick='changeChecklistsForItems();' id='ItemsChangeMultipleChecklists' class='inventoryButton'>";
		echo "</div>";
		echo "<div id='ItemsAddMultipleToTransfer'>";
      echo "<div id='transfer_pending_warning'>Warning: <span></span> already marked for a transfer</div>";
         // count($pendingTransfers)==1 as of the ItemsController and
         // $fromId is the user standard location
			foreach($pendingTransfers as $fromId=>$transfers){
				echo "<div class='transferFrom' style='display:none' value='".$fromId."'>";
				echo "<input type='button' value='".sprintf(__('Add to new Transfer from %s'),$locations[$fromId])."' onClick='addToNewTransfer();' style='display:block;'>";
				foreach($transfers as $transfer){
					echo "<input type='button' value='".sprintf(__('Add to existing Transfer from %s to %s planned on %s'),$transfer["From"]["name"],$transfer["To"]["name"],substr($transfer["Transfer"]["shipping_date"],0,10))."' onClick='addToNewTransfer(".$transfer["Transfer"]["id"].");' style='display:block;'>";
				}
				echo "</div>";
			}
	   echo "<div class='transferFrom' style='width:350px;'>".__('You can only select items from a single location and you must not select attached items if you want to add them to a transfer.')."</div>";
		echo "</div>";
	?>

	<?php echo $this->Session->flash(); ?>
<div id="change_columns">
<?php
   echo $this->Form->create('Item', array('style' => 'width: 100%', 'type' => 'get', 'id'=>'ItemIndexColForm'));
   echo '<table id="columns_table"><tr>';
   echo '<th>Column:</th>';
   foreach($columns as $col) {
      echo '<th>'.$col['title'].'</th>';
   }
   echo '</tr><tr>';
   echo '<td>Display:</td>';
   foreach($columns as $col_key=>$col) {
      $checked = $col['display'] ? ' checked="checked"' : '';
      echo '<td>
            <input type="hidden" name="cols['.$col_key.']" value="0">
            <input type="checkbox" name="cols['.$col_key.']" value="1"'.$checked.'>
            </td>';
   }
   echo '</tr></table>';
   echo $this->Form->end();
?>
</div>
	<table cellpadding="0" cellspacing="0" id="tbl">
		<tr>
			<th>&nbsp;</th>
			<th><?php echo $this->Paginator->sort('code');?></th>
			<th><?php echo $this->Paginator->sort('location_id');?></th>
         <?php foreach($columns as $col) if($col['display']) {
                  echo '<th>';
                  if($col['sort_key']) echo $this->Paginator->sort($col['sort_key'],$col['title']);
                  else echo $col['title'];
                  echo '</th>';
               }
         ?>
			<th class="actions"><?php echo __('Actions');?></th>
		</tr>
	<?php
	foreach ($items as $item): ?>
	<?php #debug($item); ?>
		<tr class="items <?php if($item["isAttached"]!=-1){echo "attached ";} if($item["TransferPending"]){echo "transfer_pending";} ?>">
			<td><?php echo $this->Form->checkbox('itemId',array("value"=>$item['ItemView']['id'], "class"=>'itemId','hiddenField'=>false)); ?></td>
			<?php
				//If is stock write more data into the value field so the type, quality and tags are displayed as well on top.
				if(strpos($item["ItemView"]["code"], "Stock") === 0){
					$value = $item['ItemView']['code']." of ".$item['ItemView']['item_subtype_name']." v".$item['ItemView']['item_subtype_version']; //." ".implode(" ", $item["ItemTag"]);
				}else{
					$value = $item['ItemView']['code'];
				}

			?>
			<td class='itemCode' value='<?php echo $value; ?>'>
				<?php echo $this->Html->tableLink($item['ItemView']['code'], array('controller' => 'items', 'action' => 'view', $item['ItemView']['id'])); ?>
			</td>
			<td class='itemLocation' value='<?php echo $item['ItemView']['location_name'];?>'>
            <?php if($item['TransferPending']) $htp='*'; else $htp=''; ?>
				<?php echo $this->Html->tableLink($item['ItemView']['location_name'].$htp, array('controller' => 'locations', 'action' => 'view', $item['ItemView']['location_id'])); ?>
			</td>
         <?php 
            foreach($columns as $col) if($col['display']) {
               if($col['file']) require(dirname(__FILE__).'/inventory_cols/'.$col['file']); 
               else { 
                  echo '<td>';
                  echo $this->Html->tableLink($item['ItemView'][$col['link_text']],
                        array('controller' => $col['controller'], 'action' => 'view', $item['ItemView'][$col['id']])); 
                  echo '</td>';
               }
            }
         ?>
			<td class="actions">
				<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $item['ItemView']['id'], $item['ItemView']['code']), null, __('Are you sure you want to delete "%s"?', $item['ItemView']['code'])); ?>
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

	<div class="paging" id="Navigator">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
