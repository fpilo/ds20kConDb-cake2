<div id="tabs" class='related'>
	<ul>
		<?php if($displayTable): ?>
			<li><a href="#table"><?php echo __('Table'); ?></a></li>
		<?php endif; ?>
		<li><a href="#strips"><?php echo __('Errors by Strip'); ?></a></li>
		<li><a href="#parameters"><?php echo __('Errors by Type'); ?></a></li>
	</ul>
	<?php if($displayTable): ?>
		<div id="table">
			<?php require(dirname(__FILE__).'/table.ctp'); ?>
		</div>
	<?php endif; ?>
	<div id="strips">
		<table>
			<tr><th>Strip</th><th>Error(s)</th></tr>
			<?php foreach($strips as $strip=>$errors): ?>
				<tr>
					<td>
						<?php echo $strip; ?>
					</td>
					<td>
						<?php echo implode(", ", $errors); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
	<div id="parameters">
		<table>
			<tr><th>Error</th><th>Strip(s)</th></tr>
			<?php foreach($parameters as $error=>$strips): ?>
				<tr>
					<td style="width:150px;">
						<?php echo $error; ?>
					</td>
					<td>
						<?php echo implode(", ", $strips); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		restoreTabs('MeasurementsViewStripsTabIndex');
	});
</script>

