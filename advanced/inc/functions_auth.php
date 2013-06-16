<?php
//##########################
//Payment and signup functions
//##########################

$_DEBUG=1;
$adm_email="karpovsoft@yandex.ru";

function genpassword($length){

	srand((double)microtime()*1000000);

	$vowels = array("a", "e", "i", "o", "u");
	$cons = array("b", "c", "d", "g", "h", "j", "k", "l", "m", "n", "p", "r", "s", "t", "u", "v", "w", "tr",
	"cr", "br", "fr", "th", "dr", "ch", "ph", "wr", "st", "sp", "sw", "pr", "sl", "cl");

	$num_vowels = count($vowels);
	$num_cons = count($cons);

	$password = "";

	for($i = 0; $i < $length; $i++){
		$password .= $cons[rand(0, $num_cons - 1)] . $vowels[rand(0, $num_vowels - 1)];
	}

	return substr($password, 0, $length);
}

function SendSignupEmail($account_id, $id)
{
	global $adm_email, $headers, $RecordID, $output_line, $affiliate_email, $hear,
	$month, $day, $year, $firstname, $emailaddress, $cc4dig, $_POST;

	$IP_mail = getenv('REMOTE_ADDR');
	$UserAgent_mail = getenv('HTTP_USER_AGENT');
	//exit();
	$tableOperators = storageGetUserTableByFormID($account_id, "Operators", $storage_id);
	$Result = @mysql_query("SELECT name,emailaddress,password,login FROM $tableOperators WHERE rr_record_id='$id'");
	if (@mysql_num_rows($Result)<1)
	{
		return false;
	}

	$Row = @mysql_fetch_row($Result);
	//$month/$day/$year

	$message         = "Dear ".$Row[0].",\r\n".
	" \r\n".
	"*** SAVE THIS MESSAGE *** \r\n\r\nIt is my pleasure to welcome you to Psychic-Contact.com \r\n".
	" \r\nBelow you will find your login information:\r\n".
	"\r\n".
	"Your UserID: ".$Row[3]." \r\n".
	"   Password: ".$Row[2]." \r\n".
	" \r\n".
	"Login now and chat with our psychic readers, see what your future holds! \r\n".
	" \r\n".
	"Make it a great day! \r\n\r\nwww.psychic-contact.com \r\nSupport Team \r\nmailto:support@psychic-contact.com";
	@mail($Row[1], "Welcome to PsychicContact!",  $message, "From: support@psychic-contact.com");

	//to Admin
	$text_2_send = "$time_now<br>Username: $Row[3]<br>DOB: $month/$day/$year<br>First Name: $firstname<br>Gender: $_POST[gender]<br>E-mail address: $emailaddress<br>Password: $Row[2]<br>Last 4 digits of the credit card: $cc4dig<br>IP: $IP_mail<br>$UserAgent_mail<br>Affiliate: $affiliate_email<br>Referrer: $hear<br>Referrer2: $_POST[website]";
	$subject = "PSYCHAT - New Sign Up";
	mail($adm_email, $subject, $text_2_send, $headers);
}

function email_readers($subj, $body, $reader_id = 0)
{
	global $headers, $conn, $adm_email, $_DEBUG;
	if($_DEBUG==1)
	{
		mail($adm_email, $subj, "Readers Notify <br>".$body, $headers);
	}
	else
	{
		if($reader_id > 0)
		{
			$strsql = "SELECT name, emailaddress from T1_1 where rr_record_id = '$reader_id' and type = 'reader'";
		}
		else
		{
			$strsql = "SELECT name, emailaddress from T1_1 where type = 'reader'";
		}
		$rs = mysql_query($strsql, $conn) or die(mysql_error());
		while ($row = mysql_fetch_assoc($rs))
		{
			$name = $row['name'];
			$emailaddress = $row['emailaddress'];
			mail2($emailaddress, $subj, $body, $headers);
		}
	}
	mysql_free_result($rs);
}

define("ERROR_COMMON", 1);
define("ERROR_REQUIRED_FIELDS", 2);
define("ERROR_BANNED", 3);
define("ERROR_DUPLICATED", 4);
/**
 * Check new client
 *
 * @param unknown_type $client_data
 * @return unknown
 */
