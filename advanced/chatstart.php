<?php
$groups_allowed=array('reader','client', 'Administrator');
require_once("common/common.php");

require_once ("config/config.php");
require_once ("inc/functions_common.php");
// Gloalbs
$PageName                = "Start Chat";
$LoginRequired        = true;
$NUM_COLS                = 3;
$_GET['jchat'] = 1;




$chat_selected = $HTTP_GET_VARS[chat];

require_once("common/dbmessage.php");

$conn777 = mysql_connect($dbhost, $dbusername, $dbuserpassword);

mysql_select_db($default_dbname);

$operator = get_user($_SESSION['login_operator_id']);

# choose chat_type
# 0 - default, bu admin control
# 1 - new chat(debug)
# 2 - old chat(jchat) 
# 3 - emergency_debug
# 4 - emergency
# 5 - debug 2



//get user chat type
$userChatType = $operator['chat_type'];

if ($userChatType == 0) {
//then turn on admin settiong by default
	$filename = $app_path."config/chat_type.txt";
	
	$handle = fopen($filename, "r");
	
	$userChatType = fgets($handle);

	fclose($handle);
	
	if (!is_int($userChatType) && ($userChatType < 0) && ($userChatType > 2)) $userChatType = 1;
}

switch ($userChatType) {
		case 1: {
				?>
					<script language="javascript">
						window.location.href = 'http://www.psychic-contact.com/advanced/chatstart_new.php';
					</script>
				<?php
			
				break;
				}
		case 5: {
				?>
					<script language="javascript">
						window.location.href = 'http://www.psychic-contact.com/advanced/chatstart_new_2.php';
					</script>
				<?php
			
				break;
				}
		case 6: {
				?>
					<script language="javascript">
						window.location.href = 'http://www.psychic-contact.com/advanced/chatstart_flash.php';
					</script>
				<?php
			
				break;
				}				

		default: {
				continue; 
				break;
				}
	}	

#end


if(strlen($HTTP_SESSION_VARS[login_operator_id]) < 1)
{
	$HTTP_SESSION_VARS[login_operator_id] = $_COOKIE[login_operator_id];
}
$strsql = "SELECT * FROM T1_1 WHERE rr_record_id ='$HTTP_SESSION_VARS[login_operator_id]'";
$rs = mysql_query($strsql, $conn777) or die(mysql_error());
while ($row = mysql_fetch_assoc($rs)){
	$type= $row["type"];
}
mysql_free_result($rs);

if($type == "Administrator"){
?>
<SCRIPT LANGUAGE="JavaScript">
timer=window.setTimeout("refresh();", 100);
function refresh(){
	parent.document.location.href = "chat.php";
}
</SCRIPT>
<?php
}
else if('reader' == $type)
{
 ?>
<SCRIPT LANGUAGE="JavaScript">
timer=window.setTimeout("refresh();", 100);
function refresh(){
	parent.document.location.href = "chat.php";
}
</SCRIPT>
<?php
}

?>

<?php
// Set this as default page
preferenceSet($PREF_DEFPAGE, $PREF_DEFPAGE_CHAT, "chatstart.php");

// Get all readers who are online
$tableOperators        = storageGetUserTableByFormID($login_account_id, "Operators", &$storage_id);
$tableChatSessions        = storageGetUserTableByFormID($login_account_id, "ChatSessions", &$storage_id);
$tableRequests        = "T1_7";

$favorite_reader_id = preferenceGet($PREF_FAVORITE,"reader");

$favorite_reader = storageGetFormRecord($login_account_id, "Operators", $favorite_reader_id);

if (empty($favorite_reader))
{
	$favorite_reader_id = "";
}

