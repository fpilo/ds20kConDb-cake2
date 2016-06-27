<?php if (isset($message)): ?>
	<p class="error-message error"><?php echo $message; ?></p>
<?php endif; ?>

<div>
<?php if (isset($fileInfo)): ?>
	<?php if (is_array($fileInfo)): ?>
		File name: <?php echo $fileInfo["name"]; ?>
	<?php endif; ?>
<?php endif; ?>
</div>

<div>
<?php if (isset($formData)): ?>
	<?php

	/*
	* Read columns specified in the specified columns interval (lastCol is an optional parameter)
	* TODO METTERE QUESTA FUNZIONE IN UNA LIBRERIA SEPARATA
	*/
	function show_array($array, $hasHeaders = false, $firstCol, $lastCol = -1){
			
		if(is_array($array) == 1){          // check if input is an array
			foreach($array as $key_val => $value) {
				if (is_array($value) == 1){   // array is multidimensional
					if($hasHeaders){
						echo "<thead>\n<tr>";
						show_array($value, $hasHeaders, $firstCol);
						echo "</tr>\n</thead>\n<tbody>\n";
						$hasHeaders = false;
					} else{
						echo "<tr>";
						show_array($value, $hasHeaders, $firstCol);
						echo "</tr>\n";
					}
				}
				else{                        // (sub)array is not multidim
					if($key_val>=$firstCol && ($lastCol<0 || $key_val<$lastCol)){
						if($hasHeaders){
							echo "<th main width=\"120\">".$value."</th>"; 
						} else{ 
							echo "<td main width=\"120\">".$value."</td>"; 
						}
					}
				}
			} //foreach $array
			echo "</tbody>";
		}  
		else{ // argument $array is not an array
			return;
		}
	}

	function html_show_array($array){
		echo "<table id=\"comp_list_table\" cellspacing=\"0\" border=\"2\">\n";
		show_array($array, true, 0); //Set here the column range
		echo "</table>\n";
	}
	
	html_show_array($formData);
	
	?>
<?php endif; ?>
</div>
