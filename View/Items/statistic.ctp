<?php echo $this->Html->css("jquery.multiple.select.css"); ?>
<?php echo $this->Html->script('jquery.multiple.select.js'); ?>
<?php echo $this->Html->script('searchItems.js'); ?>

<?php
	$slide = 	'$("div.search").slideToggle("slow");
					//var se = document.getElementById("searchDIV");
					if(sessionStorage.getItem("ItemIndexFilterVisability") == 1)
						sessionStorage.setItem("ItemIndexFilterVisability", 0);
					else
						sessionStorage.setItem("ItemIndexFilterVisability", 1);

					 event.preventDefault();';
?>
<script>
$(document).ready(function() {

	if(sessionStorage.getItem("ItemIndexFilterVisability") == 1)
		$("div.search").show();
});
</script>

<?php $this->Js->get('#search')->event('click', $slide); ?>

<div class="items index">
	<h6><?php echo $this->Html->link(__('Show items'), array('controller' => 'items', 'action' => 'index')); ?></h6>

	<?php require(dirname(__FILE__).'/search_selector.ctp'); ?>


	<?php require(dirname(__FILE__).'/../Items/statistic_table.ctp'); ?>

</div>

<div id='verticalmenu'>
	<h2><?php echo __('Statistics'); ?></h2>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
