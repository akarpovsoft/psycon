<?php

/*
for ($k=0; $k<1500; $k++)
{
	echo " ";
}
if(is_dir("logs/$reader") !== TRUE){
	@mkdir ("../logs/$reader", 0777);
}

if(isset($session_id)){
	$new_file = @fopen("../logs/$reader/${session_id}.txt", "w+");
	if(@flock($new_file, LOCK_EX))
	{
		fputs($new_file, "");
		@flock($new_file, LOCK_UN);
	}
	@fclose($new_file);
	@chmod("../logs/$reader/${session_id}.txt", 0777);
}

*/

function getChatRequest($request_id)
{
	global $tableChatRequests;
	
	$rs=mysql_query("SELECT * FROM $tableChatRequests WHERE rr_record_id='$request_id'");
	
	$row=mysql_fetch_array($rs);
	return $row;
}

function sendChatRequest($client_id, $reader_id, $subject, $duration, $er)
{
	$tableChatRequests="T1_7";
	
	$time_now=time();
	mysql_query("DELETE from Ping_chat where Client = '".$client_id."'");
	mysql_query("INSERT into Ping_chat (Client, Reader, Balance, Bal_start, Reader_ping, Client_ping, Bal_ping) values ('".$client_id."', '".$reader_id."', '$duration', '$duration', '$time_now', '$time_now', '$time_now')");
	
	mysql_query("DELETE FROM $tableChatRequests WHERE client_id='$client_id'");
	if($er)
	{
		$sql = "INSERT INTO $tableChatRequests (client_id,reader_id,laststatusupdate,subject, duration) VALUES ('$client_id','$reader_id', DATE_ADD(now(), interval 40 second),'$subject', '".$duration."')";
	}
	else{
		$sql = "INSERT INTO $tableChatRequests (client_id,reader_id,laststatusupdate,subject, duration) VALUES ('$client_id','$reader_id',now(),'$subject', '".$duration."')";
	}
	$rs = mysql_query($sql);
	$request_id = mysql_insert_id();
	
	return $request_id;
}

function checkChatReader($reader_id)
{
	$Result = mysql_query("SELECT rr_record_id FROM T1_1 WHERE rr_record_id='$reader_id' AND (to_days(now())=to_days(laststatusupdate) and (time_to_sec(now()) - time_to_sec(laststatusupdate) < 10))");
	$Row = mysql_fetch_row($Result);
	return is_array($Row);
	
}

define('READER_BUSY',-1);
define('CLIENT_BANNED',-2);

function getStatrtChatStatus($client, $reader_id)
{
	$Result = @mysql_query("SELECT rr_record_id FROM T1_12 where reader_id='$reader_id' AND (to_days(now())=to_days(laststatusupdate) and (time_to_sec(now()) - time_to_sec(laststatusupdate) < 10))");
	$count = @mysql_num_rows($Result);
	if ($count>0)
	{
		return READER_BUSY;
	}
	
	$Result = @mysql_query("SELECT rr_record_id from T1_7 where reader_id='$reader_id' and (to_days(now())=to_days(laststatusupdate) and (time_to_sec(now()) - time_to_sec(laststatusupdate) < 10))");
	$count = @mysql_num_rows($Result);
	
	if ($count>0)
	{
		return READER_BUSY;
	}
	
	if ($client['balance'] < 3)
	{    exit();
	}
	
	$userban = @mysql_query("SELECT ban_id FROM userban where reader_id='$reader_id' AND user_id = '".$client["rr_record_id"]."'");
	$banned = @mysql_num_rows($userban);
	mysql_free_result($userban);
	return 1;
}

function chatAvailableReaders(&$have_favorite_reader)
{
	global $tableOperators, $tableChatSessions, $tableRequests;
	
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
			continue;

		$ArrayReaders[] = array("id"=>$Row[0],"name"=>$Row[1]);
	
		if (!empty($favorite_reader_id) &&
		Strcasecmp($Row[0],$favorite_reader_id)==0)
		{
			$have_favorite_reader = true;
		}
	}
	return $ArrayReaders;
}

function getClientNotes($client_id)
{
	$extra_notes='';
	$strsql = "SELECT note from notes_clients where client = '$client_id'";
	$rs = mysql_query($strsql);
	while ($row = mysql_fetch_assoc($rs)){
		$extra_notes .= $row['note'].' ';
	}
	mysql_free_result($rs);
	
	$sql= 'UPDATE notes_clients SET flag = flag + 1 WHERE client = \'' . $client_id . '\'';
	mysql_query($sql);
	return $extra_notes;
}

