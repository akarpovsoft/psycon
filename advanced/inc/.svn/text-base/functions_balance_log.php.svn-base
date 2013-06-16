<?php

function getBalanceLog($clientID)
{
	global $conn;
	
	$sql= "SELECT * FROM `balance_log` WHERE `client_id` =".$clientID." ORDER BY `id` DESC";
	
	$q 	= mysql_query($sql,$conn);
	
	$result = array();
	
	while($row = mysql_fetch_assoc($q))
		$result[] = $row;
	
	return $result;
}

function setBalanceLog($clientID, $operation, $sum, $notes, $disableEmail = false, $bmt_adds = false)
{
	global $conn, $headers, $adm_email;
	
	$user    = get_user($clientID);
	
	$balance = $user['balance'];
	
	$array = getBalanceLog($clientID);

	
	$balanceBefore = $array[0]['balance'];

	if ($operation == 0) {
		$expectedBalance = $balanceBefore - $sum;
		$adds = "Time removed : $sum.";
	}
	else { 
		$expectedBalance = $balanceBefore + $sum;
		$adds = "Time added : $sum.";
	}
	
		
	if (!empty($bmt_adds))
	{
		$balance = $expectedBalance;
		
		$notes = "BMT $notes ".$bmt_adds;
		
	}
		
	$sql = "INSERT INTO `balance_log` ( `id` , `client_id` , `date` , `operation` , `sum` , `balance` , `notes` ) 
			VALUES ('', '$clientID', NOW(), '$operation', '$sum', '$balance', '$notes' );";
	
	$q = mysql_query($sql, $conn);
	
	$id = mysql_insert_id();
	
	if (( abs($expectedBalance - $balance) > 1) && (empty($disableEmail)) && false)
	{
		$date = date("Y-m-d- H:i");
		$subject = "Balance warning (".$user['login'].", ".$date.")";
		$text2send = "Possible balance error found.<br>Current operation notes: $notes<br>Current log id : $id<br>Expected Balance after opertaion :".$expectedBalance."<br>Real Balance after operation : ".$balance."<br> $adds";
		$mails = array("karpovsoft@yandex.ru");
		foreach ($mails as $mail)
			mail($mail, $subject, $text2send, $headers);
	}
	
	return mysql_error();
}

?>