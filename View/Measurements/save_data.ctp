<script type="text/javascript">
$(document).ready(function () {
});
</script>
<?php if (isset($message)) debug($message); ?>

<div class="items view">
	<?php foreach ($measurementIds as $id): ?>
		Saved new <?php echo $this->Html->link("Measurement",array('controller'=>"measurements",'action'=>"view",$id)); ?> to the Database. 
		To get an overview over the latest measurements go to the <?php echo $this->Html->link("overview",array('controller'=>"measurements","action"=>"index/sort:id/direction:desc")); ?> <br />
	<?php endforeach; ?>
	<br />
	<input type='button' value='Hide' class='delete_measurement'/>
</div>

