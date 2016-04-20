var project_id;
var manufacturer_id;
var item_type_id;
var item_subtype_id;
var item_subtype_version_id;

var versionManufacturer = new Array();

$(document).ready(function(){
	project_id = sessionStorage.getItem('ItemAddProjectId');
	manufacturer_id = sessionStorage.getItem('ItemAddManufacturerId');
	item_type_id = sessionStorage.getItem('ItemAddItemTypeId');
	item_subtype_id = sessionStorage.getItem('ItemAddItemSubtypeId');
	item_subtype_version_id = sessionStorage.getItem('ItemAddItemSubtypeVersionId');

	StartSubSelect('project_id', project, project_id);
	if(project_id != null) {
		StartSubSelect('manufacturer_id', manufacturer[project_id], manufacturer_id);
		if(manufacturer_id != null) {
			StartSubSelect('item_type_id', itemType[project_id][manufacturer_id], item_type_id);
			if(item_type_id != null) {
				RefreshSubSelect(document.getElementById('item_type_id'));
				StartSubSelect('item_subtype_id', itemSubtype[project_id][manufacturer_id][item_type_id], item_subtype_id);
				if(item_subtype_id != null) {
					StartSubSelect('item_subtype_version_id', itemSubtypeVersion[project_id][manufacturer_id][item_type_id][item_subtype_id], item_subtype_version_id);
					var s = document.getElementById('show_changelog');
					s.setAttribute("href", changeLogUrl+item_subtype_id);
				}
			}
		}
	}
});

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


