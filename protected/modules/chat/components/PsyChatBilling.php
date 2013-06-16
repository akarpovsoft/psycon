<?php

class PsyChatBilling
{
    /*
    private $db;
    public $msgCountLimit = 6;
    public $durationLimit = 120;
    public $sessionId;
    public $clientId;
    public $clientName;
    public $readerIid;
    public $readerName;
    public $subject;
    public $date;
    public $duration;
    public $freeTime;
    public $paidTime;
    public $returnedTime;
    public $dueToReader;
    public $rate;
    public $affiliate;
    public $chatLocation;
    public $finished;
    public $status;
    public $readerMsgCount;
    public $clientMsgCount;
   */
    private $chatContext;
    private $history;
    private $freeTime;
    
	function __construct($sessionId)
	{
            $this->chatContext	= ChatContext::getContext( ChatContext::getSessionKey($sessionId), ChatContext::READER);
            $this->history		= ChatHistory::getSession($sessionId);
            $this->freeTime		= RemoveFreetime::getFreeTime($this->chatContext->client_id);
	}
	
	/**
	 * Get users with chat session time less then 4 minutes and send chattranscripts to
	 *
	 */
	static function getShortSessions($limitInMinutes, $startDate = false, $endDate = false)
	{
		if (!$startDate) $startDate = date("Y-m-d H:i:s", strtotime("-1 day"));
		if (!$endDate)   $endDate   = date("Y-m-d H:i:s", strtotime("-1 hour"));

		$connection = Yii::app()->db;
		$sql = "SELECT h.`Session_id`,h.`Date`,h.`Reader_name`,h.`Client_name`,h.`Duration`, h.`Paid_time`, h.`Due_to_reader`,tr.`Transcripts`, tr.`ReadedId` FROM `chattranscripts` tr INNER JOIN  `History` h ON  tr.`SessionId` = h.`Session_id`  WHERE  h.Date > '$startDate' AND   h.Date < '$endDate' AND h.`Duration`<=".($limitInMinutes * 60)."  AND h.`Finished`=1";
		$command=$connection->createCommand($sql);
		$rs = $command->queryAll();
/*
		$summary		= array();
		$chat_transc	= array();
		$details		= array();

		foreach ((array)$rs as $row)
		{
			//form summary
			/*
			$summary[] = "Session # : ".$row['Session_id']." Date : ".$row['Date']."\n".
				"Reader : ".$row['Reader_name']." Client : ".$row['Client_name']."\n".
				"Duration : ".number_format($row['Duration']/60,2)."\n".
				"-------------------------------";
			$details[] = $summary[sizeof($summary)-1].$row['Transcripts']."\n";
			* /
			$chat_transc[] = $row;
		}
*/
		return $rs->readAll();
/*
		$admin_text = "SUMMARY:"."\n\n".join("\n",$summary)."FULL DETAILS:"."\n\n".join("\n", $details)."END";

		PsyMailer::send(/*Config::adminMail()* / 'linkoivan@gmail.com', 'PSYCHAT - Chat Transcripts for <=4 mins sessions.', nl2br($admin_text));
*/
	}
	
	/**
	 * Function gets all unfinished sessions in last day(or in a preset time)   
	 *
	 * @param mixed $startDate start date
	 * @param mixed $endDate end date
	 * @return mixed fetched sessions
	 *
	 */
	public static function  getUnfinishedSessions($startDate = false, $endDate = false)
	{
/*		
		$old_sql = 
	    "SELECT d.*, h.*,c.*, r.seniority as reader_seniority, f.* 
		from T1_1 as c, T1_1 as r, debug_extra as d, History as h 
		LEFT JOIN remove_freetime as f on f.ClientID=h.Client_id  
				where h.Date > '$startDate' and h.Date < '$endDate' AND DATE_ADD(h.Date, interval 1 hour) < now() 
		and h.Client_id = c.rr_record_id and h.Reader_id = r.rr_record_id and d.session_id = h.Session_id and Finished < 1 
		order by h.Date desc"; // DATE_ADD(h.Date, interval 1 hour) < now() and 
*/
				
		$connection = Yii::app()->db;
		$sql = "SELECT d.*, h.*,c.*, r.seniority as reader_seniority, f.* from T1_1 as c, T1_1 as r, debug_extra as d, History as h LEFT JOIN remove_freetime as f on f.ClientID=h.Client_id  where DATE_ADD(h.Date, interval 7 DAY) > now() AND DATE_ADD(h.Date, interval 0 hour) < now() and h.Client_id=c.rr_record_id and h.Reader_id=r.rr_record_id and d.session_id=h.Session_id and Finished<1 order by h.Date desc";
		$command = $connection->createCommand($sql);
		$sessionData = $command->queryAll();
		return $sessionData->readAll();		

	}
	
