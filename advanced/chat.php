<?php
require_once('inc/Core.php');
$topMenuActive = 1; // include top navigation menu

if(strlen($HTTP_COOKIE_VARS[filter_month]) < 1){
	$time2live = 86400 *30;
	$cur_month = date("n", time());
	setcookie("filter_month", "$cur_month", time()+$time2live, "/");
	setcookie("filter_reader", "all", time()+$time2live, "/");
	setcookie("filter_period", "All", time()+$time2live, "/");
	$HTTP_COOKIE_VARS[filter_month] = $cur_month;
}
if(strlen($HTTP_COOKIE_VARS[filter_year]) < 1)
{
	$time2live = 86400 *30;
	$cur_year = date("Y", time());
	setcookie("filter_year", "$cur_year", time()+$time2live, "/");
	$HTTP_COOKIE_VARS[filter_year] = $cur_year;
}
if(empty($_COOKIE[filter_reader]))
{
	setcookie("filter_reader", "2", time()+$time2live, "/");
	$_COOKIE[filter_reader] = '2';
	$filter_reader          = 2;
}

if(empty($_COOKIE[Reader_id]))
{
	setcookie("Reader_id", "2", time()+$time2live, "/");
	$_COOKIE[Reader_id] = '2';
	$Reader_id          = 2;
}

if(empty($HTTP_COOKIE_VARS['filter_period']))
{
	setcookie("filter_period", "All", time()+$time2live, "/");
	$HTTP_COOKIE_VARS['filter_period'] = 'All';
}

require_once ("config/config.php");
require_once("common/common.php");

$this_year = date("Y", time());
$last_year = $this_year;
$last_year--;

// Gloalbs
$PageName                = $STR_HISTORY;
$LoginRequired        = true;
$NUM_COLS = 7;


require_once("common/dbmessage.php");

// azmom asked to disable
if($login_operator_id==18737) {
	echo '<br /><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;History reviewing is disabled.Please contact Admin to enable it again';
	include ("bottom.php");
	die();
}

$conn = mysql_connect($dbhost, $dbusername, $dbuserpassword);
mysql_select_db($default_dbname);
$type="zero";

$strsql = "SELECT * FROM T1_1 WHERE rr_record_id ='$HTTP_SESSION_VARS[login_operator_id]'";
$rs = mysql_query($strsql, $conn) or die(mysql_error());
while ($row = mysql_fetch_assoc($rs)){
	$type = $row["type"];
	$affiliate_db = $row["affiliate"];
}
mysql_free_result($rs);

if($affiliate_db == "canada"){
	Header("Location: $http_canada/chat.php");
	exit();
}

if($type == "Administrator" or $type == "reader"){
	$NUM_COLS+=3;
}
if($type == "Administrator"){
	$NUM_COLS+=1;
}
if($type != "Administrator" and $type != "reader"){
	$NUM_COLS-=2;
}

