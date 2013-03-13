<!DOCTYPE HTML>
<html lang="zh-cn">
<head>
	<meta charset="UTF-8">
	<title><?php echo $title; ?>-随时随地分享身边的新鲜事儿</title>
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/base/base.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/base/global.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/components/skin.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/page/home.css">
    <style>
        
    </style>
    <link rel="shortcut icon" type="image/ico" href="<?php echo Yii::app()->theme->baseUrl ?>/favico.gif" />
	<script src="<?php echo Yii::app()->theme->baseUrl ?>/js/sea.js" id="seajsnode"></script>
    <?php include_once('include/config.php');?>
    <script>
        Config.isMe = <?php echo $isMe; ?>;
        Config.isFollow = <?php echo $isFollow; ?>; // 请求人是否关注了当前用户     
    </script>
</head>
<body>

<?php include_once('include/header.php');?>

<div id="body-wrapper">
    <div class="container">
        <div id="profile-area" class="fn-clear">
            <div class="user-info fn-left">
                <div class="avatar">
                    <a href="<?php echo $author->url; ?>"><img src="<?php echo $author->AvatarUrl ?>" alt="" style="width:180px; height:180px;"></a>
                </div>
                <div class="count-msg">
                    <ul class="sl-floatcenter">
                        <li class="sl-floatcenter-item"><a href=""><strong><?php echo count($followeds); ?></strong>关注</a></li>
                        <li class="sl-floatcenter-item"><a href=""><strong><?php echo count($followers); ?></strong>粉丝</a></li>
                        <li class="sl-floatcenter-item"><a href=""><strong><?php echo count($author->weibos); ?></strong>微博</a></li>
                    </ul>
                </div>
            </div>
            <div class="fn-bfc user-profile">
                <h3 class="title"><?php echo CHtml::link($author->nikename, array('user/home', "id"=>$author->id)); ?></h3>
                <p><?php echo $profile->about; ?></p>
                <p><?php echo $profile->location; ?></p>
                <p><a href="<?php echo $profile->url; ?>">个人资料</a></p>
                <div class="toggle-follow">
                    <!-- <a class="btn">+关注</a> -->
                </div>
                <div id="pm-area">
                    <a class="btn send-pm">发私信</a>
                </div>
            </div>
        </div>
    </div>
    <div class="container" id="home-content">
        <div id="items-wrapper" class="fn-left">
            <div class="loading"></div>
            <div class="items-area">
            </div>
        </div>
               
        <div class="fn-bfc">
            <h3 class="title">他的粉丝(<?php echo count($followers); ?>)</h3>
            <ul class="unstyled">
                <?php foreach ($followers as $follower): ?>
                    <li class="avatar-item"><a href="<?php echo $follower->url;?>"><img src="<?php echo $follower->AvatarUrl; ?>" alt=""></a></li>
                    <a href="<?php echo $follower->url;?>"><?php echo $follower->nikename; ?></a>
                <?php endforeach ?>
            </ul>
            <h3 class="title">他的关注(<?php echo count($followeds); ?>)</h3>
            <ul class="unstyled">
                <?php foreach ($followeds as $followed): ?>
                    <li class="avatar-item">
                        <a href="<?php echo $followed->url;?>"><img src="<?php echo $followed->AvatarUrl; ?>" alt=""></a>
                        <a href="<?php echo $followed->url;?>"><?php echo $followed->nikename; ?></a>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>        
    </div>
</div><!-- body-wrapper -->
 
<script src="<?php echo Yii::app()->theme->baseUrl ?>/js/config.js"></script>
<script>
seajs.use('init.js');
seajs.use('page/mine.js');
</script>

<?php // com template ?>
<div id="user-trend" class="fn-hide"></div>
<?php include_once('templates/trend.html');?>
<?php include_once('templates/weibo-tpl.html'); ?>
<?php include_once('templates/forward_modal.html');?>
<?php include_once('templates/comment.html');?>
<?php include_once('templates/comment-input.html');?>
<script id="follow-tpl" type="text/template">
{{^isMe}}
<a class="btn" id="J-toggle-follow">{{#isFollow}}取消关注{{/isFollow}}{{^isFollow}}+关注{{/isFollow}}</a>
{{/isMe}}
</script>

<?php // page template ?>
<script id="weibo-count-tpl" type="text/template">
<p style="text-align:center; margin:10px; color:#F48C12; border:1px solid #f9f2a7; background:#FFF; ">
    <a href="javascript:;" id="J-weiboCount">{{weiboCount}}条新微博</a>
</p>
</script>
<script id="face-tpl" type="text/template">
<a href="javascript:;" title="{{ name }}"><img src="{{ url }}" title="{{ name }}" alt="{{ name }}" /></a>
</script>
<?php include_once('templates/image-input-modal.html');?>
<?php include_once('templates/pm_modal.html');?>
</body>
</html>