function checkNewClient($client_data)
{

	global $conn, $_SERVER;

	/////////////////// Required fields checking
	if(strlen($client_data['name']) == 0 or strlen($client_data['login']) == 0 or strlen($client_data['password']) == 0  or strlen($client_data['emailaddress']) == 0 or empty($client_data['month']) or empty($client_data['day']) or empty($client_data['year']) or strlen($client_data['ccard']) < 1 or strlen($client_data['exp_month'])==0 or strlen($client_data['exp_year'])==0){
		$check_result['error']=ERROR_REQUIRED_FIELDS;

		return $check_result;
	}

	/////////////////////IP checking
	$ipPattern = $_SERVER['REMOTE_ADDR'];
	$ipPattern = preg_replace("/\.\d+$/", "", $ipPattern);

	$strsql = "SELECT UserID FROM band_list WHERE IP LIKE '$ipPattern%' limit 0, 1";
	$rs = mysql_query($strsql, $conn) or die(mysql_error());
	while($row = mysql_fetch_assoc($rs)){

		$row2=get_user($row['UserID']);

		$login_db = $row2[login];
		$name_db  = $row2[name];

		$body     = "Reason: IP $ipPattern<br>NEW Username: ".$client_data["login"]." Real name: ".$client_data["firstname"]." ".$client_data["lastname"]."<br>
	Matches: $login_db Real name: $name_db";

		$check_result['reader_notes'].="<br>$body";
		$check_result['warning']=1;

	}
	mysql_free_result($rs);

	///////// Banned Day of birth checking
	$strsql = "SELECT ID FROM banned_dob WHERE  year = '$client_data[year]' and  month = '$client_data[month]' and day = '$client_data[day]' and (gender = '$client_data[gender]' or gender = 'Both')";
	$rs = mysql_query($strsql, $conn) or die(mysql_error());
	while ($row = mysql_fetch_assoc($rs)){
		$check_result['declined_msg_admin']="The  DOB ($client_data[month] $client_data[day] $client_data[year]) is banned.";
		$check_result['error']=ERROR_DUPLICATED;
	}
	mysql_free_result($rs);

	///////// Banned password checking
	$strsql = "SELECT ID FROM banned_password WHERE  `password` = '".$client_data['password']."'";
	$rs = mysql_query($strsql, $conn) or die(mysql_error());
	if($row = mysql_fetch_assoc($rs)){
		$check_result['declined_msg_admin']="Registration attempt with banned password (".$client_data['password'].").";
		$check_result['error']=ERROR_DUPLICATED;
	}
	mysql_free_result($rs);

	$yearnow = date("Y", time());
	if(($yearnow - $year) < 17)
	{
		$check_result['declined_msg_admin'] = "The user is too young. DOB:$client_data[month]/$client_data[day]/$client_data[year]";
		$check_result['error']=ERROR_COMMON;
	}

	///////// Login name checking
	$strsql = "SELECT login FROM T1_1 WHERE login = '".$client_data['login']."'";
	$rs = mysql_query($strsql, $conn) or die(mysql_error());
	while ($row = mysql_fetch_assoc($rs))
	{
		$check_result['declined_msg_admin']='The username is taken.';
		$check_result['display_to_client']='Sorry, the username is taken';
		$check_result['error']=ERROR_COMMON;
	}
	mysql_free_result($rs);


	///////// Email checking
	$strsql = "SELECT login, rr_createdate FROM T1_1 WHERE emailaddress = '".$client_data['emailaddress']."'";
	$rs = mysql_query($strsql, $conn) or die(mysql_error());
	while ($row = mysql_fetch_assoc($rs)){
		$login_used = $row['login'];
		$signupDate  = date("m/d/Y", strtotime($row['rr_createdate']));
		$check_result['declined_msg_admin']="The same email address.<br>For Username: $login_used<br>Signed up:".$signupDate;
		$check_result['error']=ERROR_DUPLICATED;
	}
	mysql_free_result($rs);

	///////// Card number checking
	$client_data['ccard'] = ereg_replace(' ', '', $client_data['ccard']);
	$client_data['ccard'] = ereg_replace('-', '', $client_data['ccard']);

	$clen=strlen($client_data['ccard']);
	$cc4dig = preg_replace("/^\d{".($clen-4)."}/", "*", $client_data['ccard']);

	$strsql = "SELECT T1_1.* FROM T1_1, T1_4 WHERE T1_1.rr_record_id=T1_4.rr_record_id AND cardnumber = '".$client_data['ccard']."'";
	$rs = mysql_query($strsql, $conn) or die(mysql_error());
	while ($row = mysql_fetch_assoc($rs))
	{
		$signupDate  = date("m/d/Y", strtotime($row['rr_createdate']));

		$Cc_num = preg_replace("/\s+/", "", $client_data['ccard']);
		$Cc_num=maskChars($Cc_num, 4);

		$check_result['declined_msg_admin'] = "The Credit Card # ($Cc_num) is in the database as of: $signupDate";
		$check_result['error']=ERROR_DUPLICATED;
	}
	mysql_free_result($rs);

	///////// Possible second account registration attempt checking
	$strsql = "SELECT rr_record_id, name, login, day, month, year, password FROM T1_1 WHERE
   	(
	    (year = '".$client_data['year']."' and month = '".$client_data['month']."' and 
	    day = '".$client_data['day']."')  or (address like '".$client_data['billingstreet']."%' )  
	    or   (password = '" . $client_data['password'] . "')
	) 
	and name LIKE '%".$client_data['lastname']."%' and gender = '".$client_data['gender']."' limit 0, 1";

	$rs = mysql_query($strsql, $conn) or die(mysql_error());
	while ($row = mysql_fetch_assoc($rs)){
		$login_db = $row[login];
		$name_db  = $row[name];
		$day_db   = $row[day];
		$month_db = $row[month];
		$year_db  = $row[year];

		if($day_db == $client_data['day'] and $month_db == $client_data['month'] and $year_db == $client_data['year'])
		{
			$reason     = "Reason: DOB $month_db $day_db,$year_db";
		}
		elseif($row['password']==$client_data['password'])
		{
			$reason  = "Reason: Password ";
		}
		else
		{
			$reason  = "Reason: Address ".$client_data['billingstreet'];

			$billingaddress_short = preg_replace("/[a-z]|[A-Z]|\s+|,/", "", $client_data['billingstreet']);
			$check_result['warning']=1;
		}

		$check_result['reader_notes'].="<br>$reason<br>NEW Username: ".$client_data["login"]." Real name: ".$client_data["firstname"]." ".$client_data["lastname"]."<br>
	Matches: $login_db Real name: $name_db";
	}
	mysql_free_result($rs);

	if ((strlen($check_result['reader_notes']) > 5) and ($check_result['warning'] > 0))
	{	$check_result['reader_notes'] .= '<br>DO NOT READ!<br>';

	}

	return  $check_result;
}