$sql_client ="";
if($type != "Administrator" and $type !="reader"){
	$sql_client = " where Client_id = '$HTTP_SESSION_VARS[login_operator_id]' ";
	if(isset($filter)){
		$keywords = split(" ", $filter);
		while(list($key, $val) = each($keywords)){
			$sql_client .= " and (Client_name like '%$val%' or Reader_name like '%$val%' or Subject like '%$val%' or Session_id = '$val') ";
		}
	}
	if(strlen($HTTP_COOKIE_VARS[filter_month]) > 0 and $HTTP_COOKIE_VARS[filter_month] !="all")
	{
		//$sql_client .= " and month(History.Date) = '$HTTP_COOKIE_VARS[filter_month]' ";
		//$sql_client2 .= " month(Date) = '$HTTP_COOKIE_VARS[filter_month]' ";
		$sql_client  .= ' and History.Date LIKE \'' . $HTTP_COOKIE_VARS['filter_year'] . '-' . sprintf("%02d",$HTTP_COOKIE_VARS['filter_month']) . '%\'';
		$sql_client2 .= ' and Date LIKE \'' . $HTTP_COOKIE_VARS['filter_year'] . '-' . sprintf("%02d",$HTTP_COOKIE_VARS['filter_month']) . '%\'';
	}
	else
	{    $sql_client  .= ' and History.Date LIKE \'' . $HTTP_COOKIE_VARS['filter_year'] . '-%\'';
	$sql_client2 .= ' and Date LIKE \'' . $HTTP_COOKIE_VARS['filter_year'] . '-%\'';
	}
	//$sql_client .= " and year(History.Date) = '$HTTP_COOKIE_VARS[filter_year]'";
	//$sql_client2 .= "  year(Date) = '$HTTP_COOKIE_VARS[filter_year]'";
}
elseif($type == "Administrator"){

	if(strlen($_COOKIE[payout_from]) > 0)
	{
		$reader_select = $_COOKIE[payout_reader];

		$sql_client = " where Reader_id = '$_COOKIE[payout_reader]' and History.Date >= '$_COOKIE[payout_from]' and History.Date <= '$_COOKIE[payout_to]' ";
		$sql_client2 = " Date >= '$_COOKIE[payout_from]' and Date <= '$_COOKIE[payout_to]' ";
	}
	elseif($_COOKIE[payout_client] > 0)
	{

		$sql_client = " where Client_id = '$_COOKIE[payout_client]' ";
	}
	else
	{
		$no_where=false;
		$sql_client = "";
		$reader_select = $_COOKIE[filter_reader];
		if(isset($filter)){
			$keywords = split(" ", $filter);
			while(list($key, $val) = each($keywords)){
				if($key == 0){
					$sql_client .="where ";
				}
				else{
					$sql_client .="and ";
				}
				$sql_client .= " (Client_name like '%$val%' or Reader_name like '%$val%' or Subject like '%$val%' or Session_id = '$val') ";
			}
		}
		elseif(strlen($HTTP_COOKIE_VARS[filter_reader]) <= 0 || $HTTP_COOKIE_VARS[filter_reader] =="all") 
			$no_where=true;
			
		$filter_periodSql = '';
		if(!empty($HTTP_COOKIE_VARS['filter_period']) and ('All' != $HTTP_COOKIE_VARS['filter_period']))
		{
			$period_start = ($HTTP_COOKIE_VARS['filter_period']  == "1-15")?1:16;
			$period_stop  = ($HTTP_COOKIE_VARS['filter_period']  == "1-15")?15:31;

			$filter_periodSql = ' and (DAYOFMONTH(Date) >= '.$period_start.'  and DAYOFMONTH(Date) <= '.$period_stop.')';
		}
		//$HTTP_COOKIE_VARS['filter_period']
		if(strlen($HTTP_COOKIE_VARS[filter_month]) > 0 and $HTTP_COOKIE_VARS[filter_month] !="all")
		{
			if(strlen($HTTP_COOKIE_VARS[filter_reader]) > 0 and $HTTP_COOKIE_VARS[filter_reader] !="all"){
				if(!isset($filter) and (strlen($HTTP_COOKIE_VARS[filter_month]) < 1 or $HTTP_COOKIE_VARS[filter_month] == "all")){
					$sql_client .= "where Reader_id = '$HTTP_COOKIE_VARS[filter_reader]'";
				}
				else{
					$sql_client .= "where Reader_id = '$HTTP_COOKIE_VARS[filter_reader]'";
				}
			}
			else
				$no_where=true;

			if(!isset($filter))
			{
				//$sql_client .= "where month(History.Date) = '$HTTP_COOKIE_VARS[filter_month]' and year(History.Date) = '$HTTP_COOKIE_VARS[filter_year]' $filter_periodSql";
				$sql_client .= ($no_where?" WHERE 1 ":"")."and History.Date LIKE '$HTTP_COOKIE_VARS[filter_year]-".sprintf("%02d",$HTTP_COOKIE_VARS['filter_month'])."%' $filter_periodSql";
				$sql_client2 .= " Date LIKE '$HTTP_COOKIE_VARS[filter_year]-".sprintf("%02d",$HTTP_COOKIE_VARS['filter_month'])."%' $filter_periodSql";
			}
			else{
				//$sql_client .= " and month(History.Date) = '$HTTP_COOKIE_VARS[filter_month]' and year(History.Date) = '$HTTP_COOKIE_VARS[filter_year]') $filter_periodSql";
				//$sql_client2 .= "  month(Date) = '$HTTP_COOKIE_VARS[filter_month]' and year(Date) = '$HTTP_COOKIE_VARS[filter_year]') $filter_periodSql";
				$sql_client .= ($no_where?" WHERE 1 ":"")." and History.Date LIKE '$HTTP_COOKIE_VARS[filter_year]-".sprintf("%02d",$HTTP_COOKIE_VARS['filter_month'])."' $filter_periodSql";
				$sql_client2 .= "  Date LIKE '$HTTP_COOKIE_VARS[filter_year]-".sprintf("%02d",$HTTP_COOKIE_VARS['filter_month'])."' $filter_periodSql";

			}
		}

	}

}

