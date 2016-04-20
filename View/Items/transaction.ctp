<script>

	var value = 0;
	var stop = false;
	
	$(document).ready(function() {
		start_transaction();
	});
	
	function start_transaction(){
		stop = false;
		transaction();
	}
	
	function output_progress(output) {
		if ((output != null)) {
			$("#progress").replaceWith(output);
		}
		
		/*
		 * Activate/deactivate buttons if the transaction has finished.
		 */		
		if(stop == true) {
			$("#start_button").removeAttr('disabled');
			$("#finish_button").removeAttr('disabled');
			$("#stop_button").attr('disabled', 'disabled');
		} else {
			$("#start_button").attr('disabled', 'disabled');
			$("#finish_button").attr('disabled', 'disabled');
			$("#stop_button").removeAttr('disabled');
		}
	}
	
	function transaction(output) {
		
		output_progress(output);
		
		/*
		 * After the server processed the post request, transaction will be called again and the next
		 * request will be send to save the next item.
		 * Except someone pressed the StopButton(stop == true) or the Server output == finished.  
		 */
		if(stop == true) {
			$.ajax({
				type:	'POST',
				//url:	'/cakephp/ds20kcondb/items/transaction/',
				url:	'<?php echo $this->Html->url(array('controller' => 'items', 'action' => 'transaction')); ?>',	
				data:	{action:  'stop'},
				success:	output_progress
			});
		} else {
			progress = $("#progressbar").attr("value");			
			
			if(progress < 100) {
				$.ajax({
					type:	'POST',
					//url:	'/cakephp/ds20kcondb/items/transaction/',
					url:	'<?php echo $this->Html->url(array('controller' => 'items', 'action' => 'transaction')); ?>',					
					data:	{action:  'next'},
					success:	transaction
				});
			} else {
				stop_transaction();
				$("#start_button").attr('disabled', 'disabled');
				$("#finish_button").removeAttr('disabled', 'disabled');
				$("#stop_button").attr('disabled', 'disabled');
			}
		}
	}
	
	function stop_transaction() {		
		stop = true;
	}
</script>
<div>
	<center>
	<h2>Registrating items</h2>
	<div id="progress">

	<progress id="progressbar" max="100" value="<?php echo $transaction['progress']; ?>" style="width: 400px; height: 30px">
	</progress>
	<br>
	
	<?php 
		if($transaction['progress'] < 100) {
			$transaction['progress'] = $transaction['progress'] * 100;
			$transaction['progress'] = round($transaction['progress']);
			$transaction['progress'] = $transaction['progress']/100;
			
			echo '<strong style="color: #fff">Progress: '.$transaction['progress'].'% done.</strong>';		 	
		} else {
			echo '<strong style="color: #fff">FINISHED</strong>';
		}
	?>
	
	<br>
	<br>
	
	<div>
	<input id="start_button" type="button" name="start" value="Continue" disabled="disabled" style="width: 100px;" onclick="start_transaction()">
	<input id="stop_button" type="button" name="stop" value="Stop" style="width: 100px;" onclick="stop_transaction()">
	<?php $url = (empty($transaction['Url']) ? array('controller' => 'items', 'action' => 'index') : $transaction['Url']); ?>
	<input id="finish_button" type="button" name="finish" value="Finish" disabled="disabled" style="width: 100px;" onclick="location='<?php echo $this->Html->url($url); ?>'">
	</div>
	<br>
	
	<br>
	
	<?php
		$pending = '';
		$success = '';
		$failed = '';
		$errors = '';
		
		foreach($transaction['Item'] as $code => $item) {
			switch($item['status']) {
				case 'pending':
					$pending .= $code.'; ';
					break;
				case 'success':
					$success .= $code.'; ';
					break;
				case 'failed':
					$failed .= $code.'; ';
					foreach($item['error'] as $e) {
						$errors .= '<tr><td>'.$code.'</td><td>'.$e.'</td></tr>';
					}
					break;
			}
		}					
	?>
	
	<div style="width:80%; background: #fff; padding: 20px; border-radius: 20px 20px 20px 20px;
  	-moz-border-radius: 20px 20px 20px 20px ;
  	-webkit-border-radius: 20px 20px 20px 20px;">

  	<?php if(empty($failed) && empty($pending)): ?>
			<span style="font-weight:bold; font-size:150%; color:#090">All items saved successful</span>;
	<?php endif; ?>
	
	<?php if(!empty($errors)): ?>
	
    <div style="width:80%; background: #a00; padding: 20px; border-radius: 20px 20px 20px 20px;
        -moz-border-radius: 20px 20px 20px 20px ;
        -webkit-border-radius: 20px 20px 20px 20px;">    
    	<h2 align="left">Errors</h2>
    	<table>
    		<tr>
    			<th style="width:33.3%; color:#FFFFFF">
    				Item code
    			</th>
    			<th style="color:#FFFFFF">
    				Error Message
    			</th>
    		</tr>
    		<?php echo $errors; ?>
    	</table>    	
    </div>

	<?php endif; ?>
	
	<h3 align="left">Database Status</h3>
	<table style="table-layout:fixed;">
		<tr>
			<th>
				Pending
			</th>
			<th>
				Success
			</th>
			<th>
				Failed
			</th>
		</tr>
		<tr>	
			<td>
				<?php echo $pending; ?>
			</td>
			<td>
				<?php echo $success; ?>
			</td>
			<td>
				<?php echo $failed; ?>
			</td>
		</tr>		
	</table>		
	
	</div>
	</center>
</div>