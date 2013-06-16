<?php
$app_path="";

$groups_allowed=array('reader','client', 'Administrator');
require_once($app_path."common/chat_header.php");

//navigation structure
$menu = array(
	array('title'=>$lang['Fund'],'link'=>$https_addr.'chataddfunds.php','image'=>'images/envelopesmall.gif','additional'=>$lang['chatmain_txt_4'],'is_viewed'=>1),
	array('title'=>$lang['Select_your_reader'],'link'=>'readers.php','image'=>'images/arrowsmall.gif','additional'=>'','is_viewed'=>1),
	array('title'=>$lang['Start_your_Reading.'],'link'=>'chatstart.php','image'=>'images/entersmall.gif','additional'=>'','is_viewed'=>1),
	array('title'=>$lang['Message_Center'],'link'=>'messagecenter.php','image'=>'images/envelopesmall.gif','additional'=>'','is_viewed'=>1),
	array('title'=>$lang['Purchases'],'link'=>'purchase_history.php','image'=>'images/envelopesmall.gif','additional'=>'','is_viewed'=>1),
	array('title'=>$lang['Page_Reader'],'link'=>'notifyreaders.php','image'=>'images/envelopesmall.gif','additional'=>'','is_viewed'=>1),
	
);

// Set this as default page
preferenceSet($PREF_DEFPAGE, $PREF_DEFPAGE_CHATMAIN, "chatmain.php");
?>

<SCRIPT language=JavaScript src="common.js" type=text/javascript></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">
<!--
var mytimer = null;

function init()
{
	mytimer = setInterval("update_status_timer()",15000) ;
}

function update_status_timer()
{
	update_status();
}

function update_status()
{
	<?php
	if (Strcasecmp($operator['type'],'reader')==0)
	{
		?>
		unique = getUnique() ;

		document.forms['main'].statusimage<?=$login_operator_id?>.src = "chatgetstatus.php?account_id=<?=$login_account_id?>&operator_id=<?=$login_operator_id?>&statusfor=reader&unique=" + unique;
		<?php
	}
	?>
}

function update_status_timer_quick()
{
	update_status();
}

function OpenMonitorWindow()
{
	var date = new Date() ;
	unique = getUnique() ;
	url = "chatmonitor.php?sid=<?=$login_operator_id?>&username=<?=$operator['login']?>&unique="+unique;
	newwin1 = window.open(url, "monitorchatwindow", "scrollbars=yes,menubar=no,resizable=1,location=no,width=600,height=360,left=200,top=200");
	newwin1.focus() ;
	update_status();
}

function OnopenLog(fileopen)
{
	w2 = window.open("openlog.php?file="+fileopen,"Logs","width=500,height=500,toolbar=0,location=0,status=0,menubar=0,scrollbars=1,resizable=1");
	window.w2.focus();
}

function openQuest()
{
	w2 = window.open("nrr_quest.php","Questionnaire","width=600,height=600,toolbar=0,location=0,status=0,menubar=0,scrollbars=1,resizable=1");
	window.w2.focus();
}

//-->
</SCRIPT>

<?php
$balance = arrayvalue($operator,'balance');

$favorite_reader_id = preferenceGet($PREF_FAVORITE,"reader");

?>


<table cellspacing=0 cellpadding=0 border=0 width="50%">
<tr>
<td width="50%">
<?php 
require_once('inc/user_account.php');
?>
</td>
<td>
	<table width="100%" cellspacing=0 cellpadding=0 border=0 bgcolor="#0000">
        <tr>
                <td width="1"><img src="images/pixel.gif" height="1" width="1" border="0"></td>
                <td><img src="images/pixel.gif" height="1" width="1" border="0"></td>
                <td width="1"><img src="images/pixel.gif" height="1" width="1" border="0"></td>
        </tr>
        <tr>
                <td width="1"><img src="images/pixel.gif" height="1" width="1" border="0"></td>
                <td width="100%">


                                <table width="100%" cellspacing="0" cellpadding="4" border="0" bgcolor="#ffffcc">
        <tr>
                <td class="pptextbold"><?php echo $lang['Balance'];?>:</td>
                <td class="pptext" align=right><?php
                if (Strcasecmp(arrayvalue($operator,'type'),'reader')==0)
                {
                	echo "$";
                	$word = "";
                }
                else
                {
                	$word = "&nbsp;".$lang['minutes'];
                }
                ?><?=number_format(arrayvalue($operator,'balance'),1)?><?=$word?>&nbsp;</td>
        </tr>
        <?php
        if (Strcasecmp(arrayvalue($operator,'type'),'reader')==0)
        {
        ?>
        <tr>
                <td class="pptext" colspan=2><span class="TextTiny"><?php echo $lang['chatmain_txt_1'];?></span></td>
        </tr>
        <?php
        }
        else
        {
        ?>

        <tr>
                <td class="pptext" colspan=2><a class=LinkSmall href="Javascript:popUpErrorWin('balance.html');"><?php echo $lang['What_is_this'];?></a></td>
        </tr>
        <?php
        }
        ?>
       	</table>

	    </td>
	    <td width="1"><img src="images/pixel.gif" height="1" width="1" border="0"></td>
    </tr>
    <tr>
        <td width="1"><img src="images/pixel.gif" height="1" width="1" border="0"></td>
        <td><img src="images/pixel.gif" height="1" width="1" border="0"></td>
        <td width="1"><img src="images/pixel.gif" height="1" width="1" border="0"></td>
    </tr>
    </table>
