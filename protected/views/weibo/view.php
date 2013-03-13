<?php
/* @var $this WeiboController */
/* @var $model Weibo */

$this->breadcrumbs=array(
	'Weibos'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Weibo', 'url'=>array('index')),
	array('label'=>'Create Weibo', 'url'=>array('create')),
	array('label'=>'Update Weibo', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Weibo', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Weibo', 'url'=>array('admin')),
);
?>

<h1>View Weibo #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'author',
		'content',
		'image_id',
		'video_id',
		'music_id',
		'root_id',
		'dateline',
		'type',
	),
)); ?>
