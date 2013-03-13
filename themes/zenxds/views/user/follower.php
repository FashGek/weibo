<!DOCTYPE HTML>
<html lang="zh-cn">
<head>
	<meta charset="UTF-8">
	<title><?php echo $title; ?>-随时随地分享身边的新鲜事儿</title>
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/base/base.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/base/global.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/components/skin.css">
	<style>
        body {
            padding-top: 60px;
        }
        .avatar-item {
            margin: 10px;
            width: 50px;
            padding: 5px;
        }
    </style>
    <link rel="shortcut icon" type="image/ico" href="<?php echo Yii::app()->theme->baseUrl ?>/favico.gif" />
	<script src="<?php echo Yii::app()->theme->baseUrl ?>/js/sea.js" id="seajsnode"></script>
</head>
<body>

<?php include_once('include/header.php');?>

<div id="body-wrapper">
    <div class="container" style="background:#FFF;">
        <ul class="unstyled fn-list">
            <?php foreach ($users as $user): ?>
                <li class="avatar-item fn-inline"><a href="<?php echo $user->url;?>"><img style="width:50px; height:50px;" src="<?php echo $user->AvatarUrl; ?>" alt=""></a>
                    <a href="<?php echo $user->url;?>"><?php echo $user->nikename; ?></a>
                </li>
                
            <?php endforeach ?>
        </ul>
    </div>
</div><!-- body-wrapper -->
 
<script src="<?php echo Yii::app()->theme->baseUrl ?>/js/config.js"></script>
<script>
seajs.use('init.js');
</script>

<?php include_once('templates/weibo-tpl.html'); ?>
<?php include_once('templates/forward_modal.html');?>
<?php include_once('templates/trend.html');?>
<?php include_once('templates/comment.html');?>
<?php include_once('templates/comment-detail.html');?>
<?php include_once('templates/comment-input.html');?>
<?php include_once('templates/comment-detail-input.html');?>

<div id="user-trend" class="fn-hide"></div>
</body>
</html>

