<?php
	$this->Html->addCrumb('Item Types', '/item_types/index/');
	$this->Html->addCrumb('Item Subtypes', '/item_subtypes/index/');
?>
<script type="text/javascript">
	var project = new Array();
	var manufacturer = new Array();
	var itemType = new Array();
	var itemSubtype = new Array();
	var itemSubtypeVersion = new Array();

	var project_ids = new Array();
	var manufacturer_ids = new Array();
	var item_type_ids = new Array();
	var item_subtype_ids = new Array();
	var item_subtype_version_ids = new Array();

	<?php
		if(!empty($itemSubtypeVersions)) {
			foreach($itemSubtypeVersions['Project'] as $project) {
				$project_id = $project['Project']['id'];
				$project_name = $project['Project']['name'];

				echo "project[".$project_id."] = new Option(\"".$project_name."\", ".$project_id.");\n";

				if(isset($project['Manufacturer'])) {

					echo "manufacturer[".$project_id."] = new Array();\n";
					echo "itemType[".$project_id."] = new Array();\n";
					echo "itemSubtype[".$project_id."] = new Array();\n";
					echo "itemSubtypeVersion[".$project_id."] = new Array();\n";

					foreach($project['Manufacturer'] as $manufacturer) {
						$manufacturer_id = $manufacturer['Manufacturer']['id'];
						$manufacturer_name = $manufacturer['Manufacturer']['name'];

						echo "manufacturer[".$project_id."][".$manufacturer_id."] = new Option(\"".$manufacturer_name."\", ".$manufacturer_id.");\n";

						if(isset($manufacturer['ItemType'])) {

							echo "itemType[".$project_id."][".$manufacturer_id."] = new Array();\n";
							echo "itemSubtype[".$project_id."][".$manufacturer_id."] = new Array();\n";
							echo "itemSubtypeVersion[".$project_id."][".$manufacturer_id."] = new Array();\n";

							foreach($manufacturer['ItemType'] as $itemType) {
								$itemType_id = $itemType['ItemType']['id'];
								$itemType_name = $itemType['ItemType']['name'];

								echo "itemType[".$project_id."][".$manufacturer_id."][".$itemType_id."] = new Option(\"".$itemType_name."\", ".$itemType_id.");\n";

								if(isset($itemType['ItemSubtype'])) {

									echo "itemSubtype[".$project_id."][".$manufacturer_id."][".$itemType_id."] = new Array();\n";
									echo "itemSubtypeVersion[".$project_id."][".$manufacturer_id."][".$itemType_id."] = new Array();\n";

									foreach($itemType['ItemSubtype'] as $itemSubtype) {
										$itemSubtype_id = $itemSubtype['ItemSubtype']['id'];
										$itemSubtype_name = $itemSubtype['ItemSubtype']['name'];

										echo "itemSubtype[".$project_id."][".$manufacturer_id."][".$itemType_id."][".$itemSubtype_id."] = new Option(\"".$itemSubtype_name."\", ".$itemSubtype_id.");\n";

										if(isset($itemSubtype['ItemSubtypeVersion'])) {

											echo "itemSubtypeVersion[".$project_id."][".$manufacturer_id."][".$itemType_id."][".$itemSubtype_id."] = new Array();\n";

											foreach($itemSubtype['ItemSubtypeVersion'] as $itemSubtypeVersion) {
												$itemSubtypeVersion_id = $itemSubtypeVersion['id'];
												$itemSubtypeVersion_name = $itemSubtypeVersion['version'];

												echo "itemSubtypeVersion[".$project_id."][".$manufacturer_id."][".$itemType_id."][".$itemSubtype_id."][".$itemSubtypeVersion_id."] = new Option(\"".$itemSubtypeVersion_name."\", ".$itemSubtypeVersion_id.");\n";
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
	?>

	$(document).ready(function(){
			project_ids = GetArray('ItemProjectIds');
			manufacturer_ids = GetArray('ItemManufacturerIds');
			item_type_ids = GetArray('ItemItemTypeIds');
			item_subtype_ids = GetArray('ItemItemSubtypeIds');
			item_subtype_version_ids = GetArray('ItemItemSubtypeVersionIds');

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

			$('#Limit').change(function(){
				$.post(
					'/cakephp/ds20kcondb/items/change_limit/',
					{field:  'limit', value: $('#Limit').val() },
					updateIndex
				);
			});

			function updateIndex(output){
				//$('#').remove();
				if(output.length > 0){
					$('#lala').after('<div id="code-notEmpty" class="error-message">'+ output +'</div>');
				}
			}
		});

		function GetArray(key) {
			var arr = new Array();
			arr = sessionStorage.getItem(key);

			if(arr != undefined)
            		arr = JSON.parse( sessionStorage.getItem(key) );
            else 	arr = new Array();

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

        	for(var opt in subSelect.options) {
				for(var k in arr) {
					if(subSelect.options[opt].value == arr[k])
						subSelect.options[opt].selected = true;
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

	            sessionStorage.removeItem('ItemItemSubtypeVersionIds');
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
				changelog.setAttribute("href", "<?php echo $this->Html->url(array('controller' => 'itemSubtypes', 'action' => 'changelog')); ?>/"+subtypes);
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

        function SubSelectOnChange(elem) {
        	arr = new Array();
			var i, j;
			var counter = 0;

			// welcher value wurde ausgewählt
			for (i=0; i<elem.options.length; i++) {
				// elem.options[0] := (Select all)
				if(elem.options[0].selected) {
					for (j=1; j<elem.options.length; j++) {
						arr[counter] = elem.options[j].value;
						counter++;
						elem.options[j].selected = true;
					}
					break;
				}
				else if (elem.options[i].selected) {
					arr[counter] = elem.options[i].value;
					counter++;
				}
			}
			elem.options[0].selected = false;


			if(elem.id == 'item_subtype_version_id') {
				item_subtype_version_ids = arr;
				sessionStorage.setItem('ItemItemSubtypeVersionIds', JSON.stringify(item_subtype_version_ids));
			}
			if(elem.id == 'item_subtype_id') {
				item_subtype_ids = arr;
				sessionStorage.setItem('ItemItemSubtypeIds', JSON.stringify(item_subtype_ids));
				sessionStorage.removeItem('ItemItemSubtypeVersionIds');
				UpdateItemSubtypeVersion();
			}
			if(elem.id == 'item_type_id') {
				item_type_ids = arr;
				sessionStorage.setItem('ItemItemTypeIds', JSON.stringify(item_type_ids));
				sessionStorage.removeItem('ItemItemSubtypeIds');
				sessionStorage.removeItem('ItemItemSubtypeVersionIds');
				UpdateItemSubtype();
			}
			if(elem.id == 'manufacturer_id') {
				manufacturer_ids = arr;
				sessionStorage.setItem('ItemManufacturerIds', JSON.stringify(manufacturer_ids));
				sessionStorage.removeItem('ItemItemTypeIds');
				sessionStorage.removeItem('ItemItemSubtypeIds');
				sessionStorage.removeItem('ItemItemSubtypeVersionIds');
				UpdateItemType();
			}
			if(elem.id == 'project_id') {
				project_ids = arr;
				sessionStorage.setItem('ItemProjectIds', JSON.stringify(project_ids));
				sessionStorage.removeItem('ItemManufacturerIds');
				sessionStorage.removeItem('ItemItemTypeIds');
				sessionStorage.removeItem('ItemItemSubtypeIds');
				sessionStorage.removeItem('ItemItemSubtypeVersionIds');
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
    </script>

<?php
	$slide = 	'$("div.search").slideToggle("slow");
					var se = document.getElementById("searchDIV");
					if(sessionStorage.getItem("ItemSubtypeIndexFilterVisability") == 1)
						sessionStorage.setItem("ItemSubtypeIndexFilterVisability", 0);
					else
						sessionStorage.setItem("ItemSubtypeIndexFilterVisability", 1);

					 event.preventDefault();';
?>

<?php $this->Js->get('#search')->event('click', $slide); ?>

<div class="itemSubtypes index">
	<?php echo $this->Form->create('ItemSubtype', array('style' => 'width: 100%', 'type' => 'post'));?>
		<fieldset>
			<div style="display: inline">
			<?php
					if(empty($filter['name'])) {
						echo $this->Form->input('name', array(
											'div' => false,
											'label' => false,
											'placeholder' => 'Search by subtype name ...',
											'after' => '<div class="input-message" id="search">Extended search...</div>'));
					} else {
						echo $this->Form->input('name', array(
											'div' => false,
											'label' => false,
											'default' => $filter['name'],
											'after' => '<div class="input-message" id="search">Extended search...</div>'));
					}

					echo $this->Form->submit(__('Search'), array('div' => false));
			?>
			</div>

			<div class="search" id="searchDIV"> <!-- style="display: none">-->
			<table>
				<tr>
					<td>
					<?php
							if(empty($filter['project_id'])) {
								$filter['project_id'] = '';
							}

							echo $this->Form->input('project_id', array(
											'div' => false,
											'size' => 10,
											'multiple' => true,
											//'options' => $projects,
											'empty' => '(Select all)',
											'onchange' => 'SubSelectOnChange(this)',
											'default' => $filter['project_id'],
											'id' => 'project_id'));
					 ?>
					</td>
					<td><?php
							if(empty($filter['item_type_id'])) {
								$filter['item_type_id'] = '';
							}

							echo $this->Form->input('item_type_id', array(
										'div' => false, '
										size' => 10,
										'multiple' => true,
										//'options' => $itemTypes,
										'empty' => '(Select all)',
										'onchange' => 'SubSelectOnChange(this)',
										'default' => $filter['item_type_id'],
										'id' => 'item_type_id')); ?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<?php
							if(empty($filter['limit'])) {
								$filter['limit'] = 50;
							}

							$limits = array(25 => '25', 50 => '50', 100 => '100', 200 => '200', 500 => '500');
							echo $this->Form->input('limit', array(
													'options' => $limits,
													'div' => false,
													'selected' => $filter['limit'],
													'label' => 'Results/page'));
						?>
					</td>
				</tr>
			</table>
			</div>
		</fieldset>
	<?php echo $this->Form->end(); ?>

	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('shortname');?></th>
			<th><?php echo $this->Paginator->sort('item_type_id');?></th>
			<th>Project</th>
			<th>Manufacturer</th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($itemSubtypes as $itemSubtype): ?>
	<tr>
		<td><?php echo h($itemSubtype['ItemSubtype']['id']); ?>&nbsp;</td>
		<td><?php echo $this->Html->link($itemSubtype['ItemSubtype']['name'], array('action' => 'view', $itemSubtype['ItemSubtype']['id'])); ?>&nbsp;</td>
		<td><?php echo h($itemSubtype['ItemSubtype']['shortname']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($itemSubtype['ItemType']['name'], array('controller' => 'item_types', 'action' => 'view', $itemSubtype['ItemType']['id'])); ?>
		</td>
		<td>
			<?php
				$projects = array();
				foreach($itemSubtype['ItemSubtypeVersion'] as $version) {
					foreach($version['Project'] as $project) {
						$projects[$project['name']] = $project['id'];
					}
				}

				foreach($projects as $project_name => $project_id) {
					echo $this->Html->link($project_name, array('controller' => 'projects', 'action' => 'view', $project_id));
					echo ' ';
				}
			?>
		</td>
		<td>
			<?php
				$manufacturers = array();
				foreach($itemSubtype['ItemSubtypeVersion'] as $version) {
					$manufacturers[$version['Manufacturer']['name']] = $version['Manufacturer']['id'];
				}
				foreach($manufacturers as $manufacturer_name => $manufacturer_id) {
					echo $this->Html->link($manufacturer_name, array('controller' => 'manufacturers', 'action' => 'view', $manufacturer_id));
					echo ' ';
				}
			?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $itemSubtype['ItemSubtype']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $itemSubtype['ItemSubtype']['id']), null, __('Are you sure you want to delete # %s?', $itemSubtype['ItemSubtype']['id'])); ?>
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

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Subtypes'); ?></h2>
	<ul>
		<li class='active last'><?php echo $this->Html->link(__('New subtype'), array('action' => 'add')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>