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
	$(function(){
		//Automatically remove each tag on letting it go
		$(".group .tag").draggable({
			start: function(event,ui){
				checkButton($(this).parent().parent());
				},
			stop:function(event,ui){$(this).remove();},
		});
		//Make all tags in the selector of all tags draggable
		$("#itemTags .tag").draggable({
			appendTo: "body",
			helper: "clone",
			revert: "true",
		});
		$("#newTag").draggable( 'disable' )
		$("#newTag").click(function(){
			$("#newTagWindow").dialog({
				resizable: false,
				height: 250,
				modal: true,
				buttons:{
					"Create":function(){
						var newTagName = $("#newTagName").val();
						if(newTagName == ""){
							$("#newTagName").attr("placeholder",'Item Tag name must not be empty')
							return;
						}
						$.post(
							'<?php echo $this->Html->url(array("controller"=>"ItemTags","action"=>"add")); ?>',
							{"ItemTag[name]":newTagName},
							function(data){
								if(!data.success){
									$("#newTagWindow").find(".message").remove();
									$("#newTagWindow").prepend("<div class='message'>"+data.message+"</div>");
								}else{
									//Tag was added to database, add it to the displayed tags and close the dialog
									var newTag = "<div class='tag' value='"+data.newTagId+"'>"+newTagName+"</div>";
									newTag = $(newTag).draggable({
										appendTo: "body",
										helper: "clone",
										revert: "true",
									});
									newTag.insertBefore("#newTag");
									$("#newTagWindow").dialog("close");
								}
							},
							"json"
						);
					},
					"Cancel":function(){
						$(this).dialog("close");
					}
				}
			});
		});
		//Make all fields in the selector of all fields draggable
		$("#newFields .newField").draggable({
			appendTo: "body",
			helper: "clone",
			revert: "true",
			stop:function(event,ui){
//				console.log(this);
			}
		});
		//Make group fields droppable for new Fields from the new Fields selector
		$(".group").droppable({
			hoverClass: "hover",
			accept: ".newField",
			drop: function(event,ui){
				appendWithDuplicateCheck(this,ui.draggable[0],".field");
				checkButton($(this));
			}
		});
		//Make the header of the group droppable and add the tag to all fields in the group
		$(".group .header").droppable({
			hoverClass: "hover", //Class to apply when the field is hovered
			accept:".tag", //Only accept tags
//			activeClass: "active",//Class to apply when a draggable is selected that could be dropped here
			drop: function(event,ui){
				$(this).parent().find(".field").each(function(){
					appendWithDuplicateCheck(this,ui.draggable[0],".tag");
					checkButton($(this).parent());
				});
			}
		});
		//Make a field droppable and add the tag to it if applicable
		$(".field").droppable(fieldDroppableSettings);
		$(".remove").each(function(){
			$(this).click(function(){
				var field = $(this).parent().parent();
				removeField(field);
			})
		});
		doElementAlignment();
		$(window).resize(doElementAlignment); //react to resizing
	});

	function doElementAlignment(){
		//Move the top of the groups slightly below the bottom of the tag and field selector
		var height = $("#selectables").height();
		var newTop = height+10;
		$(".index.projectsItemTypes").css({"padding-top":newTop+"px"});

		//Scroll the selector box only to the top and then keep it there
		var s = $("#selectables");
		var pos = s.position();
		$(window).scroll(function(){
			var windowpos = $(this).scrollTop();
			if(windowpos>=pos.top){
				//window would hit top, set position to 5px below the window
				 s.css({top:"0px"});
			}else{
				//Window hasn't hit top jet set position to supposedly position (e.g. 90 px from top) minus the already made distance
				 s.css({top:pos.top-windowpos});
			}
		});
	}

	function removeField(field){
		if(window.confirm("Are you sure?")){
			$.post(
				'<?php echo $this->Html->url(array("controller"=>"ProjectsItemTypes","action"=>"removeFieldFromGroup"))?>',
				{field: field.attr("value"),
				group: field.parent().attr("value"),
				groupBy: $("#groupBy").val()}, //data to be sent
				function(data,textStatus,jqXHR){ //success
					if(data.error){
						window.reload();
					}else{
						field.fadeOut(500,function(){$(this).remove()});
					}
				}
			)
		}
	}


	function checkButton(element){
		var groupId = $(element).attr("value");
		var groupName = $(element).find(".groupHeader").first().html();
		var saveButton = "<input type='button' value='save "+groupName+" Tags' id='save_"+groupId+"' class='saveGroup'>";
		if($("#save_"+groupId).length == 0){
			$(element).prepend(saveButton);
		}
		updateSaveButtonFunctionality();
	}

	function updateSaveButtonFunctionality(){
		$(".saveGroup").each(function(){
			var button = $(this);
			$(this).off("click");//remove click handler before adding a new one
			$(this).click(function(){
				button.attr("disabled", true);
				var group = new Array();
				var groupId = $(this).parent().attr("value");
				group[groupId] = new Array();
				//Find all fields
				$(this).parent().find(".field").each(function(){
					//get fieldId
					var fieldId = $(this).attr("value");
					group[groupId][fieldId] = new Array();
					$(this).find(".tag").each(function(){
						group[groupId][fieldId].push(parseInt($(this).attr("value")));
					});
				});
				$.post(
					'<?php echo $this->Html->url(array("controller"=>"ProjectsItemTypes","action"=>"setTagsForGroup"))?>',
					{tagData:JSON.stringify(group),
						groupBy:$("#groupBy").val()}, //data to be sent
					function(data,textStatus,jqXHR){
						if(data.error){
							window.reload();
						}else{
							button.replaceWith("<div class='notification'>Saved</div>");
							$(".notification").each(function(){$(this).fadeIn(500).fadeOut(500,function(){$(this).remove()});})
						}
					},
					"json" //expected datatype
				);

			});
		})
	}

	//Checks if tag is already in field before adding it
	//Returns boolean signifying if an action was taken
	function appendWithDuplicateCheck(target,element,classInUse){
		var sameTarget = ($(element).parent()[0]==$(target)[0]); //Check if the target area and the parent of the item beeing dragged are the same
		element = $(element).clone().attr("style","");
		element.removeClass("ui-draggable-dragging"); //Whyever the system doesn't remove this class automatically so I have to do it manually here.
		if($(element).hasClass("newField")){
			//If field don't add dragging functionality but add remove button'
			$(element).removeClass("newField");
			$(element).find(".fieldHeader").append("<div class='remove ui-icon ui-icon-close'>&nbsp;</div>");
			$(element).find(".remove").click(function(){
				var field = $(this).parent().parent();
				removeField(field);
			});
			//Make the field act like every other field, e.g. droppable
			$(element).droppable(fieldDroppableSettings);
		}else{
			$(element).draggable({
				start: function(event,ui){
						//Only add the button if it is not the field selector
						if(!$(target).hasClass("newField")){
							checkButton($(this).parent().parent());
						}
					},
				//Automatically remove each tag on letting it go
				stop:function(event,ui){$(this).remove();}
			});
		}
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
</script>
<style type="text/css">
	.notification{
		text-align:center;
	}

	#selectables{
		position: fixed;
		top:90px;
		border-top-left-radius: 10px;
		border-bottom-left-radius: 10px;
		z-index: 5;
		background-color: green;
		box-shadow: solid 1px black;
	}

	#itemTags{
		display:inline-block;
		min-height: 50px;
		max-height: 165px;
		overflow-y: scroll;
	}
	#newFields{
		display:inline-block;
		min-height: 50px;
		max-height: 165px;
		overflow-y: scroll;

	}

	.index.projectsItemTypes{
		padding-top:100px;
	}

	#tagClouds{
		top:20px;
	}
	.ui-draggable-dragging{
		z-index:6;
	}

	#newTag{
		background-color: yellow!important;
	}
	.tag{
		background-color: #AAAAAA;
	}

	.field .tag{
		background-color:#AAAAAA;
	}
	.group .tag{
		background-color:green;
	}
	.header{
		text-align: center;
		border-top-left-radius: 7px;
		border-top-right-radius: 7px;
	}

	.group .header{
		background-color:#333333;
		color:#EEEEEE;
		font-size: 12pt;
	}

	.field .header{
		background-color:#444444;
		color:#EEEEEE;
		font-size:10pt;
	}
	.group{
		min-width:400px;
		max-width:800px;
		min-height:100px;
		background:#003d4c;
		float:left;
		margin:10px;
		border-radius: 10px;
	}
	.field{
		min-width:188px;
		min-height:60px;
		background:#3e698c;
		float:left;
		margin:5px;
		border: solid 1px black;
		border-radius: 10px;
	}

	.groupHeader.hover{
		background:#555555;
	}
	.group.hover{
		background:#002d3c;
	}
	.field.hover{
		background:#2e597c;
	}
	.field .remove{
		float:right;
		background-image: url('<?php echo $this->Html->url(array("controller"=>"css","action"=>null))."/cupertino/images/ui-icons_ffffff_256x240.png";?>');
		cursor:pointer;
	}
	.newField{
		cursor:pointer;
	}
