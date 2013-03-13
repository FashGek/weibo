<?php

class WeiboController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'ajaxRouter', 'ajaxUpdate', 'homeAjaxRouter', 'atWeibos', 'collectWeibos', 'intime', 'hot', 'ajaxView'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionAjaxView($id)
	{
		$model = $this->loadModel($id);
		$ret = array($model->toJSON());
		echo json_encode($ret);
	}
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{	
		$requestor = User::model()->findByPk(Yii::app()->user->id);
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
		// $pages = new CPagination(10);  
		// $pages->pageSize = Yii::app()->params['weiboPerpage'];
		$urls = array(
			"atUrl" => $this->createUrl('user/at'),
			"homeUrl" => $this->createUrl('site/login'),
            "commentUrl" => $this->createUrl('user/comment'),
            "pmUrl" => $this->createUrl('user/pm'),
            "collectUrl" => $this->createUrl('user/collect'),
		);

		$this->renderPartial('view',array(
			'title'=> '微博',
			'author' => $author,
			'requestor'=> $requestor,
			"urls" => $urls,

			'groups' => $author->groups,
			'followers' => $author->followers,
			'followeds' => $author->followeds,
			'profile' => $author->profile,

			'isMe' => $isMe,
			'isFollow' => $isFollow,
			'remark' => $requestor->getRemark($author->id),
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Weibo;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Weibo']))
		{
			$model->attributes=$_POST['Weibo'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
	public function actionAjaxRouter($id=null) {
		$method = $_SERVER['REQUEST_METHOD'];

		if ($method == 'POST') {
			return $this->actionAjaxCreate();
		}elseif ($method == 'GET') {
			return $this->actionAjaxGetList($id);
		} elseif ($method == 'DELETE') {
			return $this->actionAjaxDelete($id);
		} elseif ($method == 'PUT') {
			return $this->actionAjaxUpdate($id);
		}
		Yii::app()->end();
	}

	public function actionAjaxCreate() {
		$requestBody = file_get_contents('php://input');
		$params = json_decode($requestBody, true);


		// forward
		if ($params['forward_id']) {
			$weibo = Weibo::model()->findByPk($params['forward_id']);
			$weibo->addForward($model->id);
		}
		if ($params['image_url']) {
			$media = new Media;
			$media->url = $params['image_url'];
			$media->save();
		}
		$model = new Weibo;
		$model->attributes = $params;
		$model->image_id = $media->id;
		$model->save();
		Yii::app()->end();
	}

	public function actionAjaxGetList($id)
	{
		
		$weibos = Weibo::model()->recent($id)->findAll();

		$ret = $this->actionWeibosToJSON($weibos);
		echo $ret;
		Yii::app()->end();
	}

	public function actionAtWeibos($id)
	{
		$key = "user:" . $id . ":at";
		$list = new ARedisList($key);
		
		$weibos = array();
		foreach ($list as $weiboId) {
			$weibo = Weibo::model()->findByPk($weiboId);
			array_push($weibos, $weibo);
		}

		$ret = $this->actionWeibosToJSON($weibos);
		echo $ret;
		Yii::app()->end();
	}

	public function actionCollectWeibos($id)
	{
		$user = User::model()->findByPk($id);
		$collects = $user->collects();
		
		$weibos = array();
		foreach ($collects as $collect) {
			array_push($weibos, $collect->weibo);
		}

		$ret = $this->actionWeibosToJSON($weibos);
		echo $ret;
		Yii::app()->end();
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

	public function actionAjaxDelete($id)
	{
		$weibo = $this->loadModel($id);
		$weibo->type = 1;
		$weibo->content = "该微博已被原作者删除";
		$weibo->save();
		Yii::app()->end();
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Weibo']))
		{
			$model->attributes=$_POST['Weibo'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionAjaxUpdate($id)
	{	
		$params = $this->getParams();

		Collect::model()->toggleCollect($id, $params['isCollect']);
		echo 'success';
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Weibo');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Weibo('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Weibo']))
			$model->attributes=$_GET['Weibo'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Weibo the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Weibo::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	public function getParams()
	{
		$requestBody = file_get_contents('php://input');
		$params = json_decode($requestBody, true);
		return $params;
	}

	public function actionIntime()
	{
		$dataProvider = new CActiveDataProvider('Weibo',array(
			 'criteria'=>array(
		        'condition'=>'type=1',
		        'order'=>'dateline ASC'
		    ),
		));

		// $weibos = Weibo::model()->findAll();
		$this->renderPartial('intime', array(
			'dataProvider' => $dataProvider
		));
	}

	public function actionHot()
	{
		$redis = Yii::app()->redis->getClient();
		$key = "weibo:rank";
		$ids = $redis->zrevrange($key, 0, -1);
		
		$weibos = array();

		foreach($ids as $id) {
		    $weibo = Weibo::model()->findByPk($id);
		    if ($weibo->type == 0) {
		    	array_push($weibos, $weibo);
		    }
		    
		}
		$this->renderPartial('hot', array(
			"weibos" => $weibos
		));
	}

	/**
	 * Performs the AJAX validation.
	 * @param Weibo $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='weibo-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
