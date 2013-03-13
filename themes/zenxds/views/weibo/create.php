<?php
/* @var $this WeiboController */
/* @var $model Weibo */

$this->breadcrumbs=array(
	'Weibos'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Weibo', 'url'=>array('index')),
	array('label'=>'Manage Weibo', 'url'=>array('admin')),
);
?>

<h1>Create Weibo</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>