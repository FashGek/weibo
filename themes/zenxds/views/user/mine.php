<!DOCTYPE HTML>
<html lang="zh-cn">
<head>
	<meta charset="UTF-8">
	<title><?php echo $title; ?>-随时随地分享身边的新鲜事儿</title>
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/base/base.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/base/global.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/components/skin.css">
    <link rel="shortcut icon" type="image/ico" href="<?php echo Yii::app()->theme->baseUrl ?>/favico.gif" />
	<script src="<?php echo Yii::app()->theme->baseUrl ?>/js/sea.js" id="seajsnode"></script>
    <?php include_once('include/config.php');?>
    <script>
        Config.isInput = true;
        Config.page = 'mine';
    </script>
</head>
<body>

<?php include_once('include/header.php');?>

<div id="body-wrapper">
    <div class="container" id="content">
        <div class="col-main">
            <div id="publisher">
                <div class="header">
                    <h2 class="sl-hide-text title">有什么新鲜事想告诉大家？</h2>
                    <p>发言请遵守社区公约，<span class="length-remain">还可以输入<?php echo Yii::app()->params['weiboLength']?></span>字</p>
                </div>
                <div class="input-box">
                    <textarea class="input-detail" title="微博输入框" name="" style="margin: 1px 0px 0px; padding: 5px; font-size: 14px; font-family: Tahoma, 宋体; word-wrap: break-word; line-height: 18px; overflow-y: auto; overflow-x: hidden; outline: none;"></textarea>
                </div>
                <div class="func-area fn-clear">
                    <div class="func-list fn-left">
                        <a class="weibo-icon icon16 icon-face" title="表情"></a>
                        <a class="weibo-icon icon16 icon-img" title="图片"></a>
                        <a class="weibo-icon icon16 icon-qing" title="话题"></a>
                    </div>
                    <div class="submit fn-right" style="margin: 5px 12px 0 0;">
                        <a class="btn disabled weibo-submit" id="J-submit" href="javascript:;">发布</a>
                    </div>  
                </div>
                <div class="sub-content-area">
                    <a class="close" href="javascript:;" title="关闭"></a>
                    <div id="face-detail" class="sub-item hide">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#qq" data-toggle="tab">QQ表情</a></li>
                            <li><a href="#tusiji" data-toggle="tab">兔斯基</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="qq"></div>
                            <div class="tab-pane" id="tusiji"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="items-wrapper">
                <div class="loading fn-hide"></div>
                <div id="new-weibo-count"></div>
                <div class="items-area">
                </div>
            </div>
        </div>        
        <div class="col-sub">
           <?php include_once('include/leftNav.php');?>
        </div>        
        <div class="col-extra">
            <div class="inner">
                <div class="user-info fn-clear">
                    <div class="avatar fn-left">
                        <a href="<?php echo $author->url; ?>"><img src="<?php echo $author->AvatarUrl ?>" alt="" style="width:80px; height:80px;"></a>
                    </div>
                    <div class="info">
                        <?php echo CHtml::link($author->nikename, array('user/home', "id"=>$author->id)) ?>
                    </div>
                </div>
                <div class="count-msg">
                    <ul class="sl-floatcenter">
                        <li class="sl-floatcenter-item"><a href="<?php echo $this->createUrl('user/followed'); ?>"><strong><?php echo count($followeds); ?></strong>关注</a></li>
                        <li class="sl-floatcenter-item"><a href="<?php echo $this->createUrl('user/follower'); ?>"><strong><?php echo count($followers); ?></strong>粉丝</a></li>
                        <li class="sl-floatcenter-item"><a href=""><strong><?php echo count($author->weibos); ?></strong>微博</a></li>
                    </ul>
                </div>
            </div>      
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
</body>
</html>

