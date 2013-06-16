<?php 
ini_set('session.gc_maxlifetime', 3600);
require_once($app_path."inc/functions_auth.php");

$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];

$form_login = "$LoginName";
$form_password = "$LoginPassword";


//echo "len".$HTTP_GET_VARS[pwd];

if(strlen($_GET[pwd]))
{
	$form_password = $_GET[pwd];
	$LoginPassword = $_GET[pwd];
}

$login_max_tries     = 10; //max tries before banning
$login_ban_period    = 1;  //banning period (hours)

$ip        = $_SERVER['REMOTE_ADDR'];
$tries     = 0;
$found_log = 0;

$mysql_ins = "UPDATE login_stat set tries= '0', last_try = now() where (username = '$form_login' or ip = '$ip') and reset_time < now()";
mysql_query($mysql_ins);


$strsql = "SELECT tries, reset_time FROM login_stat where username = '$form_login' or ip = '$ip'";
$rs = mysql_query($strsql) or die(mysql_error());
while($row = mysql_fetch_assoc($rs)){
	$tries     += $row['tries'];
	$reset_time = $row['reset_time'];
	$found_log  = 1;
}
mysql_free_result($rs);


if(0 == $found_log)
{
	$mysql_ins = "INSERT into login_stat (username, ip, tries) values('$form_login', '$ip', '0')";
	mysql_query($mysql_ins);
}

$login_ok=0;


if($tries < $login_max_tries)
{
	$type_db = '';

	$strsql = "SELECT * FROM T1_1 where login = '$form_login'";
	
	$rs = mysql_query($strsql) or die(mysql_error());
	while($row = mysql_fetch_assoc($rs)){
		$rr_record_id_db = $row["rr_record_id"];
		$type_db  = $row["type"];
		$Client_login = $row[login];
		$login_db = $Client_login;
		$name_db = $row["name"];
		$Client_password = $row[password];
		$Client_affiliate = $row[affiliate];
		
		
		$randomstr = date("H-d-n", time());
		$Client_password_md5 = "$Client_password$randomstr";
		$Client_password_md5 = ($Client_password_md5);

		//Canada
		if('canada' == $Client_affiliate)
		{
			$form_password_md5 = "$form_password$randomstr";
			$form_password_md5 = ($form_password_md5);
			//echo" $http_canada/chatlogin.php?LoginName=$form_login&pwd=$form_password_md5";
			header("Location: $http_canada/chatlogin.php?LoginName=$form_login&pwd=$form_password_md5\n\n");
			mysql_free_result($rs);
			mysql_close();
			exit($conn);
		}


		if($Client_password == $form_password)
		{
			//setcookie("Registered", "$Client_login", time()+$time2live, "/");
			setcookie("Registered", "$Client_login", time()+172800, "/"); // 172800 = 2 days

			if (isset($_POST['ref']) and (strlen($_POST['ref']) > 1))
			{    $Client_password_md5_2 = md5($Client_password . date("d-n", time()));
			header("Location: " . $_POST['ref'] . "?id=$rr_record_id_db&pwd=$Client_password_md5_2");
			exit();
			}
			$Client_status = $row[Client_status];
			
			$login_ok=1;
			break;
		}
		else if(strlen($_GET[pwd]) > 0 and $Client_login == $form_login and $Client_password_md5 == $_GET[pwd])
		{
			setcookie("Registered", "$Client_login", time()+172800, "/"); // 172800 = 2 days
			$form_password = $Client_password;
			$LoginName = $Client_login;
			$LoginPassword = $Client_password;
			$Client_status = $row[Client_status];
			$login_ok=1;
			break;
		}

	}

	mysql_free_result($rs);
	if($login_ok > 0)
	{
		$mysql_ins = "UPDATE login_stat set tries= '0', last_try = now() where username = '$form_login' or ip = '$ip'";
		mysql_query($mysql_ins);
		if('reader' == $type_db)
		{
			setcookie("Reader_id", "$rr_record_id_db", time()+21600, "/");
			$mysql_ins = "INSERT into total_online_time (reader_id, last_reset, last_update) values('$rr_record_id_db', now(), '0')";
			@mysql_query($mysql_ins);

			$curr_time = time();

			$mysql = "UPDATE total_online_time set last_update = '0' where reader_id = '$rr_record_id_db'";
			@mysql_query($mysql);

			//Save the time Readers Log in to arrange the list of Readers Online
			$time = time();
			$mysql = "UPDATE T1_1 set free_mins = '$time' where rr_record_id = '$rr_record_id_db'";
			@mysql_query($mysql);

		}

	}
	else
	{
		$mysql_ins = "UPDATE login_stat set tries= tries+1, last_try = now(), reset_time = DATE_ADD(now(), interval $login_ban_period hour) where username = '$form_login' or ip = '$ip'";
		mysql_query($mysql_ins);
	}
}
else //Bann that client for some time
{
	//$ErrorStr = "You made $login_max_tries tries to log in. You are temporary banned for $login_ban_period hour(s).";
	//PrintFormResultIfNeeded(false, $ErrorStr);
}

