<div class="items form">
<?php if (isset($items['Saved'])): ?>
	<h2> <?php echo count($items['Saved']); ?> items saved</h2>
	<ul>
	<?php foreach ($items['Saved'] as $item): ?>
		<li><?php echo $item; ?></li>
	<?php endforeach?>
	</ul>
<?php endif?>

<?php if (isset($items['NotSaved'])): ?>
	<h2> <?php echo count($items['NotSaved']); ?> items NOT saved</h2>
	<ul>
	<?php foreach ($items['NotSaved'] as $item): ?>
		<li><?php echo $item; ?></li>
	<?php endforeach?>
	</ul>
<?php  endif?>
</div>

<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Register'), array('action' => 'register')); ?></li>
		<li><?php echo $this->Html->link(__('Items'), array('action' => 'index')); ?></li>
	</ul>
</div>