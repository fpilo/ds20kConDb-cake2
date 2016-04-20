<?php

   $options = array(
      'type' => 'select',
      'size' => 8,
      'options' => $list_states,
      'style' => 'width: 400px;',
      'label' => 'state'
   );

   if(isset($label)) {
      $options['label'] = $label;
   }
   if(isset($multiple) && $multiple) {
      $options['multiple'] = 'multiple';
      $options['label'] .= ' (ctrl-click to select multiple)';
   }
   echo $this->Form->input('state', $options);

?>
