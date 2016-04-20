<?php
	$this->Html->addCrumb('ClActions', '/clActions');
	$this->Html->addCrumb('View', '/clActions/view/'.$clAction['ClAction']['id']);
?>

<div class="clActions view">
	<fieldset>
	<legend>Information</legend>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($clAction['ClAction']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($clAction['ClAction']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($clAction['ClAction']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Hierarchy level'); ?></dt>
		<dd>
			<?php echo h($clAction['ClAction']['hierarchy_level']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Source states'); ?></dt>
		<dd>
		<?php
			$i = 0;
			foreach($clAction['ClState'] as $clstate){
				if($clstate['type']=='source'){
					if($i==0) echo h($clstate['name']);
					else echo ','.h($clstate['name']);						
					$i++;
					}
			}			
		?>
		&nbsp;
		</dd>
		<dt><?php echo __('Target states'); ?></dt>
		<dd>
		<?php
			foreach($clAction['ClState'] as $clstate){
				if($clstate['type']=='target') echo h($clstate['name']); 
			}
		?>
		&nbsp;
		</dd>
	</dl>
	</fieldset>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('ClActions');?></h2>
	<ul>
		<li class="active"><?php echo $this->Html->link(__('New'), array('action' => 'add')); ?></li>
		<li class="active"><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $clAction['ClAction']['id'])); ?> </li>
		<li class="active last"><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $clAction['ClAction']['id']), null, __('Are you sure you want to delete %s?', $clAction['ClAction']['name'])); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
