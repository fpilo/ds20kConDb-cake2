<?php
	$this->Html->addCrumb("Stock ".$item['ItemSubtype']['name']." v".$item['ItemSubtypeVersion']['version'], '/items/view/'.$item['Item']['id']);

?>

<script type="text/javascript">
			$(function(){
				restoreTabs('stockItemTabIndex');
			});
</script>

<div class="items view">

	<div id="tabs" class='related'>
		<ul>
			<li><a href="#information"><?php echo __('Item Information'); ?></a></li>
			<li><a href="#histories"><?php echo __('History'); ?></a></li>
			<li><a href="#files"><?php echo __('Files');?></a></li>
			<li><a href="#transfers"><?php echo __('Transfers');?></a></li>
		</ul>

		<div id="information">
			<dl>
				<dt><?php echo __('Item Type'); ?></dt>
				<dd>
					<?php echo $this->Html->link($item['ItemType']['name'], array('controller' => 'item_types', 'action' => 'view', $item['ItemType']['id'])); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Item Subtype'); ?></dt>
				<dd>
					<?php echo $this->Html->link($item['ItemSubtype']['name'], array('controller' => 'item_subtypes', 'action' => 'view', $item['ItemSubtype']['id'])); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Version'); ?></dt>
				<?php
					//Defining the name displayed for a subtype version
					$sVName = $item['ItemSubtypeVersion']['version'];
					if($item['ItemSubtypeVersion']['name'] != ""){
						$sVName = $item['ItemSubtypeVersion']['version']." (".$item['ItemSubtypeVersion']['name'].")";
					}
				?>
				<dd>
					<?php echo $this->Html->link($sVName, array('controller' => 'item_subtype_versions', 'action' => 'view', $item['ItemSubtypeVersion']['id'])); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Tags'); ?></dt>
				<dd>
					<?php foreach($item["ItemTag"] as $tag): ?>
					<?php echo $this->Html->link($tag['name'], array('controller' => 'item_tags', 'action' => 'view', $tag["id"])); ?>
					<?php endforeach; ?>
					<?php echo $this->Html->image('ElegantBlueWeb/settings.png', array(
																				'title' => 'Change tags',
																				'alt' => __('edit'),
																				'border' => '0',
																				'width'=>'16',
																				'height'=>'16',
																				'url' => array('controller' => 'items', 'action' => 'changeTags', $item['Item']['id'])));
					?>&nbsp;
				</dd>
				<?php if (!empty($item['ItemSubtypeVersion']['Component'])): ?>
				<dt><?php echo __('Components Tags'); ?></dt>
				<?php
					$tmpTags = array();
					foreach($item["Component"] as $component){
						if(($component['ItemComposition']['valid'] == TRUE) && $component['ItemComposition']['component_id'] != null){
							foreach($component["ItemTag"] as $itemTag){
								$tmpTags[] = $this->Html->tableLink($itemTag["name"],array("controller"=>"item_tags","action"=>"view",$itemTag["id"]));
							}
						}elseif(($component['ItemComposition']['valid'] == TRUE) && $component['ItemComposition']['component_id'] == null) {
							foreach($component["Stock"]["StockTag"] as $itemTag){
								$tmpTags[] = $this->Html->tableLink($itemTag["name"],array("controller"=>"item_tags","action"=>"view",$itemTag["id"]));
							}
						}
					}
					$tmpTags = array_unique($tmpTags);
					$tags = implode(", ", $tmpTags);
				?>
				<dd>
					<?php echo $tags; ?>&nbsp;
				</dd>
				<?php endif; ?>
				<dt><?php echo __('Quality'); ?></dt>
				<dd>
					<?php echo $this->Html->link($item['ItemQuality']['name'], array('controller' => 'item_tags', 'action' => 'view', $item['ItemQuality']["id"])); ?>
					<?php echo $this->Html->image('ElegantBlueWeb/settings.png', array(
																				'title' => 'Change tags',
																				'alt' => __('edit'),
																				'border' => '0',
																				'width'=>'16',
																				'height'=>'16',
																				'url' => array('controller' => 'items', 'action' => 'changeQuality', $item['Item']['id'])));
					?>
					&nbsp;
				</dd>
				<dt><?php echo __('State'); ?></dt>
				<dd>
					<?php echo $this->Html->link($item['State']['name'], array('controller' => 'states', 'action' => 'view', $item['State']['id'])); ?>
					&nbsp;
					<?php echo $this->Html->image('ElegantBlueWeb/settings.png', array(
																				'title' => 'Change state',
																				'alt' => __('edit'),
																				'border' => '0',
																				'width'=>'16',
																				'height'=>'16',
																				'url' => array('controller' => 'items', 'action' => 'changeState', $item['Item']['id'])));
					?>

					&nbsp;
				</dd>
				<dt><?php echo __('Manufacturer'); ?></dt>
				<dd>
					<?php echo $this->Html->link($item['Manufacturer']['name'], array('controller' => 'manufacturers', 'action' => 'view', $item['Manufacturer']['id'])); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Project'); ?></dt>
				<dd>
					<?php echo $this->Html->link($item['Project']['name'], array('controller' => 'projects', 'action' => 'view', $item['Project']['id'])); ?>
					&nbsp;
					<?php echo $this->Html->image('ElegantBlueWeb/settings.png', array(
																				'title' => 'Change project',
																				'alt' => __('edit'),
																				'border' => '0',
																				'width'=>'16',
																				'height'=>'16',
																				'url' => array('controller' => 'items', 'action' => 'changeProject', $item['Item']['id'])));
					?>
					&nbsp;
				</dd>
				<dt><?php echo __('Comment'); ?></dt>
				<dd>
					<?php echo $item['Item']['comment'];
					echo $this->Html->image('ElegantBlueWeb/settings.png', array(
																				'title' => 'Edit comment',
																				'alt' => __('edit'),
																				'border' => '0',
																				'width'=>'16',
																				'height'=>'16',
																				'url' => array('controller' => 'items', 'action' => 'changeComment', $item['Item']['id'])));
			 		?>
				&nbsp;
				</dd>
				<table style='width:350px'>
				<?php
					echo $this->Html->tableHeaders(array("Location","Amount"));
					foreach($locationWithAmount as $element){
						echo $this->Html->tableCells(array(
							array(
								$this->Html->link($element['Location']['name'], array('controller' => 'locations', 'action' => 'view', $element['Location']['id'])),
								"<span class='amount'>".$element['ItemStocks']['amount']."</span> ".$this->Html->image('ElegantBlueWeb/settings.png', array(
																				'title' => 'Change amount',
																				'alt' => __('edit'),
																				'border' => '0',
																				'width'=>'16',
																				'height'=>'16',
																				'onclick' => "changeAmount(this,".$item['Item']['id'].",\"setStockAmount\",".$element['Location']['id'].",".$element['ItemStocks']['amount'].")"))
							)
						));
					}
				?>
				</table>
			</dl>
			<table style='width:auto;'>
					<tr>
						<th>
							Parameter
						</th>
						<th>
							Value
						</th>
						<th>
							Comment
						</th>
						<th>
							Date and Time
						</th>
					</tr>
				<?php foreach($item["ItemsParameters"] as $itemParameter): ?>
					<tr>
						<td>
							<?php echo $itemParameter["Parameter"]["name"]; ?>
						</td>
						<td>
							<?php echo $itemParameter["value"]; ?>
						</td>
						<td>
							<?php echo $itemParameter["comment"]; ?>
						</td>
						<td>
							<?php echo $itemParameter["timestamp"]; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				<tr id='newItemParameter'>
					<td style="min-width:200px;">
						<div class="ui-widget">
						<select name='data[parameter]' id='combobox'>
							<option disabled selected>--select an option--</option>
						<?php foreach($parameters as $pId=>$pName): ?>
							<option value="<?php echo $pId; ?>" ><?php echo h($pName); ?></option>
						<?php endforeach; ?>
						</select>
						</div>
					</td>
					<td>
						<input type='text' value='' placeholder="Value" id='newValue'/>
					</td>
					<td>
						<input type='text' value='' placeholder="Comment" id='newComment'/>
					</td>
					<td>
						<input type='button' value='Save' id='saveItemParameter'/>
					</td>
				</tr>
			</table>
			<?php #echo $this->Form->input("parameter_id",array( "class"=>'ui-widget',"id"=>"combobox"),$parameters); ?>
		</div>

	   <?php require(dirname(__FILE__).'/view_history_tab.ctp'); ?>

		<div id="files">

			<h3>Subtype files: <?php echo $item['ItemSubtype']['name']; ?></h3>
			<table class="file">
				<tr>
					<th><?php echo __('Name'); ?></th>
					<th><?php echo __('Size'); ?></th>
					<th><?php echo __('Type'); ?></th>
					<th><?php echo __('Comment'); ?></th>
					<th><?php echo __('User'); ?></th>
					<th class="actions"><?php echo __('Actions');?></th>
				</tr>
				<?php if(!empty($item['ItemSubtype']['DbFile'])): ?>
				<?php foreach ($item['ItemSubtype']['DbFile'] as $f): ?>
				<tr>
					<td>
						<?php echo $this->Html->tableLink($f['name'], array('controller' => 'db_files', 'action' => 'view', $f['id'], 'Item', $item['Item']['id'])); ?>
					</td>
					<td>
						<?php echo $f['size']; ?>
					</td>
					<td>
						<?php echo $f['type']; ?>
					</td>
					<td>
						<?php echo $f['comment']; ?>
						<?php echo $this->Html->image('ElegantBlueWeb/settings.png', array(
																				'title' => 'Edit comment',
																				'alt' => __('edit'),
																				'border' => '0',
																				'width'=>'16',
																				'height'=>'16',
                                                                                'align' => 'right',
																				'url' => array('controller' => 'db_files', 'action' => 'changeComment', $f['id'])));
						?>
					</td>
					<td>
						<?php echo $f['User']['username']; ?>
					</td>
					<td>
						<?php echo $this->Html->image('download.png', array(
																'title' => 'Download',
																'alt' => __('Download file'),
																'border' => '0',
																'width'=>'24',
																'height'=>'24',
																'url' => array('controller' => 'db_files','action' => 'download', $f['id'], true))); ?>
						&nbsp;
						<?php echo $this->Form->postLink($this->Html->image('delete.png',
																array(
																	'title' => 'Delete',
																	'alt' => __('Delete file'),
																	'border' => '0',
																	'width'=>'24',
																	'height'=>'24')),
																array(
																	'controller' => 'db_files',
																	'action' => 'delete',
																	'ItemSubtype',
																	$item['ItemSubtype']['id'],
																	$f['id']),
																array('escape' => false),
																__('Are you sure you want to delete %s?', $f['name'])); ?>
					</td>
				</tr>
				<?php endforeach; ?>
				<?php else: ?>
					<tr>
						<td colspan="6">No files found.</td>
					</tr>
				<?php endif; ?>
				<tr>
					<td colspan="6">
						<?php echo $this->Html->image('upload.png', array(
															'title' => 'Upload new file',
															'alt' => __('Upload file'),
															'border' => '0',
															'width'=>'34',
															'height'=>'34',
															'url' => array('controller' => 'db_files','action' => 'add', 'ItemSubtype', $item['ItemSubtype']['id']))); ?>
					</td>
				</tr>
			</table>

		<h3>Version files: <?php echo $item['ItemSubtype']['name'].' v'.$item['ItemSubtypeVersion']['version']; ?></h3>
			<table class="file">
				<tr>
					<th><?php echo __('Name'); ?></th>
					<th><?php echo __('Size'); ?></th>
					<th><?php echo __('Type'); ?></th>
					<th><?php echo __('Comment'); ?></th>
					<th><?php echo __('User'); ?></th>
					<th class="actions"><?php echo __('Actions');?></th>
				</tr>
				<?php if(!empty($item['ItemSubtypeVersion']['DbFile'])): ?>
				<?php foreach ($item['ItemSubtypeVersion']['DbFile'] as $f): ?>
				<tr>
					<td>
						<?php echo $this->Html->tableLink($f['name'], array('controller' => 'db_files', 'action' => 'view', $f['id'], 'Item', $item['Item']['id'])); ?>
					</td>
					<td>
						<?php echo $f['size']; ?>
					</td>
					<td>
						<?php echo $f['type']; ?>
					</td>
					<td>
						<?php echo $f['comment']; ?>
						&nbsp;
						<?php echo $this->Html->image('ElegantBlueWeb/settings.png', array(
																				'title' => 'Edit comment',
																				'alt' => __('edit'),
																				'border' => '0',
																				'width'=>'16',
																				'height'=>'16',
                                                                                'align' => 'right',
																				'url' => array('controller' => 'db_files', 'action' => 'changeComment', $f['id'])));
						?>
					</td>
					<td>
						<?php echo $f['User']['username']; ?>
					</td>
					<td>
						<?php echo $this->Html->image('download.png', array(
																'title' => 'Download',
																'alt' => __('Download file'),
																'border' => '0',
																'width'=>'24',
																'height'=>'24',
																'url' => array('controller' => 'db_files','action' => 'download', $f['id'], true))); ?>
						&nbsp;
						<?php echo $this->Form->postLink($this->Html->image('delete.png',
																array(
																	'title' => 'Delete',
																	'alt' => __('Delete file'),
																	'border' => '0',
																	'width'=>'24',
																	'height'=>'24')),
																array(
																	'controller' => 'db_files',
																	'action' => 'delete',
																	'ItemSubtypeVersion',
																	$item['ItemSubtypeVersion']['id'],
																	$f['id']),
																array('escape' => false),
																__('Are you sure you want to delete %s?', $f['name'])); ?>
					</td>
				</tr>
				<?php endforeach; ?>
				<?php else: ?>
				<tr>
					<td colspan="6">No files found.</td>
				</tr>
				<?php endif; ?>
				<tr>
					<td colspan="6">
						<?php echo $this->Html->image('upload.png', array(
															'title' => 'Upload new file',
															'alt' => __('Upload file'),
															'border' => '0',
															'width'=>'34',
															'height'=>'34',
															'url' => array('controller' => 'db_files','action' => 'add', 'ItemSubtypeVersion', $item['ItemSubtypeVersion']['id']))); ?>
					</td>
				</tr>
			</table>

		<h3>Project files: <?php echo $item['Project']['name']; ?></h3>
			<table class="file">
				<tr>
					<th><?php echo __('Name'); ?></th>
					<th><?php echo __('Size'); ?></th>
					<th><?php echo __('Type'); ?></th>
					<th><?php echo __('Comment'); ?></th>
					<th><?php echo __('User'); ?></th>
					<th class="actions"><?php echo __('Actions');?></th>
				</tr>
				<?php if(!empty($item['Project']['DbFile'])): ?>
				<?php foreach ($item['Project']['DbFile'] as $f): ?>
				<tr>
					<td>
						<?php echo $this->Html->tableLink($f['name'], array('controller' => 'db_files', 'action' => 'view', $f['id'], 'Item', $item['Item']['id'])); ?>
					</td>
					<td>
						<?php echo $f['size']; ?>
					</td>
					<td>
						<?php echo $f['type']; ?>
					</td>
					<td>
						<?php echo $f['comment']; ?>
						&nbsp;
						<?php echo $this->Html->image('ElegantBlueWeb/settings.png', array(
																				'title' => 'Edit comment',
																				'alt' => __('edit'),
																				'border' => '0',
																				'width'=>'16',
																				'height'=>'16',
																				'align' => 'right',
																				'url' => array('controller' => 'db_files', 'action' => 'changeComment', $f['id'])));
						?>
					</td>
					<td>
						<?php echo $f['User']['username']; ?>
					</td>
					<td>
						<?php echo $this->Html->image('download.png', array(
																'title' => 'Download',
																'alt' => __('Download file'),
																'border' => '0',
																'width'=>'24',
																'height'=>'24',
																'url' => array('controller' => 'db_files','action' => 'download', $f['id'], true))); ?>
						&nbsp;
						<?php echo $this->Form->postLink($this->Html->image('delete.png',
																array(
																	'title' => 'Delete',
																	'alt' => __('Delete file'),
																	'border' => '0',
																	'width'=>'24',
																	'height'=>'24')),
																array(
																	'controller' => 'db_files',
																	'action' => 'delete',
																	'Project',
																	$item['Project']['id'],
																	$f['id']),
																array('escape' => false),
																__('Are you sure you want to delete %s?', $f['name'])); ?>
					</td>
				</tr>
				<?php endforeach; ?>
				<?php else: ?>
				<tr>
					<td colspan="6">No files found.</td>
				</tr>
				<?php endif; ?>
				<tr>
					<td colspan="6">
						<?php echo $this->Html->image('upload.png', array(
															'title' => 'Upload new file',
															'alt' => __('Upload file'),
															'border' => '0',
															'width'=>'34',
															'height'=>'34',
															'url' => array('controller' => 'db_files','action' => 'add', 'Project', $item['Project']['id']))); ?>
					</td>
				</tr>
			</table>
		</div>

		<div id="transfers">
			<?php if (!empty($transfers)):?>
				<table cellpadding = "0" cellspacing = "0">
					<tr>
						<th><?php echo __('Shipping Date'); ?></th>
						<th><?php echo __('From'); ?></th>
						<th><?php echo __('To'); ?></th>
						<th><?php echo __('Tracking Number'); ?></th>
						<th><?php echo __('Deliverer'); ?></th>
						<th><?php echo __('Comment'); ?></th>
						<th><?php echo __('Responsible User'); ?></th>
						<th class="actions"><?php echo __('Actions');?></th>
					</tr>
					<?php
						$i = 0;
						foreach ($transfers as $t): ?>
						<tr>
							<td>
								<?php echo substr($t['shipping_date'],0,10); ?>
							</td>
							<td>
								<?php echo $this->Html->link($t['ItemsTransfer']['From']['name'], array('controller' => 'locations','action' => 'view', $t['ItemsTransfer']['From']['id'])); ?>
							</td>
							<td>
								<?php echo $this->Html->link($t['ItemsTransfer']['To']['name'], array('controller' => 'locations','action' => 'view', $t['ItemsTransfer']['To']['id'])); ?>
							</td>
							<td>
								<?php 	if(!empty($t['link'])) echo $this->Html->link($t['tracking_number'], $t['link']);
										else echo $t['tracking_number']; ?>
							</td>
							<td>
								<?php echo $this->Html->link($t['Deliverer']['name'], array('controller' => 'deliverers','action' => 'view', $t['Deliverer']['id'])); ?>
							</td>
							<td>
								<?php echo $t['comment']; ?>
							</td>
							<td>
								<?php 	if(isset($t['User']['username']))
											echo $this->Html->link($t['User']['username'], array('controller' => 'users', 'action' => 'view', $t['User']['id']));
										else
											echo 'Unknown';
								?>
							</td>
							<td class="actions">
								<?php echo $this->Html->link(__('View'), array('controller' => 'transfers','action' => 'view', $t['id'])); ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php else: ?>
				No transfers found.
			<?php endif; ?>
		</div>
	</div>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('Item:'); ?></h2>
	<span><?php
		echo "Stock ".$item['ItemSubtype']['name']." v".$item['ItemSubtypeVersion']['version'];
		?></span>
	<ul>
		<li class='active'><?php echo $this->Html->link(
											'Add comment',
											array('controller' => 'histories', 'action' => 'addComment', $item['Item']['id']),
											array('title' => 'Add a comment to the items history'));?> </li>

		<?php if (!empty($item['ItemSubtypeVersion']['Component'])):?>
		<li class='active'><?php echo $this->Html->link(
											'Post registration',
											array('controller' => 'items', 'action' => 'postRegistration', $item['Item']['id']),
											array('title' => 'Register components of this item'));?> </li>
		<?php endif; ?>
	</ul>

	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?></div>
