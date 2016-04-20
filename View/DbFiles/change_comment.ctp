<div class="dbFiles form">
<?php echo $this->Form->create('DbFile');?>
	<fieldset>
		<legend><?php echo __('Change comment of '.$this->Form->value('DbFile.name')); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('comment', array('type' => 'textarea'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
    <h2>Edit Comment</h2>
    <ul>
        <li class='active last'><?php echo $this->Html->link(__('Cancel'), $referer); ?></li>
    </ul>
    <?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>