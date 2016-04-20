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
	   <span style="font-weight:bold; font-size:150%; color:#090">All items saved successful</span>
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
</div>