</div>

<style>
  .custom-combobox {
    position: relative;
    display: inline-block;
  }
  .custom-combobox-toggle {
    position: absolute;
    top: 0;
    bottom: 0;
    margin-left: -1px;
    padding: 0;
    display:none;
  }
  .custom-combobox-input {
    margin: 0;
    padding: 5px 10px;
    cursor:pointer;
  }
  .ui-autocomplete{
  	max-height:200px;
  	overflow-y: auto;
  	overflow-x: hidden;
  }
  </style>

<?php echo $this->Html->script("combobox"); ?>
<script type='text/javascript'>
var overlayCloseButton = '<div style="float: right;"><?php echo $this->Html->image("ElegantBlueWeb/xmark.png",array("alt"=>"X","width"=>"20","onClick"=>"closeSelectorOverlay();","style"=>"cursor:pointer; z-index:5;")); ?></div>';
var itemViewBaseUrl = '<?php echo $this->Html->url(array("controller"=>"Items","action"=>"view")); ?>/';
</script>
<script>
var viewedItemId = <?php echo $item['Item']['id']; ?>;
  $(function() {
    $( "#combobox" ).combobox();
    $( "#toggle" ).click(function() {
      $( "#combobox" ).toggle();
    });
    $("#saveItemParameter").click(function(){
    	var newItemParameter = {}
    	if($("#combobox").val() != null){
	    	newItemParameter.parameter = $("#combobox").val();
	    	// console.log($("#combobox").val());
    	}else{
    		alert("You need to select a parameter");
    		return
    	}
    	if($("#newValue").val() != ""){
	    	newItemParameter.value = $("#newValue").val();
    	}else{
    		alert("You need to enter a value for this parameter");
    		$("#newValue").focus();
    		return
    	}
    	newItemParameter.comment = $("#newComment").val();
    	// console.log(newItemParameter);
    	$.ajax('<?php echo $this->Html->url(array("controller"=>"Items","action"=>"addToItem",$item['Item']['id'],"itemParameter")); ?>',{
    		type: "POST",
    		data: newItemParameter,
    		success: function(data){
    			$("#newItemParameter").parent().append(data);
    			//Push input to end again
    			$("#newItemParameter").parent().append($("#newItemParameter"));
    			//Reset the input fields
    			// $("#combobox").prop("selectedIndex", -1);
    			// $("#combobox").parent().find("span input").val("--select an option--");
    			$("#newValue").val("");
    			$("#newComment").val("");
    		}
    	});
    });
  });
	function changeAmount(element,id,command,location,currentAmount){
		var abort = true;
		//Ask user if to add back to stock
		$("#content").append(
					"<div id='dialog'>What would you like to change the amount to? The current amount is <b>"+currentAmount+"</b>.<br />"+
					"<input type='number' value='"+currentAmount+"' name='amount' />"+
					"<input type='text' value='' name='comment' placeholder='Enter a comment' />"+
					"</div>");
		$("#dialog").dialog({
			resizable: false,
			height: 200,
			modal: true,
			buttons: {
				"Modify": function() {
					abort = false;
					addBackToStock = true;
					$(this).dialog("close");
				},
				"Abort": function(){
					abort = true;
					$(this).dialog("close");
				}
			},
			close: function(){
				if(abort){
					return false;
				}
				amount = $("#dialog input[type=number]").val();
				userComment = $("#dialog input[type=text]").val()
				$.ajax('<?php echo $this->Html->url(array("controller"=>"Items","action"=>"addToItem")); ?>/'+id+'/'+command,{
					type: "POST",
					data: {
							"location_id": location,
							"amount": (amount-currentAmount),
							"previousAmount": currentAmount,
							"userComment": userComment,
						},
					success: function(data){
						//Update displayed value
						$(element).parent().find("span").html($("#dialog input").val());
						console.log(data);
					}
				});
			}
		});
	}
  </script>
