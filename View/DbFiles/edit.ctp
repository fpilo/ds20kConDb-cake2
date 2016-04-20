<div class="dbFiles form">
<?php echo $this->Form->create('DbFile');?>
	<fieldset>
		<legend><?php echo __('Edit Db File'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('real_name');
		echo $this->Form->input('comment');
		echo $this->Form->input('size');
		echo $this->Form->input('type');
		echo $this->Form->input('ItemSubtypeVersion');
		echo $this->Form->input('ItemSubtype');
		echo $this->Form->input('Item');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
    <h2>Edit File</h2>
    <ul>
        <li class='active last'><?php echo $this->Html->link(__('Cancel'), $referer); ?></li>
    </ul>
    <?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
