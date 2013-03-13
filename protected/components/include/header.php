<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a href="<?php echo $urls['homeUrl']; ?>" id="logo" class="brand sl-hide-text">微博</a>
            <ul class="nav">
                <li class="active"><?php echo CHtml::link('首页', Yii::app()->homeUrl) ?></li>
                <li><?php echo CHtml::link('实时', array('weibo/intime')); ?></li>
                <li><?php echo CHtml::link('热门', array('weibo/hot')); ?></li>
            </ul>
            <ul class="pull-right nav">
                <li><?php echo CHtml::link($requestor->nikename, array('profile/update', 'id'=>$requestor->id)); ?></li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-envelope icon-white"></i><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><?php echo CHtml::link("帐号设置", array('profile/update')); ?></li>
                        <li class="divider"></li>
                        <li><a tabindex="-1" href="<?php echo $this->createUrl('site/logout') ?>">退出</a></li>
                    </ul>
                </li>
            </ul>
            <form class="navbar-search pull-right" action="<?php echo $this->createUrl('site/search') ?>" method="get">
                <input type="text" class="search-query span2" name="k" placeholder="搜索微博或找人">
            </form>
        </div>
    </div>
</div> <!--  end navbar    -->