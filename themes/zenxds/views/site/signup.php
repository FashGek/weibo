<!DOCTYPE HTML>
<html lang="zh-cn">
<head>
	<meta charset="UTF-8">
	<title><?php echo $title; ?>-随时随地分享身边的新鲜事儿</title>
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/base/base.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/base/global.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/page/signup.css">
	<link rel="shortcut icon" type="image/ico" href="<?php echo Yii::app()->theme->baseUrl ?>/favico.gif" />
</head>
<body>
<div id="body-wrapper">
	<div class="container" id="header-wrapper">
		<h1 class="sl-hide-text" id="logo"><?php echo CHtml::link(Yii::app()->name, array('site/index')) ?></a></h1>
	</div>
	<div class="container" id="content-wrappper">
		<div id="signup-box" class="fn-left">
			<div class="form">
				<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
					'id'=>'signup-form',
					'enableClientValidation'=>true,
					'clientOptions'=>array(
						'validateOnSubmit'=>true,
					),
				)); ?>

				<div class="item">
					<?php echo $form->textFieldRow($model,'username'); ?>
				</div>

				<div class="item">
					<?php echo $form->textFieldRow($model,'nikename'); ?>
				</div>

				<div class="item">
					<?php echo $form->textFieldRow($model,'email'); ?>
				</div>

				<div class="item">
					<?php echo $form->passwordFieldRow($model,'password'); ?>
				</div>

				<div class="item">
					<?php echo $form->passwordFieldRow($model,'password_repeat'); ?>
				</div>


				<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'warning', 'size'=>'large', 'label'=>'立即注册')); ?>

				<?php $this->endWidget(); ?>
			</div><!-- form -->	
		</div>

		<div class="bfc">
			<p>已有帐号， <?php echo CHtml::link('直接登录»', array('site/login')) ?></p>
		</div>
	</div>
</div><!-- body-wrapper -->
<script>
document.getElementById('User_username').focus();
</script>
</body>
</html>