<?php
/* @var $this FollowController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Follows',
);

$this->menu=array(
	array('label'=>'Create Follow', 'url'=>array('create')),
	array('label'=>'Manage Follow', 'url'=>array('admin')),
);
?>

<h1>Follows</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
