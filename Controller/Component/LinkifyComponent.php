<?php

App::uses('Component', 'Controller');

class LinkifyComponent extends Component {

   private $Item, $Html;

   public function history_items($history) {
      $this->Item = ClassRegistry::init('Item',true);
      $this->Html = (new View())->loadHelper('Html');
      foreach($history as $key=>$history_entry) {
         $comment_replace = array();
         $history[$key]['History']['comment'] = preg_replace_callback("/'([^\s]*)'/U",array($this,'_history_items_callback'),$history_entry['History']['comment']);
      }
      return $history;
   }

   protected function _history_items_callback($matches){
      $id = $this->Item->find('first',array('conditions'=>array('code'=>$matches[1])));
      if(!empty($id)) {
         $id = $id['Item']['id'];
         return $this->Html->link($matches[0],array('controller'=>'items','action'=>'view',$id));
         //return Router::url(array('controller'=>'items','action'=>'view',$id));
      } else return $matches[0];
   }

}

?>
