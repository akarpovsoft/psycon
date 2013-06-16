<?php
require_once("common/common.php");
require_once ("config/config.php");

// Draw head logo
$PageName = $STR_OVERVIEW;
$LoginRequired = true;

$ONLOAD = "init()";

// Set this as default page
preferenceSet($PREF_DEFPAGE, $PREF_DEFPAGE_CHATMAIN, "chatmain.php");

// Open account table
dbhandle();

$account_id = $HTTP_SESSION_VARS[login_account_id];



$operator = storageGetFormRecord($account_id, "Operators", $login_operator_id);

?>

<SCRIPT language=JavaScript src="common.js"
type=text/javascript></SCRIPT>
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

		document.forms['main'].statusimage<?=$login_operator_id?>.src = "chatgetstatus.php?account_id=<?=$login_account_id?>&operator_id=<?=$login_operator_id?>&unique=" + unique;
		<?php
	}
	?>
}

function update_status_timer_quick()
{
	update_status();
}

//-->
</SCRIPT>
<?php

$balance = arrayvalue($operator,'balance');

// Draw the top navigation bar
//DrawNavigationBar("My Account", array());

//PrintPageStart();

//Draw2ColumnStart();

$favorite_reader_id = preferenceGet($PREF_FAVORITE,"reader");

if (Strcasecmp($operator['type'],'reader')!=0 &&
Strcasecmp($operator['type'],'Administrator')!=0 &&
($balance<1 || empty($favorite_reader_id)))
{
	// DrawGrayBoxTitle("Get Started");
	if ($balance<1)
	{
		// DrawGrayBoxLink("Fund Account", "chataddfunds.php");
	}

	if (empty($favorite_reader_id))
	{
		//DrawGrayBoxLink("Select Reader", "chatourreaders.php");
	}
	// DrawGrayBoxBottom();
}

//DrawGrayBoxTitle("Chat Transcripts");
//DrawGrayBoxLink("None available yet", "");
//DrawGrayBoxBottom();

//DrawGrayBoxTitle("I still need help");
//DrawGrayBoxLink("Contact Support", "contact_us.php");
//DrawGrayBoxBottom();

//Draw2ColumnSeparator();
?>

<?php
$conn = mysql_connect($dbhost, $dbusername, $dbuserpassword);
mysql_select_db($default_dbname);


//PrintFormResultIfNeeded();

PrintPageHeading("Change your Information");

$typestr = GetTypeStr($operator['type']);
if (empty($typestr))
{
	$typestr="Basic";
}

