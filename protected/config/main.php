<?php

require_once 'dbconfig.php';
require_once 'blog_dbconfig.php';
require_once 'hula_dbconfig.php';
require_once 'netrestrictor.php';

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');


// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Psychic Contact - Online Live Psychic Chat',
	'language'=>'en_us',
	'sourceLanguage'=>'blabla',
	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.components.widgets.*',
		'application.components.widgets.ssl.*',
		'application.helpers.*',
	),
	
	// site modules
	'modules' => array('answers','api','goFish','chat','hula',
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'mypass123',
			'ipFilters' => array('*'),
		),
	),
	
	// application components
	'components'=>array(
		'user'=>array(
			'class' => 'PsyUser',
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		'db'=>$dbconfig,
		'blog_db' => $blog_db,
		'hula_db' => $hula_db,
                'cache' => array(
                    'class'=>'system.caching.CFileCache',
                ),
		// uncomment the following to use a MySQL database
		/*
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=testdrive',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		*/
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
        'session'=>array(
        	'class'=>'PsyDbHttpSession',
        ),
        'preference'=>array(
        	'class'=>'UserPreference',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning, info',
					'logFile' => 'mylog.log',
					'enabled' => true,
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
		   	'showScriptName'=>false,
			'rules' => array(
                            'reader/<id:.*?>'=>'profile/index',
                            'page/<id:.*?>'=>'staticPage/show',
				'preview/<id:.*?>'=>'profile/preview',
                            'article/<alias:.*?>'=>'articles/index',
                            'chat/chatstart' => 'chat/default/chatstart',
                            'chat/waitingFail' => 'chat/default/waitingFail',
                            'chat/chatRequest' => 'chat/default/chatRequest',
                            'chat/chatStartChecking' => 'chat/default/chatStartChecking',
                            'chat/readerMonitor' => 'chat/default/readerMonitor',
                            'chat/readerStatusChange' => 'chat/default/readerStatusChange',
                            'chat/startChatting' => 'chat/default/startChatting',
                            'chat/clientendsession' => 'chat/default/clientendsession',
                            'chat/readerendsession' => 'chat/default/readerendsession',
                            'chat/ChatClient' => 'chat/default/ChatClient',
                            'chat/ChatReader' => 'chat/default/ChatReader',
                            'chat/checkNewClient' => 'chat/default/checkNewClient',
                            'chat/monitorLogout' => 'chat/default/monitorLogout',
                            'chat/removeRequest' => 'chat/default/removeRequest',
                            'chat/readerStatusCheck' => 'chat/default/readerStatusCheck',
				'teambuy' => 'site/teambuy'
                        )
		)
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'javachat@psychic-contact.com',
		'http_path'=>"http://www.psychic-contact.com",
		'RR_HOST'=>"localhost",
		'ssl_addr'=>"https://www.psychic-contact.com/advanced/",
		'http_addr'=>"http://www.psychic-contact.com/advanced/",
		'http_canada'=> "http://www.psychic-contact.ca/chat",
		'https_canada'=>"https://www.psychic-contact.ca/chat",
		'http_url'=>"https://psychic-contact.com/advanced/",
		'DOCUMENT_ROOT'=>realpath('./')."../../",
		'regards_mail'=>"<br><br>Regards,<br>Psychic Contact<br>www.psychic-contact.com/chat",
		'HTTP_BASE_URL'=>"http://move.psychic-contact.com/advanced/",
		'HTTPS_BASE_URL'=>"https://move.psychic-contact.com/advanced/",
                'site_domain' => 'http://www.psychic-contact.com',
                'site_domain_ssl' => 'https://www.psychic-contact.com',
                'default_lang' => 'en_us',
                'default_layout' => 'application.views.layouts.new',
                'default_layout_readers' => 'application.views.layouts.readers',
                'default_layout_main_page' => 'application.views.layouts.main_page',
                'default_chat_layout' => 'application.views.layouts.chat',
		  'default_chatreadings_layout' => 'application.views.layouts.chatreadings',
		  'default_iPhone_layout' => 'application.views.layouts.iPhone',
		  'default_signup_layout' => 'application.views.layouts.signup',
		  'default_without_left_menu' => 'application.views.layouts.new_without_left_menu',
                'site_type' => 'www.psychic-contact.com/advanced',
                'paypal_url' => 'https://www.paypal.com',
                'paypal_buisness_email' => 'orders@psychic-contact.com',
		//'paypal_url' => 'https://sandbox.paypal.com',
		//'paypal_buisness_email' => 'batman@yandex.ru',
		  'netrestrict' => $restrict,
		'project_root' => '/home/psychi/domains/psychic-contact.com/public_html',
		'default_chatreadings_layout' => 'application.views.layouts.chatreadings',
		'chatIdleTime' => 240,
        'num_answers' => '5',
        'num_question' => '3',
	 'payment_system' => 'echo',
	 'default_ps' => 2,
	 'account_params' => array(
			'1' => array(				// PS TYPE (ECHO)
				'1' => array(			// ACCOUNT ID
					'login' => '212>3340195',
					'pin' => '86740249',
				),
			),
			'2' => array(				// PS TYPE (AUTHNET)
				'1' => array(			// ACCOUNT ID
					'login' => '93WCWcd7e',
					'pin' => '6M5edbs2BJ7L6L24',
				),
                                '2' => array(
                                        'login' => '63yY47dpK9',
                                        'pin' => '584DP3ja7D99LgRZ'
                                )
			),
			'3' => array(				// PS TYPE (FIRST DATA)
				'1' => array(			// ACCOUNT ID
					'login' => 'WS1909594750._.1',
					'pin' => 'mvP2PpRA',
				),
			),
			'4' => array(				// PS TYPE (IS)
				'1' => array(			// ACCOUNT ID
					'login' => '4371',
					'pin' => '',
				),
			),
		),
		'standart_avatar_width' => 150,
              'rasp_domains' => array(
                     'PN'=>array('site_url' => 'www.psychiccontact.net', 
                                 'site_name' => 'Psychiccontact.net', 
                                 'affiliate' => 41,
                                 'admin_email' => 'support@psychiccontact.net'), 
                     'SP'=>array('site_url' => 'www.spiritualplace.com',
                                 'site_name' => 'SpiritualPlace', 
                                 'affiliate' => 22,
                                 'admin_email' => 'support@spiritualplace.com'), 
	              'MS'=>array('site_url' => 'www.mysticalsearch.com',
                                 'site_name' => 'MysticalSearch', 
                                 'affiliate' => 5,
                                 'admin_email' => 'mysticladydi@yahoo.com'),
	),),	
);