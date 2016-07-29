<?php
	$this->Html->addCrumb('Measurements', '/measurements');
	$this->Html->addCrumb('Measurement #'.$measurement['Measurement']['id'].' View', '/measurements/view/'.$measurement['Measurement']['id']);
	$plotLoaded = false;
?>

<script type="text/javascript">
			$(function(){
				restoreTabs('itemTypesTabIndex');
			});
</script>

<div class="measurements view">
	<div id="tabs" class='related'>
		<ul>
			<li><a href="#information"><?php echo __('Information'); ?></a></li>
			<li><a href="#plots"><?php echo __('Plots'); ?></a></li>
			<li><a href="#data"><?php echo __('Data'); ?></a></li>
		</ul>
	
		<div id='information'>
			<dl>
				<dt><?php echo __('Id'); ?></dt>
				<dd>
					<?php echo h($measurement['Measurement']['id']); ?>
					<input type="hidden" id="measurementId" value="<?php echo $measurement['Measurement']['id']; ?>" />
					&nbsp;
				</dd>
				<dt><?php echo __('History'); ?></dt>
				<dd>
					<?php echo $this->Html->link($measurement['History']['id'], array('controller' => 'histories', 'action' => 'view', $measurement['History']['id'])); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Item'); ?></dt>
				<dd>
					<?php echo $this->Html->link($measurement['Item']['code'], array('controller' => 'items', 'action' => 'view', $measurement['Item']['id'])); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Measurement Setup'); ?></dt>
				<dd>
					<?php echo $this->Html->link($measurement['Device']['name'], array('controller' => 'devices', 'action' => 'view', $measurement['Device']['id'])); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('User'); ?></dt>
				<dd>
					<?php echo $this->Html->link($measurement['User']['username'], array('controller' => 'users', 'action' => 'view', $measurement['User']['id'])); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Measurement Type'); ?></dt>
				<dd>
					<?php echo $this->Html->link($measurement['MeasurementType']['name'], array('controller' => 'measurement_types', 'action' => 'view', $measurement['MeasurementType']['id'])); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Measurement Start'); ?></dt>
				<dd>
					<?php echo h($measurement['Measurement']['start']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Measurement Stop'); ?></dt>
				<dd>
					<?php echo h($measurement['Measurement']['stop']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Download Data'); ?></dt>
				<dd>
					<?php echo $this->Html->link("Original Datafile",array("controller"=>"measurements","action"=>"download",$measurement["Measurement"]["id"],true)) ?><br />
					<?php echo $this->Html->link("Standardized CSV Datafile",array("controller"=>"measurements","action"=>"download",$measurement["Measurement"]["id"],false)) ?><br />
				</dd>
			</dl>
		</div>
	
		<div id='plots'>
			<?php
				$plotLoaded = false;
				require(dirname(__FILE__).'/plot.ctp'); 
			?>
		</div>
		
		<div id='data' class="related view">
			<?php 
				require(dirname(__FILE__).'/table.ctp');
			?>
		</div>
		
	</div>



<?php //require(dirname(__FILE__).'/header.ctp'); ?>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Actions'); ?></h2>
	<ul>
		<li class='active'><?php echo $this->Html->link(__('Edit Measurement'), array('action' => 'edit', $measurement['Measurement']['id'])); ?> </li>
		<li class='active last'><?php echo $this->Form->postLink(__('Delete Measurement'), array('action' => 'delete', $measurement['Measurement']['id']), null, __('Are you sure you want to delete # %s?', $measurement['Measurement']['id'])); ?> </li>
		<!--<li><?php echo $this->Html->link(__('New Measurement'), array('action' => 'add')); ?> </li>-->
	</ul>
	<?php require(dirname(__FILE__).'/../../Layouts/menu.ctp'); ?>
</div>
