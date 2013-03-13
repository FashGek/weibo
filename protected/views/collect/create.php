<?php
/* @var $this CollectController */
/* @var $model Collect */

$this->breadcrumbs=array(
	'Collects'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Collect', 'url'=>array('index')),
	array('label'=>'Manage Collect', 'url'=>array('admin')),
);
?>

<h1>Create Collect</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>