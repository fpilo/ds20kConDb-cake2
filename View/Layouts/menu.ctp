<?php

	$itemMenuData = array();
	//Get the menu structure from the itemSubtypesVersion Array
	$itemData = json_decode($this->My->toJson($itemSubtypeVersions),true);
	//Start with the itemTypes Section
	$itemMenuData[] = "<li class=' has-sub'>".$this->Html->link(__('Item Types'), array('plugin' => null, 'controller' => 'item_types', 'action' => 'index'))."</a>";
	$itemMenuData[] = "<ul>";
	
	if(!empty($emptyItemTypes)){

		foreach($emptyItemTypes as $eit_key => $eit_val) {
			$itemData[2][$eit_key] = $eit_val;
		}
		
	}
	
	   foreach($itemData[2] as $item_type_id=>$itemType){
	   
			$itemMenuData[] = "<li class='active has-sub'>".$this->Html->link($itemType["n"], array('plugin' => null, 'controller' => 'item_types', 'action' => 'view',$item_type_id))."</a>";
			$itemMenuData[] = "<ul>";
			if(array_key_exists('s',$itemType)) foreach($itemType["s"] as $item_subtype_id=>$itemSubtype){
				//Set the subtype name with the manufacturer
				$itemMenuData[] = "<li class='has-sub wide'>".$this->Html->link($itemSubtype["n"], array('plugin' => null, 'controller' => 'item_subtypes', 'action' => 'view',$item_subtype_id))."</a>";
				$itemMenuData[] = "<ul>";
				if(array_key_exists('v',$itemSubtype)) foreach($itemSubtype["v"] as $item_subtype_version_id=>$itemSubtypeVersion){
					//Manufacturer is set either separately or globally for an item subtype or even an item type, need to go through it in hierarchy to set it correctly
					if(isset($itemSubtypeVersion["m"])){
						$m = $itemSubtypeVersion["m"][0];
					}elseif(isset($itemSubtype["m"])){
						$m = $itemSubtype["m"][0];
					}else{
						$m = $itemType["m"][0];
					}
					$itemMenuData[] = "<li class='active has-sub'>".$this->Html->link($itemSubtypeVersion["n"]." - ".$itemData[1][$m]["n"], array('plugin' => null, 'controller' => 'item_subtype_versions', 'action' => 'view',$item_subtype_version_id))."</a>";
					$itemMenuData[] = "</li>";
				}
				if($this->Session->check('Auth.User.Permissions.controllers/AclManager/Acl/index'))
					$itemMenuData[] = "<li class='active new'>".$this->Html->link(__('Add new Version'), array('plugin' => null, 'controller' => 'item_subtype_versions', 'action' => 'add',$item_subtype_id))."</a>";
				$itemMenuData[] = "</ul>";
				$itemMenuData[] = "</li>"; //Close the item SubtypeVersion section
			}
			if($this->Session->check('Auth.User.Permissions.controllers/AclManager/Acl/index'))
				$itemMenuData[] = "<li class='active new wide'>".$this->Html->link(__('Create new Item Subtype'), array('plugin' => null, 'controller' => 'item_subtypes', 'action' => 'add'))."</a>";
			$itemMenuData[] = "</ul>";
			$itemMenuData[] = "</li>"; //Close the item Subtype section
		
		}
	
	if($this->Session->check('Auth.User.Permissions.controllers/AclManager/Acl/index'))
		$itemMenuData[] = "<li class='active new'>".$this->Html->link(__('Create new Item Type'), array('plugin' => null, 'controller' => 'item_types', 'action' => 'add'))."</a>";
	$itemMenuData[] = "</ul>";
	$itemMenuData[] = "</li>"; //Close the item type section
#	debug($itemData);
?>

