<?php
/* @var $this WeiboController */
/* @var $model Weibo */

$this->breadcrumbs=array(
	'Weibos'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Weibo', 'url'=>array('index')),
	array('label'=>'Create Weibo', 'url'=>array('create')),
	array('label'=>'View Weibo', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Weibo', 'url'=>array('admin')),
);
?>

<h1>Update Weibo <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>