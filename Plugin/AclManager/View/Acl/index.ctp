<div class="view">
	<h5>Version Informations:</h5>
	
	
	<span class="notice success">
	<?php echo __('CakePHP: ' . Configure::version()); ?>
	</span>	
	
	<span class="notice success">
	<?php echo __('Acl Manager: ' . Configure::read('AclManager.version')); ?>    
	</span>
	
	<span class="notice success">
	<?php echo 'PHP: ' . phpversion(); ?>    
	</span>

	<span class="notice success">
	<?php echo 'Folder with original files: ' . $mOrig; ?>    
	</span>

	<span class="notice success">
	<?php echo 'Folder with converted files: ' . $mConv; ?>    
	</span>

	<span class="notice success">
	<?php echo 'Folder with cached files: ' . $mCache; ?>    
	</span>

	<span class="notice success">
	<?php echo 'Folder with uploaded files (should be almost empty): ' . $mTmp; ?>    
	</span>
	<div class="actions">
	<?php echo $this->Html->link("Clear Cache",array("controller"=>"Acl","action"=>"clearCache")); ?>
	</div>
	<!--
	<p>This plugin allows you to easily manage your permissions. To use it you need to set up your Acl environment.</p>
	<p>Note: This plugin has only been designed to work with Actions as authorizer ($this->Auth->autorize = 'Actions').</p>
	-->
	<p>&nbsp;</p>
</div>

<div id='verticalmenu'>
	<h2>Administration</h2>
	<ul>
		<li class='active'><?php echo $this->Html->link(__('Permissions'), array('action' => 'permissions')); ?></li>
		<li class='active'><?php echo $this->Html->link(__('Groups'), array('plugin' => null, 'controller' => 'groups', 'action' => 'index')); ?></li>
		<li class='active'><?php echo $this->Html->link(__('Users'), array('plugin' => null, 'controller' => 'users', 'action' => 'index')); ?></li>
		<li class='active last'><?php echo $this->Html->link(__('Log'), array('plugin' => null, 'controller' => 'logs', 'action' => 'index')); ?></li>		
	</ul>
	<?php require(dirname(__FILE__).'/../../../../View/Layouts/menu.ctp'); ?>
</div>