$no_access = 0;
$allIps    = '';
$strsql    = "SELECT * FROM band_list WHERE UserID = '$rr_record_id_db'";
$rs = mysql_query($strsql) or die(mysql_error());
while($row = mysql_fetch_assoc($rs))
{
	$Client_status = $row[Client_status];
	if('banned' == $Client_status or 'unactivated' == $Client_status or 'banned_by_reader' == $Client_status){
		$no_access=1;
	}
	$allIps = $row['IP'];
}
mysql_free_result($rs);

if (!preg_match("/$REMOTE_ADDR/", "$allIps"))
{
	$allIps .= "\n" . $REMOTE_ADDR;
}

//if($type_db != "Administrator" and $type_db != "reader" and (!isset($Client_status) or strlen($Client_status) < 1)){
if($type_db != "Administrator" and (!isset($Client_status) or strlen($Client_status) < 1)){

	$login_db=addslashes($login_db);
	$name_db=addslashes($name_db);

	$upd="INSERT INTO band_list VALUES ('$rr_record_id_db', '$login_db', '$name_db', '$REMOTE_ADDR', now(), '', '', 'limited', '1999-01-01', '', '1999-01-01', '')";
	mysql_query($upd);
}
elseif($no_access < 1){
	$upd="UPDATE band_list set IP = '$allIps', Date = now() where UserID = '$rr_record_id_db'";
	mysql_query($upd);
}
elseif($no_access == 1){
	$upd="UPDATE band_list set Date = now() where UserID = '$rr_record_id_db'";
	mysql_query($upd);
}
//echo $type_db.'_'.$login_ok.'_'.$no_access;

if('client' == $type_db and '1' == $login_ok and $no_access < 1)
{
	$time_now =date("M j, Y h:i a", time());

	//Send emails to Readers
	$strsql = "SELECT * FROM T1_1 WHERE type = 'reader'";
	$rs = mysql_query($strsql) or die(mysql_error());

	while($row = mysql_fetch_assoc($rs)){
		$reader_login = $row["login"];
		$reader_email = $row["emailaddress"];
		$reader_name = $row["name"];
		$text_2_send = "<b>$time_now<br></b><br><b>".$lang['user']."</b>:<b><font color='#FF0000'> $Client_login </font></b>".$lang['ilogin_msg_1']."<br>&nbsp;";
		$subject = $lang['PSYCHAT']." - ".$lang['ilogin_msg_2_1']." $Client_login ".$lang['ilogin_msg_2_2'];
		//mail($reader_email, $subject, $text_2_send, $headers);
		//mail("sumca2004@gmail.com", $subject, $text_2_send, $headers);
	}

	mysql_free_result($rs);

	//Send email to admin
	$text_2_send = "<b>$time_now<br></b><br><b>".$lang['user']."</b>:<b><font color='#FF0000'> $Client_login </font></b>".$lang['ilogin_msg_1']."<br>&nbsp;";
	$subject = $lang['PSYCHAT']." - ".$lang['ilogin_msg_2_1']." $Client_login ".$lang['ilogin_msg_2_2'];
	//mail($adm_email, $subject, $text_2_send, $headers);

}


