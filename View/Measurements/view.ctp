<?php require(dirname(__FILE__).'/view/header.ctp'); ?>
</div>
<?php require(dirname(__FILE__).'/view/menu.ctp'); ?>

<div class="related view">
	<?php
		if($measurementQueueStatus == 3):
			if(in_array($measurement['MeasurementType']['id'],array(5,24))):
				require(dirname(__FILE__).'/strip_table.ctp');
			else:
				require(dirname(__FILE__).'/table.ctp');
			endif;
		else:
			require(dirname(__FILE__).'/not_yet_imported.ctp');
		endif;
	?>
</div>