if(strlen($action) < 1){
	$strsql = "SELECT * FROM T1_1 WHERE rr_record_id ='$login_operator_id'";
	$rs = mysql_query($strsql, $conn) or die(mysql_error());
	while ($row = mysql_fetch_assoc($rs)){
		$name_client = $row["name"];
		$login_client = $row["login"];
		$id_client = $row["rr_record_id"];
		$password_client = $row["password"];
		$type_client = $row["type"];
		$email_client  = $row["emailaddress"];
		$hear_client  = $row["hear"];
		$balance_client = $row["balance"];
		$address_client = str_replace("\n","<br>",$row["address"]);
		$free_mins = $row["free_mins"];

		$rr_createdate  = $row["rr_createdate"];  //2005-06-20 for old Clients
		$day_dob        = $row["day"];
		$month_dob      = $row["month"];
		$year_dob       = $row["year"];
	}
	mysql_free_result($rs);

	$strsql = "SELECT * FROM T1_4 WHERE rr_record_id ='$login_operator_id'";
	$rs = mysql_query($strsql, $conn) or die(mysql_error());
	while ($row = mysql_fetch_assoc($rs)){
		$firstname_client = $row["firstname"];
		$lastname_client = $row["lastname"];
		$billingaddress = $row["billingaddress"];
		$billingcity = $row["billingcity"];
		$billingstate = $row["billingstate"];
		$billingzip = $row["billingzip"];
		$billingcountry = $row["billingcountry"];

	}
	mysql_free_result($rs);
	if(strlen($month_dob) > 2)
	{
		$dob = "$month_dob $day_dob, $year_dob";
	}
	else
	{
		$dob = "$month_dob/$day_dob/$year_dob";
	}
	if('reader' == $operator['type'])
	{
    ?>
    <script language="JavaScript">
    <!--
    parent.location.href='reader_edit.php';
    //-->
    </script>
    <?php
	}
?>
<form name="edit" action="<?php echo $app_addr; ?>/client_edit.php" method="post">
<input type=hidden name=action value="edit2">


<table border=0 <?=$TABLE_PADDING?> width=480>
<tr>
        <td><?php ImgTag("","images/oneoperatoricon_small.gif"); ?>&nbsp;</td>
        <td nowrap>
        <SPAN class=TextSmall>
        First Name:<br><small>(Credit Card Billing Name)</small>
        </SPAN>
        </td>
        <td >
        <b><?=$firstname_client?></b>
        </td>
</tr>
<tr>
        <td>&nbsp;</td>
        <td nowrap>
        <SPAN class=TextSmall>
        Last Name:<br><small>(Credit Card Billing Name)</small>
        </SPAN>
        </td>
        <td >
<b><?=$lastname_client?></b>
        </td>
</tr>
<tr>
        <td></td>
        <td nowrap>
        <SPAN class=TextSmall>
        Login:&nbsp;&nbsp;
        </SPAN>
        </td>
        <td >
        <b><?=$login_client?></b>
        </td>
</tr>
<tr>
        <td></td>
        <td nowrap>
        <SPAN class=TextSmall>
        Password:&nbsp;&nbsp;
        </SPAN>
        </td>
        <td >
        <input class="InputBoxFront" type="password" size="40" maxlength="50" name="password" value="<?=$password_client?>" id="password">
        </td>
</tr>
<tr>
        <td></td>
        <td></td>
        <td width="100%">
        <?php DrawHR(); ?>
        </td>
</tr>

<tr>
        <td></td>
        <td nowrap>
        <SPAN class=TextSmall>
        Email Address:&nbsp;&nbsp;
        </SPAN>
        </td>
        <td >
        <span class=TextTiny>Email will be the main form of communication, please make sure you type a valid Email Address<br></span>
        <input class="InputBoxFront"  size="40" maxlength="50" name="emailaddress" value="<?=$email_client?>">
        </td>
</tr>
<tr>
        <td></td>
        <td nowrap>
        <SPAN class=TextSmall>
        Address:
        </SPAN>
        </td>
        <td><?php echo"$billingaddress <br> $billingcity <br> ZIP: $billingzip <br> $billingstate <br> $billingcountry";?></td>
</tr>
<tr>
        <td></td>
        <td nowrap>
        <SPAN class=TextSmall>
        DOB:
        </SPAN>
        </td>
        <td>
<?php
if('2005-06-20' == $rr_createdate)
{
	HTMLSelectBoxFromFile("/data/month_birth.txt", "month", "$month_dob", "", "SelectBoxStandard");
	HTMLSelectBoxFromFile("/data/day_birth.txt", "day", "$day_dob", "", "SelectBoxStandard");
	HTMLSelectBoxFromFile("/data/year_birth.txt", "year", "$year_dob", "", "SelectBoxStandard");
}
else
{
	echo"$dob";
}
?>
</td>
</tr>
        <tr>
                <td width="100%" colspan=5>
                <table cellspacing=0 cellpadding=0 border=0 width="100%">
                <tr>
                <td width="100%"></td>
                <td nowrap width=10><img src="images/transp.gif"></td>
                <td>
                </td>
                <td nowrap width=7><img src="images/transp.gif"></td>
                <td >
                <input type="submit" name="save" value="Save">&nbsp;
                </td>
                </tr></table>
                </td>
        </tr>

</table>
</form>
<?php
}
elseif($action == "edit2"){

	$upd="UPDATE T1_1 set password = '$password', emailaddress = '$emailaddress' where rr_record_id='$login_operator_id'";
	mysql_query($upd, $conn);

	if(!empty($_POST[month])) //Update DOB for old Clients
	{
		$upd="UPDATE T1_1 set day = '$_POST[day]', month = '$_POST[month]', year = '$_POST[year]', rr_createdate = '2005-06-19' where rr_record_id ='$login_operator_id'";
		mysql_query($upd, $conn);
	}

	echo"New information has been saved.";
}


$CLOSE_FORM = true;

?>

<br><br><br>

<?php
mysql_close($conn);

?>
