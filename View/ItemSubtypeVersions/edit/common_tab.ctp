<script>
   $(document).ready(function(){
      RefreshSubSelect(document.getElementById("common_project_id"),function(){
         //Select the previously selected Manufacturer
         $("#common_manufacturer_id option").each(function(){
            if($(this).val() == "<?php echo $this->Form->value("ItemSubtypeVersion.manufacturer_id");?>"){
                $(this).attr("selected","selected");
            }
         });
      });
   });
</script>

<div id="common">
   <b>
      <?php
         echo 'Version Number: ' .$this->Form->value('ItemSubtypeVersion.version');
         echo $this->Form->hidden('version', array('value' => $this->Form->value('ItemSubtypeVersion.version')));
         echo $this->Form->hidden('has_components', array('value' => 0));
      ?>
   </b>
   <table>
      <tr>
         <td style="width: 25%">
            <?php $htmlOptions = array(	'multiple' => true,
                                 'size' => 8,
                                 'group' => 'common',
                                 'onchange' => 'RefreshSubSelect(this)'
                              );
            ?>
            <?php echo $this->Form->input('Project', array_merge($htmlOptions, array(
                                                               'id' => 'common_project_id',
                                                               'options' => $common_projects,
                                                               'controller' => 'project',
                                                               'multiple' => false,
                                                               'childId' => 'common_manufacturer_id')));
            ?>
         </td>
         <td style="width: 25%">
            <?php echo $this->Form->input('manufacturer_id', array_merge($htmlOptions, array(
                                                      'multiple' => false,
                                                      'id' => 'common_manufacturer_id',
                                                      'options' => $common_manufacturers,
                                                      'controller' => 'manufacturer',
                                                      'class'		 => 'dependent')));
            ?>
         </td>
         <td style="width: 25%">
            <?php echo $this->Form->input('name');
            ?>
         </td>
         <td style="width: 50%">
            <?php echo $this->Form->input('comment', array_merge($htmlOptions, array(	'type' => 'textarea',
                                                'id' => 'common_comment',
                                                'value' => $common_comment,
                                                'controller' => 'comment')));
            ?>
         </td>
      </tr>
   </table>
</div>

