<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',

	// preloading 'log' component
	'preload'=>array('log'),

	// application components
	'components'=>array(
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
		
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),

		'authManager'=>array(
			'class'           => 'CDbAuthManager',
			'connectionID'    => 'db',
			'defaultRoles'    => array('authenticated', 'owner'),
			
			'itemTable'       => 'auth_item',
			'itemChildTable'  => 'auth_item_child',
			'assignmentTable' => 'auth_assignment',
		),
	),
);