function userLogin($username, $password, &$ErrorStr)
{
	$username                = _addslashes($username);
	$password                = _addslashes($password);

	$Result1 = @mysql_query("SELECT rr_record_id FROM T1_1 WHERE login='$username' AND ".
	"password='$password'");

	// Do we have a match
	if (@mysql_num_rows($Result1) == 0)
	{
		$ErrorStr = $lang['dbaccount_msg_3_1']." <b>[$account_name]</b>".$lang['dbaccount_msg_3_2'];
		return false;
	}

	// Set returned parameters
	$Row1 = mysql_fetch_row($Result1);
	$operator_id = $Row1[0];

	// Return success
	return $operator_id;
}

# на вход $login_operator_id и $groups_allowed
# false : переход на inform.php (возвращается $group_name)
# true : пользователь прошел проверку и получает доступ к странице ( возвращается operator)

function validateUser($login_operator_id,$groups_allowed, &$param)
{
	
	if (isset($login_operator_id))
		$operator = get_user($login_operator_id);
	
	if (count($groups_allowed) > 0)
	{
		if (is_array($operator))
		{			
			if (!in_array($operator['type'],$groups_allowed)) 
			{		
				return 0; //доступ закрыт
			}
		}
		else 
		{
			return -1; //пользователь не авторизовался
		}
	}
	
	$param = $operator;
	
	return 1; // доступ открыт, загрузка страницы
}

