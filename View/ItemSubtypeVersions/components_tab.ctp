   <table class="DefaultSelectors" cellpadding="0" cellspacing="0" id="table_component_subtypes">
      <td colspan="5">
      <?php
         echo $this->element("standard_selector",array(
            "hideLocation" => true,
            "multiple" => true
         ));
         echo $this->element("state_selector",array(
            "multiple" => true,
            "label" => 'Required state(s). Select none to allow all.'
         ));
      ?>
      </td>
   <tr>
      <td></td>
      <td></td>
      <td><input type="button" name="AddButton" value="Add Component" id="AddButton" style="width: 150px"/></td>
      <td></td>
      <td></td>
   </tr>
   </table>
   <?php
      if($editWithAttached) {
         echo $this->Form->hidden("manufacturer_id");
         echo $this->Form->hidden("ItemSubtypeVersion.version");
      }
   ?>
   <?php echo $this->Form->hidden("editWithAttached",array("value"=>$editWithAttached)); ?>
   <?php require(dirname(__FILE__).'/update_components.ctp'); ?>

<br>
<br>
