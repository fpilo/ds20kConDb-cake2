<?php
	$this->Html->addCrumb('Measurements', '/measurements');
	$this->Html->addCrumb('Measurement #'.$measurement['Measurement']['id'].' View', '/measurements/view/'.$measurement['Measurement']['id']);
	$plotLoaded = false;
?>

<div class="measurements view" id='measurement_header'>
<h2><?php  echo __('Measurement');?></h2>
	<dl style="max-width:400px; float:left;" id="information">
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
		<dt onclick="$('#tag_selector').fadeIn(200);" style='cursor:pointer; text-decoration:underline'><?php echo __('Measurement Tags'); ?></dt>
		<dd id='display_tags'>
			<?php 
            $setTags = array();
            if(count($measurement["MeasurementTag"])>0): 
               foreach($measurement["MeasurementTag"] as $tag): 
                  $tmp[] = $this->Html->link($tag["name"], array('controller' => 'measurement_tags', 'action' => 'view', $tag["id"]),array("id"=>"display_tag_".$tag["id"]));
                  $setTags[$tag["id"]] = $tag["name"];
               endforeach;
               echo implode(" ",$tmp);
            endif;
         ?>
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
		<?php
		if(count($measurementParameter)>0):
			foreach($measurementParameter as $parameter):
				#Special condition for special parameter tables that need to be displayed as float or otherwise they wouldn't be readable
				if(in_array($parameter["Parameter"]["name"],array("Table","Strip Error Limits"))): ?>
					<div style="float:left;" id='parameter'>
						<b><?php echo $parameter["Parameter"]["name"]; ?></b>
						<?php echo $parameter["MeasurementParameter"]["value"]; ?>
						<script type="text/javascript">
							$(function(){
								$("#parameter").appendTo('#measurement_header');
							});
						</script>
					</div>
				<?php else:
					echo "<dt>".$parameter["Parameter"]["name"]."</dt><dd>".$parameter["MeasurementParameter"]["value"]."</dd>";
				endif;
			endforeach;
		endif;
		?>
		<dt><?php echo __('Download Data'); ?></dt>
		<dd>
			<?php echo $this->Html->link("Original Datafile",array("controller"=>"measurements","action"=>"download",$measurement["Measurement"]["id"],true)) ?><br />
			<?php echo $this->Html->link("Standardized CSV Datafile",array("controller"=>"measurements","action"=>"download",$measurement["Measurement"]["id"],false)) ?><br />
			
		</dd>
	</dl>
	<?php if(isset($measurementSet)): ?>
		<div style="float:left; width:900px;" id='parameterTable'>
			<b>Measurement Set: <?php echo $this->Html->link($measurementSet["MeasurementSet"]["name"],array("controller"=>"measurement_sets","action"=>"view",$measurementSet["MeasurementSet"]["id"])) ?> </b><br />
			<?php if ($measurementSet["MeasurementSet"]["parameter_table"] != null): ?>
				<b>Measurement APV Configuration</b>
				<?php echo $measurementSet["MeasurementSet"]["parameter_table"]; ?>
			<?php endif; ?>
		</div>
		<script type="text/javascript">
			$(function(){
				$("#parameterTable").appendTo('#measurement_header');
			});
		</script>
	<?php endif; ?>
	<div id='tag_selector'>
		<div id='aviable_tags' class="tags">
			<?php
				foreach($measurementTags as $tagId=>$tagName){
					//Only display tags if not set
					if(!in_array($tagId, array_keys($setTags)))
						echo "<div id='tag_".$tagId."' class='tag'>".$tagName."</div>";
				}
			?>
		</div>
		<div id='set_tags' class="tags">
			<?php
				foreach($setTags as $tagId=>$tagName){
					echo "<div id='tag_".$tagId."' class='tag'>".$tagName."</div>";
				}
			?>
		</div>
		<input type="button" value="close overlay" onclick="$(this).parent().fadeOut(200);" />
	</div>