elseif($type == "reader"){
	$reader_select = $HTTP_SESSION_VARS[login_operator_id];

	$sql_client = " where Reader_id = '$HTTP_SESSION_VARS[login_operator_id]' ";
	if(isset($filter)){
		$keywords = split(" ", $filter);
		while(list($key, $val) = each($keywords)){
			$sql_client .= " and (Client_name like '%$val%' or Reader_name like '%$val%' or Subject like '%$val%' or Session_id = '$val') ";
		}
	}
	if(strlen($HTTP_COOKIE_VARS[filter_month]) > 0 and $HTTP_COOKIE_VARS[filter_month] !="all"){
		//$sql_client .= " and month(History.Date) = '$HTTP_COOKIE_VARS[filter_month]' and year(History.Date) = '$HTTP_COOKIE_VARS[filter_year]'";
		//$sql_client2 .= "  month(Date) = '$HTTP_COOKIE_VARS[filter_month]' and year(Date) = '$HTTP_COOKIE_VARS[filter_year]'";
		$sql_client .= " and History.Date LIKE '$HTTP_COOKIE_VARS[filter_year]-".sprintf("%02d",$HTTP_COOKIE_VARS['filter_month'])."%' ";
		$sql_client2 .= "  Date LIKE '$HTTP_COOKIE_VARS[filter_year]-".sprintf("%02d",$HTTP_COOKIE_VARS['filter_month'])."%' ";

	}
}

// Set this as default page
preferenceSet($PREF_DEFPAGE, $PREF_DEFPAGE_CHAT, "chat.php");

// Draw the top navigation bar
//DrawNavigationBar($STR_CHAT, array($PageName => "chat.php"), false);


PrintPageStart();

PrintFormResultIfNeeded();

