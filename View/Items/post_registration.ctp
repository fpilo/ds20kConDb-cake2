<?php
	$this->Html->addCrumb($item['Item']['code'], '/items/view/'.$item['Item']['id']);
	$this->Html->addCrumb('Post registration', '/items/postRegistration/'.$item['Item']['id']);
?>

<div class="items view">
	<?php
		$components = $item['ItemSubtypeVersion']['Component'];
	?>
	<?php if (!empty($item['ItemSubtypeVersion']['Component'])):?>
	<div id="components">
				<?php //echo debug($item); ?>
				<h1><?php  echo __('Components: '); ?></h1>

				<table>
				<?php
					echo $this->Html->tableHeaders(array(
								'Code',
								'Created',
								'Position',
								'Type',
								'Subtype',
								'Version',
								'Actions'));

					$cells = array();
					$componentSlots = array();

					foreach($item['ItemSubtypeVersion']['Component'] as $componentSlot) {
						$image		= ($componentSlot['created'] ? 'Tick.png' : 'Error.png');
						$status 	= $this->Html->image($image, array(
											//'alt' => __('edit'),
											'border' => '0',
											'width'=>'16',
											'height'=>'16',
											//'url' => array('controller' => 'items', 'action' => 'changeItemSubtypeVersion', $item['Item']['id'])
											));
						$position 	= $componentSlot['ItemSubtypeVersionsComposition']['position'];
						$type 		= $componentSlot['ItemSubtype']['ItemType']['name'];
						$subtype 	= $componentSlot['ItemSubtype']['name'];
						$version 	= $componentSlot['version'];
						$code 		= $componentSlot['code'];
						$isStock	= $componentSlot['ItemSubtypeVersionsComposition']['is_stock'];
						$actions 	= '';

						if(!$componentSlot['created'] && !$isStock) {
							$actions .= $this->Form->create(__('Item'), array('style' => 'width: 100%'));
							$actions .= $this->Form->hidden('ItemComposition.item_id', array('value' => $item['Item']['id']));
                            $actions .= $this->Form->hidden('ItemComposition.position', array('value' => $position));
                            $actions .= $this->Form->hidden('ItemComposition.valid', array('value' => 0));
							$actions .= $this->Form->hidden('Item.code', array('value' => $code));
                            $actions .= $this->Form->hidden('Item.comment', array('value' => 'Post registration'));
							$actions .= $this->Form->hidden('Item.item_type_id', array('value' => $componentSlot['ItemSubtype']['ItemType']['id']));
							$actions .= $this->Form->hidden('Item.item_subtype_id', array('value' => $componentSlot['ItemSubtype']['id']));
							$actions .= $this->Form->hidden('Item.item_subtype_version_id', array('value' => $componentSlot['id']));
							$actions .= $this->Form->hidden('Item.location_id', array('value' => $item['Item']['location_id']));
							$actions .= $this->Form->hidden('Item.item_quality_id', array('value' => $item['Item']['item_quality_id']));
							$actions .= $this->Form->hidden('Item.state_id', array('value' => $item['Item']['state_id']));
							$actions .= $this->Form->hidden('Item.manufacturer_id', array('value' => $componentSlot['manufacturer_id']));
							// Components project is the defaul project set in the Version
							$actions .= $this->Form->hidden('Item.project_id', array('value' => $componentSlot['ItemSubtypeVersionsComposition']['project_id']));
							// Components Project is the parents project (maybe the parents project has changed)
							//$actions .= $this->Form->hidden('Item.project_id', array('value' => $item['Item']['project_id']));
							$actions .= $this->Form->submit('Register', array('div' => false));
							$actions .= $this->Form->end();
						} elseif ($isStock) {
							$actions = 'Stock item';
						}

						$cells[$position] = array($code, $status, $position, $type, $subtype, $version, array($actions, array('class'=> 'actions')));

						// new array of components indexed by position
						$componentSlots[$position] = $componentSlot;
					}

					echo $this->Html->tableCells($cells);
				?>
				</table>

	</div>
	<?php endif; ?>
</div>

<div id='verticalmenu'>
	<h2><?php  echo __('Item:'); ?></h2>
	<span><?php echo $item['Item']['code']; ?></span>
	<ul>
		<li class='active'><?php echo $this->Html->link(__('Back'), array('controller' => 'items', 'action' => 'view', $item['Item']['id']));?> </li>
	</ul>

	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?></div>
</div>
