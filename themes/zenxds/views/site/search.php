<!DOCTYPE HTML>
<html lang="zh-cn">
<head>
	<meta charset="UTF-8">
	<title><?php echo $title; ?>-随时随地分享身边的新鲜事儿</title>
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/base/base.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/base/global.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/components/column2.css">
	<style>
        .item {
        	margin: 10px 0;
        	padding: 0 10px;
        }
    </style>
    <link rel="shortcut icon" type="image/ico" href="<?php echo Yii::app()->theme->baseUrl ?>/favico.gif" />
	<script src="<?php echo Yii::app()->theme->baseUrl ?>/js/sea.js" id="seajsnode"></script>
</head>
<body>

<?php include_once('include/header.php');?>
<div id="body-wrapper">
    <div class="container" id="content">
    	<div class="search-input">
    		<form class="form-search" method="get" action="">
			  <input type="text" class="input-medium search-query" name="k" value="<?php echo $_GET['k']; ?>">
			  <button type="submit" class="btn">搜索</button>
			</form>
    	</div>
        <div class="col-main">
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$weibo->search(),
	'itemView'=>'/weibo/_view',		# the _view file to render
    "emptyText" => '未找到微博',
	// 'sortableAttributes'=>array(
	//        'title',
 	//        'create_time'=>'Post Time',
 	//    ),

)); ?>
        </div>        
        <div class="col-sub">
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$user->search(),
	'itemView'=>'/user/_view',
    'emptyText' => '未找到用户',
)); ?>
        </div>        
    </div>
</div><!-- body-wrapper -->

<script src="<?php echo Yii::app()->theme->baseUrl ?>/js/config.js"></script>
<script>
seajs.use('init.js');
</script>
<?php include_once('templates/trend.html');?>
<div id="user-trend" class="fn-hide"></div>
</body>
</html>



<?php 
// $this->widget('zii.widgets.grid.CGridView', array(
// 	'id'=>'user-grid',
// 	'dataProvider'=>$user->search(),
// 	'filter'=>$user,
// 	'columns'=>array(
// 		'id',
// 		'username',
// 		'email',
// 		'password',
// 		'last_login_time',
// 		'create_time',
// 		array(
// 			'class'=>'CButtonColumn',
// 		),
// 	),
// )); 
?>