<?php
require_once("config/config.php");
//require_once("email_messages.php");
require_once("common/common.php");
$conn = mysql_connect($dbhost, $dbusername, $dbuserpassword);
mysql_select_db($default_dbname);

$AccountName = "PsychicContact";
$_AccountName = $AccountName;
$HIDE_ACCOUNT_NAME = true;

//session_start();
dbhandle();

$ACCOUNT_TYPE = $ACCOUNT_CHAT;
$NO_LEFT_BR = true;

$PageName = "Online Live Psychic Chat";

// Set this as default page
//preferenceSet($PREF_DEFPAGE, $PREF_DEFPAGE_CHATMAIN, "chatmain.php");

// Open account table
dbhandle();

// Draw the top navigation bar
//DrawNavigationBar("Home", array());

$operator = storageGetFormRecord('1', "Operators", $login_operator_id);

if($operator[rr_record_id] > 1)
{
$strsql = "SELECT * FROM T1_1 WHERE rr_record_id = '$operator[rr_record_id]'";
$rs = mysql_query($strsql, $conn) or die(mysql_error());
$row = mysql_fetch_assoc($rs);
$affiliate= $row["affiliate"];
$client_login = $row["login"];
$client_name = $row["name"];
$client_gender = $row["gender"];
$client_email = $row["emailaddress"];
$client_phone = $row["phone"];

$free_mins = $row["free_mins"];
$balance = $row["balance"];
$user_type = $row["user_type"];
$Aff_client = $row["affiliate"];
mysql_free_result($rs);

$strsql = "SELECT * FROM T1_4 WHERE rr_record_id ='$operator[rr_record_id]'";
$rs = mysql_query($strsql, $conn) or die(mysql_error());
while ($row = mysql_fetch_assoc($rs)){
$firstname_client = $row["firstname"];
$lastname_client = $row["lastname"];
$billingaddress = $row["billingaddress"];
$billingcity = $row["billingcity"];
$billingstate = $row["billingstate"];
$billingzip = $row["billingzip"];
$billingcountry = $row["billingcountry"];
$signupdate  = $row["rr_createdate"];
}
mysql_free_result($rs);
}

