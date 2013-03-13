<?php

class FaceController extends Controller
{
	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
	public function actionGetList()
	{
		$faces = Yii::app()->params['faces'];
		$ret = array();
		$baseURL = Yii::app()->theme->baseUrl;
		foreach ($faces as $key => $value) {
			array_push($ret, array(
				'name' => $key,
				'url' => $baseURL . $value
			));
		}
		echo CJSON::encode($ret);
		Yii::app()->end();
	}
}