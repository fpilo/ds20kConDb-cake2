<?php $plotLoaded = false;
require(dirname(__FILE__).'/plot.ctp'); ?>
<?php require(dirname(__FILE__).'/header.ctp'); ?>
</div>
<script type="text/javascript">
	options.lines.show = false;
	options.points.show = true;
</script>
<?php require(dirname(__FILE__).'/menu.ctp'); ?>
<?php $displayTable = true;?>
<div class="related view">
	<?php require(dirname(__FILE__).'/strip_tabs.ctp'); ?>
</div>
