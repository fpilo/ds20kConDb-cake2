<script type="text/javascript">
	var fieldDroppableSettings = {
		hoverClass: "hover", //Class to apply when the field is hovered
		accept:".tag", //Only accept tags
		drop: function(event,ui){
			//create copy of the dragged element and set the style to an empty string to remove the position information
			appendWithDuplicateCheck(this,ui.draggable[0],".tag")
			//Only create the save button if the parent element is a group (prevents save button during creation of fields with tags)
			if($(this).parent().hasClass('group'))
				checkButton($(this).parent());
		}
	};
	//Checks if tag is already in field before adding it
	//Returns boolean signifying if an action was taken
	function appendWithDuplicateCheck(target,element,classInUse){
		var sameTarget = ($(element).parent()[0]==$(target)[0]); //Check if the target area and the parent of the item beeing dragged are the same
		element = $(element).clone().attr("style","");
		element.removeClass("ui-draggable-dragging"); //Whyever the system doesn't remove this class automatically so I have to do it manually here.
		$(element).draggable({
			containment: $(target).parent(),
			start: function(event,ui){
					checkButton($(this).parent());
				},
			//Automatically remove each tag on letting it go
			stop:function(event,ui){$(this).remove();},

		});
		var append = true;
		if(!sameTarget){
			//if the target is not the same area check if an item with the same value exists
			$(target).find(classInUse).each(function(){
				if($(this).attr("value") == $(element).attr("value")){
					append = false; //If this tag already is in this field set flag to false
					return false; //Break loop
				}
			});
		}
		if(append) $(target).append(element);
		return append;
	}

	function checkButton(element){
		var itemId = $(element).attr("value");
		var itemName = $(element).find(".header").first().html();
		var saveButton = "<input type='button' value='save "+itemName+" Tags' id='save_"+itemId+"' class='saveItem'>";
		if($("#save_"+itemId).length == 0){
			$(element).find(".header").first().html(saveButton);
		}
		updateSaveButtonFunctionality();
	}

	function updateSaveButtonFunctionality(){
		$(".saveItem").each(function(){
			var button = $(this);
			$(this).off("click");//remove click handler before adding a new one
			$(this).click(function(){
				button.attr("disabled", true);
				var itemId = $(this).parent().parent().attr("value");
				var itemName = $(this).attr("value").split(" ")[1];
				itemTags = new Array();
				//Find all fields
				$(this).parent().parent().find(".tag").each(function(){
					itemTags.push(parseInt($(this).attr("value")));
				});
				$.post(
					'<?php echo $this->Html->url(array("controller"=>"Items","action"=>"setTagsForItem"))?>/'+itemId,
					{itemTags:JSON.stringify(itemTags)}, //data to be sent
					function(data,textStatus,jqXHR){
						if(data.error){
							window.reload();
						}else{
							var parentEl = button.parent();
							button.replaceWith("<div class='notification'>Saved "+itemName+"</div>");
							parentEl.find(".notification").fadeIn(500).fadeOut(500,function(){$(this).parent().html(itemName);});
						}
					},
					"json" //expected datatype
				);

			});
		})
	}

	$(function(){
		//Automatically remove each tag on letting it go
		$(".item .tag").each(function(){
			var el = $(this);
			el.draggable({
				start: function(event,ui){
					checkButton($(this).parent());
					},
				stop:function(event,ui){$(this).remove();},
				containment: el.parent().parent(),
			});
		});
		$(".newTags .tag").each(function(){
			var el = $(this);
			el.draggable({
				helper: "clone",
				revert: "true",
				stop:function(event,ui){
	//				console.log(this);
				},
				containment: el.parent().parent(),
			});
		});
		$(".item").droppable({
			hoverClass: "hover",
			accept: ".tag",
			drop: function(event,ui){
				appendWithDuplicateCheck(this,ui.draggable[0],".tag");
				checkButton($(this));
			}
		});
		$(".field .header").droppable({
			hoverClass: "hover",
			accept: ".tag",
			drop: function(event,ui){
				$(this).parent().find(".item").each(function(){
					appendWithDuplicateCheck(this,ui.draggable[0],".tag");
					checkButton($(this));
				});
				var itemName = $(this).attr("value");
				var itemTypeId = $(this).attr("itemTypeId");
				var saveButton = "<input type='button' value='save "+itemName+" Tags' id='save_"+itemTypeId+"' class='saveItemType'>";
				if($("#save_"+itemTypeId).length == 0){
					$(this).html(saveButton);
					$("#save_"+itemTypeId).click(function(){
						var parentEl = $(this).parent();
						$(this).parent().parent().find(".saveItem").click();
						$(this).replaceWith("<div class='notification'>Saved "+itemName+"</div>")
						$(parentEl).find(".notification").fadeIn(500).fadeOut(500,function(){$(this).replaceWith($(this).parent().attr("value"))});

					});
				}

			}
		});
	});
