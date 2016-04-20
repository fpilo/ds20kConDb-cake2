<?php
   // $this->Html->addCrumb('Item Types', '/item_types/index/');
   $this->Html->addCrumb($this->data['ItemSubtype']["ItemType"]['name'], '/item_types/view/'.$this->data['ItemSubtype']["ItemType"]['id']);
   // $this->Html->addCrumb('Item Subtypes', '/item_subtypes/index/');
   $this->Html->addCrumb($this->data['ItemSubtype']['name'], '/item_subtypes/view/'.$this->data['ItemSubtype']['id']);
   $stVersionName = ($this->data['ItemSubtypeVersion']['name'] != "")? $this->data['ItemSubtypeVersion']['version']." (".$this->data['ItemSubtypeVersion']['name'].")": "v".$this->data['ItemSubtypeVersion']['version'];
   $this->Html->addCrumb($stVersionName, '/item_subtype_versions/view/'.$this->data['ItemSubtypeVersion']['id']);
?>

<script type="text/javascript">
   var prefix = "editSubtypeVersion";
   var add_or_edit = 'Edit';
   var session_id = 'editItemSubtypeVersion.<?php echo $this->Form->value('ItemSubtypeVersion.id'); ?>';

   <?php require(dirname(__FILE__).'/javascript.ctp'); ?>

   function IsStockChanged(elem) {
      var pos = elem.name.split("][");
      var dummy = pos[1];
      var isStock = elem.checked;

      if(isStock)
         var value = 1;
      else
         var value = 0;

      $.post(
         '<?php echo $this->Html->url(array('controller' => 'itemSubtypeVersions', 'action' => 'changeComponent')); ?>/',
         {dummy: dummy, field: 'is_stock', value: value, session: session_id }
      );
   }

   function AllVersionsChanged(elem) {
      var pos = elem.name.split("][");
      var dummy = pos[1];
      var allVersions = elem.checked;

      if(allVersions)
         var value = 1;
      else
         var value = 0;

      $.post(
         '<?php echo $this->Html->url(array('controller' => 'itemSubtypeVersions', 'action' => 'changeComponent')); ?>/',
         {dummy: dummy, field: 'all_versions', value: value, session: session_id }
      );
   }

</script>

<div id="dialog" title="Missing Version" style="display: none;">
   <p>You have not selected a version. Do you wish to allow all versions (including future) or cancel and choose?</p>
</div>

<div class="itemSubtypeVersions form">
<?php
   if(!$editWithAttached) {
      echo $this->Form->create('ItemSubtypeVersion');
   } else {
      echo $this->Form->create('ItemSubtypeVersion',array('onsubmit'=>'return confirm("Components cannot be removed from this configuration after submitting the data. Are you sure you want to continue?");'));
   }
   ?>
   <fieldset>
      <legend><?php echo __('Edit version ' .$this->Form->value('ItemSubtypeVersion.version'). ' of '.$this->request->data['ItemSubtype']['ItemType']['name'].' - '.$this->request->data['ItemSubtype']['name']); ?></legend>
      <?php echo $this->Form->input('id');?>

      <div id="tabs" class='related'>
         <ul>
            <?php if(!$editWithAttached) { echo '<li><a href="#common">'.__('Common Data').'</a></li>'; } ?>
            <li><a href="#components"><?php echo __('Components'); ?></a></li>
         </ul>

         <?php if(!$editWithAttached) { require(dirname(__FILE__).'/edit/common_tab.ctp'); } ?>

         <div id="components">
            
            <?php require(dirname(__FILE__).'/components_tab.ctp'); ?>

            <div align="center" class="actions" >
            <?php echo $this->Html->link(__('Reload'), array('controller' => 'itemSubtypeVersions', 'action' => 'resetEdit', $this->Form->value('ItemSubtypeVersion.id'))); ?>
            </div>

            <br>
            <br>
         </div>
      </div>
   </fieldset>

<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
   <h2><?php  echo __('Item Subtype Versions');?></h2>
   <ul>
      <li class='active' ><?php echo $this->Html->link(__('Cancel'), array('controller' => 'itemSubtypeVersions', 'action' => 'view', $this->Form->value('ItemSubtypeVersion.id'))); ?> </li>
      <li class='active last'><?php echo $this->Form->postLink(__('Delete'), array(
                     'action' => 'delete',
                     $this->Form->value('ItemSubtypeVersion.id')),
                     null,
                     __('Are you sure you want to delete version '.$this->Form->value('ItemSubtypeVersion.version').' from '.$this->Form->value('ItemSubtype.ItemType.name').' '.$this->Form->value('ItemSubtype.name').'?')); ?></li>
   </ul>
   <?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
