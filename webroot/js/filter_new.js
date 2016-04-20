var ItemHirachyData = new Array(
	"project_id",
	"manufacturer_id",
	"item_type_id",
	"item_subtype_id",
	"item_subtype_version_id"
);

var FieldData = {
	"project_id":
	{
		sessId: prefix+"ProjectId",
		name: "Project",
		data: project
	},
	"manufacturer_id":
	{
		sessId:prefix+"ManufacturerId",
		name:"Manufacturer",
		data: manufacturer
	},
	"item_type_id":
	{
		sessId:prefix+"ItemTypeId",
		name:"Item Type",
		data: itemType
	},
	"item_subtype_id":
	{
		sessId:prefix+"ItemSubtypeId",
		name:"Item Subtype",
		data: itemSubtype
	},
	"item_subtype_version_id":
	{
		sessId:prefix+"ItemSubtypeVersionId",
		name:"Item Subtype Version",
		data: itemSubtypeVersion
	},
	"location_id":
	{
		sessID: prefix+"LocationId",
		name: "Location"
	}
};

$(document).ready(function(){

	//Initialize all the fields with the values stored in the session Storage (if any)
	var breakLoop = false;
	$(ItemHirachyData).each(function(){
		parentElements = ItemHirachyData.slice(0,ItemHirachyData.indexOf(this.toString()));
		var data = FieldData[this].data;
		$(parentElements).each(function(){
			selectedId = sessionStorage.getItem(FieldData[this].sessId);
			if(selectedId == null){
				breakLoop = true;
				return;
			}
			data = data[selectedId];
		});
		if(breakLoop) return;
		data.sort(SortByLabel);

		StartSubSelect(this,data,sessionStorage.getItem(FieldData[this].sessId));
		activateChangelogLink();
	});
	//Special treatings for the location
	$("#location_id option").each(function(){
		if($(this).val() == sessionStorage.getItem(FieldData["location_id"].sessId)){
			this.selected=true;
			return;
		}
	});
});

function activateChangelogLink(){
	//Check if a itemsubtypeversion is selected
	if(sessionStorage.getItem(FieldData["item_subtype_id"].sessId) != null){
		$('#show_changelog').attr("href", changeLogUrl+sessionStorage.getItem(FieldData["item_subtype_id"].sessId));
	}else{
		$('#show_changelog').attr("href", changeLogUrl);
	}
}


function ClearSubSelect(subSelect){
		var text = $(subSelect).parent().parent().prev().find("label").html();
		$(subSelect)
		.empty()
	    .append('<option>Please select an '+text+'</option>')
	    .attr("disabled","1");


}


