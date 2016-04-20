<script type="text/javascript">
	
    var project_id;
    var manufacturer_id;
    var item_type_id;
    var item_subtype_id;
    var item_subtype_version_id;
	
	var project = new Array();
	var manufacturer = new Array();
	var itemType = new Array();
	var itemSubtype = new Array();
	var itemSubtypeVersion = new Array();
	
	<?php
		
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
									
									echo "itemSubtype[".$project_id."][".$manufacturer_id."][".$itemType_id."][".$itemSubtype_id."] = new Option(\"".$itemSubtype_name."\", ".$itemSubtype_id.");\n\n";
								
									if(isset($itemSubtype['ItemSubtypeVersion'])) {
									
										echo "itemSubtypeVersion[".$project_id."][".$manufacturer_id."][".$itemType_id."][".$itemSubtype_id."] = new Array();\n";
								
										foreach($itemSubtype['ItemSubtypeVersion'] as $itemSubtypeVersion) {
											$itemSubtypeVersion_id = $itemSubtypeVersion['id'];
											$itemSubtypeVersion_name = $itemSubtypeVersion['version'];
											
											echo "itemSubtypeVersion[".$project_id."][".$manufacturer_id."][".$itemType_id."][".$itemSubtype_id."][".$itemSubtypeVersion_id."] = new Option(\"".$itemSubtypeVersion_name."\", ".$itemSubtypeVersion_id.");\n\n";
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
			project_id = <?php echo $item['Item']['project_id']; ?>;
			manufacturer_id = <?php echo $item['Item']['manufacturer_id']; ?>;
			item_type_id = <?php echo $item['Item']['item_type_id']; ?>;
			item_subtype_id = <?php echo $item['Item']['item_subtype_id']; ?>;
			item_subtype_version_id = <?php echo $item['Item']['item_subtype_version_id']; ?>;
			
			StartSubSelect('project_id', project, project_id);
			if(project_id != null) {
				StartSubSelect('manufacturer_id', manufacturer[project_id], manufacturer_id);
				if(manufacturer_id != null) {
					StartSubSelect('item_type_id', itemType[project_id][manufacturer_id], item_type_id);
					if(item_type_id != null) {
						StartSubSelect('item_subtype_id', itemSubtype[project_id][manufacturer_id][item_type_id], item_subtype_id);
						if(item_subtype_id != null) {
							StartSubSelect('item_subtype_version_id', itemSubtypeVersion[project_id][manufacturer_id][item_type_id][item_subtype_id], item_subtype_version_id);
							var s = document.getElementById('show_changelog');
							s.setAttribute("href", "<?php echo $this->Html->url(array('controller' => 'itemSubtypes', 'action' => 'changelog')); ?>/"+item_subtype_id);
						}
					}
				}
			}
		});
		
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
        	if(elem.id == 'project_id') {
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
					s.setAttribute("href", "<?php echo $this->Html->url(array('controller' => 'itemSubtypes', 'action' => 'changelog')); ?>/"+item_subtype_id);
				}
			} else if(elem.id == 'item_subtype_version_id') {
				if(elem.selectedIndex >= 0) {
					item_subtype_version_id = elem.options[elem.selectedIndex].value;
		            sessionStorage.setItem('ItemAddItemSubtypeVersionId', item_subtype_version_id);
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
    </script>


<div class="items form">
<?php echo $this->Form->create('Item');?>
	<fieldset>
		<legend><?php echo __('Select new Subtype Version'); ?></legend>
		<table cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<?php 
							echo $this->Form->input('id');
							echo $this->Form->input('project_id', array(
														'style' => 'width: 100px',
														'onchange' => 'RefreshSubSelect(this)',
														'size' => 8,
														'id' => 'project_id'));
						?>
					</td>
					<td>
						<?php 
							echo $this->Form->input('manufacturer_id', array(
														'disabled' => 'disabled',
														'options' => array('Select a project'),
														'style' => 'width: 100px',
														'onchange' => 'RefreshSubSelect(this)',
														'size' => 8,
														'id' => 'manufacturer_id')); 
						?>
					</td>
					<td>
						<?php 
							echo $this->Form->input('item_type_id', array(
														'disabled' => 'disabled',
														'style' => 'width: 100px',
														'onchange' => 'RefreshSubSelect(this)',
														'size' => 8,
														'options' => array('Please select a manufacturer'),
														'id' => 'item_type_id'));
						?>
					</td>
					<td>
						<?php 
							echo $this->Form->input('item_subtype_id', array(
														'disabled' => 'disabled',
														'style' => 'width: 100px',
														'onchange' => 'RefreshSubSelect(this)',
														'size' => 8,
														'options' => array('Please select a item type'),
														'id' => 'item_subtype_id'));
						?>
					</td>
					<td>					
						<?php 
							echo $this->Form->input('item_subtype_version_id', array(
														'disabled' => 'disabled',
														'style' => 'width: 100px',
														'onchange' => 'RefreshSubSelect(this)',
														'size' => 8, 'options' => array('Please select a subtype'),
														'id' => 'item_subtype_version_id'));
						?>
						<?php echo $this->Html->link(__('Show Changelog'), array('controller' => 'itemSubtypes', 'action' => 'changelog'), array('id' => 'show_changelog', 'target' => '_blank')); ?>
					</td>
				</tr>
				<tr>
					<td colspan="5">
						<?php echo $this->Form->input('History.comment', array('default' => '')); ?>						
					</td>
				</tr>
		</table>		
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
	<h2>Change subtype version</h2>
	<ul>
		<li class='active'><?php echo $this->Html->link(__('Cancel'), array('action' => 'view', $this->Form->value('Item.id')));?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>