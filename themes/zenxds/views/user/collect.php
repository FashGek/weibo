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
        #content {
            padding-right: 0;
            width: 790px;
            background: #FFF;
        }
    </style>
    <link rel="shortcut icon" type="image/ico" href="<?php echo Yii::app()->theme->baseUrl ?>/favico.gif" />
	<script src="<?php echo Yii::app()->theme->baseUrl ?>/js/sea.js" id="seajsnode"></script>
    <?php include_once('include/config.php');?>
    <script>
        Config.request.weibos = "<?php echo $this->createUrl('weibo/collectWeibos') ?>";
    </script>
</head>
<body>

<?php include_once('include/header.php');?>

<div id="body-wrapper">
    <div class="container" id="content">
        <div class="col-main">
            <div id="items-wrapper">
                <div class="loading"></div>
                <div class="items-area">
                </div>
            </div>
        </div>        
        <div class="col-sub">
           <?php include_once('include/leftNav.php');?>
        </div>        
    </div>
</div><!-- body-wrapper -->
 
<script src="<?php echo Yii::app()->theme->baseUrl ?>/js/config.js"></script>
<script>
seajs.use('init.js');
seajs.use('page/mine.js');
</script>

<script id="face-tpl" type="text/template">
<a href="javascript:;" title="{{ name }}"><img src="{{ url }}" title="{{ name }}" alt="{{ name }}" /></a>
</script>
<?php include_once('templates/weibo-tpl.html'); ?>
<?php include_once('templates/forward_modal.html');?>
<?php include_once('templates/trend.html');?>
<?php include_once('templates/comment.html');?>
<?php include_once('templates/comment-input.html');?>

<script id="follow-tpl" type="text/template">
{{^isMe}}
<a class="btn btn-block" id="J-toggle-follow">{{#isFollow}}取消关注{{/isFollow}}{{^isFollow}}+关注{{/isFollow}}</a>
{{/isMe}}
</script>

<div id="user-trend" class="fn-hide"></div>
</body>
</html>