<ul id="main-menu" class="sm sm-darkblue sm-vertical">
	<li class='active has-sub'><?php echo $this->Html->link(__('Items'), array('plugin' => null, 'controller' => 'items', 'action' => 'index')); ?></a>
		<ul>
			<li class='active'><?php echo $this->Html->link(__('Create'), array('plugin' => null, 'controller' => 'items', 'action' => 'create')); ?></a></li>
			<?php echo implode("",$itemMenuData); ?>
		</ul>
   </li>
   <li class='active has-sub'>
   		<?php
   			if(isset($incomingTransfers))
				$transfersCount = " (in ".$incomingTransfers.", out ".$outgoingTransfers.")";
			else
				$transfersCount = "";
   		?>
		<?php echo $this->Html->link(__('Transfers').$transfersCount, array('plugin' => null, 'controller' => 'transfers', 'action' => 'index')); ?></a>
   </li>
   <li class='active has-sub'><?php echo $this->Html->link(__('Measurements'), array('plugin' => null, 'controller' => 'measurements', 'action' => 'index',"sort"=>"id","direction"=>"desc")); ?></a>
      <ul>
		<li class='last'>
				<?php echo $this->Html->link(__('Add Measurement'), array('plugin' => null, 'controller' => 'measurements', 'action' => 'add')); ?></a>
		</li>
		<li class='last'>
				<?php echo $this->Html->link(__('Measurement Tags'), array('plugin' => null, 'controller' => 'measurement_tags', 'action' => 'index')); ?></a>
		</li>
		<li class='last'>
				<?php echo $this->Html->link(__('Parameter matching'), array('plugin' => null, 'controller' => 'matchings', 'action' => 'index')); ?></a>
		</li>
      </ul>
   </li>
   <li class='active has-sub'><?php echo $this->Html->link(__('Checklists'), array('plugin' => null, 'controller' => 'checklists', 'action' => 'index')); ?></a>
	<ul>
		<li class='active has-sub'>
				<?php echo $this->Html->link(__('ClTemplates'), array('controller' => 'clTemplates', 'action' => 'index')); ?></a>
					<ul>
					   <li class='active last'>
							<?php echo $this->Html->link(__('Create new template'), array('plugin' => null, 'controller' => 'clTemplates', 'action' => 'add')); ?></a>
					   </li>
					</ul>
        </li>
		<li class='active has-sub last'>
				<?php echo $this->Html->link(__('ClStates'), array('controller' => 'clStates', 'action' => 'index')); ?></a>
					<ul>
					   <li class='active last'>
							<?php echo $this->Html->link(__('Create new state'), array('plugin' => null, 'controller' => 'clStates', 'action' => 'add')); ?></a>
					   </li>
					</ul>
        </li>
	</ul>
   </li>
   <li class='active'><?php echo $this->Html->link(__('History'), array('plugin' => null, 'controller' => 'histories', 'action' => 'index')); ?></a>
   </li>
   <li class='has-sub last'><a href='#'>Management</a>
      <ul class='long'>
		 <?php if($this->Session->check('Auth.User.Permissions.controllers/AclManager/Acl/index')) : ?>
		 <li class='active has-sub'><?php echo $this->Html->link(__('Administration'), array('plugin' => 'acl_manager','controller' => 'acl', 'action' => 'index')); ?></a>
			<ul>
			    <li><?php echo $this->Html->link(__('Users'), array('plugin' => null, 'controller' => 'users', 'action' => 'index')); ?></a></li>
			    <li><?php echo $this->Html->link(__('Groups'), array('plugin' => null, 'controller' => 'groups', 'action' => 'index')); ?></a></li>
			    <li><?php echo $this->Html->link(__('Permissions'), array('plugin' => 'acl_manager','controller' => 'acl', 'action' => 'permissions')); ?></a></li>
				<li class='last'><?php echo $this->Html->link(__('Log'), array('plugin' => null, 'controller' => 'logs', 'action' => 'index')); ?></a></li>
		 	</ul>
		 </li>
		 <?php endif; ?>
         <li class='active has-sub'><?php echo $this->Html->link(__('Locations'), array('plugin' => null, 'controller' => 'locations', 'action' => 'index')); ?></a>
            <ul>
               <li class='active last'><?php echo $this->Html->link(__('Create new location'), array('plugin' => null, 'controller' => 'locations', 'action' => 'add')); ?></a></li>
            </ul>
         </li>
         <li class='active has-sub'><?php echo $this->Html->link(__('Projects'), array('plugin' => null, 'controller' => 'projects', 'action' => 'index')); ?>
            <ul>
               <li class='active last'><?php echo $this->Html->link(__('Create new project'), array('plugin' => null, 'controller' => 'projects', 'action' => 'add')); ?></a></li>
            </ul>
         </li>
         <li class='active has-sub'><?php echo $this->Html->link(__('Manufacturers'), array('plugin' => null, 'controller' => 'manufacturers', 'action' => 'index')); ?></a>
            <ul>
               <li class='active last'><?php echo $this->Html->link(__('Create new manufacturer'), array('plugin' => null, 'controller' => 'manufacturers', 'action' => 'add')); ?></a></li>
            </ul>
         </li>
         <li class='active has-sub'><?php echo $this->Html->link(__('Measurements'), array('controller' => 'measurements', 'action' => 'index')); ?></a>
			<ul>
				<li><?php echo $this->Html->link(__('Measurement Setups'), array('controller' => 'devices', 'action' => 'index')); ?></a></li>
				<li><?php echo $this->Html->link(__('Measurement Sets'), array('controller' => 'measurement_sets', 'action' => 'index')); ?></a></li>
				<li><?php echo $this->Html->link(__('Measurement Types'), array('controller' => 'measurement_types', 'action' => 'index')); ?></a></li>
				<li><?php echo $this->Html->link(__('Measurement Tags'), array('controller' => 'measurement_tags', 'action' => 'index')); ?></a></li>
				<li class='last'><?php echo $this->Html->link(__('Parameters'), array('controller' => 'parameters', 'action' => 'index')); ?></a></li>
			</ul>
		</li>
         <li class='active has-sub'><?php echo $this->Html->link(__('ClTemplates'), array('controller' => 'clTemplates', 'action' => 'index')); ?></a>
            <ul>
               <li class='active last'><?php echo $this->Html->link(__('Create new template'), array('plugin' => null, 'controller' => 'clTemplates', 'action' => 'add')); ?></a></li>
            </ul>
         </li>
         <li class='active has-sub'><?php echo $this->Html->link(__('Qualities'), array('controller' => 'item_qualities', 'action' => 'index')); ?></a>
            <ul>
               <li class='active last'><?php echo $this->Html->link(__('Create new quality'), array('plugin' => null, 'controller' => 'item_qualities', 'action' => 'add')); ?></a></li>
            </ul>
         </li>
         <li class='active has-sub'><?php echo $this->Html->link(__('Deliverers'), array('plugin' => null, 'controller' => 'deliverers', 'action' => 'index')); ?></a>
            <ul>
               <li class='active last'><?php echo $this->Html->link(__('Create new deliverer'), array('plugin' => null, 'controller' => 'deliverers', 'action' => 'add')); ?></a></li>
            </ul>
         </li>
         <li class='active has-sub'><?php echo $this->Html->link(__('Tag Cloud'), array('plugin' => null, 'controller' => 'ProjectsItemTypes', 'action' => 'index')); ?></a></li>
		 <li class='active'><?php echo $this->Html->link(__('Tags'), array('plugin' => null, 'controller' => 'item_tags', 'action' => 'index')); ?></a></li>
      </ul>
   </li>
</ul>
