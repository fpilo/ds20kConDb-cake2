<?php
//Logic required for the output of a nice tag layout
//First assign all the already selected tags to the
$setTags = array();
?>
<script type="text/javascript">
	$(function(){
		$(".tag").click(function(){
			var tag = $(this);
			tag.hide();
			if($(this).parent().attr("id") == "aviable_tags"){
				//Tag is an aviable tag set it as new tag and on success move it over to the other display
				$.ajax({
					url: "<?php echo $this->Html->url(array("controller"=>"items","action"=>"addTag")); ?>/"+$("#ItemId").attr("value")+"/"+$(this).attr("id"),
					type: "GET"
				}).done(function(result){
					tag.detach().appendTo("#set_tags").fadeIn(200);
				});
			}else if($(this).parent().attr("id") == "set_tags"){
				//Tag is an aviable tag set it as new tag and on success move it over to the other display
				$.ajax({
					url: "<?php echo $this->Html->url(array("controller"=>"items","action"=>"removeTag")); ?>/"+$("#ItemId").attr("value")+"/"+$(this).attr("id"),
					type: "GET"
				}).done(function(result){
					tag.detach().appendTo("#aviable_tags").fadeIn(200);
				});
			}
		});
	});
//	jQuery("#NodesToMove").detach().appendTo('#DestinationContainerNode');
</script>
<div class="items view">
	<?php
		echo $this->Form->input("Item.id",array('hiddenField' => true,"value"=>$item["Item"]["id"]));
	?>

	<div class="tags" id="set_tags">
		<div>Set Tags</div>
		<?php
			foreach($item["ItemTag"] as $tag){
				echo "<div id='tag_".$tag["id"]."' class='tag'>".$tag["name"]."</div>";
				$setTags[] = $tag["id"];
			}
		?>
	</div>
	<div class="tags" id="aviable_tags">
		<div>Aviable Tags</div>
		<?php
			foreach($itemTags as $tagId=>$tagName){
				//Only display tags if not set
				if(!in_array($tagId, $setTags))
					echo "<div id='tag_".$tagId."' class='tag'>".$tagName."</div>";
			}
		?>
	</div>
</div>
<div id='verticalmenu'>
	<h2><?php echo __('Edit '.$item["Item"]["code"]). ':'; ?></h2>
	<ul>
		<li class="active last"><?php echo $this->Html->link(__('Return'), array('controller' => 'items', 'action' => 'view', $item["Item"]["id"])); ?> </li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
