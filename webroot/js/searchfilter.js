	$(document).ready(function(){
		SearchFilter();
	});
	function SearchFilter(){
		// Names of the arrays
		/*
		prName = prefix + 'ProjectIds';
		maName = prefix + 'ManufacturerIds';
		tyName = prefix + 'ItemTypeIds';
		suName = prefix + 'ItemSubtypeIds';
		veName = prefix + 'ItemSubtypeVersionIds';
		loName = prefix + 'LocationIds';
		stName = prefix + 'StateIds';
		*/
		prName = 'ProjectIds';
		maName = 'ManufacturerIds';
		tyName = 'ItemTypeIds';
		suName = 'ItemSubtypeIds';
		veName = 'ItemSubtypeVersionIds';
		loName = 'LocationIds';
		stName = 'StateIds';


		project_ids 		= GetArray(prName);
		manufacturer_ids 	= GetArray(maName);
		item_type_ids 		= GetArray(tyName);
		item_subtype_ids 	= GetArray(suName);
		item_subtype_version_ids = GetArray(veName);
		location_ids 		= GetArray(loName);
		state_ids 			= GetArray(stName);

		SetSelectedValues('location_id', location_ids);
		SetSelectedValues('state_id', state_ids);

		/*
		 * INIT Project SubSelect
		 */

		//if(isset(project_ids)) {
		ResetSubSelect('project_id');
		var elem = document.getElementById('project_id');

		//fill array with options in arr
		for (var k in project) {
			elem.appendChild(project[k]);
		}
		// mark values from initValues as selected
		SetSelectedValues('project_id', project_ids);

		/*
		 * Init other SubSelects
		 */
		InitSubSelect('manufacturer_id');
		InitSubSelect('item_type_id');
		InitSubSelect('item_subtype_id');
		InitSubSelect('item_subtype_version_id');

		if(isset(project_ids)) {
			UpdateManufacturer();
			SetSelectedValues('manufacturer_id', manufacturer_ids);
			if(isset(manufacturer_ids)) {
				UpdateItemType();
				SetSelectedValues('item_type_id', item_type_ids);
				if(isset(item_type_ids)) {
					UpdateItemSubtype();
					SetSelectedValues('item_subtype_id', item_subtype_ids);
					if(isset(item_subtype_ids)) {
						UpdateItemSubtypeVersion();
						SetSelectedValues('item_subtype_version_id', item_subtype_version_ids);
					}
				}
			}
		}
	}

function GetArray(key) {
	var arr = new Array();
	arr = sessionStorage.getItem(key);

	if(arr != undefined)
    		arr = JSON.parse( arr );
    else
    	arr = new Array();

    return arr;
}