//Handles changes to the selection fields in the hirachical selectors
function RefreshSubSelect(elem,f){
	var currElem;
	var thisElem = $(elem);
	var parameters = "";
	var next = "";


	//Find all previous elements
	currElem = thisElem;
	parameters += $(currElem).serialize(); //Add newly selected parameter to list
	var count = 0;
	while($(currElem).parent().parent().prev().find(".dependent").attr("name") != undefined){
		currElem = $(currElem).parent().parent().prev().find(".dependent");
		parameters += "&"+$(currElem).serialize();
		//Making sure to not produce an infinite loop
		count += 1;
		if(count > 10){
			break;
		}
	}
	//Find the next element and check if the next element exists
	if($(elem).parent().parent().next().find(".dependent").attr("name") != undefined){
		currElem = $(elem).parent().parent().next().find(".dependent");
		next = currElem.attr("id");
		parameters += "&next="+next;

		//Reset all following subselects
		currElem = elem;
		while($(currElem).parent().parent().next().find(".dependent").attr("name") != undefined){
			currElem = $(currElem).parent().parent().next().find(".dependent");
			ClearSubSelect(currElem);
		}
		//Load data for next Subselect
		$.post(getAvailableUrl, parameters, function(data){
			StartSubSelect(next,data,-1);
			if (typeof f == "function") f(); //Call callback function if set
		},"json");
	}else{
		//Already at last
		$.post(getAvailableUrl, "next=manufacturer_id&data[Item][item_subtype_version_id]="+thisElem.val(), function(data){
			$("#manufacturer_id").val(data.manufacturer_id);
			if (typeof f == "function") f(); //Call callback function if set
		},"json");
	}
	
	return;
	// console.log("subCategories",elem.id);
	var pos = ItemHirachyData.indexOf(elem.id);
	if(pos>=0){
		parentElements = ItemHirachyData.slice(0,pos+1);
		subElements = ItemHirachyData.slice(pos+1);
		allIds = ItemHirachyData.slice(pos);
	}else{
		parentElements = new Array();
		subElements = new Array();
		allIds = new Array();
	}

    if(elem.selectedIndex >= 0) {
		// which value was selected
	    current_id = elem.options[elem.selectedIndex].value;
	    // Store the value in the session that corresponds to the selection
	    sessionStorage.setItem(FieldData[elem.id].sessId, current_id);
		//Initialize all subselects that are after the next one
		$(subElements).each(function(){
	        InitSubSelect(document.getElementById(this));
		});
		//Remove the values stored in the session for the various fields
		$(subElements).each(function(){
	        sessionStorage.removeItem(FieldData[this].sessId);
		});
		//If there is a next subselect remove all elements from it and add new ones
		if(isset(subElements[0])){
	        ResetSubSelect(document.getElementById(subElements[0]));
			activateChangelogLink();


	        var s = document.getElementById(subElements[0]);
			var tmp = FieldData[subElements[0]].data;
			$(parentElements).each(function(){
				tmp = tmp[sessionStorage.getItem(FieldData[this].sessId)];
			});

			var arr = tmp;
			arr.sort(SortByLabel);

			if((typeof(arr) != "undefined") && (arr != null)) {
				for (var k in arr)
				{
					s.appendChild(arr[k]);
				}
				EnableSubSelect(s);
			}
			else {
				s.appendChild(new Option("No "+FieldData[subElements[0]].name,0));
				DisableSubSelect(s);
			}
		}
	}

}

/**
 * Function to sort by name attribute of object
 */
function SortByLabel(a,b){
  var aName = a.label.toLowerCase();
  var bName = b.label.toLowerCase();
  return ((aName < bName) ? -1 : ((aName > bName) ? 1 : 0));
}

// tritt bei onchange in Kraft, bzw. bei der Initiierung
function RefreshSubSelectOld(elem)
{
	if(elem.id == 'state_id') {
		if(elem.selectedIndex >= 0) {
			state_id = elem.options[elem.selectedIndex].value;
            sessionStorage.setItem('ItemAddStateId', state_id);
		}
	} else if(elem.id == 'comment') {
		if(elem.selectedIndex >= 0) {
			comment = elem.options[elem.selectedIndex].value;
            sessionStorage.setItem('ItemAddComment', comment);
		}
	} else if(elem.id == 'ItemSubtypeItemTypeId'){
		//Item Type Selector in Item_Subtypes.add view
		if(elem.selectedIndex >= 0) {
            // welcher value wurde ausgew�hlt
            item_subtype_item_type_id = elem.options[elem.selectedIndex].value;
            sessionStorage.setItem('ItemSubtypeItemTypeId', item_subtype_item_type_id);
            var s = document.getElementById('ProjectProject');
            ResetSubSelect(s);
            //Reset the Manufacturer selector to show an unselectable information
            ResetSubSelect(document.getElementById('ItemSubtypeVersionManufacturerId'));
            DisableSubSelect(document.getElementById('ItemSubtypeVersionManufacturerId'));
            var node=document.createElement("option");
			var textnode=document.createTextNode("Select a project");
			node.appendChild(textnode);
            document.getElementById('ItemSubtypeVersionManufacturerId').appendChild(node);


            var s = document.getElementById('ProjectProject');

            $.getJSON(itemSubtypesBaseUrl+"/getProjectsForItemType/"+item_subtype_item_type_id,null,function(data){
				var index = -1;
				for (var k in data)
				{
					node = document.createElement("option");
					textnode = document.createTextNode(data[k]);
					attributenode = document.createAttribute("value");
					attributenode.value = k;
					node.appendChild(textnode);
					node.setAttributeNode(attributenode);
					s.appendChild(node);
					index++;
				}
				EnableSubSelect(s);
            });

        }
	}
}

