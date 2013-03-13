<?php

class UserController extends Controller
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
			array('allow',  
				'actions'=>array('home', 'trend', 'at', 'comment', 'pm', 'collect', 'follower', 'followed'),
				'users'=>array('@'),
				// 'roles'=>array('owner'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('admin'),
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

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new User;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
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

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
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
		$dataProvider=new CActiveDataProvider('User');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionTrend($time)
	{
		$id = Yii::app()->user->id;

		// @count
		$key = $key = "user:" . $id . ":atCount";
		$counter = new ARedisCounter($key);
		$atCount = $counter->getValue();

		// comment count
		$key = $key = "user:" . $id . ":commentCount";
		$counter = new ARedisCounter($key);
		$commentCount = $counter->getValue();

		// pm count
		$key = $key = "user:" . $id . ":pmCount";
		$counter = new ARedisCounter($key);
		$pmCount = $counter->getValue();
		
		// new weibo conut
		$weiboCount = Weibo::model()->getIntimeCount($time);

		$ret = array(
			"atCount" => $atCount,
			"commentCount" => $commentCount,
			"pmCount" => $pmCount,
			"weiboCount" => $weiboCount,
		);

		echo CJSON::encode($ret);
		Yii::app()->end();
	}

	public function actionHome($id)
	{
		$requestor = User::model()->findByPk(Yii::app()->user->id);
		$author = $this->loadModel($id);

		$params = array('id'=>$author->id);
		if(Yii::app()->user->checkAccess('owner', $params))
		{
			$isMe = 1;
			$isFollow = true;
			$template= "mine";
		} else {
			$isMe = 0;
			$isFollow = $requestor->getIsFollow($author->id);
			$template= "home";
		}
		// $pages = new CPagination(10);  
		// $pages->pageSize = Yii::app()->params['weiboPerpage'];



		$this->renderPartial($template,array(
			'title'=> '微博',
			'author' => $author,
			'requestor'=> $requestor,

			// mine & home
			'followers' => $author->followers,
			'followeds' => $author->followeds,
			'profile' => $author->profile,

			'isMe' => $isMe,
			'isFollow' => $isFollow,

			// 'remark' => $requestor->getRemark($author->id)
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return User the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param User $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionAt()
	{
		$id = Yii::app()->user->id;
		$requestor = User::model()->findByPk($id);
		$author = $this->loadModel($id);

		$requestor->clearAt();

		$params = array('id'=>$author->id);
		if(Yii::app()->user->checkAccess('owner', $params))
		{
			$isMe = 1;
			$isFollow = true;
		} else {
			$isMe = 0;
			$isFollow = $requestor->getIsFollow($author->id);
		}

		$template= "at";
		// $pages = new CPagination(10);  
		// $pages->pageSize = Yii::app()->params['weiboPerpage'];
		$urls = array(
			"atUrl" => $this->createUrl('user/at'),
			"homeUrl" => $this->createUrl('site/login'),
            "commentUrl" => $this->createUrl('user/comment'),
            "pmUrl" => $this->createUrl('user/pm'),
            "collectUrl" => $this->createUrl('user/collect'),
		);


		$this->renderPartial($template,array(
			'title'=> '@',
			'author' => $author,
			'requestor'=> $requestor,
			"urls" => $urls,
			'groups' => $author->groups,

			'followers' => $author->followers,
			'followeds' => $author->followeds,
			'profile' => $author->profile,

			'isMe' => $isMe,
			'isFollow' => $isFollow,
			'remark' => $requestor->getRemark($author->id)
		));
	}

	public function actionComment()
	{
		$id = Yii::app()->user->id;
		$requestor = User::model()->findByPk($id);
		$author = $this->loadModel($id);

		$requestor->clearComment();

		$params = array('id'=>$author->id);
		if(Yii::app()->user->checkAccess('owner', $params))
		{
			$isMe = 1;
			$isFollow = true;
		} else {
			$isMe = 0;
			$isFollow = $requestor->getIsFollow($author->id);
		}

		$template= "comment";
		// $pages = new CPagination(10);  
		// $pages->pageSize = Yii::app()->params['weiboPerpage'];
		$urls = array(
			"atUrl" => $this->createUrl('user/at'),
			"homeUrl" => $this->createUrl('site/login'),
            "commentUrl" => $this->createUrl('user/comment'),
            "pmUrl" => $this->createUrl('user/pm'),
            "collectUrl" => $this->createUrl('user/collect'),
		);


		$this->renderPartial($template,array(
			'title'=> '评论',
			'author' => $author,
			'requestor'=> $requestor,
			"urls" => $urls,
			'groups' => $author->groups,

			'followers' => $author->followers,
			'followeds' => $author->followeds,
			'profile' => $author->profile,

			'isMe' => $isMe,
			'isFollow' => $isFollow,
			'remark' => $requestor->getRemark($author->id)
		));
	}

	public function actionFollower()
	{
		$user = User::model()->findByPk(Yii::app()->user->id);
		$this->renderPartial('follower', array(
			'users' => $user->followers
		));
	}
	public function actionFollowed()
	{
		$user = User::model()->findByPk(Yii::app()->user->id);
		$this->renderPartial('followed', array(
			'users' => $user->followeds
		));
	}

	public function actionCollect()
	{
		$id = Yii::app()->user->id;
		$requestor = User::model()->findByPk($id);
		$author = $this->loadModel($id);

		$requestor->clearAt();

		$params = array('id'=>$author->id);
		if(Yii::app()->user->checkAccess('owner', $params))
		{
			$isMe = 1;
			$isFollow = true;
		} else {
			$isMe = 0;
			$isFollow = $requestor->getIsFollow($author->id);
		}

		$template= "collect";
		// $pages = new CPagination(10);  
		// $pages->pageSize = Yii::app()->params['weiboPerpage'];
		$urls = array(
			"atUrl" => $this->createUrl('user/at'),
			"homeUrl" => $this->createUrl('site/login'),
            "commentUrl" => $this->createUrl('user/comment'),
            "pmUrl" => $this->createUrl('user/pm'),
            "collectUrl" => $this->createUrl('user/collect'),
		);


		$this->renderPartial($template,array(
			'title'=> '收藏',
			'author' => $author,
			'requestor'=> $requestor,
			"urls" => $urls,
			'groups' => $author->groups,

			'followers' => $author->followers,
			'followeds' => $author->followeds,
			'profile' => $author->profile,

			'isMe' => $isMe,
			'isFollow' => $isFollow,
			'remark' => $requestor->getRemark($author->id)
		));
	}

	public function actionPm()
	{
		$id = Yii::app()->user->id;
		$requestor = User::model()->findByPk($id);
		$author = $this->loadModel($id);

		$requestor->clearPm();

		$params = array('id'=>$author->id);
		if(Yii::app()->user->checkAccess('owner', $params))
		{
			$isMe = 1;
			$isFollow = true;
		} else {
			$isMe = 0;
			$isFollow = $requestor->getIsFollow($author->id);
		}

		$template= "pm";
		// $pages = new CPagination(10);  
		// $pages->pageSize = Yii::app()->params['weiboPerpage'];
		$urls = array(
			"atUrl" => $this->createUrl('user/at'),
			"homeUrl" => $this->createUrl('site/login'),
            "commentUrl" => $this->createUrl('user/comment'),
            "pmUrl" => $this->createUrl('user/pm'),
            "collectUrl" => $this->createUrl('user/collect'),
		);


		$this->renderPartial($template,array(
			'title'=> '私信',
			'author' => $author,
			'requestor'=> $requestor,
			"urls" => $urls,
			'groups' => $author->groups,

			'followers' => $author->followers,
			'followeds' => $author->followeds,
			'profile' => $author->profile,

			'isMe' => $isMe,
			'isFollow' => $isFollow,
			'remark' => $requestor->getRemark($author->id)
		));
	}
}