function StartSubSelect(subSelectId, arr, initValue) {
	ResetSubSelect(document.getElementById(subSelectId));
	var s = document.getElementById(subSelectId);

	if((typeof(arr) != "undefined") && (arr != null)) {
		var index = -1;
		for (var k in arr)
		{
			s.appendChild(arr[k]);
			index++;
			if(arr[k].value == initValue)
				s.selectedIndex = index;
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

// tritt bei onchange in Kraft, bzw. bei der Initiierung
function RefreshSubSelect(elem)
{


	if(elem.id == 'ItemSubtypeVersionManufacturerId') {
		// sub-<select>
        if(elem.selectedIndex >= 0) {
        	// welcher value wurde ausgew�hlt
            versionManufacturer_id = elem.options[elem.selectedIndex].value;
            sessionStorage.setItem('ItemSubtypeVersionAddManufacturerId', versionManufacturer_id);
		}
	} else if(elem.id == 'ProjectProject') {
		// sub-<select>
        if(elem.selectedIndex >= 0) {
        	// welcher value wurde ausgew�hlt
            var i;
            var project_ids = new Array();
            var counter = 0;
            // welcher value wurde ausgewählt
			for (i=0; i<elem.options.length; i++) {
				// elem.options[0] := (Select all)
				if (elem.options[i].selected) {
					project_ids[counter] = elem.options[i].value;
					counter++;
				}
			}

            sessionStorage.setItem('ItemSubtypeVersionAddProjectIds', JSON.stringify(project_ids));
			//sessionStorage.removeItem('ItemSubtypeVersionAddManufacturerId');
            ResetSubSelect(document.getElementById('ItemSubtypeVersionManufacturerId'));

            var s = document.getElementById('ItemSubtypeVersionManufacturerId');

			var childAppended = false;

			for (var i in project_ids) {
				var arr = versionManufacturer[project_ids[i]];

				if((typeof(arr) != "undefined") && (arr != null)) {
					for (var k in arr) {
						var append = true;

						// check if some childs are twice
						// if so: dont append to subSelect
						for(var opt in s.options)
							if(s.options[opt].value == arr[k].value)
								append = false;

						if(append) {
							s.appendChild(arr[k]);
							childAppended = true;
						}
					}
				}
			}
			if(childAppended) {
				EnableSubSelect(s);

				var versionManufacturer_id = sessionStorage.getItem('ItemSubtypeVersionAddManufacturerId');

	        	for(var opt in s.options) {
					if(s.options[opt].value == versionManufacturer_id)
						s.options[opt].selected = true;
				}
			}
			else {
				s.appendChild(new Option("No Manufacturer",0));
				DisableSubSelect(s);
			}
		}
	} else if(elem.id == 'project_id') {
		// sub-<select>
        if(elem.selectedIndex >= 0) {
        	// welcher value wurde ausgew�hlt
            project_id = elem.options[elem.selectedIndex].value;
            sessionStorage.setItem('ItemAddProjectId', project_id);

            // sub-<select>
            InitSubSelect(document.getElementById('item_subtype_version_id'));
            InitSubSelect(document.getElementById('item_subtype_id'));
            InitSubSelect(document.getElementById('item_type_id'));
			sessionStorage.removeItem('ItemAddManufacturerId');
			sessionStorage.removeItem('ItemAddItemTypeId');
			sessionStorage.removeItem('ItemAddItemSubtypeId');
			sessionStorage.removeItem('ItemAddItemSubtypeVersionId');

            ResetSubSelect(document.getElementById('manufacturer_id'));

            var s = document.getElementById('manufacturer_id');

			var arr = manufacturer[project_id];

			if((typeof(arr) != "undefined") && (arr != null)) {
				for (var k in arr)
				{
					s.appendChild(arr[k]);
				}
				EnableSubSelect(s);
			}
			else {
				s.appendChild(new Option("No Manufacturer",0));
				DisableSubSelect(s);
			}
		}
	} else if(elem.id == 'manufacturer_id') {
		// sub-<select>
		if(elem.selectedIndex >= 0) {
            // welcher value wurde ausgew�hlt
            manufacturer_id = elem.options[elem.selectedIndex].value;
            sessionStorage.setItem('ItemAddManufacturerId', manufacturer_id);

            // sub-<select>
            InitSubSelect(document.getElementById('item_subtype_version_id'));
            InitSubSelect(document.getElementById('item_subtype_id'));
			sessionStorage.removeItem('ItemAddItemTypeId');
			sessionStorage.removeItem('ItemAddItemSubtypeId');
			sessionStorage.removeItem('ItemAddItemSubtypeVersionId');

            ResetSubSelect(document.getElementById('item_type_id'));
            var s = document.getElementById('item_type_id');

			var arr = itemType[project_id][manufacturer_id];

			if((typeof(arr) != "undefined") && (arr != null)) {
				for (var k in arr)
				{
					s.appendChild(arr[k]);
				}
				EnableSubSelect(s);
			}
			else {
				s.appendChild(new Option("No Item Type",0));
				DisableSubSelect(s);
			}
		}
	} else if(elem.id == 'item_type_id') {
		// sub-<select>
        if(elem.selectedIndex >= 0) {
        	// welcher value wurde ausgew�hlt
            item_type_id = elem.options[elem.selectedIndex].value;
            sessionStorage.setItem('ItemAddItemTypeId', item_type_id);

            // sub-<select>
            InitSubSelect(document.getElementById('item_subtype_version_id'));
			sessionStorage.removeItem('ItemAddItemSubtypeId');
			sessionStorage.removeItem('ItemAddItemSubtypeVersionId');

            ResetSubSelect(document.getElementById('item_subtype_id'));
            var s = document.getElementById('item_subtype_id');

            var arr = itemSubtype[project_id][manufacturer_id][item_type_id];

			if((typeof(arr) != "undefined") && (arr != null)) {
				for (var k in arr)
				{
					s.appendChild(arr[k]);
				}
				EnableSubSelect(s);
			}
			else {
				s.appendChild(new Option("No Item Subtype",0));
				DisableSubSelect(s);
			}
			updateTagSelector(item_type_id);

		}

	} else if(elem.id == 'item_subtype_id') {
		// sub-<select>
		if(elem.selectedIndex >= 0) {
            // welcher value wurde ausgew�hlt
            item_subtype_id = elem.options[elem.selectedIndex].value;
            sessionStorage.setItem('ItemAddItemSubtypeId', item_subtype_id);

            sessionStorage.removeItem('ItemAddItemSubtypeVersionId');

            // sub-<select>
            var s = document.getElementById('item_subtype_version_id');
            ResetSubSelect(s);
            var s = document.getElementById('item_subtype_version_id');

            var arr = itemSubtypeVersion[project_id][manufacturer_id][item_type_id][item_subtype_id];

			if((typeof(arr) != "undefined") && (arr != null)) {
				var index = -1;
				for (var k in arr)
				{
					s.appendChild(arr[k]);
					index++;
				}
				EnableSubSelect(s);
				s.selectedIndex = index;

				item_subtype_version_id = s.options[s.selectedIndex].value;
				sessionStorage.setItem('ItemAddItemSubtypeVersionId', item_subtype_version_id);
			}
			else {
				s.appendChild(new Option("No Subtype Version",0));
				DisableSubSelect(s);
			}

			var s = document.getElementById('show_changelog');
			//s.setAttribute("href", "<?php echo $this->Html->url(array('controller' => 'itemSubtypes', 'action' => 'changelog')); ?>/"+item_subtype_id);
			//s.setAttribute("href", "item_subtypes/changelog/"+item_subtype_id);
			s.setAttribute("href", changeLogUrl+item_subtype_id);
		}
	} else if(elem.id == 'item_subtype_version_id') {
		if(elem.selectedIndex >= 0) {
			item_subtype_version_id = elem.options[elem.selectedIndex].value;
            sessionStorage.setItem('ItemAddItemSubtypeVersionId', item_subtype_version_id);
		}
	} else if(elem.id == 'location_id') {
		if(elem.selectedIndex >= 0) {
			location_id = elem.options[elem.selectedIndex].value;
            sessionStorage.setItem('ItemAddLocationId', location_id);
		}
	} else if(elem.id == 'state_id') {
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

	s.appendChild(new Option("Nothing selected",0));
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