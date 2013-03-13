<?php

class SiteController extends Controller
{
	public $defaultAction = 'login';
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$test_data = array (
		    "name" => "Chris",
		    "value" => 10000,
		    "taxed_value" => 10000 - (10000 * 0.4),
		    "in_ca" => true
		);

		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index', array(
			"test_data" => $test_data
		));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	public function actionTest()
	{
		$content = '#abc#';
		$topics = Weibo::model()->getTopicsFromContent($content);
		foreach ($topics as $key => $value) {
			echo $value->id;
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		if(!Yii::app()->user->isGuest)
		{
			$homepage = $this->createUrl('user/home', array('id'=>Yii::app()->user->id));
			$this->redirect($homepage);
		}

		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}

		// display the login form
		$this->renderPartial('login',array(
			'model'=>$model,
			'title'=>'登陆',
		));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	public function actionSignup()
	{
		if(!Yii::app()->user->isGuest)
		{
			$this->redirect(Yii::app()->homeUrl);
		}
		$model=new User;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save()){
				$model->addDefaultGroups();
				$model->addProfile();
				
				$password = $_POST['User']['password'];

				// login
				$identity = new UserIdentity($model->username, $password);
				$identity->authenticate();
    			Yii::app()->user->login($identity, 3600*24*30);

				$this->redirect(Yii::app()->homeUrl);
			}
		}

		$this->renderPartial('signup',array(
			'model'=>$model,
			'title'=>'注册',
		));
	}

	// keyword
	public function actionSearch($k)
	{
		$user = new User('search');
		$user->unsetAttributes();
		$user->nikename = $k;
		
		$weibo = new Weibo('search');
		$weibo->unsetAttributes();
		$weibo->content = $k;
		// $keywords = explode(' ', $k));

		$this->renderPartial('search', array(
			'title' => '搜索',
			'user' => $user,
			'weibo' => $weibo
		));
	}
}