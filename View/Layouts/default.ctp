<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link		  http://cakephp.org CakePHP(tm) Project
 * @package	   app.View.Layouts
 * @since		 CakePHP(tm) v 0.10.0.1076
 * @license	   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo __('DS20KCONDB: '); ?>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('individual');
		echo $this->Html->css('cake.generic');
		echo $this->Html->css('menu_assets/styles');
		echo $this->Html->css('smart-menu/sm-core-css');
		echo $this->Html->css('smart-menu/sm-darkblue/sm-darkblue');
		echo $this->Html->css('print', 'stylesheet', array('media' => 'print'));

		/* Designs */
		//echo $this->Html->css('ui-lightness/jquery-ui-1.8.21.custom.css');
		//echo $this->Html->css('cupertino/jquery-ui-1.9.2.custom.css');
		echo $this->Html->css('cupertino/jquery-ui-1.11.1.min.css');

		echo $this->Html->script('jquery-1.10.2'); // Include JQuery library
		echo $this->Html->script('jquery-ui-1.11.1.min'); // Include JQuery UI library
		echo $this->Html->script('jquery.smartmenus'); // Include JQuery Smart Menus plugin
		echo $this->Html->script('jquery.tableSelect'); // Include JS Script
		echo $this->Html->script('saveScrollPosition');
		echo $this->Html->script('autoFontSize');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>

	<?php
	$eventCode = 	'$("div.debug:visible").slideUp("slow");
					 $("div.debug:hidden").slideDown("slow");
							 event.preventDefault();';
	?>

	<?php $this->Js->get('#debug')->event('click', $eventCode); ?>
	<script type='text/javascript'>
		$( document ).ajaxError(function() {
		  //Ajax error happened, probably automatic logout, reload the page...
		  location.reload();
		});
		$(function(){
			$(document).bind("ajaxStart", function(){
				$("html").addClass('busy');
			}).bind("ajaxStop", function(){
				$("html").removeClass('busy');
			});
			$('#main-menu').smartmenus({hideTimeout:1000});
			$('#action-menu').smartmenus({hideTimeout:1000});
			$("#verticalmenu > ul").addClass("sm sm-darkblue sm-vertical"); //Add class to previous element to design it correctly
		});	
		
		function restoreTabs(key){
			previousTabIndex = sessionStorage.getItem(key);
			if(previousTabIndex == null) {
				previousTabIndex = 0;
			}

			var $tabs = $('#tabs').tabs(  {
							active: previousTabIndex,
							activate: function(e, ui) {
								//track the new index
								previousTabIndex = ui.newTab.index();
								sessionStorage.setItem(key,previousTabIndex);
							}
						});
		}
	</script>
</head>
<body>
	<div id="container">
		<div id='cssmenu'>
		<?php
			echo $this->Html->getCrumbList('breadCrumb', array(
				//'text' => $this->Html->image('home.png'),
				'text' => 'Home',
				'url' => array('plugin' => null,'controller' => 'items', 'action' => 'index'),
				'escape' => false));
		?>
		<ul>
		   <!-- <li class='center'>ds20kConDb</li> -->

		   <?php $username = Inflector::humanize($this->Session->read('Auth.User.username')); ?>
		   <?php if(!empty($username)): ?>
		   <li class='has-sub last'><?php echo $this->Html->link($username."@".$standardLocation["Location"]["name"], array('plugin' => null,'controller' => 'users', 'action' => 'edit_self')); ?>
			  <ul>
				 <li><?php echo $this->Html->link(__('Change Password'), array('plugin' => null,'controller' => 'users', 'action' => 'changePassword')); ?></li>
				<li><?php echo $this->Html->link(__('Change Standard Location'), array('plugin' => null,'controller' => 'users', 'action' => 'changeStandardLocation'));?></li>
				 <li class='last'><?php echo $this->Html->link(__('Logout'), array('plugin' => null,'controller' => 'users', 'action' => 'logout')); ?></li>
			  </ul>
		   </li>
		   <?php endif; ?>
		</ul>
		</div>

		<div id="content">
			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>

		<div id="buttons">
			<?php echo $this->Html->image('cake.power.gif', array(
															 'alt' => __('Show Debug'),
															 'border' => '0',
															 'id'=>'debug',
															 'width'=>'75',
															 'height'=>'15')); ?>
			<?php echo $this->Html->image('bug.png', array(
															'title' => 'Bug report',
															'alt' => __('Bug report'),
															'border' => '0',
															'width'=>'16',
															'height'=>'16',
															'url' => 'https://bitbucket.org/hephy/hephydb/issues',
															'linkOptions' => array('target' => '_blank')
															));
															if($_SERVER["HTTP_HOST"] == "localhost"){
																echo "Time to render: ".round(microtime(true) - TIME_START, 3);
																echo " Memory peak usage: ".round(memory_get_peak_usage()/(pow(1024, 2)), 3)."MB";
															}
															?>
		</div>
		<div class="debug" style="display: none">
			<?php //echo $this->element('sql_dump'); ?>
		</div>
	</div>
	<?php
		//echo $this->Js->writeBuffer(array('cache' => TRUE)); // Write cached scripts
		echo $this->Js->writeBuffer(); // Write cached scripts
	?>
</body>
</html>
