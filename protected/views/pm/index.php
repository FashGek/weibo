<?php
/* @var $this PmController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Pms',
);

$this->menu=array(
	array('label'=>'Create Pm', 'url'=>array('create')),
	array('label'=>'Manage Pm', 'url'=>array('admin')),
);
?>

<h1>Pms</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
