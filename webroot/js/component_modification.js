var targetRow = null;
var selectedItems = Array(); //Stores all the items that were selected in this iteration locally so they can be hidden when the selection window is displayed. Is overwritten on page generation because the remaining configuration is also not saved
$(function(){
	$(".equipButtons").click(function(event){
		event.preventDefault();
		var url = $(this).attr("href");
		var pos = $(this).offset();
		openSelectorOverlay(url,pos);
		targetRow = $(this).parent().parent();
	});
});
var rowBackups = new Array();

function removeItemFromPosition(element,event,componentPosition,itemId){
	if (typeof(event) !== 'undefined'){
		event.preventDefault();
	}
	var row = $(element).parent().parent();
	var table = $(row).parent().parent();
	if (typeof storeItemAttachment == 'function') {
		storeItemDetachment(itemId,componentPosition,row,table,replaceRowAndActivateButtons);
	}else{
		replaceRowAndActivateButtons(componentPosition,row,table,itemId);
	}
}

function replaceRowAndActivateButtons(componentPosition,row,table,itemId){
	row.replaceWith(rowBackups[componentPosition]);
	selectedItems.splice(selectedItems.indexOf(itemId),1); //Remove element from array

	//Make sure the buttons work after reset as well
	$(table).find("tr td:first-child").each(function(){
		if($(this).html()==componentPosition){
			$(this).parent().find(".equipButtons").click(function(event){
				event.preventDefault();
				var url = $(this).attr("href");
				var pos = $(this).offset();
				openSelectorOverlay(url,pos);
				targetRow = $(this).parent().parent();
			});
		}
	});
}


function openSelectorOverlay(url,pos){
	//position contains object with top and left of the button that has been clicked
	var size = {width:1000,height:400}; //Define the size of the overlay
	//Special treatment for the left side because it should not cover the main menu, therefore always at least 200px space to the left, if necessary resize width
	if((pos.left-size.width)<230){
		size.width = pos.left-230;
	}
	if((pos.top-size.height)<10){
		pos.top = size.height+10;
	}
	var targetPos = {width:size.width, height:size.height, left:(pos.left-size.width), top:(pos.top-size.height)};
	//Create the overlay and attach it to the body
	if($("#selectorOverlay").attr("id") == undefined){
		$("#container").append("<div id='selectorOverlay'></div>");
	}
	$("#selectorOverlay").attr("style","top:"+pos.top+"px; left:"+pos.left+"px; width:0px; height:0px;");
	loadDataIntoOverlay(url);
	$("#selectorOverlay").animate(targetPos,300);

}

function closeSelectorOverlay(){
	targetPos = $("#selectorOverlay").offset();
	targetPos.left += $("#selectorOverlay").width();
	targetPos.top += $("#selectorOverlay").height();
	targetPos.left += "px";
	targetPos.top += "px";
	targetPos.width = "0px";
	targetPos.height = "0px";
	$("#selectorOverlay").html("").animate(targetPos,300,function(){
		$("#selectorOverlay").remove();
	});

}

function loadDataIntoOverlay(url,postData){
	$("#selectorOverlay").load(url,function(){
		$("#selectorOverlay").prepend(overlayCloseButton);
	});
}


function selectItemForPosition(element,itemId,componentPosition){
	//block button so it can only be pressed once
	$(element).attr("disabled",true);

	selectedItems.push(itemId);

	$.getJSON(itemViewBaseUrl+itemId,function(data){
		var newTargetRow = getNewTargetRow(data,componentPosition,itemId);
		rowBackups[componentPosition] = targetRow;
		$(targetRow).replaceWith(newTargetRow);
	});
	if (typeof storeItemAttachment == 'function') {
		storeItemAttachment(itemId,componentPosition);
	}

	//fade away the selection window
	closeSelectorOverlay();
}