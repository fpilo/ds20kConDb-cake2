<script type="text/javascript">

	var fieldDroppableSettings = {
		hoverClass: "hover", //Class to apply when the field is hovered
		accept:".checklist", //Only accept checklists
		drop: function(event,ui){
			//create copy of the dragged element and set the style to an empty string to remove the position information
			appendWithUniqueCheck(this,ui.draggable[0],".checklist")
			//Only create the save button if the parent element is a group (prevents save button during creation of fields with checklists)
			if($(this).parent().hasClass('group'))
				checkButton($(this).parent());
		}
	};
	
	//Checks if checklist is already in field before adding it
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
			//Automatically remove each checklist on letting it go
			stop:function(event,ui){$(this).remove();},

		});
		var append = true;
		if(!sameTarget){
			//if the target is not the same area check if an item with the same value exists
			$(target).find(classInUse).each(function(){
				if($(this).attr("value") == $(element).attr("value")){
					append = false; //If this checklist already is in this field set flag to false
					return false; //Break loop
				}
			});
		}
		if(append) $(target).append(element);
		return append;
	}

	//Checks if a checklist is already in field before adding it
	//Returns boolean signifying if an action was taken
	function appendWithUniqueCheck(target,element,classInUse){

		var sameTarget = ($(element).parent()[0]==$(target)[0]); //Check if the target area and the parent of the item beeing dragged are the same

		element = $(element).clone().attr("style","");
		element.removeClass("ui-draggable-dragging"); //Whyever the system doesn't remove this class automatically so I have to do it manually here.
		$(element).draggable({
			containment: $(target).parent(),
			start: function(event,ui){
				checkButton($(this).parent());
			},
			//Automatically remove each checklist on letting it go
			stop:function(event,ui){
				$(this).remove();
			}
		});
		
		var append = true;
		if(!sameTarget){
			//if the target is not the same check if a checklist already exists
			if($(target).find(classInUse).size()>0){
				if($($(target).find(classInUse)[0]).attr("value") != $(element).attr("value"))
					alert("Only one checklist per item is allowed!");
				append = false; //If this checklist already is in this field set flag to false
				return false;
			};
		}

		if(append) $(target).append(element);
		return append;
		
	}

	function checkButton(element){
	
		var itemId = $(element).attr("value");
		var itemName = $(element).find(".header").first().html();
		var saveButton = "<input type='button' value='Save changes for "+itemName+"' id='save_"+itemId+"' class='saveItem'>";
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
				itemName = button.attr("value").split(" ").pop();
				itemChecklists = new Array();
				//Find all fields
				$(this).parent().parent().find(".checklist").each(function(){
					itemChecklists.push(parseInt($(this).attr("value")));
				});

				$.post(
					'<?php echo $this->Html->url(array("controller"=>"Items","action"=>"setChecklistForItem"))?>/'+itemId,
					{itemChecklists:JSON.stringify(itemChecklists)}, //data to be sent
					function(data,textStatus,jqXHR){
						console.log(JSON.stringify(data));
						if(data.error){
							window.reload();
						}else{
							if(data.success && data.checklistId > 0){
								console.log(data);
								button.replaceWith("<div class='notification'>New checklist created for "+itemName+"</div>");
								$(".notification").each(function(){$(this).fadeIn(500).fadeOut(500,function(){$(this).parent().html(itemName);});});
							}
							else if(data.success && data.checklistId == null ){
								console.log(data);
								button.replaceWith("<div class='notification'>Checklist removed for "+itemName+"</div>");
								$(".notification").each(function(){$(this).fadeIn(500).fadeOut(500,function(){$(this).parent().html(itemName);});});
							}
							else{
								console.log(data);
								//Checklist must be removed!!
								button.replaceWith("<div class='error-message'>Error. Only one checklist is allowed</div>");
								$(".error-message").each(function(){$(this).fadeIn(500).fadeOut(500,function(){$(this).parent().html(itemName);});});
							}
						}
					},
					"json" //expected datatype
				);
			});
			
		})
	}

	$(function(){
		//Automatically remove each checklist on letting it go
		$(".item .checklist").each(function(){
			var el = $(this);
			el.draggable({
				start: function(event,ui){
					//checkButton($(this).parent());
					},
				stop:function(event,ui){checkButton($(this).parent()); $(this).remove();},
				containment: el.parent().parent(),
			});
		});
		$(".newChecklists .checklist").each(function(){
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
			accept: ".checklist",
			drop: function(event,ui){
				if(appendWithUniqueCheck(this,ui.draggable[0],".checklist"))
					checkButton($(this));
			}
		});
		$(".field .header").droppable({
			hoverClass: "hover",
			accept: ".checklist",
			drop: function(event,ui){
				$(this).parent().find(".item").each(function(){
					if(appendWithUniqueCheck(this,ui.draggable[0],".checklist"))
						checkButton($(this));
				});
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
	/*Format of the block containing all available checklist templates */
	.newChecklists{
		width: 48%;
		float: right;
		height:120px;
		color:white;
		display: inline-table;
	}
	.newChecklists .checklist{
		color: black;
	}
	#itemTypes{
		background-color:blue;
	}
	.checklist{
		display: block;
		float:left;
		padding:7px;
		margin:5px;
		border-radius: 10px;
		cursor: pointer;
		background-color: grey;
		border-radius: 10px;
	}
	.item .checklist{
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
	.error-message{
		height:10px;
		font-size:10px;
		margin-bottom:0px;
	}
</style>
<?php //debug($projectsItemTypes); ?>
<div class="index">
	<h3>Drag and drop checklist templates from the selector on the right to the items on the left to add them to the item. <br />
		Drag the checklist template to the item type to add it to all items at once. <br />
		To remove a checklist from an item drag it to the right <br />
		Every Item needs to be saved individually. Changes are only applied to the item once the save button is pressed. </h3>
	<div id="projects">
	
		<?php foreach($projectsItemSubtypes as $projectId=>$itemSubtype): ?>
		<div class="group" value="<?php echo $projectId; ?>">
			<div class="header"><?php echo $projects[$projectId]; ?></div>
			<?php foreach($itemSubtype as $itemSubtypeId=>$items): ?>
			<div class="field" value="<?php echo $itemSubtypeId; ?>">
				<div class="field header"><?php echo $itemSubtypes[$itemSubtypeId]; ?></div>
				<div class="newChecklists">
					<?php if(count($availableClTemplates[$projectId][$itemSubtypeId])==0): ?>
						<?php echo "No template available"; ?>
					<?php else: ?>
						<?php foreach($availableClTemplates[$projectId][$itemSubtypeId] as $clTemplateId=>$clTemplateName): ?>
							<div class='checklist' value="<?php echo $clTemplateId; ?>"><?php echo $clTemplateName; ?></div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
				<?php foreach($items as $itemId=>$item): ?>
				<div class="item" value="<?php echo $itemId; ?>">
					<div class="header"><?php echo $item["Item"]["code"] ?></div>
					<?php if(!is_null($item["Checklist"]["id"])): ?>
						<div class='checklist' value="<?php echo $item["Checklist"]["id"]; ?>"><?php echo $item["Checklist"]["name"]; ?></div>
					<?php endif; ?>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endforeach; ?>
	</div>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Change multiple Item Checklists'); ?></h2>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
