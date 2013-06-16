<?php
// convert message codes to messages

$result=$_REQUEST['ok'];// To display info image
switch($_REQUEST['msgcode'])
{
	case MSG_DUPLICATE:
		$ResultStr = $lang['2nd_account_msg'];
		break;
	case MSG_UNACTIVATED:
		$ResultStr = $lang['ilogin_msg_17_1']." <a href=\"customer_service.php\">".$lang['ilogin_msg_17_2']."</a>.";
		break;
	case MSG_BANNED:
		$ResultStr = "<span style='color:#FF0000'>".$lang['ilogin_msg_18']."</span>";		
		break;
	case MSG_TRIES_LIMIT:
		$ResultStr = $lang['ilogin_msg_21_1']." $login_max_tries ".$lang['ilogin_msg_21_2']." $login_ban_period ".$lang['ilogin_msg_21_3'];
		break;
	case MSG_TRANS_DECLINED:
		$ResultStr = $lang['Transaction_declined']." : ".$_REQUEST['details'].$lang['May_mistake'];
		break;
	case MSG_ACCOUNT_APPROVED:
		$result=true;
		$ResultStr = $lang['Account_approved'];
		break;
	case MSG_ACCESS_DENIED:
		$ResultStr = $lang['ilogout_msg_4'];
		break;
}

if(!empty($_REQUEST['msgtext']))
{
	$ResultStr.=$_REQUEST['msgtext'];
}

PrintFormResultIfNeeded(false);

?>