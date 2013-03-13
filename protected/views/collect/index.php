<?php
/* @var $this CollectController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Collects',
);

$this->menu=array(
	array('label'=>'Create Collect', 'url'=>array('create')),
	array('label'=>'Manage Collect', 'url'=>array('admin')),
);
?>

<h1>Collects</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
