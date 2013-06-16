<?php

function createUser($user_id, $user_data)
{
	$IP = $_SERVER['REMOTE_ADDR'];

	// User table
	$sql="INSERT INTO T1_1 SET rr_acount_id=1, rr_createdate=now(), rr_lastaccess=now(), name='".$user_data['name']."', password='".$user_data['password']."', login='".$user_data['login']."', emailaddress='".$user_data['emailaddress']."', hear='".$user_data['hear']."', day='".$user_data['day']."', month='".$user_data['month']."', year='".$user_data['year']."', address='".$user_data['address']."', type='".$user_data['type']."', balance='".$user_data['balance']."', banlist='N', gender='".$user_data['gender']."', affiliate='".$user_data['affiliate']."', first_reading='Yes', user_type='New', free_mins='".$user_data['free_mins']."'";
	$rs=mysql_query($sql);
	$user_id=mysql_insert_id($rs);

	// Client restrictions table
	$sql = "INSERT into band_list (UserID, UserName, FullName, IP, Date, Client_status, Notes) VALUES ('$user_id', '".$user_data['login']."', '".$user_data['name']."', '$IP', now(), 'limited', 'The client did not activate the account after signing up')";
	mysql_query($sql);

	// credit cards table
	$sql="INSERT INTO T1_4 (rr_record_id, firstname, lastname, billingcountry, billingaddress, billingcity, billingstate, billingzip, cardnumber, month, year)
VALUES ('$user_id', '".$user_data['firstname']."', '".$user_data['firstname']."', '".$user_data['billingcountry']."', '".$user_data['billingaddress']."', '".$user_data['billingcity']."', '".$user_data['billingstate']."', '".$user_data['billingzip']."', '".$user_data['ccard_number']."', '".$user_data['ccard_exp_month']."', '".$user_data['ccard_exp_year']."')";
	mysql_query($sql);

/*	
	$pwd1=get_unique(15);

	$mysql_ins = "INSERT into psychic_prereg (Client_id, Date, Pwd) VALUES('$user_id', now(), '$pwd1')";
	mysql_query($mysql_ins, $conn);
*/
	return $user_id;
}

function getBanInfo($user_id)
{
	$strsql = "SELECT * FROM band_list WHERE UserID = '$user_id'";
	$rs = mysql_query($strsql);
	$res=mysql_fetch_array($rs);
	return $res;
}
?>