$Result = @mysql_query("SELECT $tableOperators.rr_record_id,$tableOperators.name FROM $tableOperators left join $tableChatSessions on ($tableOperators.rr_record_id=$tableChatSessions.reader_id and $tableChatSessions.type='reader') LEFT JOIN $tableRequests on $tableOperators.rr_record_id=$tableRequests.reader_id AND to_days(now())=to_days($tableRequests.laststatusupdate) and (time_to_sec(now()) - time_to_sec($tableRequests.laststatusupdate) < 10) LEFT JOIN week_online_time wt ON wt.reader_id = $tableOperators.rr_record_id WHERE (($tableChatSessions.rr_record_id is null) or not ((to_days(now())=to_days($tableChatSessions.laststatusupdate) and (time_to_sec(now()) - time_to_sec($tableChatSessions.laststatusupdate) < 10)))) and $tableOperators.status='online' and (to_days(now())=to_days($tableOperators.laststatusupdate) and (time_to_sec(now()) - time_to_sec($tableOperators.laststatusupdate) < 10)) and $tableRequests.rr_record_id is null AND $tableOperators.type='reader' group by $tableOperators.rr_record_id ORDER BY time_online DESC");

$strsql = "SELECT Reader_id from wp_readers where Wp_status = 'wponly'";
$rs = mysql_query($strsql) or die(mysql_error());
while ($row = mysql_fetch_assoc($rs)){
	$Reader_id_wpstatus = $row['Reader_id'];
	$offLineReaders[$Reader_id_wpstatus] = $Reader_id_wpstatus;
}
mysql_free_result($rs);

$count = @mysql_num_rows($Result);

$have_favorite_reader = false;
$countMinus = 0;

// Read everything into an array for easier access
for ($i=0; $i<$count; $i++)
{
	$Row = @mysql_fetch_row($Result);

	$idReader = $Row[0];
	if(!empty($offLineReaders[$idReader]))
	{
		$countMinus++;
		continue;
	}
	$ArrayReaders[] = array("id"=>$Row[0],"name"=>$Row[1]);

	if (!empty($favorite_reader_id) &&
	Strcasecmp($Row[0],$favorite_reader_id)==0)
	{
		$have_favorite_reader = true;
	}
}
$count -= $countMinus; // Remove Readers with webphone only
?>
<a name="chart"></a>

<table cellspacing=0 cellpadding=0 border=0 width=500>
<tr>
<td>
<?php
PrintFormResultIfNeeded();

$ArrayTimes[] = "15";
$ArrayTimes[] = "30";
$ArrayTimes[] = "45";
$ArrayTimes[] = "60";

PrintPageHeading("Start Chat");

$balance = arrayvalue($operator,'balance')+0;
$client_name = arrayvalue($operator,'name');

if ($balance<3)
{
	PrintPageDescription("Before you can start, you need to <a href='chataddfunds.php' class=LinkMedium>Fund</a> your account.<br><br>");

        ?>
        <table border=0 width="100%">
        <tr>
        <td colspan=3><br><br>
        <?php DrawXPButton("&nbsp;&nbsp;&nbsp;<< Fund Account&nbsp;&nbsp;&nbsp;","Fund","chataddfunds.php"); ?>
        </td>
        </tr>
        </table>
        </td>
        </tr>
<tr>
<td>
<?php PrintPageHeading(""); ?>
</td>
</tr>

</table>
<br><br><br>
        <?php
        die;

}
$strsql = "SELECT Client_status from band_list where UserID = '$HTTP_SESSION_VARS[login_operator_id]'";
$rs = mysql_query($strsql) or die(mysql_error());
while ($row = mysql_fetch_assoc($rs)){
	$Client_status_db = $row["Client_status"];
}
mysql_free_result($rs);

if ('banned' == $Client_status_db or 'unactivated' == $Client_status_db or 'banned_by_reader' == $Client_status_db)
{
	PrintPageDescription("<font color=\"FF0000\">Your account is <b>Disabled</b>.</font><br>If you think this is a mistake, please <a href=\"contact_us.php\">contact us.</a>");

        ?>
        <br>
        </td>
        </tr>

</table>
<br><br><br>
        <?php
        include ("bottom.php");
        die;

}

if ($count<1)
{
	PrintPageDescription("We're sorry, None of our Readers are currently available.  <br><br>Please try again later.");

        ?>
        <br>
        </td>
        </tr>

</table>
<br><br><br>
        <?php
        include ("bottom.php");
        die;

}