PrintPageHeading($STR_HISTORY);
PrintPageDescription("Select a month and year to view your recent chat transcripts. Click session name to view transcript.<br><span style='color:#880000'>Please note that if you use IE 8 to view your chat transcripts- you may experience problems due to a bug with opening popup windows.
Please use FF or Chrome or an older version of IE instead- thank you. Admin</span>");
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
function OpenChatWindow(reader,fileopen)
{
	w2 = window.open("openlog.php?file="+reader+"/"+fileopen+".txt","Logs","width=500,height=500,toolbar=0,location=0,status=0,menubar=0,scrollbars=1,resizable=1");
	window.w2.focus();
}
//-->
</SCRIPT>

<a name="chart"></a>

<?php

// Open preference table
dbhandle();
// Open preference table
if (!preferenceOpen())
{
	die (sql_error());
}

$tableOperators        = storageGetUserTableByFormID($login_account_id, "Operators", $storage_id);
$tableChatScript        = storageGetUserTableByFormID($login_account_id, "ChatScript", $storage_id);

$operator = storageGetFormRecord($login_account_id, "Operators", $login_operator_id);

if (Strcasecmp(arrayvalue($operator,'type'),'reader')==0)
{
	$script_clause = "and $tableChatScript.reader_id = '$login_operator_id' and operators2.rr_record_id='$login_operator_id'";
	$client_or_reader = "reader";
}
else if (Strcasecmp(arrayvalue($operator,'type'),'client')==0)
{
	$client_or_reader = "client";

	$script_clause = "and $tableChatScript.client_id = '$login_operator_id' and operators1.rr_record_id='$login_operator_id'";
}
else
{
	$client_or_reader = "";

	$script_clause = "";
}

// Get chart filter
GetChartFilter($PREF_CHATHISTORY);



$sql_client_duration = preg_replace("/reader_payment\.Date/", "History.Date", $sql_client);

$count = storageGetSQLCount($login_account_id, $filter,"SELECT * from History $sql_client_duration");

$sqlStr = 'SELECT SUM(Duration) as total FROM History ' . $sql_client_duration;
$rs1 = mysql_query($sqlStr);
$row1 = @mysql_fetch_assoc($rs1);
$dur_total = $row1['total'];
@mysql_free_result($rs1);

/*
$ArrayDur = storageGetSQLList($login_account_id, "SELECT Duration from History  $sql_client");
for ($i=0; $i<count($ArrayDur)-1; $i++)
{
$Item_dur = $ArrayDur[$i+1];
$duration = arrayvalue($Item_dur,'Duration');
$dur_total+=$duration;
}
*/



// Get chart preferences
GetChartPreferences($PREF_CHATHISTORY, "name", $count);
/*
if(!eregi("ORDER BY",$sql_client))
{
$sql_client .= ' ORDER BY Date';
}
*/
if(1 != $sort_ascending)
{
	$sort_order = 'desc';
}
else
{
	$sort_order = 'asc';
}

if(empty($sort_by_field) or ('name' == $sort_by_field))
{    $sort_by_field  = 'History.Date';
$sort_order = 'desc';
//$sort_ascending = 'asc';

}

$ArrayResult = array();
$core = new Core;
$pageArr = $core->splitToPages($count, $_GET['page'], 20);

//$ArrayResult = storageGetSQLList($login_account_id, "SELECT History.*, reader_payment.ChatLocation from History left join reader_payment on  History.Session_id = reader_payment.Session_numb $sql_client", $sort_by_field, $sort_ascending, 20, $start_from_page);
//$pageArr['limit']

$strsql = "SELECT History.* from History $sql_client  ORDER BY '$sort_by_field' $sort_order ".$pageArr['limit']." ";

//exit();

$rs = mysql_query($strsql, $conn) or die(mysql_error());
//die("su");
while ($row = mysql_fetch_assoc($rs))
{
	$ArrayResult[] = $row;
}
mysql_free_result($rs);

echo mysql_error();


?>
<?php DrawChartTop($count, "chat.php",'', '', "history");?>

<!-- Title -->
<tr>
<?php
DrawChartXSpace(true);
printChartSortByLink("chat.php", "Date", "Date Recorded");
DrawChartTopBorder();
printChartSortByLink("chat.php", "Subject", "Subject");
DrawChartTopBorder();
if($type == "Administrator" or $type == "reader")
{
	printChartSortByLink("chat.php", "Session_id", "Session #");
	DrawChartTopBorder();
}
printChartSortByLink("chat.php", "Reader_name", "Reader");
DrawChartTopBorder();
if($type == "Administrator" or $type == "reader"){
	printChartSortByLink("chat.php", "Client_name", "Client");
	DrawChartTopBorder();
}
printChartSortByLink("chat.php", "Duration", "Duration (mins.)");
DrawChartTopBorder();

printChartSortByLink("chat.php", "Paid_time", "Paid time (mins.)");
if($operator['type'] == "Administrator" or $operator['type'] == "reader"){
	DrawChartTopBorder();
	printChartSortByLink("chat.php", "Free_time", "Free_time (mins.)");

	DrawChartTopBorder();
	printChartSortByLink("chat.php", "Due_to_reader", "Due to reader ($)");
	DrawChartTopBorder();
	printChartSortByLink("chat.php", "Rate", "Rate");
}
if($type == "Administrator"){
	DrawChartTopBorder();
	printChartSortByLink("chat.php", "Affiliate", "Affiliate");
}
?>
</tr>

<?php

// Print table body
for ($i=0; $i<count($ArrayResult); $i++)
{
	$Item = $ArrayResult[$i];
?>

<!-- Body -->
<?php DrawChartYSpace($NUM_COLS, true, true, SingularBGColor($i)); ?>
<tr <?=SingularBGColor($i);?>>
<?php
DrawChartXSpace();

//$Link        = "<a onmouseover=\"OnHoverPicture($i);\" ".

$reader_id = arrayvalue($Item,'Reader_id');
$client_id = arrayvalue($Item,'Client_id');

$reader_name = arrayvalue($Item,'Reader_name');
//$client_name = arrayvalue($Item,'Client_name');
$client_name = $Item[Client_name];

if(strlen($client_name) < 1)
{
	$client_name = $client_id;
}

$request_id = arrayvalue($Item,'Session_id');
$subject = arrayvalue($Item,'Subject');
if('client' == $type)
{
	$subject = ereg_replace("Freebie$", "", $subject);
}
$date_record = arrayvalue($Item,'Date');
$Rate_record = arrayvalue($Item,'Rate');
$duration = arrayvalue($Item,'Duration');
$duration= $duration/60;
$duration=round($duration,3);
$ChatLocation = $Item[ChatLocation];
//#############
$ses_id         = arrayvalue($Item,'Session_id');
$Free_time      = arrayvalue($Item,'Free_time');
$Paid_time      = arrayvalue($Item,'Paid_time');
$Due_to_reader  = arrayvalue($Item,'Due_to_reader');
$Affiliate      = arrayvalue($Item,'Affiliate');

if(strlen($duration) < 1 or $duration == 0){$duration="-";}
$g_date = strtotime($date_record);
$date_formated = date("M j, Y g:i:s a", $g_date);
//here added session info
if($type == "Administrator" or $type == "reader")
{
	$date_formated = "<a href = session_info.php?session_id=".$ses_id.">$date_formated</a>";
}


printChartCell($date_formated);
DrawChartVisibleBorder();
$Link        = GetLinkTag($subject, "Javascript:OpenChatWindow($reader_id, $request_id)");

printChartCell($Link,"images/operatoricon_small.gif");
DrawChartVisibleBorder();
if($type == "Administrator"){

	$ses_id = "<a href=\"add_funds_readers.php?action=open&reader_id=$reader_id&sid=$ses_id\" class=\"LinkSmall\">$ses_id</a>";
}
if($type == "Administrator" or $type == "reader")
{
	printChartCell($ses_id);
	DrawChartVisibleBorder();
}

printChartCell($reader_name);
DrawChartVisibleBorder();

$name_full = split(" ", $client_name);

if($type == "Administrator" or $type == "reader"){
	printChartCell($client_name);
	DrawChartVisibleBorder();
}
printChartCell($duration);
DrawChartVisibleBorder();


printChartCell($Paid_time);
if($type == "Administrator" or $type == "reader"){
	DrawChartVisibleBorder();
	printChartCell($Free_time);


	if(!empty($ChatLocation) and !empty($ses_id))
	{
		$currency = 'Canada' == $ChatLocation?'(CAD)':'(USD)';
	}
	DrawChartVisibleBorder();
	printChartCell($Due_to_reader);
	DrawChartVisibleBorder();
	printChartCell("\$$Rate_record $currency");

}
if($type == "Administrator"){
	DrawChartVisibleBorder();
	printChartCell("$Affiliate");
}


echo"</tr>";


}
// Finished printing table body

// If we had nothing
if (count($ArrayResult) < 1)
{
	DrawNoRecordsMessage("No Chat Transcripts found.");
}

DrawChartBottom();
if (count($ArrayResult)-1 >= 1)
{if($pageArr['total'] < 1)
{    $pageArr['total'] = 1;
}
echo'<br>Total pages: '.$pageArr['total'];
echo'<br>'.$pageArr['pages'];

$min = floor($dur_total/60);
$sec = floor($dur_total%60);
$duration_min+=$min;
$duration_sec+=$sec;

echo"<div align=\"left\">Total chat duration: <b>$duration_min</b> min. $duration_sec sec.</div><br>";

if($type == "Administrator" or $type == "reader"){

	//$HTTP_COOKIE_VARS[filter_month]
	//$HTTP_COOKIE_VARS[filter_year]
	$HTTP_COOKIE_VARS[filter_month] = ereg_replace('all', '', $HTTP_COOKIE_VARS[filter_month]);
	if(strlen($HTTP_COOKIE_VARS[filter_month]) == 1)
	{
		$HTTP_COOKIE_VARS[filter_month] = '0'.$HTTP_COOKIE_VARS[filter_month];
	}
	$Sum_extra_usd = 0;
	$Sum_extra_cad = 0;

	$strsql = "SELECT Sum, ChatLocation from reader_payment where ReaderID = '$reader_select' and (Session_numb = '' or Session_numb = '0') and Date like '$HTTP_COOKIE_VARS[filter_year]-$HTTP_COOKIE_VARS[filter_month]%'";
	$rs = mysql_query($strsql, $conn) or die(mysql_error());
	while ($row = mysql_fetch_assoc($rs)){
		if('International' == $row[ChatLocation])
		{
			$Sum_extra_usd += $row["Sum"];
		}
		else
		{
			$Sum_extra_cad += $row["Sum"];
		}
	}
	mysql_free_result($rs);
	echo"<div align=\"left\">Amount for Webphone/Email Readings: <b>\$$Sum_extra_usd USD</b> &nbsp; <b>\$$Sum_extra_cad CAD</b></div>";

	if(!empty($reader_select) and 'all' != $reader_select)
	{
		if(!empty($sql_client2))
		{
			$sql_adds = " and ";
		}
		$sql_client2 = " where ReaderID ='$reader_select' $sql_adds $sql_client2";
	}
	elseif(!empty($sql_client2) or 'all' != $reader_select)
	{

		$sql_client2 = "where $sql_client2";
	}

	$strsql = "SELECT Sum, ChatLocation FROM reader_payment  $sql_client2";

	
	$rs = mysql_query($strsql, $conn) or die(mysql_error());
	while ($row = mysql_fetch_assoc($rs)){
		$ChatLocation = $row[ChatLocation];
		if('Canada' != $ChatLocation)
		{
			$payout_total_usd += $row[Sum];
		}
		else
		{
			$payout_total_cad += $row[Sum];
		}

	}
	mysql_free_result($rs);


	echo"<div align=\"left\">Total Amount for Payout: <b>\$$payout_total_usd USD</b> &nbsp; <b>\$$payout_total_cad CAD</b></div>";
}
}
// Draw bottom copyright message

mysql_close($conn);
?>