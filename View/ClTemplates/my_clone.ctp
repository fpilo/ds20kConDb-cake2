<?php
	$this->Html->addCrumb('ClTemplates', '/clTemplates');
	$this->Html->addCrumb('Clone', '/clTemplates/myClone/');
?>

<?php #debug($clTemplates); ?>
<div class="cltemplates form">
<?php echo $this->Form->create('ClTemplate');?>
	<fieldset>
		<legend><?php echo __('Clone Checklist Template'); ?></legend>
	<?php
		echo $this->Form->input('cl_template_id', array(
									'label' => 'Existing templates list',
									'empty' => '(choose one)'
									));
	?>
	</fieldset>

<?php echo $this->Form->end(__('Submit'));?>
</div>

<div id='verticalmenu'>
	<h2><?php echo __('Templates');?></h2>
	<ul>
		<li class="active last"><?php echo $this->Html->link(__('Cancel'), array('action' => 'index')); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>