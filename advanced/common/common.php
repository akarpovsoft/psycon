<?php

// No time limit
//ini_set('session.gc_maxlifetime', 3600);

@set_time_limit (0);
//error_reporting(E_COMPILE_ERROR|E_RECOVERABLE_ERROR|E_ERROR|E_CORE_ERROR);
error_reporting(E_ALL&~E_NOTICE);

// No cache
Header("Cache-Control: no-cache, must-revalidate");
Header("Pragma: no-cache");
Header("Expires: Tue, Jan 12 1999 01:01:01 GMT");

header('Content-type: text/html; charset=utf-8');

require_once ($app_path."config/config.php");
require_once($app_path."common/constants.php");
require_once($app_path."common/dbsession.php");

require_once($app_path."common/database.php");
require_once($app_path."inc/functions_display.php");
require_once($app_path."inc/functions_common.php");
require_once($app_path."inc/functions_auth.php");
require_once($app_path."common/network.php");

//include($app_path."lang/current_lang.php");

$ACCOUNT_TYPE = $ACCOUNT_CHAT;
$AccountName = "AstralContact";

dbhandle(); // Connect to db
session_start();

//ini_set('session.gc_maxlifetime', 120);
$tableOperators="T1_1";
$tableChatSessions="T1_12";
$tableRequests="T1_7";
$tableChatRequests="T1_7";
if(isset($_GET['lang'])) $_SESSION['lang']=$_GET['lang'];


if(!is_array($_SESSION))	$_SESSION=$HTTP_SESSION_VARS;
while(list($post_key1, $post_val1) = each($_SESSION)){
	${$post_key1} = "$post_val1";
}
$account_id=1;



if (isset($GLOBALS['HTTP_USER_AGENT']))
{
	if (strstr(strtolower($GLOBALS['HTTP_USER_AGENT']), "msie") != "")
	{
		$IEBROWSER = true;
	}
	else
	{
		$IEBROWSER = false;
	}
}
else
{
	$IEBROWSER = true;
}

if (!$IEBROWSER)
{
	$NS_IMG = "<img src=\"images/transp.gif\">";
}
else
{
	$NS_IMG = "";
}

if (!$IEBROWSER)
{
	$TABLE_PADDING = "";
}
else
{
	$TABLE_PADDING = "cellspacing=0 cellpadding=1";
}

# на вход $login_operator_id и $groups_allowed
# false : переход на inform.php (возвращается $group_name)
# true : пользователь прошел проверку и получает доступ к странице ( возвращается operator)

# -1  не зареганый
#  0  нет прав
#  1  прошел

$tmp = validateUser($_SESSION['login_operator_id'],$groups_allowed, &$param);

function GetAccountID()
{
        global $INTERNAL_PAGE, $login_account_id, $account_id, $LoginRequired, $DREAMTIME_ACCOUNT;

        if (isset($LoginRequired) && !isset($INTERNAL_PAGE))
        {
                if (!empty($DREAMTIME_ACCOUNT))
                {
                        return $DREAMTIME_ACCOUNT;
                }

                if (session_is_registered('login_account_id'))
                {
                        return $login_account_id;
                }
        }
        else
        {
                return $account_id;
        }

        return "";
}

function GetAccountTypeByServerName()
{
        global        $SERVER_NAME, $ACCOUNT_MLM, $ACCOUNT_RR, $ACCOUNT_DOWNLINE, $ACCOUNT_CHAT, $ACCOUNT_DTF;

        if (_strpos(strtolower($SERVER_NAME),"downlinesystem")!=-1)
        {
                return $ACCOUNT_DOWNLINE;
        }

        if (_strpos(strtolower($SERVER_NAME),"richandhealthy")!=-1)
        {
                return $ACCOUNT_MLM;
        }

        if (_strpos(strtolower($SERVER_NAME),"psychicdoor")!=-1)
        {
                return $ACCOUNT_CHAT;
        }

        if (_strpos(strtolower($SERVER_NAME),"psychic-contact")!=-1)
        {
                return $ACCOUNT_CHAT;
        }

        if (_strpos(strtolower($SERVER_NAME),"debttofreedom")!=-1)
        {
                return $ACCOUNT_DTF;
        }

        return $ACCOUNT_RR;
}

include_once($app_path."lang/current_lang.php"); 
require_once($app_path."common/email_messages.php");
$month_name[1]=$lang['Jan'];
$month_name[2]=$lang["Feb"];
$month_name[3]=$lang["Mar"];
$month_name[4]=$lang["Apr"];
$month_name[5]=$lang["May"];
$month_name[6]=$lang["Jun"];
$month_name[7]=$lang["Jul"];
$month_name[8]=$lang["Aug"];
$month_name[9]=$lang["Sep"];
$month_name[10]=$lang["Oct"];
$month_name[11]=$lang["Nov"];
$month_name[12]=$lang["Dec"];
$month_name[all]=$lang["All_months"];
$month_name[year]=$lang["Last_12_months"];

$regards_mail = $lang['regards']."<br>astral-contact.ru/chat/";

if ($tmp == 1)
{
	$operator = $param;

}
else if ($tmp == -1)
{
	header('Location:'.$http_addr.'inform.php?msgtext='.urlencode($lang['Unauthorized_access']).'&no_reg=1'); //lang
	
	die("-1");
}
else if ($tmp == 0)
{
	header('Location:'.$http_addr.'inform.php?msgtext='.urlencode($lang['No_permissions']).'&no_reg=1'); //lang
	
	die("0");
}

?>