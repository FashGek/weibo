<?php
/* @var $this FollowController */
/* @var $model Follow */

$this->breadcrumbs=array(
	'Follows'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Follow', 'url'=>array('index')),
	array('label'=>'Manage Follow', 'url'=>array('admin')),
);
?>

<h1>Create Follow</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>