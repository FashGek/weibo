<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');

require_once(dirname(__FILE__). '/face.php');


// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	
	'name'=>'微博',
	'theme' => 'zenxds',

	// preloading 'log' component
	'preload'=>array('log'),

	'language' => 'zh_cn',	# i18n

	'homeUrl'=>array('site/login'),		// redirect to after logout

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'ext.YiiRedis.*'
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'zenxds',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false, 
			'rules'=>array(
				
				'<action:(login|logout|about|signup|search)>' => 'site/<action>',
				// 'home' => 'user/home', 
				'n/<nikename:.+>'=>'n/view',
				't/<name:.+>'=>'topic/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/ajaxRouter',
				// '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		
		// 'db'=>array(
		// 	'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		// ),
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=weibo',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'zenxds',
			'charset' => 'utf8',
		),
		
		# Yii::app()->authManager
		'authManager'=>array(
			'class'           => 'CDbAuthManager',
			'connectionID'    => 'db',
			'defaultRoles'    => array('authenticated', 'owner'),
			
			'itemTable'       => 'auth_item',
			'itemChildTable'  => 'auth_item_child',
			'assignmentTable' => 'auth_assignment',
		),

		'mustache'=>array(
            'class'=>'ext.mustache.components.MustacheApplicationComponent',
            // Default settings (not needed)
            'templatePathAlias'=>'application.components.templates',
            'templateExtension'=>'html',
            'extension'=>true,
        ),

		"redis" => array(
	        "class" => "ext.YiiRedis.ARedisConnection",
	        "hostname" => "localhost",
	        "port" => 6379,
	        "database" => 2
	    ),

		'bootstrap'=>array(
            'class'=>'bootstrap.components.Bootstrap',
        ),
        
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'zenxds@gmail.com',
		'defaultGroups' => array('名人明星', '同学', '同事', '朋友', '其他'),
		'weiboLength' => 140,
		'faces' => $faceMap,

		'weiboPerPage'=> 3,
	),
);