	private static function createFromArray($array)
	{
		$obj = new ChatSession($array['Sesssion_id']);

		foreach($array as $k => $v)
		{
			$var = $obj->strtocamel($k, false); 

			$obj->$var = $v;
		}
		
		return $obj;
	}
	
	/**
	 * Function calculate balance after reading
	 *
	 * @param mixed $sessionData session data
	 * @param mixed $addLost addLost flag
	 * @param mixed $cronFinished cron flag
	 * @return mixed if the session is free for client or not
	 */
	public function endSession( $addLost, $cronFinished)
	{
		$timeToReturn = 0;
		
		$chatDuration		= $this->history->getDurationMins();
		$totalFreeTime		= round($this->chatContext->freeTimeOnStart() /60, 2);
		
		// Get User Data
		
		$clientData = $this->chatContext->client;
		$siteType = $clientData['site_type'];
		
		// Get Reader payment rate
		$this->history->Rate = $this->getPaymentRate($clientData['affiliate'], $this->chatContext->reader->seniority, $this->history->Paid_time);
		$this->history->Due_to_reader = $this->history->dueToReader();
                
                var_dump($this->history->Due_to_reader);
                die();
		// Get extra information about session
		$sdata = $this->getSessionExtra();
		
		// Nopayment flag
		$nopayment = ($addLost || (($sdata['client_msg_count']==0 && $sdata['reader_msg_count']<6) || $sdata['reader_msg_count']==0) ); 
		
		if($nopayment)		
			$timeToReturn = 'all';
		
		$extraData = array(
			'add_lost_time' => ($addLost ? 1 : 0),
			'bmt_time' => BmtStat::processBmt($this->chatContext->client_id),
			'payment' => ($nopayment ? 0 : 1),
			'chat_type' => $this->getChatType($this->chatContext->session_id),
			'free_time_after' => round($this->chatContext->freeTimeRemaining() /60, 2),
		);
		
		if (($this->getFinishInitiator() == 'timeout')&&(!eregi('all', $timeToReturn))) 		
			$timeToReturn += 2;
		
		$this->history->ChatLocation = $this->getChatLocation(); // from T1_1 (client)
		
		if (!empty($timeToReturn))
		{
			$this->returnSessionTime($timeToReturn);					
		}
		
		if (!$nopayment)
		{
			if ($chatDuration > 0) 
				$this->setBalanceLog( "0", $this->chatContext->paidChatDuration(), "Chat session ".$this->chatContext->session_id, $clientData);
				
			$extraData['deposited_payments'] = $this->countTimeMarkTransDeps();
			
			if($totalFreeTime > 0)
				RemoveFreetime::fixFreeTime($this->chatContext->client_id);
		}
        
        //Reader go busy at 10 mins after end chatting
        Readers::updateReaderStatus($this->chatContext->reader_id, 'break10'); 

		/// Save all history changes
		$this->history->save();
		
		/// Clear all flags on end session
		$TimeEvent = new TimeEvents();
		$TimeEvent->clearFlags($this->chatContext);
				
		/// Delete old chat context records
		ChatContext::deleteOldSessions();
		
		$this->saveSessionExtra($extraData);
		$this->finishSession($cronFinished);

		return $nopayment;
	}
	
	
	/**
	 * Convert a string to camel case, optionally capitalizing the first char and optionally setting which characters are
     * acceptable.
	 *
     * @param  string  $str              text to convert to camel case.
     * @param  bool    $capitalizeFirst  optional. whether to capitalize the first chare (e.g. "camelCase" vs. "CamelCase").
     * @param  string  $allowed          optional. regex of the chars to allow in the final string
     * 
     * @return string camel cased result
	 */
	private function strtocamel($str, $capitalizeFirst = true, $allowed = 'A-Za-z0-9') 
	{
		return preg_replace(
			array(
				'/([A-Z][a-z])/e',			// all occurances of caps followed by lowers
				'/([a-zA-Z])([a-zA-Z]*)/e', // all occurances of words w/ first char captured separately
				'/[^'.$allowed.']+/e',		// all non allowed chars (non alpha numerics, by default)
				'/^([a-zA-Z])/e'			// first alpha char
			),
			array(
	            '" ".$1',								// add spaces
				'strtoupper("$1").strtolower("$2")',	// capitalize first, lower the rest
				'',										// delete undesired chars
				'strto'.($capitalizeFirst ? 'upper' : 'lower').'("$1")' // force first char to upper or lower
			),
			$str
    );
}
	
