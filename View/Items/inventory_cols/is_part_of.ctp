<td>
<?php
   if($item['isAttached']!=-1) {
      foreach($item['isAttached'] as $aid) {
         if($aid["ItemComposition"]["valid"] == 1){
            echo ' >'.$this->Html->tableLink($aid['code'],array('controller'=>'items','action'=>'view',$aid['id']));
         }
      }
   } else { echo '&nbsp;'; }
?>
</td>
