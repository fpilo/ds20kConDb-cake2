<div class="CMSelector">
	<label><?php echo ucfirst($name); ?></label>
	<?php

	echo $this->Form->select($name,$options,$settings);

	?>
</div>