if (empty($favorite_reader_id))
{
	$ReaderMessage = "You don't have a \"Favorite\" Reader. Please select a Reader from our list of available Readers online now";
}
else
if ($have_favorite_reader)
{
	$ReaderMessage = "Your Favorite Reader - \"".arrayvalue($favorite_reader,'name')."\" is available!";
}
else
{
	$ReaderMessage = "Unfortunately your Favorite Reader - \"".arrayvalue($favorite_reader,'name')."\" is currently unavailable. Please select a different reader from the list of available readers below.";
}

PrintPageDescription("Select your preferences and click \"Chat Now\"<br><br>$ReaderMessage");
$balance_pr = round($balance, 1);
?>
<form name=Chat action="chatstart0.php" method="get">
<input name="jchat" type="hidden" value="<?php echo $_GET['jchat'];?>">
<b><font color="#FF0000">*WARNING* Disable POPUP blockers or your chat will not start!<br />If you use FireFox- you may experience problems accessing the chatroom.Please close and re-open your browser if this does occur
<br />If it persists- please try our "ER" (Emergency ChatRoom)
</font></b><br><br>
<b>
FREE 10MINS FIRST TIME CHATTERS:<br />
PLEASE NOTE:
</b>
You may leave the chat at anytime you like {BEFORE THE 10 FREE MINS ARE USED UP} and any unused FREE time will remain in your account for <b>7 days</b> at which point it will be canceled and no charge will be incurred for the un-used time). <b>You may use your free 10mins with up to 2 Readers -at your discretion</b> within the 7 days
<br /><br />
<b>REPEAT CLIENTS:</b><br />
<span style="color:rgb(0, 0, 255);font-style:italic;font-weight:bold;">"what if I don't use any of my minutes after I make a purchase?"</span>
<br /><br />
Answer:
<br /><br />
If you purchase new chat time but you do not use any of those minutes- then the charge will be canceled and the funds will eventually be returned to your credit card.<br />
At which point all unused NEWLY PURCHASED  time will be removed from your account.
<br /><br />
PayPal clients: please contact support to remove any charges on your account.
<br /><br />

You have <b><?=$balance_pr?></b> mins total time in your account as of right now...<br>
How much time would you like to use during this session?<br>
<?=$client_name?> PLEASE NOTE: You are NOT obligated to use all the
the time you specify here- but we ask you how much you think you might
use, so that your Reader has a general idea on how to structure your
Reading.
<!--
<br><br>
You may leave the chat at anytime you like and any unused time will
remain in your account for <b>7 days</b> at which point it will be cancelled
and no charge will be incurred for the un-used time).
-->
<br><br>
<input type="radio" value="<?=$balance_pr?>" checked name="time"> ALL OF MY STORED TIME (<?=$balance_pr?> mins)<br>
<?php
if($balance_pr >= 60){
	echo"<input type=\"radio\" value=\"60\" name=\"time\"> 60 mins<br>";
}
if($balance_pr >= 45){
	echo"<input type=\"radio\" value=\"45\" name=\"time\"> 45 mins<br>";
}
if($balance_pr >= 30){
	echo"<input type=\"radio\" value=\"30\" name=\"time\"> 30 mins<br>";
}
if($balance_pr >= 15){
	echo"<input type=\"radio\" value=\"15\" name=\"time\"> 15 mins<br>";
}
?>
<br>
<input type=hidden name=client_id value="<?=$login_operator_id?>">
<input type=hidden name=account_id value="<?=$login_account_id?>">

        <table border=0 width="100%">
        <tr>
        <td nowrap>
        <span class=TextSmall><b>Balance:</b></span>
        </td>
        <td width=15 nowrap><img src=images/transp.gif></td>
        <td>
        <span class=TextMedium><?=number_format($balance,1)?>&nbsp;Minutes</span>
        </td>
        </tr>
        <tr>
        <td nowrap>
        <span class=TextSmall><b>Reader:</b></span>
        </td>
        <td width=15 nowrap><img src=images/transp.gif></td>
        <td>
        <span class=TextMedium><select class=SelectBox name=reader>
        <option value="0">-- Please Select --
        <?php
        if(strlen($chat_selected) > 0){
        	$favorite_reader_id = $chat_selected;
        }
        for ($i=0; $i<$count; $i++)
        {
        	$idReader = $ArrayReaders[$i]['id'];
        	if(!empty($offLineReaders[$idReader]))
        	{
        		continue;
        	}

        	echo "<option value=\"".$ArrayReaders[$i]['id']."\" ";
        	if (Strcasecmp($ArrayReaders[$i]['id'],$favorite_reader_id)==0)
        	{
        		echo "selected";
        	}
        	echo ">".htmlspecialchars($ArrayReaders[$i]['name'])."\r\n";
        }
        ?>
        </select></span>
        </td>
        </tr>
        <tr>
        <td nowrap>
        <span class=TextSmall><b>Chat Topic:</b></span>
        </td>
        <td width=15 nowrap><img src=images/transp.gif></td>
        <td>
        <input class="InputBoxFront"  maxlength="50" name="subject" >
        </td>
        </tr>
                <tr>
        <td colspan="3">
