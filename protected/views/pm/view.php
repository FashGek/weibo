<?php
/* @var $this PmController */
/* @var $model Pm */

$this->breadcrumbs=array(
	'Pms'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Pm', 'url'=>array('index')),
	array('label'=>'Create Pm', 'url'=>array('create')),
	array('label'=>'Update Pm', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Pm', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Pm', 'url'=>array('admin')),
);
?>

<h1>View Pm #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'to_id',
		'dateline',
		'content',
	),
)); ?>
