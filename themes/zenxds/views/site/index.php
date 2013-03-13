<?php 
$this->pageTitle=Yii::app()->name;
echo Yii::app()->theme->baseUrl;
?>

<?php 
$this->mustache_render('test', $test_data);

Yii::app()->redis->getClient()->set("myKey", "Your Value 2");
echo Yii::app()->redis->getClient()->get("myKey"); // outputs "Your Value"
?>

<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
		)); ?>