</script>
<style type="text/css">
	#projects{
		display:inline-block;
	}

	.header{
		text-align: center;
	}
	/*Format of the Project Header*/
	.group .header{
		background-color:#333333;
		color:#EEEEEE;
		font-size: 12pt;
		border-top-left-radius: 7px;
		border-top-right-radius: 7px;
	}
	/*Format of the Item Type and Item Headers */
	.field .header{
		background-color:#444444;
		color:#EEEEEE;
		font-size:10pt;
		border-radius: 0px;
	}
	/*Format of the Project block*/
	.group{
		min-width:700px;
		max-width:800px;
		min-height:100px;
		background:#003d4c;
		margin:10px;
		border-radius: 10px;
		display:inline-block;
		padding: 5px;
	}
	/*Format of the Item Type block*/
	.field{
		width: 100%;
		background:#003d4c;
		padding:0px;
		border: solid 0px black;
		display:inline-block;
	}
	/*Format of the Item block*/
	.item{
		width:51%;
		float:left;
		min-height:40px;
		background: #3e698c;
	}
	/*Format of the block containing all available tags */
	.newTags{
		width: 48%;
		float: right;
		height:120px;
		color:white;
		display: inline-table;
	}
	.newTags .tag{
		color: black;
	}
	#itemTypes{
		background-color:blue;
	}
	.tag{
		background-color: grey;
	}
	.item .tag{
		background-color: green;
	}
	.item.hover{
		background-color:#2e597c!important;
	}
	.field.header.hover{
		background-color:#333333!important;
	}
	.item .header input{
		font-size:100%;
		padding:2px;
	}
	.notification{
		height:10px;
		font-size:10px;
		margin-bottom:0px;
	}
</style>
<?php //debug($projectsItemTypes); ?>
<div class="index">
	<h3>Drag and drop tags from the selector on the right to the items on the left to add them to the item. <br />
		Drag the tag to the item type to add it to all items at once. <br />
		To remove a tag from an item drag it to the right <br />
		Every Item needs to be saved individually. Tag changes are only applied to the item once the save button is pressed. </h3>
	<div id="projects">
		<?php foreach($projectsItemTypes as $projectId=>$itemType): ?>
		<div class="group" value="<?php echo $projectId; ?>">
			<div class="header"><?php echo $projects[$projectId]; ?></div>
			<?php foreach($itemType as $itemTypeId=>$items): ?>
			<div class="field" value="<?php echo $itemTypeId; ?>">
				<div class="field header" class='itemType' value='<?php echo $itemTypes[$itemTypeId]; ?>' itemTypeId="<?php echo $itemTypeId; ?>"><?php echo $itemTypes[$itemTypeId]; ?></div>
				<div class="newTags">
					<?php foreach($availableTags[$projectId][$itemTypeId] as $itemTagId=>$itemTagName): ?>
						<div class='tag' value="<?php echo $itemTagId; ?>"><?php echo $itemTagName; ?></div>
					<?php endforeach; ?>
					<?php if(count($availableTags[$projectId][$itemTypeId])==0) echo "No tags available"; ?>
				</div>
				<?php foreach($items as $itemId=>$item): ?>
				<div class="item" value="<?php echo $itemId; ?>">
					<div class="header"><?php echo $item["Item"]["code"] ?></div>
					<?php foreach($item["ItemTag"] as $itemTagId=>$tag): ?>
						<div class='tag' value="<?php echo $tag["id"]; ?>"><?php echo $tag["name"]; ?></div>
					<?php endforeach; ?>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endforeach; ?>
	</div>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Change multiple Items'); ?></h2>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
