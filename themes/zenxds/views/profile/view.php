<!DOCTYPE HTML>
<html lang="zh-cn">
<head>
	<meta charset="UTF-8">
	<title><?php echo $title; ?>-随时随地分享身边的新鲜事儿</title>
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/base/base.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/base/global.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/components/datepicker.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/page/setting.css">
    <link rel="shortcut icon" type="image/ico" href="<?php echo Yii::app()->theme->baseUrl ?>/favico.gif" />
	<script src="<?php echo Yii::app()->theme->baseUrl ?>/js/sea.js" id="seajsnode"></script>
    <?php include_once('include/config.php');?>
</head>
<body>

<?php include_once('include/header.php');?>

<div id="body-wrapper">
    <div class="container" id="content">
        <div class="col-main">
            <?php $this->widget('zii.widgets.CDetailView', array(
				'data'=>$model,
				'attributes'=>array(
					'id',
					'user_id',
					'sex',
					'about',
					'birthday',
					'blog',
					'location',
					'avatar_url',
				),
				'htmlOptions' => array(
					"class" => 'table table-bordered'
				),
			)); ?>
        </div>        
        <div class="col-sub">
           <?php include_once('include/settingNav.php');?>
        </div>        
    </div>
</div><!-- body-wrapper -->

<script src="<?php echo Yii::app()->theme->baseUrl ?>/js/config.js"></script>
<script>
seajs.use('init.js');

seajs.use(['jquery', 'datepicker'], function($) {
	$('#Profile_birthday').datepicker({
	    format: 'yyyy-dd-mm'
	});
});
</script>
<?php include_once('templates/trend.html');?>
<div id="user-trend" class="fn-hide"></div>
</body>
</html>
