<?php
//$toMainDir = 'chat/';
//chdir($toMainDir);

require_once('common/database.php');
//require_once('common/dbaccount.php');
require_once('common/dbsession.php');
require_once('common/common.php');
//require_once('common_chat.php');
require_once('config/config.php');
//require_once('inc/ReadersOnline_class.php');

$AccountName = "PsychicContact";
$_AccountName = $AccountName;
$HIDE_ACCOUNT_NAME = true;

session_start();
$conn = mysql_connect($dbhost, $dbusername, $dbuserpassword);
mysql_select_db($default_dbname);

$ACCOUNT_TYPE = $ACCOUNT_CHAT;
$NO_LEFT_BR = true;

$PageName = "Online Live Psychic Chat";

//$playIntro =1;


// Draw the top navigation bar
//DrawNavigationBar("Home", array());
?>

<?php
// Draw Login box and 'Tell me more'
//$logInFormInclude = 1;

require_once("inc/loginForm.php");
?>

<?php
// Draw bottom copyright message

@mysql_close($conn);
?>