</td>
</tr>
<tr>
<td colspan=2>

<br>

<?php
PrintFormResultIfNeeded(false);
$total_steps = 3;
$step_cur = 1;

?>
<table width="90%" align=center>
 <tr>
  <td align="right">
  <input type='button' value="NRR" onclick='openQuest()'/>
  </td>
 </tr>
 </table>
 
<table width="90%" align=center>
<tr>
<td colspan=5>
<span class=TextMedium><?=$total_steps?> <?php echo $lang['chatmain_txt_2'];?><br><br></span>
</td></tr>
</table>
</td>
</tr>
</table>
<div class="<?php echo $style_prfx; ?>-main-menu">
<ul class="<?php echo $style_prfx; ?>-main-menu-list">
<?php
	foreach ($menu as $item) {
?>
<li class="<?php echo $style_prfx; ?>-main-menu-item">
<table>
<tr>
    <td>
    <img src="<?php echo $item['image'];?>" width=56 height=55 >
    </td>
    <td nowrap>
        <span class=TextMedium>
        <b><?php echo $step_cur++;?>. <a href='<?php echo $item['link'];?>' class="<?php echo $style_prfx; ?>-main-menu-link" target="_parent"><?php echo $item['title'];?></a> <?php echo $item['additional'];?>.</b></span>
    </td>
</tr>
</table>
</li>
<?php
	}
	echo "
	</ul>
</div>";



	$strsql = "SELECT * FROM Ping_chat WHERE Client ='$HTTP_SESSION_VARS[login_operator_id]'";
	$rs = mysql_query($strsql) or die(mysql_error());
	while ($row = mysql_fetch_assoc($rs)){
		$Reader = $row["Reader"];
		$Session = $row["Session"];
	}
	mysql_free_result($rs);

	if(isset($Reader) and isset($Session)){
		@$file_stat = filemtime("logs/$Reader/$Session.txt");
		@$date_stat = date("n/j/Y g:i:s a",$file_stat);
		@$file_size = filesize("logs/$Reader/$Session.txt");
		if($file_size > 0)
		{
			echo"<br>&nbsp;&nbsp;&nbsp; ".$lang['Your_last_chat_was']." $date_stat. <b><a href=\"javascript:OnopenLog('$Reader/$Session.txt')\" class=\"LinkMedium\">".$lang['View_the_logs']."</a></b><br>";
		}
	}
	$trans_counter=1;
	$strsql = "SELECT * FROM transactions WHERE UserID ='$HTTP_SESSION_VARS[login_operator_id]' and DATE_ADD(Date, interval 7 day) >= now() and Amount_used ='0' order by Date desc";
	$rs = mysql_query($strsql) or die(mysql_error());
	while($row = mysql_fetch_assoc($rs)){
		$ORDER_NUMBER_last =  $row["ORDER_NUMBER"];
		$Date_tran = $row["Date"];
		$TRAN_AMOUNT = $row["TRAN_AMOUNT"];
		$Date_tran = strtotime($Date_tran);
		$Date_tran = date("M j, Y", $Date_tran);

		//echo"$trans_counter. <a href=\"cancel_auth.php?order=$ORDER_NUMBER_last\" class=\"LinkMedium\">Cancel</a> the autherizations on <b>\$$TRAN_AMOUNT</b> ($Date_tran)<br>";
		//echo"Your recent transactions: (You have 10 minutes to <a href=\"cancel_auth.php?order=$ORDER_NUMBER_last\" class=\"LinkMedium\">Cancel</a> these transactions)";
		$trans_counter++;
	}
	mysql_free_result($rs);
	echo"<br><br>";
	if($balance >0){
		$trans_counter =1;
		$bal_rest = $balance;

		$strsql = "SELECT * FROM transactions WHERE UserID ='$HTTP_SESSION_VARS[login_operator_id]' and Amount_used !='0' order by Date desc limit 0, 1";
		$rs = mysql_query($strsql) or die(mysql_error());
		$row = mysql_fetch_assoc($rs);
		$Las_package =  $row["TRAN_AMOUNT"];
		mysql_free_result($rs);

		$strsql = "SELECT * FROM transactions WHERE UserID ='$HTTP_SESSION_VARS[login_operator_id]' and Amount_used !='0'";
		$rs = mysql_query($strsql) or die(mysql_error());
		while($row = mysql_fetch_assoc($rs)){
			$ORDER_NUMBER_last =  $row["ORDER_NUMBER"];
			$Date_tran = $row["Date"];
			$total_amount += $row["TRAN_AMOUNT"];
		}
		mysql_free_result($rs);

		$dengi_nazad_rate=0;

	}
	mysql_close();
?>

<br>
</body>
</html>