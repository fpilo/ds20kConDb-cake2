<?php $plotLoaded = false;
require(dirname(__FILE__).'/plot.ctp'); ?>
<?php require(dirname(__FILE__).'/header.ctp'); ?>
</div>
<?php require(dirname(__FILE__).'/menu.ctp'); ?>
<script type="text/javascript">
	$(function(){
		//Hide the plot output and the controls associated because they are not needed for this kind of measurement (at least right now)
		$("#plot_output").hide();
		$("#plot_control").hide();
	});
</script>
<?php $displayTable = false; ?>
<div class="related view">
	<?php require(dirname(__FILE__).'/strip_tabs.ctp'); ?>
</div>
