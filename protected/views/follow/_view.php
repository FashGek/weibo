<?php
/* @var $this FollowController */
/* @var $data Follow */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('follower')); ?>:</b>
	<?php echo CHtml::encode($data->follower); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('followed')); ?>:</b>
	<?php echo CHtml::encode($data->followed); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('remark')); ?>:</b>
	<?php echo CHtml::encode($data->remark); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dateline')); ?>:</b>
	<?php echo CHtml::encode($data->dateline); ?>
	<br />


</div>