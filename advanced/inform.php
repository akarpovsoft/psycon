<?php
// Draw head logo

//include ("new_head.php");
//include ("left.php");
$app_path="";
require_once($app_path."common/chat_header.php");

$PageName = $lang["Login"];

?>

<script LANGUAGE="JavaScript">

function ValidateForm()
{
	x = 0
	p = 0
	msgStr = "<?php echo $lang['ilogin_msg_19'];?>\n\n"
	Experience = ""
	ReplyResult = ""

	ver = "ie";
	if (ver == "nscp") {
		d = document.M10.document;
	}
	else {
		d = document;
	}

	if (d.forms["Login"].EmailAddress.value=="")
	{
		x = 1;
		msgStr += "* <?php echo $lang['ilogin_msg_20'];?>.\n";
	}

	if (x == 1) alert(msgStr);
	else
	{
		d.forms["Login"].submit();
	}
}

//-->
</script>


<?php

require_once($app_path."common/message_dispatcher.php");

if($_REQUEST['msgcode'] == MSG_ACCESS_DENIED)
{
	echo $lang['ilogout_msg_2']."<br><br><br>";
}



if($_REQUEST['msgcode']==MSG_DUPLICATE) // Write additional section
{
	echo $lang['ilogin_msg_22'];
?>
<br><br>
<a href="how_activate.php"><?php echo $lang['ilogin_msg_23'];?></a>

 <SCRIPT LANGUAGE="JavaScript">
 <!--
 function rememsbm()
 {
 	document.forms['rememberid'].submit();
 }
 function contact()
 {
 	if(document.forms['contact'].email.value.length > 1)
 	{
 		document.forms['contact'].submit();
 	}
 	else
 	{
 		alert('<?php echo $lang['Email_address_required'];?>');

 	}
 }

 //-->
</SCRIPT>
<form name="rememberid"  action="chatlogin.php" method="post">
<input name="action" type="hidden" value="email3">
<table border="0" cellpadding="2" cellspacing="0"  width="550">
  <tr>
    <td width="50%"><?php echo $lang['email_Address'];?>:</td>
    <td width="50%"><input name="email1" type="text" value="" class="InputBoxFront"></td>
  </tr>
  <tr>
    <td><?php echo $lang['email_Address'];?>:</td>
    <td><input name="email2" type="text" value="" class="InputBoxFront"></td>
  </tr>
  <tr>
    <td><?php echo $lang['email_Address'];?>:</td>
    <td><input name="email3" type="text" value="" class="InputBoxFront"></td>
  </tr>
  <tr>
    <td width="100%" colspan="2" align="left"><br><?php DrawXPButton('&nbsp;&nbsp;'.$lang['Submit'].'&nbsp;&nbsp;' ,'Submin', "Javascript:rememsbm();"); ?></td>
  </tr>
</table>
</form>
<br>
<?php echo $lang['ilogin_msg_24'];?>
<br><br>
<form name="contact" action="chatlogin.php" method="post">
<input name="action" type="hidden" value="contact">
<table border="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="450">
  <tr>
    <td width="80%"><?php echo $lang['ilogin_msg_25'];?></td>
    <td width="20%"><input name="reason" type="radio" value="Using a computer at Internet Cafe" checked></td>
  </tr>
  <tr>
    <td><?php echo $lang['ilogin_msg_26'] ;?></td>
    <td><input name="reason" type="radio" value=""></td>
  </tr>
  <tr>
    <td><?php echo $lang['ilogin_msg_27'] ;?></td>
    <td><input name="reason" type="radio" value="First time user and never been here before."></td>
  </tr>
  <tr>
    <td><?php echo $lang['ilogin_msg_28'];?></td>
    <td><input name="reason" type="radio" value="Trouble logging in to first account."></td>
  </tr>
  <tr>
    <td><?php echo $lang['ilogin_msg_29'];?></td>
    <td><input name="reason" type="radio" value="Using a computer at Home that other family members use at this site."></td>
  </tr>
  <tr>
    <td><?php echo $lang['ilogin_msg_30'] ;?></td>
    <td><input name="reason" type="radio" value="Lost my free 10 minutes."></td>
  </tr>
  <tr>
    <td><?php echo $lang['ilogin_msg_31'] ;?></td>
    <td><input name="reason" type="radio" value="Made a mistake on email address on first account and never activated my account."></td>
  </tr>
  </table>
  <table border="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="550">
  <tr>
    <td width="50%"><?php echo $lang['Your_Email_Address'];?>:</td>
    <td width="50%"><input name="email" type="text" value="" class="InputBoxFront"></td>
  </tr>
    <tr>
    <td><?php echo $lang['Your_Username'];?>:</td>
    <td><input name="username" type="text" value="" class="InputBoxFront"></td>
  </tr>
  <tr>
    <td width="100%" colspan="2" align="left"><br><?php DrawXPButton('&nbsp;&nbsp;'.$lang['Submit'].'&nbsp;&nbsp;' ,'Submin', "Javascript:contact();"); ?></td>
  </tr>
</table>
</form>
<?php
}

$no_reg = $_REQUEST['no_reg'];
if(!$no_reg) {
?>


<form name="Login" action="<?php echo $http_addr;?>forget_password.php" method="post">
<input type=hidden name="FORGOT_PASSWORD" value="yes">

<table border=0 <?=$TABLE_PADDING?> width=350><tr><td colspan=3>
<SPAN class=TextSmall>
<?php echo $lang['ilogin_msg_32'];?>
<br><br>
</SPAN>
</td>
</tr>

<tr>
        <td nowrap valign="top">
        <SPAN class=TextMedium>
        <?php echo $lang['ilogin_msg_33'];?>
        </SPAN>
        </td>
        <td nowrap>
        <input class="InputBoxSmall"  size="30" name="EmailAddress">
        </td>
</tr>
<tr>
        <td></td>
        <td>
        <input type="button" value="<?php echo $lang['Send_Login_Details'];?>" onClick="Javascript:ValidateForm();">
        </td>
</tr>


</table>


<SPAN class=TextSmall>
<br><br>
<?php echo $lang['ilogin_msg_34_1'];?> <b><a class="LinkSmall" onfocus="blur();" href="customer_service.php"><?php echo $lang['Contact_us'];?></a></b>. <?php echo $lang['ilogin_msg_35'];?>
</form>
</SPAN>


<?php
}
// Draw bottom copyright message
include ("inc/chat_bottom.php");
?>
