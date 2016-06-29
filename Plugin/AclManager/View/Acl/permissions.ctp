<div class="form">
	<h3><?php echo sprintf(__("%s permissions"), $aroAlias); ?></h3>
	<br />
	<div class="row">
		<div class="col-md-4">
			<?php echo $this->Form->create('Page', array('default' => false));?>
			<?php echo $this->Form->input('group_id', array('id' => 'AroSelector', 'div' => false, 'label' => 'Jump to...', 'options' => $aroList, 'empty' => $aroAlias, 'value' => isset($this->params['named']['page']) ? $this->params['named']['page'] -1 : ''));?>
			<?php echo $this->Form->end(null);?>
		</div>
	</div>

	<br />
	<?php echo $this->Form->create('Perms'); ?>
	<table class="aclmanager-permissions">
		<tr>
			<th>Action</th>
			<?php foreach ($aros as $aro): ?>
			<?php $aro = array_shift($aro); ?>
			<th><?php echo h($aro[$aroDisplayField]); ?></th>
			<?php endforeach; ?>
		</tr>
	
		<?php
	
		$uglyIdent = Configure::read('AclManager.uglyIdent'); 
		$lastIdent = null;
		$parentAlias = null;
		foreach ($acos as $id => $aco) {
			$action = $aco['Action'];
			$alias = $aco['Aco']['alias'];
			if(is_null($parentAlias)) $parentAlias = $alias;
			$ident = substr_count($action, '/');
			if ($ident <= $lastIdent && !is_null($lastIdent)) {
				for ($i = 0; $i <= ($lastIdent - $ident); $i++) {
					?></tr><?php
				}
			}
			if ($ident == 1) {
			$parentAlias = $alias;
				?><tr class='aclmanager-ident-<?php echo $ident; ?>'><?php
					$this->Js->get('#'.h($parentAlias))->event('click', 
						'$("tr.'.h($parentAlias).':visible").slideUp("slow");
						 $("tr.'.h($parentAlias).':hidden").slideDown("slow");
						 event.preventDefault();'				 );
		}	
		elseif ($ident != $lastIdent) {
			?><tr class='aclmanager-ident-<?php echo $ident; ?> <?php echo h($parentAlias) ?>' style="display:none"><?php
		}
		else {
			?><tr class='<?php echo h($parentAlias) ?>' style="display:none"><?php
		}
		?><?php echo ($ident == 1 ? "<td id='".h($parentAlias)."'><strong>" : "<td>" ) . ($uglyIdent ? str_repeat("&nbsp;&nbsp;", $ident) : "") . h($alias) . ($ident == 1 ? "</strong>" : "" ); ?></td>
		<?php foreach ($aros as $aro): 
			$inherit = $this->Form->value("Perms." . str_replace("/", ":", $action) . ".{$aroAlias}:{$aro[$aroAlias]['id']}-inherit");
			$allowed = $this->Form->value("Perms." . str_replace("/", ":", $action) . ".{$aroAlias}:{$aro[$aroAlias]['id']}"); 
			$value = $inherit ? 'inherit' : null; 
			$icon = $this->Html->image(($allowed ? 'test-pass-icon.png' : 'test-fail-icon.png')); ?>
			<td>
				<?php echo $icon . " " . $this->Form->input("Perms." . str_replace("/", ":", $action) . ".{$aroAlias}:{$aro[$aroAlias]['id']}", array(
					'options' => array(
							 'inherit' => __('Inherit'),
							 'allow' => __('Allow'),
							 'deny' => __('Deny')
					),
					'empty' => __('No change'),
					'value' => $value,
					'div' => false,
					'label' => false,
					'wrapInput' => false,
					'class' => 'input-sm'
				)); ?>
			</td>
		<?php endforeach; ?>
	<?php 
		$lastIdent = $ident;
	}
	for ($i = 0; $i <= $lastIdent; $i++) {
		?></tr><?php
	}
	?></table>
	<?php
	echo $this->Form->submit(__("Save"), array('class' => 'btn btn-primary'));
	echo $this->Form->end(null);
	?>
	<p><?php echo $this->Paginator->counter(array('format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%'))); ?></p>
	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous'), array(), null, array('class'=>'disabled'));?>
	 | <?php echo $this->Paginator->numbers();?> |
		<?php echo $this->Paginator->next(__('next') . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>


<div id='verticalmenu'>
	<h2><?php echo __('Manage for'); ?></h2>
	<?php 
	$aroModels = Configure::read("AclManager.aros");
	if ($aroModels > 1): ?>
		<ul><?php foreach ($aroModels as $aroModel): ?>
			<li><?php echo $this->Html->link($aroModel, array('aro' => $aroModel)); ?></li>
		<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	
	<?php require(dirname(__FILE__).'/../../../../View/Layouts/menu.ctp'); ?>
</div>

<?php
	$params = array(
		'prefix' => $this->params['prefix'],
		'plugin' => $this->params['plugin'],
		'controller' => $this->params['controller'],
		'action' => $this->params['action']
	);
?>

<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function($){
	$("#AroSelector").on('change', function(){
		var page = $(this).val();
		if (page!=="") {
			location.href = "<?php echo $this->Html->url($params);?>/page:" + (parseInt(page)+1);
		}
	});
});
//]]>
</script>