<script type="text/javascript">
	function SelectAll(elem) {
		
		if(elem.options[0].selected) {
			for (j=1; j<elem.options.length; j++) {
				elem.options[j].selected = true;
			}
			elem.options[0].selected = false;
		}			
	}
</script>

<?php
	$slide = 	'$("div.search").slideToggle("slow");						
					var se = document.getElementById("searchDIV");
					if(sessionStorage.getItem("LogIndexFilterVisability") == 1)
						sessionStorage.setItem("LogIndexFilterVisability", 0);
					else
						sessionStorage.setItem("LogIndexFilterVisability", 1);
						
					 event.preventDefault();';
?>
<script>
$(document).ready(function() {
  
	if(sessionStorage.getItem("LogIndexFilterVisability") == 1)
		$("div.search").show();
});
</script>

<?php $this->Js->get('#search')->event('click', $slide); ?>

<div class="logs index">
	<?php echo $this->Form->create('Log', array('style' => 'width: 100%', 'type' => 'get'));?>
		<fieldset>
			<div style="display: inline">
				<?php
						if(empty($filter['comment'])) {
							echo $this->Form->input('comment', array(
									'div' => false,
									'label' => false,
									'placeholder' => 'Search log by comment ...',
									'after' => '<div class="input-message" id="search">Extended search...</div>'));
						} else {
							echo $this->Form->input('comment', array(
									'div' => false,
									'label' => false,
									'default' => $filter['comment'],
									'after' => '<div class="input-message" id="search">Extended search...</div>'));
						}
									 
						echo $this->Js->submit('Refresh', array(
							//'url'=> array('controller'=>'transfers', 'action'=>'addToCart'),
							'update'=>'#results', 
							'div' => false
						));
				?>
			</div>
			
			<div class="search" id="searchDIV" style="display: none">
				<table class="search" cellpadding="0" cellspacing="0" style="width: 100%">
					<tr>	
						<td><?php 
								$defaultLogEventId = null;
								if(!empty($filter['log_event_id']))
									$defaultLogEventId = $filter['log_event_id'];
								
								echo $this->Form->input('log_event_id', array(
											'div' => false, 
											'size' => 18, 
											'multiple' => true, 
											//'options' => array(), 
											'empty' => '(Select all)',
											'onchange' => 'SelectAll(this)',
											'default' => $defaultLogEventId,
											'id' => 'log_event_id'
											)); ?>
						</td>
						<td><?php
								$defaultUserId = null;
								if(!empty($filter['user_id']))
									$defaultUserId = $filter['user_id'];
								
								echo $this->Form->input('user_id', array(
											'div' => false, 
											'size' => 18, 
											'multiple' => true, 
											//'options' => array(), 
											'empty' => '(Select all)',
											'onchange' => 'SelectAll(this)',
											'default' => $defaultUserId,
											'id' => 'user_id'
											)); ?>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<?php
								if(empty($filter['limit']))
									$filter['limit'] = 50;
								 
								$limits = array(25 => '25', 50 => '50', 100 => '100', 200 => '200', 500 => '500');
								echo $this->Form->input('limit', array(
														'options' => $limits,
														'div' => false,
														'selected' => $filter['limit'],
														'label' => 'Results/page'));
							?>
						</td>
					</tr>
				</table>
			</div>
		</fieldset>
	<?php echo $this->Form->end(); ?>
	
	<div id="results">
		<table cellpadding="0" cellspacing="0">
		<tr>
				<th><?php echo $this->Paginator->sort('id');?></th>
				<th><?php echo $this->Paginator->sort('user_id');?></th>
				<th><?php echo $this->Paginator->sort('log_event_id');?></th>
				<th><?php echo $this->Paginator->sort('comment');?></th>
				<th><?php echo $this->Paginator->sort('created');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
		</tr>
		<?php
		foreach ($mylogs as $log): ?>
		<tr>
			<td><?php echo h($log['Log']['id']); ?>&nbsp;</td>
			<td>
				<?php echo $this->Html->link($log['User']['username'], array('controller' => 'users', 'action' => 'view', $log['User']['id'])); ?>
			</td>
			<td>
				<?php echo $this->Html->link($log['LogEvent']['name'], array('controller' => 'log_events', 'action' => 'view', $log['LogEvent']['id'])); ?>
			</td>
			<td><?php echo h($log['Log']['comment']); ?>&nbsp;</td>
			<td><?php echo h($log['Log']['created']); ?>&nbsp;</td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('action' => 'view', $log['Log']['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
		</table>
		<p>
		<?php
		echo $this->Paginator->counter(array(
		'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
		));
		?>	</p>
	
		<div class="paging">
		<?php
			echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
			echo $this->Paginator->numbers(array('separator' => ''));
			echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
		?>
		</div>
	</div>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Log'); ?></h2>
	<ul>
		<li class='active last'><?php echo $this->Html->link(__('Log Events'), array('controller' => 'log_events', 'action' => 'index')); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>