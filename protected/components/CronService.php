<?php
class CronService
{
    function __construct()
    {
    }

	static function fixUnfinishedSessions()
	{
		$sessionData = PsyChatBilling::getUnfinishedSessions();
		
		foreach( $sessionData as $row)
		{	
			$addlost = 0;
			
			//Get ssn msg count 	
			$messageCount = $row['reader_msg_count'] + $row['client_msg_count'];
			
			//Get duration 
			$duration = $row['duration'];
			
			if($messageCount < 6 || $duration < 120) {
				$addlost = 1; 
			}
			else {
				$addlost = 0;
			}

			$chatSession = new PsyChatBilling($row['rr_record_id']);
			$chatSession->endSession($addlost, 1);
		}

		$connection = Yii::app()->db;
		$sql = "UPDATE band_list set Sum_day = '0', Day_date = NOW()";
		$command=$connection->createCommand($sql);
		$command->execute();

		$connection = Yii::app()->db;
		$sql = "UPDATE band_list set Sum_month = '0', Month_date = NOW() where DATE_ADD(Month_date, interval 29 day) < now()";
		$command=$connection->createCommand($sql);
		$command->execute();
	}
	
	static function notifyShortSessions($limitInMinutes, $startDate = false, $endDate = false)
	{
		
		$shortSessions = PsyChatBilling::getShortSessions($limitInMinutes, $startDate, $endDate);

		if (count($shortSessions) > 0)
		{
			$summary	= array();
			$details	= array();
			foreach ( $shortSessions as $row)
			{				
				//form summary
				$summary[] = "Session # : ".$row['Session_id']." Date : ".$row['Date']."\n".
					"Reader : ".$row['Reader_name']." Client : ".$row['Client_name']."\n".
					"Duration : ".number_format($row['Duration']/60, 2)."\n".
					"Due to Reader : ".number_format($row['Due_to_reader'], 2)."\n".
					"-------------------------------";
				
				$details[] = $summary[sizeof($summary)-1].$row['Transcripts']."\n";

				if(($row['Due_to_reader'] == 0)&&($row['Paid_time'] == 0))
				{
					continue;
				}
				else
				{
					$subjectToReader = "SUBJECT: SHORT CHAT SESSION";

					$reader = new PsyUser($row['ReadedId']);
					$readerData = $reader->load();

					$textToReader =
						"DATE:\t".date("Y-m-d H:i")."\n\n".
						"READER:\t".$row['Reader_name']."\n\n".
						"CLIENT:\t".$row['Client_name']."\n\n".
						"Dear ".$row['Reader_name']."\n".
						"You have just completed the abovr chat session which lasted less than $limitInMinutes mins:\n".
						"Session # : ".$row['Session_id']." Date : ".$row['Date']."\n".
						"Reader : ".$row['Reader_name']." Client : ".$row['Client_name']."\n".
						"Duration : ".number_format($row['Duration']/60, 2)."\n\n".
						"Please follow protocol & use the \"HONOR SYSTEM\" if necessary.\n".
						"Or.....\n".
						"Please email Jayson/Diana letting them know this session was a \"continuation of\"/\"preceeding to\" another session (as per the new rule)";

					PsyMailer::send($readerData['emailaddress'], $subjectToReader, $textToReader);
				}
			}
	
			$admin_text = "SUMMARY:"."\n\n".join("\n",$summary)."FULL DETAILS:"."\n\n".join("\n", $details)."END";
			
			PsyMailer::send(Config::adminMail() , 'PSYCHAT - Chat Transcripts for <='.$limitInMinutes.' mins sessions.', nl2br($admin_text));			
		}
	}

	static function notifyReturnTimeStat()
	{
		$db	= PsyDB::instance();
		$email_subject = 'PSYCHAT - Return time statistics for last 15 days';
		$email_body = '';

		$sql = "SELECT T1_1.name, COUNT( session_id ) AS sessions, SUM( value ) AS total_time
				FROM `return_time_stat`
				LEFT JOIN `T1_1` ON T1_1.rr_record_id = reader_id
				WHERE (to_days(now()) - to_days(date)) < 15
				GROUP BY reader_id";
		$sql2 = "SELECT r.name as reader_name, c.name as client_name, return_time_stat.*
				 FROM `return_time_stat`
				 LEFT JOIN T1_1 as r ON r.rr_record_id = reader_id
				 LEFT JOIN T1_1 as c ON c.rr_record_id = client_id
				 WHERE (to_days(now()) - to_days(date)) < 15
				 ORDER BY reader_id";
		$result_total = $db->query($sql);
		$email_body .= 'Total statistic for all readers in last 15 days:<br> <table border><tr><td>Reader</td><td>Sessions count</td><td>Total value</td></tr>';
		foreach($result_total as $res_total){
			$email_body .= "<tr><td>".$res_total['name'].'</td><td>'.$res_total['sessions'].'</td><td>'.$res_total['total_time'].'</td></tr>';
		}
		$email_body .= '</table>';
		$result_all = $db->query($sql2);
		$email_body .= "Advanced statistics for each reader in last 15 days:<br>";
		foreach($result_all as $res){
			if($res['reader_id'] != $prev_reader)
				$email_body .= $res['reader_name'].':<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; To client: '.$res['client_name'].' returned $'.$res['value'].'. Session id: '.$res['session_id'].'. Date: '.$res['date'].' <br>';
			else
				$email_body .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; To client: '.$res['client_name'].' returned $'.$res['value'].'. Session id: '.$res['session_id'].'. Date: '.$res['date'].' <br>';
			$prev_reader = $res['reader_id'];
		}
		PsyMailer::send(Config::adminMail(), $email_subject, $email_body);
	}		
}

?>