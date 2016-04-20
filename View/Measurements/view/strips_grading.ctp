<?php
	$this->Html->addCrumb('Item Subtypes', '/ItemSubtypes');
	$this->Html->addCrumb($itemSubtypeVersion["ItemSubtype"]["name"]." v".$itemSubtypeVersion["ItemSubtypeVersion"]['version'], '/ItemSubtypeVersions/view/'.$itemSubtypeVersion["ItemSubtypeVersion"]["id"]);
?>

<div class="measurements view" id='measurement_header'>
<h3><?php echo __('Sample measurement of item');?> <span id='itemCode'><?php echo $measurement["Item"]["code"]; ?></span></h3>
</div>
<?php $plotLoaded = false;
require(dirname(__FILE__).'/plot.ctp'); ?>
<?php require(dirname(__FILE__).'/strip_limits.ctp'); ?>

<script type="text/javascript">
	options.lines.show = false;
	options.points.show = true;
</script>
<div id='verticalmenu'>
	<h2><?php echo __('Apply limits to measurements'); ?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('Back'), array('controller'=>'ItemSubtypeVersions','action' => 'view',$itemSubtypeVersion["ItemSubtypeVersion"]["id"])); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../../Layouts/menu.ctp'); ?>
</div>
<?php $displayTable = true;?>
<div class="related view">
	<?php require(dirname(__FILE__).'/strip_tabs.ctp'); ?>
</div>
