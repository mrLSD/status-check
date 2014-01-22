<?php
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Status check System',
	'defaultController' => 'status',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'ext.*',
	),

	'modules'=>array(
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),

		/**
		 * URL routings
		 */
		'urlManager'=>array(
                        'urlFormat'=>'path',
                        'showScriptName' => false,
                        'rules'=>array(
                                '<controller:\w+>/<action:\w+>'=> array('<controller>/<action>','caseSensitive' => false),
                        ),
                ),


                /**
                 * MongoDB config
                 */
                'dbm'=>array(
                        'class' => 'ext.EMongoDBConnection',
                        'db_name' => 'statuscheck',
                        'server' => 'mongodb://localhost:27017',
                ),

		'errorHandler'=>array(
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
                                        array(
                                                'class'=>'CProfileLogRoute',
                                                'levels'=>'profile',
                                                'enabled'=>true,
                                      	),
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);