if (!isset($ACCOUNT_TYPE))
{

	$ErrorStr = $lang['ilogin_msg_3'];

	header('Location: '.$app_path.'inform.php?msgtext='.$ErrorStr);
	
	die;
}

//$HeadingStr = $lang['Psychic_Contact'];

$FormName = "Login";


if (!empty($ResultStr))
{
	$ErrorStr = $ResultStr;
}
else
{
	if (!empty($HeadingStr))
	{
		$ErrorStr = $HeadingStr;
	}
	else
	{
		$ErrorStr = $lang['ilogin_msg_4'];
	}
}


if (isset($FORGOT_PASSWORD) or 'email3' == $_POST['action'])
{
	$email_addr_send = '';
	if('email3' == $_POST['action'])
	{
		if(empty($_POST[email1])) $_POST[email1] = 123;
		if(empty($_POST[email2])) $_POST[email2] = 123;
		if(empty($_POST[email3])) $_POST[email3] = 123;
		$ErrorStr = '';

		$strsql = "SELECT login, password, emailaddress, balance, rr_createdate  from T1_1 where emailaddress = '$_POST[email1]' or emailaddress = '$_POST[email2]' or emailaddress = '$_POST[email3]' order by rr_createdate limit 0,1";
		$rs = mysql_query($strsql) or die(mysql_error());
		while ($row = mysql_fetch_assoc($rs)){
			$login_db         = $row["login"];
			$password_db      = $row["password"];
			$emailaddress_db  = $row["emailaddress"];
			$balance_db       = $row["balance"];
			$rr_createdate_db = $row["rr_createdate"];
			$rr_createdate_db = date("M j, Y", strtotime($rr_createdate_db));

			$_account[]           = $login_db;
			$_user[]              = $login_db;
			$_password[]          = $password_db;
			$email_addr_send      = $emailaddress_db;

			$balance_db = round($balance_db, 0);
			if($balance_db < 1) $balance_db = '0';


			if(!empty($_POST[email1]))
			{
				$ErrorStr = $lang['ilogin_msg_5_1']." $login_db<br>".$lang['ilogin_msg_5_2']." $rr_createdate_db<br>".$lang['ilogin_msg_5_3']." $balance_db ".$lang['ilogin_msg_5_4'];
				$ErrorStr_mail = str_replace('<br>', "\n", $ErrorStr);
				@mail($email_addr_send, $lang['ilogin_msg_6'],
				$ErrorStr_mail, $lang['From'].": ".GetSupportEmail());
				//$resultForgotPassword = 1;
			}

		}
		mysql_free_result($rs);
		//You are creating more than One account.
	}
	else
	{
		$resultForgotPassword = accountGetLoginByEmail($EmailAddress, $ACCOUNT_TYPE, &$_account, &$_user, &$_password, &$email_addr_send);
	}

	if ($resultForgotPassword)
	{
		$message        = $lang['ilogin_msg_7_1'].",\r\n\r\n".$lang['ilogin_msg_7_2']." $REMOTE_ADDR \r\n\r\n".$lang['ilogin_msg_7_3'];

		for ($i=0; $i<count($_account); $i++)
		{
			//$email_addr_send = $_email_addr_send[$i];
			$message        .=
			$lang['Account_Name'].": ".$_account[$i]."\r\n".
			"       ".$lang['Login'].": ".$_user[$i]."\r\n".
			"    ".$lang['Password'].": ".$_password[$i]."\r\n".
			"\r\n";
			//                                          echo $message;
		}

		$message        .=       $lang['ilogin_msg_8'].":\r\n".HTTP_BASE;

		@mail($email_addr_send, $lang['ilogin_msg_6'],
		$message, $lang['From'].": ".GetSupportEmail());

		$ErrorStr = $lang['ilogin_msg_9'].": '".htmlspecialchars($email_addr_send)."'. ".$lang['ilogin_msg_10'];
		$result = 1;
	}
	else
	{
		if('email3' == $_POST['action'] and empty($ErrorStr))
		{
			$ErrorStr = $lang['ilogin_msg_11'];
		}
		else if('email3' != $_POST['action'])
		{
			$ErrorStr = $lang['ilogin_msg_12_1']." '".htmlspecialchars($EmailAddress)."' ".$lang['ilogin_msg_12_2'];
			
		}
	}
}
else if('contact' == $_POST[action])
{
	$text_2_send_db = $lang['Reason'].": $_POST[reason]<br>".$lang['Username']." (".$lang['ilogin_msg_13']."): $_POST[username]<br>".$lang['email_Address'].": $_POST[email]";

	$mysql_ins = "INSERT into messages (ID, From_user, From_name, To_user, Date, Subject, Body, Status, Read_message)
    VALUES('', '1', 'User', '1', now(), '2nd account problem', '$text_2_send_db', 'ok', 'no')";
	mysql_query($mysql_ins);
	@mail($adm_email, $lang['PSYCHAT']." - ".$lang['second_account_problem'], $text_2_send_db, $headers);

	$ErrorStr = $lang['ilogin_msg_14'];
	
}

