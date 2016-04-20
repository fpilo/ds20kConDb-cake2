<div class="itemSubtype changelog">
	<?php if($itemSubtypes == null): ?>
		You can close this window.
	<?php else: ?>
		<div style="width: 80%; margin-left:auto; margin-right:auto;">
		<?php foreach($itemSubtypes as $itemSubtype): ?>
			<h2>Changelog of <?php echo $itemSubtype['ItemSubtype']['name']; ?>:</h2>
			<table cellpadding="0" cellspacing="0">
				<tbody>
				<?php foreach($itemSubtype['ItemSubtypeVersion'] as $subtypeVersion): ?>
					<tr style="background-color:#fff;">
						<td style="width: 100px"><b>Version <?php echo $subtypeVersion['version']; ?>:</b></td>
						
						<?php if($subtypeVersion['comment'] == null || empty($subtypeVersion['comment'])):?>
						<td>No comment found.</td>							
						<?php else:?>
						<td><?php echo $subtypeVersion['comment']; ?></td>
						<?php endif;?>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>