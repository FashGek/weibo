<?php
abstract class BaseActiveRecord extends CActiveRecord
{
	/**
	* Attaches the timestamp behavior to update our create and update times
	*/
	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array(
			'class' => 'zii.behaviors.CTimestampBehavior',
			'createAttribute' => 'dateline',
			),
		);
	}
}