	/**
	 * Set finish flag
	 *
	 * @param mixed $sessionData session data
	 * @param mixed $cronFixed cron flag
	 * @return bool
	 *
	 */
	private function finishSession($cronFixed=0)
	{
		$this->history->Finished = 1;
		$this->history->save();
						
		$extraData = array("new_balance" => $this->chatContext->client->balance , "session_finished" => 1, "fixed_by_cron" => $cronFixed);
		$this->saveSessionExtra( $extraData);
		
		return true;
	}
	
	
	/**
	 * Seva extra data to debug_extra table
	 *
	 * @param mixed $sessionId session id
	 * @param mixed $extraData data
	 * @param mixed $create create new table flag
	 * @return bool 
	 *
	 */
	private function saveSessionExtra($extraData, $create=false)
	{
		/// @todo need test
		$debugExtra = DebugExtra::model()->findByPk($this->chatContext->session_id);
		$debugExtra->attributes = $extraData;
		$debugExtra->save();

		return true;
	}
	
	
	/**
	 * return free time when nopayment
	 *
	 * @param mixed $clientId client id
	 * @return bool 
	 *
	 */
	private function fixFreeTime()
	{
		$this->freeTime->Total_sec = $this->freeTime->Seconds;
		$this->freeTime->save();
		
        return true;
	}
	
	private function getChatType()
	{
		$row = ChatRequests::model()->findByPk($this->chatContext->session_id);
		
		return $row['chat_type'];
	}
	
	
	private function getBalanceLog()
	{
		$criteria=new CDbCriteria;
		$criteria->condition='client_id = :client_id';
		$criteria->params=array(':client_id'=> $this->chatContext->client_id);
		$criteria->order = 'id DESC';

		$data = BalanceLog::model()->find($criteria);

		return $data;

	}
	
	
	/**
	 * Function logging all operations with the user balance and sent a mail to the administrator if something wrong
	 *
	 * @param mixed $clientID client id
	 * @param mixed $operation increase or decrease the balance
	 * @param mixed $sum summa
	 * @param mixed $notes some notes
	 * @param mixed $user user data
	 * @param mixed $disableEmail if is set, email function disabled
	 * @param mixed $bmt_adds some adds with bmt payment
	 * @return mixed insert result to db
	 *
	 */
	private function setBalanceLog( $operation, $sum, $notes, $user, $disableEmail = false, $bmt_adds = false)
	{
		$balance = $user['balance'];
		
		$array = $this->getBalanceLog($this->chatContext->client_id);

		$balanceBefore = $array['balance'];
		
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
		
		$balanceLog = new BalanceLog();
		$balanceLog->client_id = $this->chatContext->client_id;
		$balanceLog->date = new CDbExpression("NOW()");
		$balanceLog->operation = $operation;
		$balanceLog->sum = $sum;
		$balanceLog->balance = $balance;
		$balanceLog->notes = $notes;
		$error = $balanceLog->save();

		$id = $balanceLog->getDbConnection()->lastInsertID;

		if (( abs($expectedBalance - $balance) > 1) && (empty($disableEmail)) && !$error && false)
		{
			$date = date("Y-m-d- H:i");
			$subject = "Balance warning (".$user['login'].", ".$date.")";
			$text2send = "Possible balance error found.<br>Current operation notes: $notes<br>Current log id : $id<br>Expected Balance after opertaion :".$expectedBalance."<br>Real Balance after operation : ".$balance."<br> $adds";
			PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $text2send);
		}
		