if(strlen($action) < 1){
$_POST[name] = "$firstname_client $lastname_client";
$_POST[username] = "$client_login";
$_POST[email] = $client_email;
contact_us_form();
}
else{
$error=0;
if(strlen($_POST[name]) < 2 or strlen($_POST[email]) < 2 or strlen($_POST[subject]) < 2 or strlen($_POST[message]) < 2)
{
echo"<center><font color=\"#FF0000\">Full Name, E-mail Address, Subject and Message are required fields.</font></center><br>";
$error=1;
        }
if($login_operator_id < 1)
{
    //imgid keyword
    $Word = 'sdfdsdfdsdhR';
    mysql_select_db($default_dbname);
    $strsql = "SELECT Word from image_gateway where Id_image = '$_POST[imgid]'";
    $rs = mysql_query($strsql, $conn) or die(mysql_error());
    while ($row = mysql_fetch_assoc($rs))
    {
        $Word = $row['Word'];
    }
    mysql_free_result($rs);
    if(strtoupper($_POST[keyword]) != $Word)
    {
        $error=1;
        echo'<center><font color="#FF0000">The characters you entered do not match the picture.</font></center>';
    }
}
if(ereg(",",$_POST[email]) or (strlen($_POST[email]) > 150) or ereg(":",$_POST[email])or ereg("\"",$_POST[email]))
{
        $error=1;
        echo"Error occured. Please contact us at <img src=\"images/contactus.jpg\"><br>";
        }
if(0 == $error)
{

$full_address = "$billingaddress, $billingcity, ZIP: $billingzip, $billingstate, $billingcountry";
$Ip = $_SERVER['REMOTE_ADDR'];

$time_now =date("M j, Y h:i a", time());
$affiliate = $Aff_client;



//Filter

$_POST[email]    = preg_replace("/\s+|\t+|\n+|\r+|;|:|,/", "", $_POST[email]);
$_POST[name]     = preg_replace("/\s+|\t+|\n+|\r+|;|:/", "", $_POST[name]);
$_POST[username] = preg_replace("/\s+|\t+|\n+|\r+|;|:/", "", $_POST[username]);
$_POST[subject]  = preg_replace("/\s+|\t+|\n+|\r+|;/", " ", $_POST[subject]);
$_POST[message]  = preg_replace("/\s+|\t+|\n+|\r+|;/", " ", $_POST[message]);

$_POST[name]     = preg_replace("/@/", "(at)", $_POST[name]);
$_POST[username] = preg_replace("/@/", "(at)", $_POST[username]);
$_POST[subject]  = preg_replace("/@/", "(at)", $_POST[subject]);
$_POST[message]  = preg_replace("/@/", "(at)", $_POST[message]);

$_POST[email]    = preg_replace("/\"/", "'", $_POST[email]);
$_POST[name]     = preg_replace("/\"/", "'", $_POST[name]);
$_POST[username] = preg_replace("/\"/", "'", $_POST[username]);
$_POST[subject]  = preg_replace("/\"/", "'", $_POST[subject]);
$_POST[message]  = preg_replace("/\"/", "'", $_POST[message]);

$_POST[email]    = substr($_POST[email], 0, 100);
$_POST[name]     = substr($_POST[name], 0, 80);
$_POST[username] = substr($_POST[username], 0, 50);
$_POST[subject]  = substr($_POST[subject], 0, 150);
$_POST[message]  = substr($_POST[message], 0, 1500);

//Email to the Admin

$subject = "PSYCHAT - Contact us: $subject_mail ($client_login)";
$subject_mail = $_POST[subject];



$text_2_send = "Subject: $_POST[subject]<br>Full Name: $_POST[name]<br>Username(client wrote it himself/herself): $_POST[username]<br><br>$_POST[message]<br>----------------<br>$time_now<br>Client (Id: $login_operator_id, Username: $client_login)<br>$client_name<br>$client_gender<br><br>Address: $full_address<br>E-mail address: $client_email ($_POST[email])<br>Phone #: $client_phone<br>Sign up date: $signupdate<br>IP:$Ip<br>System: $_SERVER[HTTP_USER_AGENT]";
$text_2_send_db = $text_2_send;

//echo"Subj: $_POST[message]";exit();

mail($adm_email, $subject, $text_2_send, $headers);


//To John
//$adm_email = 'sales@belahost.com';
//mail($adm_email, $subject, $text_2_send, $headers);

if($login_operator_id < 1)
{
    $_POST[message] .= "<br>-----------<br>$_POST[email]";
}

$mysql_ins = "INSERT into messages (ID, From_user, From_name, To_user, Date, Subject, Body, Status, Read_message)
VALUES('', '$login_operator_id', '$_POST[name]', '1', now(), '$_POST[subject]', '$_POST[message]', 'ok', 'no')";
mysql_query($mysql_ins, $conn);

echo"Thank you!<br>We have received your message. We will contact you soon.";
}
else
{
    contact_us_form();
}
}

//DrawFormBottom();



