/*
var selectedRows = function() {
	var instance = this;
	var key = 'selectedItems';
	
	if (!this.list) {
		//this.list = sessionStorage.getItem(key).split(';');
		
		//if (!this.list)
			this.list=[];
	}
	this.add = function(row) {
		list.push(row);
		//sessionStorage.setItem(key, this.list.join(';'));
	};
	this.remove = function(row) {
		for (var i=0; i<list.length;i++) {
			if (list[i] == row) {
				this.list.splice(i,1);
			}
		}
		//sessionStorage.setItem(key, this.list.join(';'));
	};
	return(instance);
}();
*/	

$(document).ready(function() {			
	//$('#search').setAttribute("style", "display: none");
	
	var se = document.getElementById("searchDIV");
	if(sessionStorage.getItem("ItemIndexFilterVisability") == 1)
		se.setAttribute("style", "display: block");
	else
		se.setAttribute("style", "display: none");
		
	$('#tbl').tableSelect({
		onClick: function(row) {
			//alert(row);
		},
		onDoubleClick: function(rows) {
			$.each(rows,function(i,row) {
				var id_key = 'items_index_selection_id';
				var code_key = 'items_index_selection_code';
				var name = $(row).children('td').eq(0).html().replace(/&nbsp;/, '');
				var code = $(row).children('td').eq(1).html();
				
				var id_string = sessionStorage.getItem(id_key);
				var code_string = sessionStorage.getItem(code_key);
				
				if (id_string !=  null && id_string != '') {
					$(row).removeClass('disabled');
					//selectedRows.remove(name);				
					
					var item_ids = id_string.split(';');
					var item_codes = code_string.split(';');
					
					var insert = true;
					for(i=0; i<item_ids.length; i++) {
						if(item_ids[i] == name) {
							item_ids.splice(i, 1);
							item_codes.splice(i, 1);
							insert = false;
						}
					}
					if(insert) {
						item_ids[item_ids.length] = name;
						item_codes[item_codes.length] = code;
					}
					
					name = item_ids.join(';');
					code = item_codes.join(';');
					
					sessionStorage.setItem(id_key, name);
					sessionStorage.setItem(code_key, code);
				} else {
					$(row).addClass('disabled');
					//selectedRows.add(name);
					sessionStorage.setItem(id_key, name);
					sessionStorage.setItem(code_key, code);
				}
			});
			updateList();
		},
		onChange: function(row) {
			//alert(row);
		}
	});
	updateList();
});

function remove(name) {

	var id_key = 'items_index_selection_id';
	var code_key = 'items_index_selection_code';
	
	var id_string = sessionStorage.getItem(id_key);
	var code_string = sessionStorage.getItem(code_key);
	
	var item_ids = id_string.split(';');
	var item_codes = code_string.split(';');
	
	for(i=0; i<item_ids.length; i++) {
		if(item_ids[i] == name) {
			item_ids.splice(i, 1);
			item_codes.splice(i, 1);
		}
	}

	name = item_ids.join(';');
	code = item_codes.join(';');
	
	sessionStorage.setItem(id_key, name);
	sessionStorage.setItem(code_key, code);

					
	updateList();
}

function updateList() {
	

	//$('.selectedRows').html('');
	var selectedRows = document.getElementById("selectedRows");
	selectedRows.innerHTML = '';
	
	var id_key = 'items_index_selection_id';
	var code_key = 'items_index_selection_code';
	
	var id_string = sessionStorage.getItem(id_key);
	var code_string = sessionStorage.getItem(code_key);
	
	if (id_string !=  null && id_string != '') {
		var item_ids = id_string.split(';');
		var item_codes = code_string.split(';');
		
		for(i=0; i<item_ids.length; i++) {
			var name = item_ids[i];
			var code = item_codes[i];
			
			var row = document.createElement("tr");
			
			var col1 = document.createElement("td");
			var col2 = document.createElement("td");
			
			var removeButton = document.createElement("input");					
			removeButton.setAttribute("onclick", "remove("+name+");");
			removeButton.setAttribute("type", "button");
			removeButton.setAttribute("value", "Remove");
			
			var hiddenKey = document.createElement("input");
			hiddenKey.setAttribute("type", "hidden");
			hiddenKey.setAttribute("value", name);
			hiddenKey.setAttribute("name", "data[Selection][Item]["+name+"]");
			
			var text = document.createTextNode(name);					

			col1.innerHTML = code;
			col1.appendChild(hiddenKey);
			col2.appendChild(removeButton);
			//('<td><input type="hidden" name"data[Selection][key]" value="'+key+'"></td><td> '+sessionStorage.getItem(key)+'</td><td><input type="button" value="Remove" onclick="remove(\''+i+'\');"></td>');
			row.appendChild(col1);
			row.appendChild(col2);
			
			selectedRows.appendChild(row);
			//selectedRows.append('<tr><td><input type="hidden" name"data[Selection][key]" value="'+key+'"></td><td> '+sessionStorage.getItem(key)+'</td><td><input type="button" value="Remove" onclick="remove(\''+i+'\');"></td></tr>');
		}
   }
}