function InitSubSelect(subSelect)
{
	// alle <option>s des sub-<select> entfernen (reset)
    ResetSubSelect(subSelect);
    var s = document.getElementById(subSelect.id);

    parentSelectId = ItemHirachyData[ItemHirachyData.indexOf(subSelect.id)-1];
	s.appendChild(new Option("Please select a "+FieldData[parentSelectId].name,0));
	DisableSubSelect(s);
}

// same as php isset
function isset(arr) {
	if((arr != "undefined") && (arr != null) && (arr.length > 0))
		return true;
	else
		return false;
}

function GetArray(key) {
	var arr = new Array();
	arr = sessionStorage.getItem(key);

	if(arr != undefined)
    		arr = JSON.parse( sessionStorage.getItem(key) );
    else 	arr = new Array();

    return arr;
}

function SetSelectedValues(subSelectId, arr) {
	var subSelect = document.getElementById(subSelectId);

	for(var opt in subSelect.options) {
		for(var k in arr) {
			if(subSelect.options[opt].value == arr[k])
				subSelect.options[opt].selected = true;
		}
	}
}
function updateTagSelector(item_type_id){
	var tagSelector = document.getElementById("ItemItemTag");
	if(tagSelector == null) return;
	ResetSubSelect(tagSelector);
	tagSelector = document.getElementById("ItemItemTag");
    $.getJSON(itemTagsBaseUrl+"/getTagsForItemTypeAndProject/"+item_type_id+"/"+project_id,null,function(data){
		for (var k in data)
		{
			node = document.createElement("option");
			textnode = document.createTextNode(data[k]);
			attributenode = document.createAttribute("value");
			attributenode.value = k;
			node.appendChild(textnode);
			node.setAttributeNode(attributenode);
			tagSelector.appendChild(node);
		}
		EnableSubSelect(tagSelector);
    });
}

function createAttributeNode(value,name,nodeType){
	node = document.createElement(nodeType);
	textnode = document.createTextNode(name);
	attributenode = document.createAttribute("value");
	attributenode.value = value;
	node.appendChild(textnode);
	node.setAttributeNode(attributenode);
	return node;
}


function StartSubSelect(subSelectId, arr, initValue) {
	ResetSubSelect(document.getElementById(subSelectId));
	var s = document.getElementById(subSelectId);

	if((typeof(arr) != "undefined") && (arr != null)) {
		var index = -1;
		var children = new Array();
		for (var k in arr)
		{
			//console.log(typeof(arr[k]));
			if(typeof(arr[k]) == "object"){
				children.push(arr[k]);
			}else{
				children.push(createAttributeNode(k,arr[k],"option"));
			}
			index++;
		}
		children.sort(SortByLabel);
		for (var k in children){
			s.appendChild(children[k]);
			if($(children[k]).val() == initValue){
				s.selectedIndex = index;
			}

		}

		EnableSubSelect(s);
	}
}


// alle <option>s des sub-<select> entfernen
function ResetSubSelect(subSelect)
{
	subSelect.selectedIndex = -1;
	var selectParentNode = subSelect.parentNode;
	var newSubSelect = subSelect.cloneNode(false); // Make a shallow copy
	selectParentNode.replaceChild(newSubSelect, subSelect);
}

// übergebenes Element (sub-<select>) deaktivieren
function DisableSubSelect(elem)
{
    elem.disabled = 1;
}

// übergebenes Element (sub-<select>) aktivieren
function EnableSubSelect(elem)
{
    elem.disabled = 0;
}
