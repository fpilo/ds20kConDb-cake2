<?php
    // reset($model);
    // $modelName = key($model);
    // if($modelName == 'Item') {
        // $this->Html->addCrumb($model[$modelName]['code'], '/'.Inflector::tableize($modelName).'/view/'.$model[$modelName]['id']);
    // } else {
        // $this->Html->addCrumb($model[$modelName]['name'], '/'.Inflector::tableize($modelName).'/view/'.$model[$modelName]['id']);
    // }
    $this->Html->addCrumb($dbFile['DbFile']['real_name'], '/db_files/view/'.$dbFile['DbFile']['id']);
?>

<div class="dbFiles view">
    <h3><?php  echo __('File: '. $dbFile['DbFile']['name']);?></h3>
	<?php
    if(isset($dbFile['DbFile']['real_name'])) {
            switch ($dbFile['DbFile']['type']) {
                case 'text/plain':
                    echo $this->Html->Tag(
                        'textarea',
                        $dbFile['DbFile']['content'],
                        array('class' => 'content', 'name' => 'content', 'rows' => '13', 'readonly' => 'true'));
                    break;
                case 'application/octet-stream':
                    $tmp = explode(".",$dbFile['DbFile']['real_name']);
                    $suffix = array_pop($tmp);
                    if(!in_array($suffix,array("jpg","jpeg","png")))   {
                        break;
                    }
                case 'image/png':
                case 'image/jpeg':
                    echo $this->Html->image(
                        array('action'=>'download', $dbFile['DbFile']['id'], false),
                        array(
                            'title'=>'This is a related file to a project',
                            'width'=>500,
                            'border'=>4,
                            'url' => array('controller' => 'db_files','action' => 'download', $dbFile['DbFile']['id'], false)
                        ));
                    break;
            }
		}
	?>
	<h3>Information</h3>
    <dl>
        <dt><?php echo __('Id'); ?></dt>
        <dd>
            <?php echo h($dbFile['DbFile']['id']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Name'); ?></dt>
        <dd>
            <?php echo h($dbFile['DbFile']['name']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Server filename'); ?></dt>
        <dd>
            <?php echo h($dbFile['DbFile']['real_name']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Comment'); ?></dt>
        <dd>
            <?php echo $dbFile['DbFile']['comment']; ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Size'); ?></dt>
        <dd>
            <?php echo h($dbFile['DbFile']['size']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Type'); ?></dt>
        <dd>
            <?php echo h($dbFile['DbFile']['type']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Created'); ?></dt>
        <dd>
            <?php echo h($dbFile['DbFile']['created']); ?>
            &nbsp;
        </dd>
        </dl>
	</dl>
	</dl>

	<br>
</div>

<div id='verticalmenu'>
    <h2>File</h2>

    <ul>
        <li class='active last'><?php echo $this->Html->link(__('Back'), $referer); ?></li>
        <li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $dbFile['DbFile']['id'])); ?> </li>
        <li><?php echo $this->Html->link(__('Download'), array('action' => 'download', $dbFile['DbFile']['id'])); ?> </li>
    </ul>
    <?php require(dirname(__FILE__).'/../Layouts/menu.ctp'); ?>
</div>
