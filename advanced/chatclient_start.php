<?php
header ('Cache-Control: no-cache, pre-check=0, post-check=0, max-age=0');
header ('Expires: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
header ('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

// Draw head logo
$LoginRequired = true;
if('ca' == $_GET[ads])
{
        //$HTTP_SESSION_VARS[login_operator_id] = $_GET[client_id];
        setcookie("canadian_id", "$_GET[client_id]", time()+36000, "/");
}

$app_path = "../";
require_once($app_path."common.php");
require_once($app_path."dbsession.php");


session_start();

$session_id = $script_id;

// Open account table
dbhandle();

$account_id = GetAccountID();

$time_posted = time();
setcookie("last_post", "$time_posted", time()+36000, "/");




$myreader = storageGetFormRecord($account_id,"Operators",$reader);

$PageName = "Reading by ".arrayvalue($myreader,'name');

?>
<HTML>
<HEAD>
<TITLE>
<?=$PageName?>
</TITLE>
<script language="JavaScript">
<!--
function do_write( text_to_write )
{
window.parent.frames['box'].document.write(text_to_write);
window.setTimeout("do_revision ()", 3000);

}

function scrollme()
{
window.parent.frames["box"].window.scrollBy(0, 90000) ;
window.parent.frames["box"].window.scroll(0, 90000) ;
}

function scrll_paus()
{
window.setTimeout("scrollme()", 100);
window.setTimeout("scrollme()", 500);
}

function do_reload( relurl, localtime )
{
window.parent.frames["send"].document.forms[0].timer.value=localtime;
var localtimex = localtime
top.frames["wayin"].location.href=relurl;
window.setTimeout("do_revision ()", 12000);
}

function do_revision()
{
var now = new Date();
localtimer = now.getTime();
lastime = window.parent.frames["send"].document.forms[0].timer.value;
var diftime = localtimer - lastime;
var revurl = "wayin.php?user=<?=$client_id?>&opponent=<?=$reader?>&session_id=<?=$session_id?>";
if(diftime > 10000)
{
top.frames["wayin"].location.href=revurl;
}
}


//-->
</script>

</HEAD>
        <?php

        {
        ?>

        <?php
  {
        $ACCOUNT_TYPE = GetAccountTypeByServerName();
  }


        }
        ?>




<?php

$DONT_CLOSE_HTML = true;
$DONT_SHOW_COPYRIGHT = true;


for ($k=0; $k<1500; $k++)
{
echo " ";
}
$user_info = 'System information: '.$_SERVER['HTTP_USER_AGENT'].' '.$_SERVER['REMOTE_ADDR']."\n";
$new_file = fopen ($app_path."chat/logs/$reader/${session_id}.txt", "a");
if(flock($new_file, LOCK_EX))
{
    fputs($new_file, "$user_info");
    flock($new_file, LOCK_UN);
}
fclose($new_file);

$strsql = "SELECT Total_sec from remove_freetime where ClientID = '$client_id'";
$rs = mysql_query($strsql) or die(mysql_error());
while ($row = mysql_fetch_assoc($rs)){
$Total_sec_db = $row['Total_sec'];
}
mysql_free_result($rs);

if($Total_sec_db > 0)
{
$strsql = "SELECT Subject from History where Session_id = '$session_id'";
$rs = mysql_query($strsql) or die(mysql_error());
while ($row = mysql_fetch_assoc($rs)){
$Subject_db = $row['Subject'];
$Subject_db .=" Freebie";
$mysql = "UPDATE History set Subject = '$Subject_db' where Session_id = '$session_id'";
mysql_query($mysql);
}
mysql_free_result($rs);
}
?>


<frameset rows="59,*,100, 110, 0,0" cols="*" border="0" frameborder="0">
        <frame src="chatclient_header.php?reader_id=<?=$reader?>&client_id=<?=$client_id?>&account_id=<?=$account_id?>" name="header" noresize border=0 scrolling=no>

        <frame src="blankhtml.html" name="box" noresize border=0 scrolling=auto>

        <frame src="chatclient_send.php?reader_id=<?=$reader?>&script_id=<?=$script_id?>&client_id=<?=$client_id?>&account_id=<?=$account_id?>&session_id=<?=$session_id?>" name="send" noresize border=0 scrolling=no>
        <frame src="chatclient_options.php?reader_id=<?=$reader?>&client_id=<?=$client_id?>&session_id=<?=$session_id?>&ads=<?=$_GET[ads]?>" name="options" noresize border=0 scrolling=no>
        <frame src="wayin.php?user=<?=$client_id?>&opponent=<?=$reader?>&session_id=<?=$session_id?>" name="wayin" noresize border=0 scrolling=no>
        <frame src="blankhtml.html" name="wayout" noresize border=0 scrolling=no>
</frameset>
<noframes>


</HTML>