		return $error;
	}
	
	
	/**
	 * Returns all used time due last reading
	 *
	 * @param mixed $sessionData last reading session data
	 * 
	 */
	private function returnSessionTime($timeBack = false)
	{
		if(($timeBack == 'all') || (!isset($timeBack) ))
			$timeBack = $this->history->getDurationMins(); /// timeBack is in mins now
		
		$readerPayment = ReaderPayment::model()->find(
				"Session_numb = :session_id",
				array(':session_id' => $this->chatContext->session_id)
		);
		
		if(is_object($readerPayment))
			$readerPayment->delete();

		$this->chatContext->client->balance += $timeBack;
		$this->chatContext->client->save();
		
		if($this->chatContext->freeTimeOnStart() > 5)
		{
			$this->freeTime->Seconds = $this->chatContext->freeTimeOnStart();
			$this->freeTime->save();
		}

		$this->history->Paid_time		= 0;
		$this->history->Due_to_reader	= 0;
		$this->history->Returned_time	= round($timeBack *60, 2); /// convert to seconds
		$this->history->Free_time		= round($timeBack *60, 2); /// convert to seconds
		$this->history->save();
		
		return true;
	}
	
	
	private function getChatLocation()
	{
		$clientData = $this->chatContext->client;
		
		return  (($clientData['affiliate'] == 'canada' || $clientData['site_type'] =='CA') ? 'Canada' : 'International');		
	}
	
	
	/**
	 * Gets a session finish initiator. 
	 *
	 * @return mixed client/reader/timeout
	 *
	 */
	private function getFinishInitiator()
	{
		return $this->chatContext->getFinishInitiator();
	}
	
	/**
	 * Define readers status and gets from meta file with readers rates
	 *
	 * @param mixed $affiliateCode affiliate code
	 * @param mixed $readerSeniority Reader Seniority
	 * @param mixed $paidChatDuration Paid Chat Duration
	 * @return mixed reader rate
	 *
	 */
	private function getPaymentRate($affiliateCode, $readerSeniority, $paidChatDuration)
	{
		require_once Yii::app()->params['project_root'].'/advanced/data/setup.meta.php';
		$affiliateRate = '';
		
		if( 'canada' == $affiliateCode)
		{
			if($paidChatDuration  <= 25){
				$rate = 0.46;
			}
			elseif($paidChatDuration  <= 40){
				$rate = 0.45;
			}
			elseif($paidChatDuration  <= 55){
				$rate = 0.45;
			}
			elseif($paidChatDuration  > 55){
				$rate = 0.49;
			}
		}
		//Old Readers
		//$Duration_chat
		elseif($readerSeniority == "Old"){
			if($affiliateRate == "Zero" or strlen($affiliateRate) < 1){
				
				
				if($paidChatDuration  < 70){
					$rate = $price_old_0_1;
				}
				else{
					$rate = $price_old_0_2;
				}
			}
			elseif($affiliateRate == "A-1"){
				if($paidChatDuration  <= 25){
					$rate = $price_old_a1_1;
				}
				elseif($paidChatDuration  <= 40){
					$rate = $price_old_a1_2;
				}
				elseif($paidChatDuration  <= 55){
					$rate = $price_old_a1_3;
				}
				elseif($paidChatDuration  > 55){
					$rate = $price_old_a1_4;
				}
			}
			elseif($affiliateRate == "A-2"){
				if($paidChatDuration  <= 25){
					$rate = $price_old_a2_1;
				}
				elseif($paidChatDuration  <= 40){
					$rate = $price_old_a2_2;
				}
				elseif($paidChatDuration  <= 70){
					$rate = $price_old_a2_3;
				}
				elseif($paidChatDuration  > 70){
					$rate = $price_old_a2_4;
				}
			}
		}
		//---
		//New Readers
		
		else //if($readerSeniority == "New")
		{
			if($affiliateRate == "Zero" or strlen($affiliateRate) < 1){
				if($paidChatDuration  < 70){
					$rate = $price_new_0_1;
				}
				else{
					$rate = $price_new_0_2;
				}
			}
			elseif($affiliateRate == "A-1"){
				if($paidChatDuration  <= 25){
					$rate = $price_new_a1_1;
				}
				elseif($paidChatDuration  <= 40){
					$rate = $price_new_a1_2;
				}
				elseif($paidChatDuration  <= 55){
					$rate = $price_new_a1_3;
				}
				elseif($paidChatDuration  > 55){
					$rate = $price_new_a1_4;
				}
			}
			elseif($affiliateRate == "A-2"){
				if($paidChatDuration  <= 25){
					$rate = $price_new_a2_1;
				}
				elseif($paidChatDuration  <= 40){
					$rate = $price_new_a2_2;
				}
				elseif($paidChatDuration  <= 70){
					$rate = $price_new_a2_3;
				}
				elseif($paidChatDuration  > 70){
					$rate = $price_new_a2_4;
				}
			}
		}
		return $rate;
	}
	
	/**
	 * Function get debug extra session data
	 *
	 * @param mixed $sessionId Session id
	 * @return array extra data
	 *
	 */
	private function getSessionExtra()
	{
		return DebugExtra::model()->findByPk($this->chatContext->session_id);
	}



	/**
	 * Function mark transactions to deposit, if need it
	 *
	 */
	private function countTimeMarkTransDeps()
	{
		$sessionDuration= $this->history->getDurationSec();
		$sessionId		= $this->history->getSessionId();
			print_r($timeRemains);
		$timeRemains	= $this->markDeposits($sessionId, $sessionDuration);
		print_r($timeRemains);
		if($timeRemains > 0)
			$timeRemains = $this->markTransactions($sessionId, $timeRemains);
		
	}


	private function markDeposits($sessionId, $sessionDuration)
	{
		$deposits = Deposits::searchUnmarkedByClientId($this->chatContext->client_id);

		$timeRemains = $sessionDuration;

		for($i = 0; $i < count($deposits); $i++) {
			if($timeRemains <= 0) {
				break;
			}

			$timeToDeduct = $deposits[$i]->getTime() > $timeRemains ? $timeRemains : $deposits[$i]->getTime() ;
			$timeRemains -= $timeToDeduct;
			$deposits[$i]->acceptSessionTime($sessionId, $timeToDeduct);
		}
		
		return $timeRemains;
	}

	private function markTransactions($sessionId, $sessionDuration) {

		$transactions = Transactions::searchUnmarkedByClientId($this->chatContext->client_id);
		
		$timeRemains = $sessionDuration;

		echo "timeRemains: ".$timeRemains;
		
		for($i=0; $i < count($transactions); $i++)
		{
			if($timeRemains<=0) 
				break;

			echo "get time: ".$transactions[$i]->getTime();

			$timeToDeduct = ($transactions[$i]->getTime() > $timeRemains ? $timeRemains : $transactions[$i]->getTime());
			
			$timeRemains -= $timeToDeduct;
			
			$transactions[$i]->acceptSessionTime($sessionId, $timeToDeduct);
		}
	}

	
	
	
	
}// end class

?>