<?php
/* @var $this WeiboController */
/* @var $model Weibo */

$this->breadcrumbs=array(
	'Weibos'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Weibo', 'url'=>array('index')),
	array('label'=>'Create Weibo', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#weibo-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Weibos</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'weibo-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'author',
		'content',
		'image_id',
		'video_id',
		'music_id',
		/*
		'root_id',
		'dateline',
		'type',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
