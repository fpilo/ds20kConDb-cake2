<?php if(!empty($id) && !empty($model)): ?>
<div class="dbFiles form">
	<fieldset>
	<legend>Add File to 
	<?php 
		echo $model.' ';
        
		if($model == 'Item') {
		    echo $item[$model]['code'];
		} else if($model == 'ItemSubtype') {
		    echo $item[$model]['name'];
        } else if($model == 'ItemSubtypeVersion') {
            echo $item['ItemSubtype']['name'].' v'.$item['ItemSubtypeVersion']['version'];
        } 
    ?>                
	</legend>
	</fieldset>
	
    <?php echo $this->Plupload->loadWidget('jqueryui', array('height' => '550px')); ?>
</div>

<?php endif; ?>

<div id='verticalmenu'>
	<h2>File Upload</h2>
	<ul>
		<li class='active last'><?php echo $this->Html->link(__('Back'), $referer); ?></li>
	</ul>
	<?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>