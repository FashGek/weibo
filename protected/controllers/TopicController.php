<?php

class TopicController extends Controller
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

	public function actionView($name)
	{
		$model = $this->loadModel($name);

		$id = Yii::app()->user->id;
		$requestor = User::model()->findByPk($id);
		$author = $requestor;

		$params = array('id'=>$author->id);
		if(Yii::app()->user->checkAccess('owner', $params))
		{
			$isMe = 1;
			$isFollow = true;
		} else {
			$isMe = 0;
			$isFollow = $requestor->getIsFollow($author->id);
		}
		$urls = array(
			"atUrl" => $this->createUrl('user/at'),
			"homeUrl" => $this->createUrl('site/login'),
            "commentUrl" => $this->createUrl('user/comment'),
            "pmUrl" => $this->createUrl('user/pm'),
            "collectUrl" => $this->createUrl('user/collect'),
		);

		$this->renderPartial('topic', array(
			'title'=> '话题',
			'author' => $author,
			'requestor'=> $requestor,
			"urls" => $urls,
			'groups' => $author->groups,

			'followers' => $author->followers,
			'followeds' => $author->followeds,
			'profile' => $author->profile,
			'topic' => $model,
			'isMe' => $isMe,
			'isFollow' => $isFollow,
			'remark' => $requestor->getRemark($author->id)
		));
	}

	public function loadModel($name)
	{
		$model=Topic::model()->findByAttributes(array(
			'name' => $name
		));
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	public function actionGetWeibos($name)
	{
		$model = $this->loadModel($name);
		$weibos = array();

		foreach ($model->topicWeibos as $item) {
			array_push($weibos, $item->weibo);
		}

		$ret = $this->actionWeibosToJSON($weibos);
		echo $ret;
	}

	public function actionWeibosToJSON($weibos) {
		$ret = array();
		foreach ($weibos as $key => $weibo) {
			$ret[$key] = $weibo->toJSON();
			
			if ($weibo->root) {
				$ret[$key]['root'] = $weibo->root->toJSON();
			}
			
		}
		return json_encode($ret);
	}

}