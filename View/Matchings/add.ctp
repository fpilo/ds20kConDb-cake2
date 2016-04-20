<div class="matchings form">
<?php echo $this->Form->create('Matching'); ?>
	<fieldset>
		<legend><?php echo __('Add Matching'); ?></legend>
	<?php
		echo $this->Form->input('name');
	?>
		<div class="ui-widget">
			<select name='data[Matching][parameter_id]' id='combobox'>
				<option disabled selected>--select an option--</option>
				<?php foreach($parameters as $pId=>$pName): ?>
					<option value="<?php echo $pId; ?>" ><?php echo h($pName); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('Matching');?></h2>
	<ul>
		<li class="active last"><?php echo $this->Html->link(__('List'), array('action' => 'index')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
<style>
	.custom-combobox {
		position: relative;
		display: inline-block;
	}
	.custom-combobox-toggle {
		position: absolute;
		top: 0;
		bottom: 0;
		margin-left: -1px;
		padding: 0;
		display:none;
	}
	.custom-combobox-input {
		margin: 0;
		padding: 5px 10px;
		cursor:pointer;
	}
	.ui-autocomplete{
		max-height:200px;
		overflow-y: auto;
		overflow-x: hidden;
	}
</style>

<?php echo $this->Html->script("combobox"); ?>
<script type="text/javascript">
	$(function() {
		$("#combobox").combobox();
		$( "#toggle" ).click(function() {
			$( "#combobox" ).toggle();
		});
	}
	);
</script>
