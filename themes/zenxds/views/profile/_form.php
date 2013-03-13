<?php
/* @var $this ProfileController */
/* @var $model Profile */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'profile-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('class'=>''),
)); ?>

	<p class="note"><span class="required">*</span>必须填写项</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="item">
		<?php echo $form->dropDownListRow($model,'sex', $model->getSexOptions(), array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="item">
		<?php echo $form->textareaRow($model,'about',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="item">
		<?php echo $form->textFieldRow($model,'birthday'); ?>
	</div>

	<div class="item">
		<?php echo $form->textFieldRow($model,'blog'); ?>
	</div>

	<div class="item">
		<?php echo $form->textFieldRow($model,'location',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="item">
		<?php echo $form->textFieldRow($model,'avatar_url',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="item buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->