/**
 * Start chat (reader side)
 *
 * @param unknown_type $session_id
 * @param unknown_type $subject
 * @param unknown_type $client
 * @param unknown_type $reader
 */
function startChat($session_id, $subject, &$client, $reader, $duration)
{
	global $lang;
	$time_now = time();
	
//Remove Previous BMT
	mysql_query("DELETE FROM BMT_stat where ID = '".$client["rr_record_id"]."'");
// remove request (session started)	
	mysql_query("DELETE FROM T1_12 where client_id = '".$client["rr_record_id"]."'");
	mysql_query("REPLACE T1_12 (rr_record_id, client_id,reader_id,laststatusupdate,type, duration,reader_add_lost_time) VALUES ($session_id, '".$client["rr_record_id"]."','".$reader["rr_record_id"]."',now(),'reader','$duration',0)");

// remove request (session started)	
	mysql_query("UPDATE T1_7 SET session_started=1 WHERE client_id = '".$client["rr_record_id"]."' AND rr_record_id='".$session_id."'");
	mysql_query("UPDATE remove_freetime set Timestamp = '".time()."' where ClientID = '$client_id'");
	
	mysql_query("UPDATE Ping_chat SET Reader_ping='$time_now', Session='".$session_id."' WHERE Reader='".$reader['rr_record_id']."'");


	if($client["first_reading"] != 'No'){
		mysql_query("UPDATE T1_1 set first_reading = 'No' where rr_record_id = '".$client["rr_record_id"]."'");
		
		$message='*** '.$lang['start_chat_1'].' ***';
		sendChatMessage($session_id,$reader["rr_record_id"], $client["rr_record_id"],  $message,'Admin');
	}

	mysql_query("INSERT INTO History (Session_id, Client_id, Client_name, Reader_id, Reader_name, Subject, Date, Duration, Free_time, Paid_time, Due_to_reader, Affiliate)  VALUES ('$session_id', '".$client["rr_record_id"]."', '".$client["login"]."', '".$reader["rr_record_id"]."', '".$reader["rr_record_id"]."',  '$subject', now(), '0', '0', '0', '0', '".$client["affiliate"]."')");
	
	$ftdata=get_free_time($client["rr_record_id"]);
	$total_free_time = $ftdata["Total_sec"];
	
	$client["free_mins"] = round($total_free_time/60);

	#create record in debug extra
	$sql_debug_extra = "INSERT INTO `debug_extra` VALUES (
		'".$client["rr_record_id"]."', '".$reader["rr_record_id"]."', '".$client['balance']."' , '$session_id', NULL , '', '0', '0', '0', NULL , '0', '0.00', '0.00', '0', '0', '0', '0', NULL , '0');";
	mysql_query($sql_debug_extra);
	#end create
	
	if($client["free_mins"] > 0){
		
		$message = "<b>".$lang['You_have']." $client[free_mins] ".$lang['start_chat_2']."</b> - ".$lang['start_chat_3'];
		sendChatMessage($session_id, $reader["rr_record_id"], $client["rr_record_id"], $message, 'Admin');

		//$message = "<b>FREEBIE CLIENT: NO MORE THAN $client[free_mins] mins- UNLESS THEY BMT!</b>";
		//sendChatMessage($session_id, $client["rr_record_id"], $reader["rr_record_id"], $message, 'Admin');
	}
	
	
}

function sendChatMessage($session_id, $from_id, $to_id, $message, $UserName)
{
	mysql_query("INSERT INTO New_posts VALUES ('', '$session_id', '$message', '$UserName','$from_id', '$to_id')");
	saveTransacript($session_id, $UserName.": ".$message);	
}

function loadChatMessages($session_id, $user_id)
{
	$sql="SELECT * FROM New_posts WHERE to_user='".$user_id."' AND Session='".$session_id."'";
	$mess = "";
	
	$rs = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($rs))
	{
		/*
		if(!empty($mess))
			$mess.="\r\n";
		*/
		$mess.=$row['Text'];
	}
	$sql="DELETE FROM New_posts WHERE to_user='".$user_id."' AND Session='".$session_id."'";
	
	mysql_query($sql);
	
	
	return $mess;
}