</style>
<div id="newTagWindow" style="display:none;">
	Please enter the name for the new tag.
	<input type="text" value="" id="newTagName">
</div>
<div class="index projectsItemTypes">
	<!-- Selection on the top that displays all Tags sorted alphabetically, sticks to its position and duplicates an element on selection (tags can't be moved out of this element, only copied) -->
	<div id='selectables'>
		<div id='itemTags'>
			<?php foreach($itemTags as $id=>$name): ?>
				<div class='tag' value="<?php echo $id; ?>"><?php echo $name; ?></div>
			<?php endforeach; ?>
			<div id="newTag" class='tag' value="0">New</div>
		</div>
		<div id='newFields'>
			<?php foreach($newFields as $id=>$name): ?>
				<div class='newField field' value="<?php echo $id; ?>">
					<div class='fieldHeader header'>
						<?php echo $name; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<!--
	Grouped fields that allow tags to be dragged into them
	Actions performed on dragging:
	*) Entering a field with a tag displays the snap possibility by changing the color slightly <-- works!
	*) Removing a Tag from a field changes the color of the dragged tag to signal it is beeing removed, vanishes if left floating outside of a field
	*) Dragging a Tag to a group Header adds the tag to all elements within the group <-- header required because of limitation of javascript that always triggers the group if it is dropped inside
	Additional:
	*) If changes have been made block closing of the tag or pressing a link with the typical request.
	*) Only show projects that are assigned to the user in question <--works
	*) Enable addition of Project or ItemType (whichever is applicable) via a userfriendly graphical interface and AJAX <-- not necessary imho
	*) Show save button if an item in a group has been changed <--works
	*) Check for duplicates before adding to element <--works
	-->
	<div id='tagClouds'>
	<?php //debug($projectsItemTypes);
		echo $this->Form->hidden("groupBy",array("value"=>$groupBy));
	?>
	<?php foreach($grouping as $groupId=>$groups): ?>
		<div class='group' value="<?php echo $groupId; ?>">
			<div class='groupHeader header'><?php echo $groups["name"]; ?></div>
			<?php foreach($groups["group"] as $fieldId=>$field): ?>
				<div class='field' value="<?php echo $fieldId; ?>">
					<div class='fieldHeader header'><?php echo $field["name"]; ?><div class='remove ui-icon ui-icon-close'>&nbsp;</div></div>
					<?php foreach($field["ItemTags"] as $tag): ?>
						<div class='tag' value="<?php echo $tag["id"];?>"><?php echo $tag["name"];?></div>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endforeach; ?>
</div>
</div>
<div id='verticalmenu'>
	<h2><?php  echo __('Tag Clouds');?></h2>
	<ul>
	<!-- Group by selection that completely rearranges the layout either to Projects grouped by Item Types or Item Types grouped by projects -->
	<li><?php echo $this->Html->link("Group by Projects",array("controller"=>"ProjectsItemTypes","action"=>"index","Projects")); ?></li>
	<li><?php echo $this->Html->link("Group by Item Types",array("controller"=>"ProjectsItemTypes","action"=>"index","ItemTypes")); ?></li>
	<li><?php echo $this->Html->link(__('New Item Tag'), array("controller"=>"ItemTags",'action' => 'add')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
