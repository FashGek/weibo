<?php
/* @var $this PmController */
/* @var $model Pm */

$this->breadcrumbs=array(
	'Pms'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Pm', 'url'=>array('index')),
	array('label'=>'Create Pm', 'url'=>array('create')),
	array('label'=>'View Pm', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Pm', 'url'=>array('admin')),
);
?>

<h1>Update Pm <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>