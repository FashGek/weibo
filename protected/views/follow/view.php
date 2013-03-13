<?php
/* @var $this FollowController */
/* @var $model Follow */

$this->breadcrumbs=array(
	'Follows'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Follow', 'url'=>array('index')),
	array('label'=>'Create Follow', 'url'=>array('create')),
	array('label'=>'Update Follow', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Follow', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Follow', 'url'=>array('admin')),
);
?>

<h1>View Follow #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'follower',
		'followed',
		'remark',
		'dateline',
	),
)); ?>