function saveTransacript($session_id, $transcript)
{
	$strsql = "SELECT Transcripts FROM chattranscripts WHERE  SessionId = '".$session_id."'";
	$rs = mysql_query($strsql) or die(mysql_error());
	$row = mysql_fetch_array($rs);
	mysql_free_result($rs);
	
	$session=get_session($session_id);
	
	if($row['Transcripts']!==null)
	{
		$transcript=$row['Transcripts']."\n".$transcript;
		$strsql = "UPDATE chattranscripts SET Transcripts='".addslashes($transcript)."' where SessionId = '".$session_id."'";
	}
	else
	{
		$strsql = "INSERT INTO chattranscripts SET Transcripts='".addslashes($transcript)."', ReadedId = '".$session['Reader_id']."', ClientId='".$session['Client_id']."', Date=NOW(), SessionId = '".$session_id."'";
	}
	
	$rs = mysql_query($strsql);
}

function regiterBMT($more, $clid)
{
	$strsql = "INSERT INTO BMT_stat SET Time=".$more.", ID = '".$clid."'";
	$rs = mysql_query($strsql);
}

function refreshSession($session_data, $time_dif)
{
	$time_dif_min = $time_dif/60;
	//var_export($session_data);
	//echo "<br>#### refreshSession; timedif = ".$time_dif_min;
	$mysql_ins = "UPDATE T1_1 set balance = (balance - $time_dif_min) where rr_record_id ='".$session_data['Client_id']."' and balance > 0";
	mysql_query($mysql_ins);
	
	$mysql_ins = "UPDATE T1_12 set laststatusupdate = NOW() where client_id ='".$session_data['Client_id']."' and reader_id ='".$session_data['Reader_id']."'";
	mysql_query($mysql_ins);
	
	#fix free time
	$sql = "SELECT Seconds,Total_sec FROM remove_freetime WHERE ClientID ='".$session_data['Client_id']."'";
	$q   = mysql_query($sql);
	$row = mysql_fetch_assoc($q);
	$seconds   = $row['Seconds'];
	$Total_sec = $row['Total_sec'];
	if (($seconds - $time_dif) <= 0) {
		$seconds = 0;
		$Total_sec = 0;
		$sql = "UPDATE remove_freetime set `Seconds` = '$seconds', `Total_sec` = '$Total_sec'  where ClientID ='".$session_data['Client_id']."'";
	}
	else {
		$sql = "UPDATE remove_freetime set Seconds = (Seconds - $time_dif) where ClientID ='".$session_data['Client_id']."' and Seconds > 0";
	}
	mysql_query($sql);
	
	#end fix
	
	$rate=get_payment_rate($session_data['affiliate'], $session_data['seniority'], $session_data['paid_time']);
	$reader_payment=round($rate*$session_data['paid_time']/60, 2);
	##
	//echo "<br>session_data['affiliate'] =".$session_data['affiliate'];
	//echo "<br>session_data['seniority'] = ".$session_data['seniority'];
	//echo "<br>session_data['paid_time'] = ".$session_data['paid_time'];
	//echo "<br>rate = ".$rate;
	//echo "<br>reader_payment = ".$reader_payment;
	##
	
	$mysql_ins = "UPDATE History set Duration = (Duration + $time_dif), Free_time = '".$session_data['free_time']."', Paid_time = '".round($session_data['paid_time']/60, 2)."', Due_to_reader = '$reader_payment', Rate = '$rate' where Session_id ='".$session_data['Session_id']."'";
	mysql_query($mysql_ins);
	if($reader_payment>0)
	{
		$ChatLocation = $session_data['affiliate'] == 'canada'?'Canada':'International';
		saveReaderPayment($session_data['Reader_id'], $session_data['Session_id'], $reader_payment, $ChatLocation);
	}
	//echo "<br>";
}

function get_session($session_id, $finished=null)
{
	$sql = "SELECT * from History where Session_id='$session_id'";
	if(!is_null($finished))
		$sql.=" AND Finished=".$finished;

	$res = mysql_query($sql);

	$row = mysql_fetch_array($res);
	mysql_free_result($res);
	
	return $row;
}

function get_session_extra($session_id)
{
	$sql = "SELECT * from debug_extra where session_id='$session_id'";

	$res = mysql_query($sql);

	$row = mysql_fetch_array($res);
	mysql_free_result($res);
	
	return $row;
}

function save_finish_initiator($session_id, $initiator)
{

	$sql = "UPDATE debug_extra SET finish_initiator='".$initiator."' WHERE session_id = '$session_id' AND finish_initiator IS NULL";
	mysql_query($sql);

	return true;
}

