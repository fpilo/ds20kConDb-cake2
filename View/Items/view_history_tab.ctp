<div id="histories">
   <?php if (!empty($history)):?>
         <table cellpadding = "0" cellspacing = "0">
         <tr>
            <th><?php echo __('Event Type'); ?></th>
            <th><?php echo __('Comment'); ?></th>
            <th><?php echo __('Created'); ?></th>
            <th><?php echo __('Modified'); ?></th>
            <th><?php echo __('Responsible User'); ?></th>
         </tr>
         <?php foreach ($history as $h): ?>
            <tr>
               <td><?php echo $this->Html->link($h['Event']['name'], array('controller' => 'events', 'action' => 'view', $h['Event']['id'])); ?></td>
               <td><?php echo $h['History']['comment'];?>
                  &nbsp;
                  <?php echo $this->Html->image('ElegantBlueWeb/settings.png', array(
                                                               'alt' => __('edit'),
                                                               'border' => '0',
                                                               'width'=>'16',
                                                               'height'=>'16',
                                                               'style' => 'float: right',
                                                               'url' => array('controller' => 'histories', 'action' => 'editComment', $h['History']['id'])));
                  ?>
               </td>
               <td><?php echo $h['History']['created'];?></td>
               <td><?php echo $h['History']['modified'];?></td>
               <td>
                  <?php 	if(isset($h['User']['username']))
                           echo $this->Html->link($h['User']['username'], array('controller' => 'users', 'action' => 'view', $h['User']['id']));
                        else
                           echo 'Unknown';
                  ?>
               </td>
            </tr>
         <?php endforeach; ?>
         </table>
   <?php endif; ?>
</div>

