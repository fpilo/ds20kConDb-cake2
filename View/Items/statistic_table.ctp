<div id="results">
	<?php echo $this->Session->flash(); ?>
	<h6>
		<?php
         echo 'Found '.$number_of_items.' items in '.count($stat_locations_header).' locations and '.count($stat_projects_header).' projects.<br>';
         echo 'This includes '.$stock_items.' stock items with a total stock of '.$total_available_stock.' available.';
      ?>
	</h6>
	<hr>
	
	<table>
		<tr>
			<td style="width: 33%">
				<table>
					<?php
						echo $this->Html->tableHeaders(array('State', '# Items')); 	
						if(!empty($stat_states_cells)) {	 
							echo $this->Html->tableCells($stat_states_cells);
						} else{
							echo '<td>-</td><td>-</td>';
						}
					 ?>						
				</table>					
			</td>
			<td style="width: 33%">
				<table>
					<?php
						echo $this->Html->tableHeaders(array('Project', '# Items'));
						if(!empty($stat_projects_cells)) {
								echo $this->Html->tableCells($stat_projects_cells);
						} else{
								echo '<td>-</td><td>-</td>';
							}
					?>
				</table>					
			</td>
			<td style="width: 33%">
				<table>
					<?php
						echo $this->Html->tableHeaders(array('Location', '# Items'));
						if(!empty($stat_locations_cells)) {
							echo $this->Html->tableCells($stat_locations_cells);
						} else{
							echo '<td>-</td><td>-</td>';
						}
					?>
				</table>					
			</td>
		</tr>
	</table>
</div>