// Store cookie to remember 'AccountName' and 'LoginName'
// (We are NEVER going to remember the 'LoginPassword' for security reasons
if (1)//isset($Remember) && $Remember)
{
	if (isset($AccountName))
	{
		setcookie("_AccountName", $AccountName, time()+3600*24*60);
		$_AccountName = $AccountName;
	}
	if (isset($LoginName))
	{
		setcookie("_LoginName", $LoginName, time()+3600*24*60);
		$_LoginName = $LoginName;
	}
}

// If we have all the information
if (!isset($FORGOT_PASSWORD))
{
	if (isset($AccountName) && $AccountName!="" &&
	isset($LoginName) && isset($LoginPassword))
	{

		// Reset
		$operator_id 	= 0;
		$ErrorStr       = $lang['ilogin_msg_15'];
		$FormName       = $lang['ilogin_msg_16'];

		if (!empty($DREAMTIME_ACCOUNT))
		{

			DTImportUser($DREAMTIME_ACCOUNT, "Operators", $LoginName);
		}
		
		// Verify if valid login
		$operator_id=userLogin($LoginName, $LoginPassword, $ErrorStr);
		
		
		if($login_ok > 0){

			if($no_access < 1){
				// Store successful login in session
				$login_account_id	= $account_id;
				$login_operator_id  = $operator_id;
				
				// Register session variables
				SessionRegisterVars($login_account_id, $login_operator_id);
				
				// Store password for easy access
				$_LoginPassword = $LoginPassword;
				session_register ("_LoginPassword");

				//Header("Location: system.php");
				Header("Location: chatmain.php");

				die('Stop script processing - we are moving to another page');        // Stop script processing - we are moving to another page
			}
			else{
				
				//include ("left.php");
				if('unactivated' == $Client_status){
					//echo"<font color=\"FF0000\">You are in the <b>Banned List</b> or you did not activate your account.</font><br>If you think this is a mistake, please contact us.";
					$ErrorStr = $lang['ilogin_msg_17_1']." <a href=\"customer_service.php\">".$lang['ilogin_msg_17_2']."</a>.";
				}
				else{
					$ErrorStr = "<font color=\"FF0000\">".$lang['ilogin_msg_18'];
				}
				header('Location: '.$app_path.'inform.php?msgtext='.$ErrorStr);
				die;
			}
		}
		else
		{
			// $ErrorStr now holds the error
			// Unregister session variables
			SessionUnregisterVars();

			$_LoginPassword = "";
			
		}
	}
	else
	{
	}
}
else
{
}

if($tries >= $login_max_tries)
{
	$ErrorStr = $lang['ilogin_msg_21_1']." $login_max_tries ".$lang['ilogin_msg_21_2']." $login_ban_period ".$lang['ilogin_msg_21_3'];
}

header('Location: '.$app_path.'inform.php?msgtext='.$ErrorStr);

die('pause');

function SessionRegisterVars($login_account_id, $login_operator_id)
{
	global        $policy_upload, $policy_communicate, $policy_data, $policy_deploy;
	global        $policy_robots, $policy_admin, $use_frames;

	// AccountID / OperatorID
	session_register ("login_account_id");
	session_register ("login_operator_id");
}

function SessionUnregisterVars()
{
	session_unregister ("login_account_id");
	session_unregister ("login_operator_id");
	session_unregister ("_LoginPassword");
}
?>