<input name="nopopup" type="checkbox" value="yes"> <font color="#FF0000">[Emergency Chat Room] Use only if the regular chat room is failing.</font></td>
<input type="hidden" name="jchat" value="1">
</td>
        </tr>
        <tr>
		<td colspan="3">
        <br>
       <b><font color="#FF0000"> NOTE! If during the chat you do not see your Reader's typing or text messaging to you:<br>
Please wait for 90 seconds and if there is still no response- leave the chat and reboot your computer. If you lost any time during the session we advise you to double check your account to be certain of this and then use the "No response from reader" link: "NRR" (found on your account page) to request time back from your reader.<br>
<br>
<br>
If your session went well and there were no tech related problems- please use "End Session" to end the chat with your Reader.<br>
REMEMBER! If not satisfied with your session- please politely inform your Reader ASAP (within the first few minutes during your chat) and they will either give your time back in full or they will pause the timer for you.<br>
</td>
        </tr>
        </table>
        <table border=0 width="100%">
        <tr>
        <td>
        <span style="font-size:12px;font-weight:bold;color:rgb(128, 0, 255);">
As a courtesy to your Reader- please do not ask them to "PAUSE THE TIMER".
Unless the chat is having tech problems or running slow due to Internet related occurrences.
Our Pause Timer feature is there to be used at the Reader's discretion.<br>
Thank you!<br>
Enjoy your reading<br>
</span>
<span style="font-size:12px;font-weight:bold;color:rgb(0, 0, 0);">
Jayson Lynn<br>
</span>
        
        </td>
        <td align=right valign="top"><br><br>
        <?php DrawXPButton("&nbsp;&nbsp;&nbsp;Chat Now >>&nbsp;&nbsp;&nbsp;","Start","Javascript:OnUpdate()"); ?>
        </td>
        </tr>
        </table>

<SCRIPT LANGUAGE="JavaScript">

function macusr(){
	alert('If you are a Mac user it is recommended that you use FireFox web browser.');
}
</SCRIPT>
<p><b><a href="http://www.psychic-contact.com/chat/iesettings/">Having PROBLEMS?
Click here for Settings that MAY Help.</a></b></p>
<p><b><a href="javascript:macusr()">Mac users here</a></b></p>
</td>
</tr>

<tr>
<td>
<?php PrintPageHeading(""); ?>
</td>
</tr>

</table>
</form>

<?php
// Open preference table
dbhandle();
// Open preference table
if (!preferenceOpen())
{
	die (sql_error());
}

?>
<br><br><br>
<SCRIPT LANGUAGE="JavaScript">
<!--
var chatstarted = 0;
function OnUpdate()
{
	if(chatstarted == 0)
	{
		x = 0
		p = 0
		msgStr = "You did not complete all required fields:\n\n"
		Experience = ""
		ReplyResult = ""

		ver = "ie";
		if (ver == "nscp") {
			d = document.M10.document;
		}
		else {
			d = document;
		}

		if (d.forms["Chat"].reader[0].selected)
		{
			x = 1;
			msgStr += "* Please select reader.\n";
		}

		if (d.forms["Chat"].subject.value=="")
		{
			x = 1;
			msgStr += "* Topic is required.\n";
		}

		if (x == 1) alert(msgStr);
		else {
			d.forms["Chat"].submit();
			chatstarted = 1;
		}
	}

}
//-->
</SCRIPT>
<?php
mysql_close($conn777);
?>