// User statuses
define('US_OK', 1);
define('US_LIMIT_OVER', -1);
define('US_BANNED', -2);

function checkBanStatus($user_id, $amount)
{
	$strsql = "SELECT * FROM band_list WHERE UserID = '$user_id'";
	$rs = mysql_query($strsql) or die(mysql_error());
	$row = mysql_fetch_assoc($rs);
	$UserName = $row["UserName"];
	$FullName = $row["FullName"];
	$Client_status = $row["Client_status"];
	$Sum_month = $row["Sum_month"];
	$Sum_day = $row["Sum_day"];
	mysql_free_result($rs);
	
	$strsql = "SELECT * FROM personal_limits where ID_client ='$user_id'";
	$rs = mysql_query($strsql) or die(mysql_error());
	
	while ($row = mysql_fetch_assoc($rs)){
		$Day_limit = $row["Day_limit"];
		$Month_limit = $row["Month_limit"];
	}
	
	mysql_free_result($rs);
	if(($Day_limit <= 0) or ($Month_limit <= 0)){
		$Day_limit = 125;
		$Month_limit = 250;
	}
	
	if((((($Sum_day+$amount) > $Day_limit) or ($Sum_month+$amount) > $Month_limit)) and $Client_status == "limited"){
		return US_LIMIT_OVER;
	}
	elseif($Client_status == "banned"){
		return US_BANNED;
	}
	return US_OK;
}

function updateBanLimits($user_id, $payment)
{
	$mysql_ins = "UPDATE band_list set Sum_month = Sum_month+".$payment['amount'].", FullName = '".$payment['firstname']." ".$payment['lastname']."', CCNumber = '".$payment['cardnumber']."', ExpDate = '".$payment['month']."/".$payment['year']."' where UserID = '$user_id' and now() < DATE_ADD(Month_date, interval 1 month)";
	mysql_query($mysql_ins);
	$mysql_ins = "UPDATE band_list set Sum_month = ".$payment['amount'].", FullName = '".$payment['firstname']." ".$payment['lastname']."', CCNumber = '".$payment['cardnumber']."', ExpDate = '".$payment['month']."/".$payment['year']."' where UserID = '$user_id' and now() > DATE_ADD(Month_date, interval 1 month)";
	mysql_query($mysql_ins);

	$mysql_ins = "UPDATE band_list set Sum_day = Sum_day+".$payment['amount']." where UserID = '$user_id' and now() < DATE_ADD(Day_date, interval 1 day)";
	mysql_query($mysql_ins);
	$mysql_ins = "UPDATE band_list set Sum_day = '".$payment['amount']."', Day_date = now() where UserID = '$user_id' and now() > DATE_ADD(Day_date, interval 1 day)";
	mysql_query($mysql_ins);
	
}

function registerTransaction($user_id, $trans_info)
{
	$mysql_ins = "INSERT into transactions (ORDER_NUMBER, UserID, Date, AUTH_CODE, TRAN_AMOUNT, Amount_used, merchant_trace_nbr, ps_type) values ('".$trans_info['order_number']."', '$user_id', '".$trans_info['date']."', '".$trans_info['auth_code']."', '".$trans_info['amount']."', '0', '".$trans_info['merchant_trace']."', '".$trans_info['ps_type']."')";
	mysql_query($mysql_ins) or die(mysql_error());
	
	
	$mysql_ins = "UPDATE T1_1 SET BALANCE = BALANCE + ".$trans_info['minutes']." WHERE rr_record_id='$user_id'";
	mysql_query($mysql_ins);
	
}


function updateBMTStat($user_id, $minutes, $reader, $order_number)
{
	$mysql_ins = "INSERT INTO BMT_stat VALUES ('', '$user_id', '$minutes' ,now())";
	mysql_query($mysql_ins);


	$mysql_ins="UPDATE Ping_chat set Balance=(Balance+".($minutes*60).") where Client='$operator_id'";
	mysql_query($mysql_ins, $conn);

	$mysql_ins = "UPDATE transactions SET Bmt = '".$reader["name"]."' where ORDER_NUMBER = '$order_number'";
	mysql_query($mysql_ins, $conn);
}


?>