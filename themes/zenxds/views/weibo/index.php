<?php
/* @var $this WeiboController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Weibos',
);

$this->menu=array(
	array('label'=>'Create Weibo', 'url'=>array('create')),
	array('label'=>'Manage Weibo', 'url'=>array('admin')),
);
?>

<h1>Weibos</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
