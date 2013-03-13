<?php
/* @var $this FollowController */
/* @var $model Follow */

$this->breadcrumbs=array(
	'Follows'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Follow', 'url'=>array('index')),
	array('label'=>'Create Follow', 'url'=>array('create')),
	array('label'=>'View Follow', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Follow', 'url'=>array('admin')),
);
?>

<h1>Update Follow <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>