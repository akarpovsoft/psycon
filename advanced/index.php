<?php

// added may 18th 2012
$redirectlist = array('advanced/site/login');

function filter($data) {

    if(is_array($data)) {
        foreach($data as $key => $value) {
            $data[$key] = filter($value);
        }
        
    }
    else  {
        $data = trim(htmlentities(strip_tags($data)));
 
        if (get_magic_quotes_gpc())
            $data = stripslashes($data);
    }
 
//    $data = mysql_real_escape_string($data);
 
    return $data;
}

$checkurl = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

if (!strstr("$checkurl", 'staticPage')) { // allow to save html tags in static pagess
	foreach($_POST as $key => $value) {
	    $_POST[$key] = filter($value);
	}
	
	foreach($_GET as $key => $value) {
	    $_GET[$key] = filter($value);
	}
}

if ( strstr("$checkurl", '../') || stristr("$checkurl", 'script' )) {
print "error";
exit;
}

//print "<!-- 
//$checkurl
//-->";

if ($checkurl == "www.psychic-contact.com/advanced/site/login" && strtolower($_SERVER['HTTPS']) != 'on') {
    exit(header("location: https://{$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}"));
}

// change the following paths if necessary
$yii=dirname(__FILE__).'/../../framework/yii.php';
$config=dirname(__FILE__).'/../../protected/config/main.php';

// remove the following line when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);

require_once($yii);
Yii::createWebApplication($config)->run();