// alle <option>s des sub-<select> entfernen
function ResetSubSelect(subSelectId)
{
	subSelect = document.getElementById(subSelectId);
	//alert('Delete: '+subSelect.id)
	subSelect.selectedIndex = -1;
	var selectParentNode = subSelect.parentNode;
	var newSubSelect = subSelect.cloneNode(false); // Make a shallow copy
	selectParentNode.replaceChild(newSubSelect, subSelect);

	if(newSubSelect.multiple == true)
		newSubSelect.appendChild(new Option("(Select all)", ""));
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

// same as php isset
function isset(arr) {
	if((arr != "undefined") && (arr != null) && (arr.length > 0))
		return true;
	else
		return false;
}

function SetSelectedValues(subSelectId, arr) {
	var subSelect = document.getElementById(subSelectId);

	if(subSelect) {
		for(var opt in subSelect.options) {
			for(var k in arr) {
				if(subSelect.options[opt].value == arr[k])
					subSelect.options[opt].selected = true;
			}
		}
	}
}

function UpdateManufacturer() {
	var elem = document.getElementById('project_id');

	if(elem.selectedIndex >= 0) {
		var childAppended = false;

        InitSubSelect('item_subtype_version_id');
        InitSubSelect('item_subtype_id');
        InitSubSelect('item_type_id');

        ResetSubSelect('manufacturer_id');
        var s = document.getElementById('manufacturer_id');

		for (var z in project_ids) {
			var p = project_ids[z];
			if(isset(manufacturer[p])) {
				var arr = manufacturer[p];

				if((typeof(arr) != "undefined") && (arr != null)) {
					for (var k in arr)
					{	var append = true;

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
		}
		if(childAppended) {
			EnableSubSelect(s);
		}
		else {
			s.appendChild(new Option("No Manufacturer",0));
			DisableSubSelect(s);
		}
	}
}

function UpdateItemType() {
	var elem = document.getElementById('manufacturer_id');

	if(elem.selectedIndex >= 0) {
		var childAppended = false;

        InitSubSelect('item_subtype_version_id');
        InitSubSelect('item_subtype_id');

        ResetSubSelect('item_type_id');
        var s = document.getElementById('item_type_id');

        for(var z in project_ids) {
        	var p = project_ids[z];
        	// same as if(isset(itemType[p])) in php
        	if((itemType[p] != "undefined") && (itemType[p] != null)) {
				for (var y in manufacturer_ids) {
					var m = manufacturer_ids[y];
					if(isset(itemType[p][m])) {

						var arr = itemType[p][m];
						if((typeof(arr) != "undefined") && (arr != null)) {
							for (var k in arr)
							{	var append = true;

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
				}
			}
		}

		if(childAppended) {
			EnableSubSelect(s);
		}
		else {
			s.appendChild(new Option("No Item Type",0));
			DisableSubSelect(s);
		}
	}
}

function UpdateItemSubtypeVersion() {
	var elem = document.getElementById('item_subtype_id');

	// sub-<select>
	if(elem.selectedIndex >= 0) {
		var childAppended = false;

        sessionStorage.removeItem(veName);
        ResetSubSelect('item_subtype_version_id');
        var s = document.getElementById('item_subtype_version_id');

        var optgroup = new Array();

        for(var z in project_ids) {
        	var p = project_ids[z];
        	// same as if(isset(itemType[p])) in php
        	if(isset(itemSubtypeVersion[p])) {
				for (var y in manufacturer_ids) {
					var m = manufacturer_ids[y];
					if(isset(itemSubtypeVersion[p][m])) {
						for (var x in item_type_ids) {
							var t = item_type_ids[x];
							if(isset(itemSubtypeVersion[p][m][t])) {
								for (var w in item_subtype_ids) {
									var st = item_subtype_ids[w];
									if(isset(itemSubtypeVersion[p][m][t][st])) {

										var arr = itemSubtypeVersion[p][m][t][st];

										if((optgroup[st] == "undefined") || (optgroup[st] == null))	{
											optgroup[st] = document.createElement("optgroup");
											optgroup[st].label = itemSubtype[p][m][t][st].text;
										}
										if((typeof(arr) != "undefined") && (arr != null)) {
											for (var k in arr) {
												var append = true;

												// check if some childs are twice
												// if so: dont append to subSelect
												for(var opt in optgroup[st].options)
													if(optgroup[st].options[opt].value == arr[k].value)
														append = false;

												if(append) {
													optgroup[st].appendChild(arr[k]);
													childAppended = true;
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}

		if(childAppended) {
			for(var st in optgroup)
				s.appendChild(optgroup[st]);

			EnableSubSelect(s);
		}
		else {
			s.appendChild(new Option("No Subtype Version",0));
			DisableSubSelect(s);
		}

		var subtypes = '';
		for (var w in item_subtype_ids)
			subtypes += item_subtype_ids[w]+'/';

		var changelog = document.getElementById('show_changelog');
		//changelog.setAttribute("href", "<?php echo $this->Html->url(array('controller' => 'itemSubtypes', 'action' => 'changelog')); ?>/"+subtypes);
		if(changelog) {	// checks if the changelog != null, undefined, 0, "" (the empty string), false or NaN
			changelog.setAttribute("href", "item_subtypes/changelog/"+subtypes);
		}
	}
}

function UpdateItemSubtype() {
	var elem = document.getElementById('item_type_id');

	if(elem.selectedIndex >= 0) {
		var childAppended = false;

		// sub-<select>
        InitSubSelect('item_subtype_version_id');

        ResetSubSelect('item_subtype_id');
        var s = document.getElementById('item_subtype_id');

        var optgroup = new Array();

        for(var z in project_ids) {
        	var p = project_ids[z];
        	// same as if(isset(itemType[p])) in php
        	if((itemSubtype[p] != "undefined") && (itemSubtype[p] != null)) {
				for (var y in manufacturer_ids) {
					var m = manufacturer_ids[y];
					if((itemSubtype[p][m] != "undefined") && (itemSubtype[p][m] != null)) {
						for (var x in item_type_ids) {
							var t = item_type_ids[x];
							if((itemSubtype[p][m][t] != "undefined") && (itemSubtype[p][m][t] != null)) {

								var arr = itemSubtype[p][m][t];
								if((optgroup[t] == "undefined") || (optgroup[t] == null))	{
									optgroup[t] = document.createElement("optgroup");
									optgroup[t].label = itemType[p][m][t].text;
								}

								if((typeof(arr) != "undefined") && (arr != null)) {
									for (var k in arr) {
										var append = true;

										// check if some childs are twice
										// if so: dont append to subSelect
										for(var opt in optgroup[t].options)
											if(optgroup[t].options[opt].value == arr[k].value)
												append = false;

										if(append) {
											optgroup[t].appendChild(arr[k]);
											childAppended = true;
										}
									}
								}
							}
						}
					}
				}
			}
		}

		if(childAppended) {
			for(var t in optgroup)
				s.appendChild(optgroup[t]);

			EnableSubSelect(s);
		}
		else {
			s.appendChild(new Option("No Item Type Subtype",0));
			DisableSubSelect(s);
		}
	}
}

function SelectAll(elem) {

	if(elem.options[0].selected) {
		for (j=1; j<elem.options.length; j++) {
			elem.options[j].selected = true;
		}
		elem.options[0].selected = false;
	}
}

function SubSelectOnChange(elem) {
	arr = new Array();
	var i, j;
	var counter = 0;

	// welcher value wurde ausgewählt
	for (i=0; i<elem.options.length; i++) {
		// elem.options[0] := (Select all)
		if(elem.options[i].selected && (elem.options[i].label == "(Select all)") && (i == 0)) {
			for (j=1; j<elem.options.length; j++) {
				arr[counter] = elem.options[j].value;
				counter++;
				elem.options[j].selected = true;
			}
			elem.options[i].selected = false;
			break;
		}
		else if (elem.options[i].selected) {
			arr[counter] = elem.options[i].value;
			counter++;
		}
	}

	if(elem.id == 'location_id') {
		location_ids = arr;
		sessionStorage.setItem(loName, JSON.stringify(location_ids));
	}
	if(elem.id == 'state_id') {
		state_ids = arr;
		sessionStorage.setItem(stName, JSON.stringify(state_ids));
	}
	if(elem.id == 'item_subtype_version_id') {
		item_subtype_version_ids = arr;
		sessionStorage.setItem(veName, JSON.stringify(item_subtype_version_ids));
	}
	if(elem.id == 'item_subtype_id') {
		item_subtype_ids = arr;
		sessionStorage.setItem(suName, JSON.stringify(item_subtype_ids));
		sessionStorage.removeItem(veName);
		UpdateItemSubtypeVersion();
	}
	if(elem.id == 'item_type_id') {
		item_type_ids = arr;
		sessionStorage.setItem(tyName, JSON.stringify(item_type_ids));
		sessionStorage.removeItem(suName);
		sessionStorage.removeItem(veName);
		UpdateItemSubtype();
	}
	if(elem.id == 'manufacturer_id') {
		manufacturer_ids = arr;
		sessionStorage.setItem(maName, JSON.stringify(manufacturer_ids));
		sessionStorage.removeItem(tyName);
		sessionStorage.removeItem(suName);
		sessionStorage.removeItem(veName);
		UpdateItemType();
	}
	if(elem.id == 'project_id') {
		project_ids = arr;
		sessionStorage.setItem(prName, JSON.stringify(project_ids));
		sessionStorage.removeItem(maName);
		sessionStorage.removeItem(tyName);
		sessionStorage.removeItem(suName);
		sessionStorage.removeItem(veName);
		UpdateManufacturer();
	}
}

function InitSubSelect(subSelectId)
{
	// alle <option>s des sub-<select> entfernen (reset)
    ResetSubSelect(subSelectId);
    var s = document.getElementById(subSelectId);

	s.appendChild(new Option("Nothing selected",0));
	DisableSubSelect(s);
}