function increment_msg_count($session_id, $type)
{
	$field=$type."_msg_count";
	$sql = "UPDATE debug_extra SET $field=$field+1 WHERE session_id = '$session_id'";
	mysql_query($sql);

	return true;
}

function set_no_reader_resp($session_id)
{
	$sql = "UPDATE debug_extra SET no_response_from_reader=1 WHERE session_id = '$session_id'";
	mysql_query($sql);
}

function save_session_log($session_id, $log)
{
	$sql = "UPDATE debug_extra SET notes=CONCAT(notes, '".addcslashes($log, "\\\"'")."') WHERE session_id = '$session_id'";
	mysql_query($sql);
}

function save_session_extra($session_id, $extra_data, $create=false)
{

	$fields=sql_values($extra_data);
	if($create)
		$sql = "INSERT INTO debug_extra SET ".$fields.", session_id = '$session_id'";
	else
		$sql = "UPDATE debug_extra SET ".$fields." WHERE session_id = '$session_id'";
	@mysql_query($sql);

	return true;
}

function getCountdownTime($session_id)
{
	$sql 		= "SELECT * FROM T1_12 WHERE rr_record_id = ".$session_id;
	$q 			= mysql_query($sql);
	$row 		= mysql_fetch_assoc($q);
	$countdown 	= $row['duration'];
		
	return $countdown;
}

function pre_finish_session($session_data)
{
	$session_id=$session_data["Session_id"];
	$sql = "UPDATE History SET Finished=-1 WHERE Session_id='$session_id'";
		
	$res = mysql_query($sql);
	return true;
}

function finish_session($session_data, $cron_fixed=0)
{
	$session_id=$session_data["Session_id"];
	$sql = "UPDATE History SET Finished=1 WHERE Session_id='$session_id'";
		
	$res = mysql_query($sql);

	$new_client_data=get_user($session_data["Client_id"]);
	$extra_data=array("new_balance" => $new_client_data['balance'], "session_finished" => 1, "fixed_by_cron" => $cron_fixed);
	save_session_extra($session_id, $extra_data);

	return true;
}

function get_free_time($client_id)
{
	$sql = "SELECT * from remove_freetime where ClientID='$client_id'";
	$res = mysql_query($sql);

	$row = mysql_fetch_array($res);
	mysql_free_result($res);
	return $row;
}

function fix_deposit($client_id)
{
	$make_deposit = 1;
	$sql = "SELECT * FROM transactions WHERE UserID ='".$client_id."' and Amount_used ='0'";
	$rs = mysql_query($sql) or die(mysql_error());
	$ret["deposited_payments"]="";
	while($row = mysql_fetch_array($rs))
	{
		$ret["deposited_payments"].=$row["Date"]."|".$row["ORDER_NUMBER"]."|".$row["TRAN_AMOUNT"]."|".$row["Bmt"]."\r\n";
	}

	$sql = "UPDATE transactions set Amount_used = TRAN_AMOUNT WHERE UserID ='".$client_id."' and Amount_used ='0'";

	return $ret;
}

function fix_free_time($client_id)
{

	$sql = "UPDATE remove_freetime set  Total_sec = Seconds  where ClientID ='".$client_id."'";
	
	mysql_query($sql);
	
	return true;
}

/**
 * Save info that client was informed that paid time will begin in 1 minute
 *
 * @param  int $client_id Client ID
 * @param  int $step step
 * @return void
 */
function saveInformStep($client_id, $step)
{
	$sql = "UPDATE remove_freetime set finish_step = ".$step."  where ClientID ='".$client_id."'";
	mysql_query($sql);
}


