<?php
/* @var $this CollectController */
/* @var $model Collect */

$this->breadcrumbs=array(
	'Collects'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Collect', 'url'=>array('index')),
	array('label'=>'Create Collect', 'url'=>array('create')),
	array('label'=>'Update Collect', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Collect', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Collect', 'url'=>array('admin')),
);
?>

<h1>View Collect #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'weibo_id',
		'dateline',
	),
)); ?>
