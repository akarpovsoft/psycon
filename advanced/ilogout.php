<?php
$NOTAG = true;
$NOFRAMES = true;
$app_path = "";

require_once($app_path."common/common.php");


/*
require_once('database.php');
require_once('dbaccount.php');
require_once('dbsession.php');
require_once('common.php');
require_once('inc/WebphoneSystem_class.php');
*/

session_start();

$account_id = 1;

$operator = get_user($login_operator_id);
/*
if('reader' == $operator['type'])
{
$wp = new WebphoneSystem();
$wp->setReaderId($operator['rr_record_id']);
$wp->updatePhoneSystem('Off');
}
*/
if (!isset($ACCOUNT_TYPE))
{
	$ErrorStr = $lang['ilogin_msg_3'];
	
	echo "= ".$ErrorStr;
	
	header('Location: '.$app_path.'inform.php?msgtext='.$ErrorStr);
	
	die('pause1');
}

$_LoginPassword = "";

session_unregister("login_account_id");
session_unregister("_LoginPassword");
session_unset();
session_destroy();
//echo "1= ".$ErrorStr."<br>";
//$ErrorStr =  $lang['You_have_been_Logged_out.'];

//die('pause');
//PrintFormResultIfNeeded(false, $lang['You_have_been_Logged_out.'], isset($Reason) ? 1 : 0); 
//echo "2= ".$ErrorStr."<br>";
if (isset($Reason))
{
        $ErrorStr = $lang['ilogout_msg_1'];
}
else
{
      $ErrorStr = $lang['ilogout_msg_2']."&nbsp;&nbsp;";
}
//echo "3= ".$ErrorStr."<br>";
$ErrorStr = $ErrorStr." ".$lang['ilogout_msg_3'];

$sql = "UPDATE `T1_1` SET `status` = 'offline' WHERE rr_record_id =".$operator['rr_record_id'];
mysql_query($sql);
//echo "4= ".$ErrorStr."<br>";

header('Location: '.$app_path.'inform.php?&ok=1&no_reg='.(isset($Reason) ? 1 : 0).'&msgtext='.$ErrorStr);
die();


?>