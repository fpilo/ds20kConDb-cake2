<td>
<?php 
   foreach($item["ItemTag"] as $tag) {
      echo "<a href='".$this->Html->url(array('controller' => 'item_tags', 'action' => 'view', $tag["ItemTag"]['id']))."'>".$tag["ItemTag"]['name']."</a>&nbsp;"; 
   }
?>
</td>
