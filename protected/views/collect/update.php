<?php
/* @var $this CollectController */
/* @var $model Collect */

$this->breadcrumbs=array(
	'Collects'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Collect', 'url'=>array('index')),
	array('label'=>'Create Collect', 'url'=>array('create')),
	array('label'=>'View Collect', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Collect', 'url'=>array('admin')),
);
?>

<h1>Update Collect <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>