<!DOCTYPE HTML>
<html lang="zh-cn">
<head>
	<meta charset="UTF-8">
	<title><?php echo $title; ?>-随时随地分享身边的新鲜事儿</title>
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/base/base.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/base/global.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/page/login.css">
</head>
<body>
<div id="body-wrapper">
	<div class="container" id="header-wrapper">
		<h1 class="sl-hide-text" id="logo"><?php echo CHtml::link(Yii::app()->name, array('site/index')) ?></a></h1>
	</div>
	<div class="container">
		<div class="board">
			<img src="<?php echo Yii::app()->theme->baseUrl ?>/img/login_ad_default.jpg" alt="">
		</div>
	</div>
	<div class="container" id="content-wrappper">
		<div class="fn-left login-info">
			<div class="slogan">
				还没有微博帐号？现在加入	
				<?php echo CHtml::link("立即注册", array('site/signup')) ?>
			</div>
			<div class="show-img">
				<img src="<?php echo Yii::app()->theme->baseUrl ?>/img/big_dream.jpg">
			</div>
		</div>


		<div id="login-box" class="fn-bfc">
			<div class="form">
				<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
					'id'=>'login-form',
					'enableClientValidation'=>true,
					'clientOptions'=>array(
						'validateOnSubmit'=>true,
					),
				)); ?>

				<div class="item">
					<?php echo $form->textFieldRow($model,'username'); ?>
				</div>

				<div class="item">
					<?php echo $form->passwordFieldRow($model,'password'); ?>
				</div>

				<div class="item">
					<?php echo $form->checkBoxRow($model,'rememberMe'); ?>
					<?php echo $form->error($model,'rememberMe'); ?>
				</div>

				<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'warning', 'label'=>'登陆')); ?>

				<?php $this->endWidget(); ?>
				<p>还没有微博？<?php echo CHtml::link("立即注册！", array('site/signup')) ?></p>
			</div><!-- form -->	
		</div>
	</div>
</div><!-- body-wrapper -->
<script>
document.getElementById('LoginForm_username').focus();
</script>
</body>
</html>