function return_session_time($session_data)
{
	$time_back=round($session_data['Duration']/60, 2);
	
	$sql = "DELETE from reader_payment where Session_numb = '".$session_data["Session_id"]."'";
	$res = mysql_query($sql);
	$log=$sql;
	$log.="\r\nError: ".mysql_error();

	$sql = "UPDATE T1_1 set balance = (balance + $time_back) where rr_record_id ='".$session_data["Client_id"]."'";
	mysql_query($sql);
	
	$log.="\r\n".$sql;
	$log.="\r\nError: ".mysql_error();
	
	//$Duration_chat
	$sql = "UPDATE remove_freetime set Seconds = Total_sec  where ClientID ='".$session_data["Client_id"]."' and Total_sec > 5";
	mysql_query($sql);
	
	$log.="\r\n".$sql;
	$log.="\r\nError: ".mysql_error();

	$sql = "UPDATE History set Paid_time = '0', Due_to_reader = '0', Free_time = '$time_back', Rate = '".$session_data["Rate"]."', ChatLocation='".$session_data["ChatLocation"]."' where Session_id ='".$session_data["Session_id"]."'";
	mysql_query($sql);
	
	$log.="\r\n".$sql;
	$log.="\r\nError: ".mysql_error();

	$sql = "SELECT * FROM History where Session_id ='".$session_data["Session_id"]."'";
	$rs=mysql_query($sql);
	$row = mysql_fetch_assoc($rs);
	$log.="\r\nFinal: Free time - ".$row['Free_time'].", Paid time - ".$row['Paid_time']."\r\n";
	
	save_session_log($session_data["Session_id"], $log);
	
	return true;
}

function process_bmt($client_id)
{
	$sql = "SELECT * FROM BMT_stat where ID = '$client_id'";
	$rs = mysql_query($sql);
	while($row = mysql_fetch_assoc($rs)){
		$Time_bmt +=  $row["Time"];
	}
	mysql_free_result($rs);
	if($Time_bmt > 0){
		$mysql_ins = "DELETE FROM BMT_stat where ID = '$client_id'";
		mysql_query($sql);


	}
	return $Time_bmt;
}

/**
 * Get reader payment rate
 *
 * @param unknown_type $affiliate_code
 * @param unknown_type $reader_seniority
 * @param unknown_type $paid_chat_duration
 * @return reader rate
 */
function get_payment_rate($affiliate_code, $reader_seniority, $paid_chat_duration)
{
	global $app_path;
	include($app_path."setup.php");
	
		$strsql = "SELECT * FROM affiliate WHERE Username ='$affiliate_code' and Rate != 'Zero'";
	$rs = mysql_query($strsql) or die(mysql_error());
	$row = mysql_fetch_assoc($rs);
	$affiliate_rate = $row["Rate"];

	if( 'canada' == $affiliate_code)
	{
		if($paid_chat_duration  <= 25){
			$rate = 0.46;
		}
		elseif($paid_chat_duration  <= 40){
			$rate = 0.45;
		}
		elseif($paid_chat_duration  <= 55){
			$rate = 0.45;
		}
		elseif($paid_chat_duration  > 55){
			$rate = 0.49;
		}
	}
	//Old Readers
	//$Duration_chat
	elseif($reader_seniority == "Old"){
		if($affiliate_rate == "Zero" or strlen($affiliate_rate) < 1){


			if($paid_chat_duration  < 70){
				$rate = $price_old_0_1;
			}
			else{
				$rate = $price_old_0_2;
			}
		}
		elseif($affiliate_rate == "A-1"){
			if($paid_chat_duration  <= 25){
				$rate = $price_old_a1_1;
			}
			elseif($paid_chat_duration  <= 40){
				$rate = $price_old_a1_2;
			}
			elseif($paid_chat_duration  <= 55){
				$rate = $price_old_a1_3;
			}
			elseif($paid_chat_duration  > 55){
				$rate = $price_old_a1_4;
			}
		}
		elseif($affiliate_rate == "A-2"){
			if($paid_chat_duration  <= 25){
				$rate = $price_old_a2_1;
			}
			elseif($paid_chat_duration  <= 40){
				$rate = $price_old_a2_2;
			}
			elseif($paid_chat_duration  <= 70){
				$rate = $price_old_a2_3;
			}
			elseif($paid_chat_duration  > 70){
				$rate = $price_old_a2_4;
			}
		}
	}
	//---
	//New Readers

	else //if($reader_seniority == "New")
	{
		if($affiliate_rate == "Zero" or strlen($affiliate_rate) < 1){
			if($paid_chat_duration  < 70){
				$rate = $price_new_0_1;
			}
			else{
				$rate = $price_new_0_2;
			}
		}
		elseif($affiliate_rate == "A-1"){
			if($paid_chat_duration  <= 25){
				$rate = $price_new_a1_1;
			}
			elseif($paid_chat_duration  <= 40){
				$rate = $price_new_a1_2;
			}
			elseif($paid_chat_duration  <= 55){
				$rate = $price_new_a1_3;
			}
			elseif($paid_chat_duration  > 55){
				$rate = $price_new_a1_4;
			}
		}
		elseif($affiliate_rate == "A-2"){
			if($paid_chat_duration  <= 25){
				$rate = $price_new_a2_1;
			}
			elseif($paid_chat_duration  <= 40){
				$rate = $price_new_a2_2;
			}
			elseif($paid_chat_duration  <= 70){
				$rate = $price_new_a2_3;
			}
			elseif($paid_chat_duration  > 70){
				$rate = $price_new_a2_4;
			}
		}
	}
	return $rate;
}

