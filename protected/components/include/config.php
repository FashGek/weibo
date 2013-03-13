<?php 
// base config

$urls = array(
    "atUrl" => $this->createUrl('user/at'),
    "homeUrl" => $this->createUrl('site/login'),
    "commentUrl" => $this->createUrl('user/comment'),
    "pmUrl" => $this->createUrl('user/pm'),
    "collectUrl" => $this->createUrl('user/collect'),
);
?>

<script>
    var Config = {
        request: {
            baseUrl: "<?php echo Yii::app()->baseUrl; ?>",

            face: "<?php echo $this->createUrl('face/getList') ?>",

            trend: "<?php echo $this->createUrl('user/trend'); ?>",
            atUrl: "<?php echo $urls['atUrl']; ?>",
            commentUrl: "<?php echo $urls['commentUrl']; ?>",
            pmUrl: "<?php echo $urls['pmUrl']; ?>",

            comments: "<?php echo $this->createUrl('comment/ajaxRouter'); ?>",

            weibo: "<?php echo $this->createUrl('weibo/ajaxRouter'); ?>",   // weibos
            
            saveWeibo: "<?php echo $this->createUrl('weibo/ajaxUpdate'); ?>",   // weibo
            follow: "<?php echo $this->createUrl('follow/ajaxRouter'); ?>",
            collect: "<?php echo $this->createUrl('collect/ajaxRouter'); ?>",
            pm: "<?php echo $this->createUrl('pm/ajaxRouter'); ?>"
        },
        user: <?php echo $author->toJSON(); ?>,
        requestor: <?php echo $requestor->toJSON(); ?>,
        maxWeiboLength: <?php echo Yii::app()->params['weiboLength']; ?>
    };
</script>