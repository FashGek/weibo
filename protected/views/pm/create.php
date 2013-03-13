<?php
/* @var $this PmController */
/* @var $model Pm */

$this->breadcrumbs=array(
	'Pms'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Pm', 'url'=>array('index')),
	array('label'=>'Manage Pm', 'url'=>array('admin')),
);
?>

<h1>Create Pm</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>