function saveReaderPayment($reader_id, $session_id, $reader_payment, $ChatLocation)
{
	$strsql = "SELECT ID FROM reader_payment WHERE Session_numb = '$session_id'";
	$rs = mysql_query($strsql);
	$row = mysql_fetch_assoc($rs);
	$ID_payment = $row["ID"];
	mysql_free_result($rs);

	if($ID_payment > 0)
	{
		$mysql_ins = "UPDATE reader_payment set Sum = '$reader_payment' where ID = '$ID_payment'";
		mysql_query($mysql_ins);
	}
	else
	{
		$ChatLocation = $ChatLocation == 'canada'?'Canada':'International';
		$mysql_ins = "INSERT into reader_payment (ID, ReaderID, Date, Sum, ChatLocation, Session_numb) values('', '$reader_id', now(), '$reader_payment', '$ChatLocation', '$session_id');";
		mysql_query($mysql_ins);
	}
}


function addMoreTime($request_id, $addMore)
{
	$sql = "UPDATE `T1_12` SET `duration` = `duration` + '$addMore' WHERE `rr_record_id` =".$request_id;
	$q   = mysql_query($sql) or die(mysql_error());
	return 1;
}

function  addLostTime($request_id, $count, $pingInterval,$duration)
{
	
	$sql = "SELECT  Duration, Rate FROM  `History` WHERE  `Session_id` =$request_id";
	$q   = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_assoc($q);

	$rate         = $row['Rate'] + 0;
	
	$currPaidTime = $duration;// +$pingInterval;
	
	//echo "<br> currPaidTime = ".$currPaidTime;
	//echo "<br> count = ".$count;

	if ($currPaidTime < $count*60) {
		$timeToAdd    =  $currPaidTime/60;  
		$currPaidTime = 0;
	}  
	else { 
		$timeToAdd    = $count;
		$currPaidTime = $currPaidTime - $count*60;
	}

	$paidReaderTime = $rate *$currPaidTime / 60 ;
	$paidReaderTime = round($paidReaderTime, 3);
	
	//echo "<br> paidReaderTime = ".$paidReaderTime;
	//$sql = "";
	
	//$sql = "UPDATE  `T1_12` SET  `reader_add_lost_time` = `reader_add_lost_time` + $timeToAdd,  `duration` = `duration` + $timeToAdd WHERE  `rr_record_id` =$request_id";
	$sql = "UPDATE  `T1_12` SET  `reader_add_lost_time` = `reader_add_lost_time` + $timeToAdd WHERE  `rr_record_id` =$request_id";
	mysql_query($sql) or die(mysql_error());
	//echo "<br> sql = ".$sql;
	
	//$sql = "UPDATE  `History` SET  `Duration` =  '$currPaidTime' WHERE  `Session_id` =$request_id";
	//mysql_query($sql) or die(mysql_error());
	//echo "<br> sql = ".$sql;
	
	$sql = "UPDATE  `History` SET  `Due_to_reader` =  '$paidReaderTime' WHERE  `Session_id` =$request_id";
	mysql_query($sql) or die(mysql_error());
	//echo "<br> sql = ".$sql;
	
	$sql = "UPDATE  `reader_payment` SET  `Sum` =  '$paidReaderTime' WHERE  `Session_numb` =$request_id ";
	mysql_query($sql) or die(mysql_error());
	//echo "<br> sql = ".$sql;
	
	return $timeToAdd;
}

function getReaderAddLostTime($session_id)
{
	$sql = "SELECT * FROM  `T1_12` WHERE rr_record_id = ".$session_id;
	$q   = mysql_query($sql);
	$row = mysql_fetch_assoc($q);
	return $row['reader_add_lost_time'];
}

function getSessionData($session_id){
	$sql = "SELECT * FROM  `T1_12` WHERE rr_record_id = ".$session_id;
	$q   = mysql_query($sql);
	$row = mysql_fetch_assoc($q);
	return $row;
}

?>