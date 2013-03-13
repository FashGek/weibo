<?php
/* @var $this WeiboController */
/* @var $data Weibo */

$this->mustache_render('weibo-tpl-b', $data->toJSON());
?>