function contact_us_form(){
        global $_POST, $login_operator_id;
?>
<center>

<table border="0" cellspacing="0"  width="450" >
<tr><td height='20px'>&nbsp;</td></tr>
   <tr>
    <td width="193" align='center'  height='10px'>  
    This site is owned and operated by Jayson Lynn.Net Inc.</td></tr>
<tr>
    <td width="193" align='center'  height='10px'>  56 Stebbins Ct.</td></tr>
<tr>
    <td width="193" align='center'  height='10px'>  DFS, FL. 32433</td></tr>
<tr>
    <td width="193" align='center'  height='10px'>  800-347-3176</td></tr>
	<tr>
    <td width="193" align='center'  height='10px'> psyjavachat@yahoo.com</td></tr>

</table>
<form name="gateway" method="POST" action="contact_us.php">
<input type="hidden" name="action" value="gateway">
<table border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="450" id="AutoNumber1">
  <tr bgcolor="#336699">
    <td width="416" colspan="2" align="center"><font color="#FFFFFF"><b>Contact Us</b></font></td>
  </tr>
  <tr>
    <td width="193">Your Full Name:</td>
    <td width="223"><input type="text" name="name" value="<?=$_POST[name]?>" size="15" maxlength="100" class="form"></td>
  </tr>
    <tr>
    <td width="193">Your E-mail Address:</td>
    <td width="223"><input type="text" name="email" value="<?=$_POST[email]?>" size="15" maxlength="100" class="form"></td>
  </tr>
  <tr>
    <td width="193">Your Username:<br><small>(if you are a registered client)</small></td>
    <td width="223"><input type="text" name="username" value="<?=$_POST[username]?>" size="15" maxlength="100" class="form"></td>
  </tr>
    <tr>
    <td width="193">Subject:</td>
    <td width="223"><select size="1" name="subject" class="form">
        <option selected value="">Please Select</option>
        <option value="General Help">General Help</option>
        <option value="Report Abuse">Report Abuse</option>
        <option value="Report Page Errors">Report Page Errors</option>
        <option value="Suggestions">Suggestions</option>
        <option value="Chat Question">Chat Question</option>
        <option value="Affiliate Question">Affiliate Question</option>
        <option value="Become A Reader Question">Become A Reader Question</option>
        <option value="Other, Not Listed">Other, Not Listed</option>
        </select>


    </td>
  </tr>
      <tr>
    <td width="193">Message:</td>
    <td width="223"><textarea name="message" rows="10" cols="30"><?=$_POST[message]?></textarea></td>
  </tr>
<?php
if($login_operator_id < 1)
{
$image_id = image_gateway('new');
?>
  <tr>
    <td width="193">Please type the characters you see at the picture:</td>
    <td width="223"><input name="imgid" type="hidden" value="<?=$image_id?>"><input name="keyword" type="text" value=""><br><img src="img_gateway.php?id=<?=$image_id?>" width="100" height="40" border=0></td>
  </tr>
  <tr>
<?php
}
?>
</table>
<table border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="419">
  <tr>
    <td width="416" align="left"><input type="submit" value="Send"></td>
  </tr>
</table>
</form>

</center>
<?php
}
mysql_close($conn);

function image_gateway($do)
{
    global $_SERVER, $conn;
    if(empty($do))
    {
        $strsql = "SELECT count(Id_image) as ip_recorded from image_gateway where IP = '$_SERVER[REMOTE_ADDR]' and DATE_ADD(Date, interval 1 hour) > now()";
        $rs = mysql_query($strsql, $conn) or die(mysql_error());
        while ($row = mysql_fetch_assoc($rs)){
            $ip_recorded = $row['ip_recorded'];
            echo"$ip_recorded";
        }
        mysql_free_result($rs);
        echo mysql_error();
        $mysql = "DELETE from image_gateway where DATE_ADD(Date, interval 2 hour) < now()";
        mysql_query($mysql, $conn);
        echo mysql_error();
    }
    else
    {
        $characters=7;
        srand ((double) microtime() * 10000000);
        $input = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","1","2","3","4","5","6","7","8","9");
        $output_line="";
        $rand_keys = array_rand ($input, $characters);
        for ($c_counter = 0; $c_counter < $characters; $c_counter++){
            $output_line.=$input[$rand_keys[$c_counter]];
        }
        $mysql = "INSERT into image_gateway (Id_image, Word, IP, Date) values ('', '$output_line', '$_SERVER[REMOTE_ADDR]', now())";
        mysql_query($mysql, $conn);
        echo mysql_error();
        $id = mysql_insert_id();
        return($id);
    }

}
?>
