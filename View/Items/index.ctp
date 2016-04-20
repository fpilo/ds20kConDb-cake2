
<?php echo $this->Html->css("jquery.multiple.select.css"); ?>
<?php echo $this->Html->script('jquery.multiple.select.js'); ?>
<?php echo $this->Html->script('searchItems.js'); ?>

<?php
	$slide = 	'$("div.search").slideToggle("slow");
					//var se = document.getElementById("searchDIV");
					if(sessionStorage.getItem("ItemIndexFilterVisibility") == 1)
						sessionStorage.setItem("ItemIndexFilterVisibility", 0);
					else
						sessionStorage.setItem("ItemIndexFilterVisibility", 1);

					 event.preventDefault();';
?>
<script>
$(document).ready(function() {
	// var tagSelected = false;
	// $("#tag_id option").each(function(){
		// if($(this).attr("selected") == "selected") tagSelected = true
	// });
	// if(!tagSelected){
		// //Only replace all tags when there is none selected
		// $("#tag_id option:first").attr("selected","selected");
		// SelectAll(document.getElementById("tag_id"));
	// }
//
	if(sessionStorage.getItem("ItemIndexFilterVisibility") == 1)
		$("div.search").show();
});
</script>

<?php $this->Js->get('#search')->event('click', $slide); ?>

<div class="items index">
		<h6><?php echo $this->Html->link(__('Show Statistic'), array('controller' => 'items', 'action' => 'statistic')); ?></h6>


	<?php require(dirname(__FILE__).'/search_selector.ctp'); ?>


	<?php require(dirname(__FILE__).'/inventory_table.ctp'); ?>

</div>

<div id='verticalmenu'>
	<h2><?php echo __('Inventory'); ?></h2>
	<ul>
		<li class='active last'><?php echo $this->Html->link(__('Download table as .csv'), array('action' => 'generateCsv')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
