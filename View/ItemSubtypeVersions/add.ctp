<?php
   // $this->Html->addCrumb('Item Types', '/item_types/index/');
   $this->Html->addCrumb($parentItemSubtype['ItemType']['name'], '/item_types/view/'.$parentItemSubtype['ItemType']['id']);
   // $this->Html->addCrumb('Item Subtypes', '/item_subtypes/index/');
   $this->Html->addCrumb($parentItemSubtype['ItemSubtype']['name'], '/item_subtypes/view/'.$parentItemSubtype['ItemSubtype']['id']);
?>

<script type="text/javascript">
   var prefix = "addSubtypeVersion";
   var add_or_edit = 'Add';
   var session_id= 'addItemSubtypeVersion.<?php echo $parentItemSubtype["ItemSubtype"]["id"]; ?>';
  
    <?php require(dirname(__FILE__).'/javascript.ctp'); ?>

   function IsStockChanged(elem) {
      var pos = elem.name.split("][");
      var dummy = pos[1];
      var isStock = elem.checked;

      var att = document.getElementsByName("data[SubtypeComponent]["+dummy+"][attached]")[0];

      document.getElementById(att.id).checked = false;
      document.getElementById(att.id).value = 0;
      AttachedChanged(att);

      if(isStock)
         var value = 1;
      else
         var value = 0;

      $.post(
         '<?php echo $this->Html->url(array('controller' => 'itemSubtypeVersions', 'action' => 'changeComponent')); ?>/',
         {dummy: dummy, field: 'is_stock', value: value, session: session_id }
      );
   }

</script>

<div id="dialog" title="Missing Version" style="display: none;">
   <p>You have not selected a version. Do you wish to allow all versions (including future) or cancel and choose?</p>
</div>

<div class="itemSubtypeVersions form">
<?php 
   echo $this->Form->create('ItemSubtypeVersion', array('type' => 'file'));
   echo $this->Form->hidden('session',array('value'=>'addItemSubtypeVersion.'.$parentItemSubtype["ItemSubtype"]["id"]));
?>
   <fieldset>

      <legend><?php echo __('Create a new version of ' .$parentItemSubtype['ItemType']['name'].' ' .$parentItemSubtype['ItemSubtype']['name']); ?></legend>

   <!-- Accordion -->
      <div id="tabs" class='related'>
         <ul>
            <li><a href="#common"><?php echo __('Common Data'); ?></a></li>
            <li><a href="#components"><?php echo __('Components'); ?></a></li>
         </ul>

         <div id="common">
            <b>
               <?php
                  if(isset($latest_version['ItemSubtypeVersion']['version']))
                     $version = $latest_version['ItemSubtypeVersion']['version']+1;
                  else {
                     $version = 1;
                  }

                  echo 'Version Number: ' .$version;
                  echo $this->Form->hidden('version', array('value' => $version));
               ?>
            </b>
         <table>
            <tr>
               <td>
                  <?php echo $this->Form->input('Project', array(
                                                'size' => 8,
                                                'style' => 'width: 90%',
                                                'onchange' => 'RefreshSubSelect(this)',
                                                'multiple' => false,
                                                ));
                  ?>
               </td>
               <td><?php echo $this->Form->input('manufacturer_id', array(
                                                'size' => 8,
                                                'disabled' => true,
                                                'options' => array('' => 'Select a project'),
                                                'style' => 'width: 90%',
                                                'onchange' => 'RefreshSubSelect(this)',
                                                'class'=> 'dependent')); ?></td>
               <td><?php echo $this->Form->input('name'); ?></td>
               <td><?php echo $this->Form->input('comment', array(
                                                'type' => 'textarea',
                                                'style' => 'width: 90%'))?></td>
            </tr>
         </table>
         </div>

         <div id="components">
         
         <?php require(dirname(__FILE__).'/components_tab.ctp'); ?>
   
         <div align="center" class="actions"  style="width: 100%">
         <table  style="width: 35%">
            <tr>
               <td><?php echo $this->Html->link(__('Reload'), array('controller' => 'itemSubtypeVersions', 'action' => 'resetAdd', $parentItemSubtype['ItemSubtype']['id'])); ?></td>
               <td><?php echo $this->Html->link(__('Remove all'), array('controller' => 'itemSubtypeVersions', 'action' => 'removeAllComponents', $parentItemSubtype['ItemSubtype']['id'])); ?></td>
            </tr>
         </table>
         </div>

         <br>
         <br>

         </div>
      </div>
   </fieldset>

<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
   <h2><?php  echo __('Item Subtype: '. $parentItemSubtype['ItemSubtype']['name']);?></h2>
   <ul>
      <li class='active last'><?php echo $this->Html->link(__('Cancel'), array('controller' => 'item_subtypes', 'action' => 'view', $parentItemSubtype['ItemSubtype']['id'])); ?> </li>
   </ul>
   <?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
