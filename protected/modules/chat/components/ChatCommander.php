<?php

class ChatCommander
{
	/**
	 * User context instance
	 * @var ChatContext class
 	 */
	private $chatContext;
	
	/**
	 * @var stdClass;
	 */
	private $parm;

	public function __construct($chatContext)
	{
		$this->chatContext  = $chatContext;
	}

	public function registerParams($params)
	{
		$this->parm = new stdClass();
		foreach($params as $key => $val)
			$this->parm->$key = $val;
	}


	/**
	 *  Command "text"
	 *  @param command = text
	 *  @param message,
	 *  @param toUserId,
	 *  @param style,
	 */
	public function commandText()
	{       
			/// inc client/reader msg counter
			$history = ChatHistory::getSession($this->chatContext->session_id);
			$history->increaseMsgCount($this->chatContext->userType);

			/// put command to queue
			$params = array(
				'message' => $this->parm->message,
				'style' => $this->parm->style,
				'nickName' => $this->chatContext->user->nickName
			);
            CommandsQueue::put($this->chatContext, 'text', serialize($params), $this->chatContext->opponent->user_id);
	}
	
	/**
	 * Puts system only message
	 * @param int $toUserId user id to which message is sent
	 * @param Array $param array of params
	 */
	public function addSystemMessage($toUserId, $message, $addToTranscripts = false)
	{
		/// put command to queue		
		$params = array('message' => strlen($message) > 0 ? $message : '' , 'style' => 'redLine', 'system' => 1);		     
        CommandsQueue::put($this->chatContext, 'text', serialize($params), $toUserId);
	}

/**
	 * Check for new comands
	 * $postId - last post id
	 */
	public function commandRefresh()
	{
		$postId = $this->parm->postId;
		/// select all from command pull
		$commands = CommandsQueue::get($this->chatContext, $postId);
		$session = ChatHistory::getSession($this->chatContext->session_id);
		
		if(!$session->sessionFinished())
			$this->chatContext->ping();
			
		$output = array();
		/// delete all from command pull
		
		if (count($commands) > 0) {
			foreach ($commands as $command)
			{
				$output[] = array(
					'command' => $command['command'],
					'params' => unserialize($command['params']),
				); 
			}
			$output['postId'] = $postId;
		} else {
			$output['status'] = 'ok';
		}
		$time = ChatHelper::time();
		//client idle time out
		if(strtotime($this->chatContext->client_ping) + Yii::app()->params['chatIdleTime'] < $time)
		{
			//show alert to opponent(Connections to opponent lost)
			$cmdQueue = new CommandsQueue();
//			$cmdQueue->put($this->chatContext, 'connectionLost', NULL, $this->chatContext->reader_id);
			$params = array('client_ping' => $this->chatContext->client_ping, 'idle_time' => Yii::app()->params['chatIdleTime'],
			'db_time' => date("Y-m-d H:i:s", $time));
			$cmdQueue->put($this->chatContext, 'connectionLost', $params, $this->chatContext->reader_id);
			$this->commandConnectionLost();
		}
		//reader idle time out
		else if(strtotime($this->chatContext->reader_ping) + Yii::app()->params['chatIdleTime'] < $time)
		{
			//show alert to opponent(Connections to opponent lost)
			$cmdQueue = new CommandsQueue();
//			$cmdQueue->put($this->chatContext, 'connectionLost', NULL, $this->chatContext->client_id);
			$params = array('reader_ping' => $this->chatContext->reader_ping, 'idle_time' => Yii::app()->params['chatIdleTime'],
			'db_time' => date("Y-m-d H:i:s", $time));
			$cmdQueue->put($this->chatContext, 'connectionLost', $params, $this->chatContext->client_id);
			$this->commandConnectionLost();
		}

		$allowUpdate = ($this->chatContext->userType == ChatContext::READER) && !($this->chatContext->pause);
		if($allowUpdate && !$session->sessionFinished())
		{
			$this->chatContext->updChatContext();
		}

		///time events
		$TimeEvent = new TimeEvents();
		$eventCode = $TimeEvent->check($this->chatContext);
		$this->processEvent($eventCode);
		$output = array_merge($output, array(
			'chat_time' => $this->chatContext->chatTimeRemaining(),
			'free_time' => $this->chatContext->freeTimeRemaining(),
			'total_time' => $this->chatContext->clientTotalTimeRemaining(),
			'eventCode' => $eventCode,
		));

		return json_encode($output);
	}
	
	/**
	 * Connection lost
	 */
	public function commandConnectionLost()
	{
		$this->chatContext->updChatContext();
		
		/// add end session command to reader & client	
		$params = array();

		$this->addSystemMessage($this->chatContext->client_id, Yii::t('lang', 'Connections_to_opponent_lost'));
		CommandsQueue::put($this->chatContext, 'closeSession', serialize($params), $this->chatContext->client_id);

		$this->addSystemMessage($this->chatContext->reader_id, Yii::t('lang', 'Connections_to_opponent_lost'));
		CommandsQueue::put($this->chatContext, 'closeSession', serialize($params), $this->chatContext->reader_id);
                
                //Reader go busy at 10 mins after end chatting
		$this->endSession(0);

	}
	
	/**
	 * End session
	 */
	public function commandEndSession()
	{
		$this->chatContext->updChatContext();
		
		/// add end session command to reader & client	
		$params = array();

		$this->addSystemMessage($this->chatContext->client_id, Yii::t('lang', 'End_session_by')." ".$this->chatContext->user->type);
		CommandsQueue::put($this->chatContext, 'closeSession', serialize($params), $this->chatContext->client_id);

		$params['addLost'] = 0;

		$this->addSystemMessage($this->chatContext->reader_id, Yii::t('lang', 'End_session_by')." ".$this->chatContext->user->type);
		CommandsQueue::put($this->chatContext, 'closeSession', serialize($params), $this->chatContext->reader_id);
                
                //Reader go busy at 10 mins after end chatting
		$this->endSession(0);

	}
	
	/**
	 * End session and return all time or custom time
	 */
	public function commandAddLostTime()
	{
		$this->chatContext->updChatContext();

		
		$params = array();
		
		$this->addSystemMessage($this->chatContext->client_id, Yii::t('lang', 'Reader_used_Add_Lost_Time'));
		CommandsQueue::put($this->chatContext, 'closeSession', serialize($params), $this->chatContext->client_id);

		/// for reader
		$params['addLost'] = 1;
                
		$this->addSystemMessage($this->chatContext->reader_id, Yii::t('lang', 'Reader_used_Add_Lost_Time'));
		CommandsQueue::put($this->chatContext, 'closeSession', serialize($params), $this->chatContext->reader_id);
		$this->endSession(1);
	}

	/**
	 * End session
	 */
	private function endSession($addLost) {
		$history = ChatHistory::getSession($this->chatContext->session_id);
		if(!$history->sessionFinished()) {
			$billing = new ChatBilling($this->chatContext);
			$nopayment = $billing->endSession($addLost, 0);
	
			/// Clear all flags on end session
			$TimeEvent = new TimeEvents();
			$TimeEvent->clearFlags($this->chatContext);
	
			$history->setFinishInitiator($this->chatContext->userType == ChatContext::CLIENT ? 'client' : 'reader');
			$this->chatContext->reader->setStatus('break10');
		}
	}

	/// ReaderChat functions

	/**
	 * Pause chat timer or wake up
	 */
	public function commandPause()
	{
		$chatStatus = $this->chatContext->pause;

		if($chatStatus)
		{
		    /// chat is on pause
		    /// wake up
		    $this->chatContext->pause = 0;

		    /// calc pause time
		    $pauseStart = strtotime($this->chatContext->pause_start);
		    $pauseEnd   = ChatHelper::time();
		    $pauseTime  = $pauseEnd - $pauseStart;
		    $this->chatContext->pause_time += $pauseTime;

		    $this->chatContext->pause_start = NULL;

		    $this->chatContext->save();
                  
		    ///add message
		    $this->addSystemMessage($this->chatContext->client_id,Yii::t('lang', 'Stand_by_is_OFF'), true);
		}
		else
		{
		    /// pause chat
		    $this->chatContext->pause = 1;
		    $this->chatContext->pause_start = ChatHelper::dbNow();
		    $this->chatContext->save();
		    
		    ///add message		  
		    $this->addSystemMessage($this->chatContext->client_id, Yii::t('lang', 'Stand_by_is_ON'), true);
		}
	}

	/**
	 * Ban the user on all site
	 */
	public function commandUserBan()
	{
		$Client_name = $this->chatContext->client->nickName;
                $client_login = $this->chatContext->client->login;
		$Reader_name = $this->chatContext->reader->nickName;
		$banReason = $this->parm->banReason;
		
		$upd = array();
		$upd[] = "UPDATE `band_list` set `Client_status` = 'banned_by_reader' WHERE `UserID` = '".$this->chatContext->client_id."'";
		$upd[] = "INSERT INTO `ban_info` (`user_id`, `username`, `who_banned`, `ip_who_banned`, `reason`, `date`) VALUES('".$this->chatContext->client_id."', '".$Client_name."', '".$Reader_name."', '".$_SERVER['REMOTE_ADDR']."', '".$banReason."', NOW())";
		$upd[] = "UPDATE `T1_1` set `balance` = 0 WHERE `rr_record_id` = ".$this->chatContext->client_id;
		$upd[] = "DELETE FROM `transactions` WHERE `UserID` = ".$this->chatContext->client_id;
		$upd[] = "DELETE FROM `reader_payment` WHERE `Session_numb` = '".$this->chatContext->session_id."'";
		$upd[] = "UPDATE `remove_freetime` SET `Seconds` = '0',`Total_sec` = '0' WHERE `ClientID` =".$this->chatContext->client_id;
		
		$time_now = date("M j, Y h:i a", time());
		$text2send = date("d-m-Y h:i a", time())."/SID# ".$this->chatContext->session_id."<br><b>$time_now<br></b><br><b>User</b>:<b><font color='#FF0000'> $client_login / $Client_name </font></b> was banned by $Reader_name<br><br>".$banReason;
		$subject = "PSYCHAT - Client ".$Client_name." was banned by". $Reader_name;
		PsyMailer::send(ChatHelper::adminEmail(), $subject, $text2send);
		
		$text2send_db = addslashes($text2send);
		$upd[]= "INSERT INTO `messages` (`ID`, `From_user`, `From_name`, `To_user`, `Date`, `Subject`, `Body`, `Status`, `Read_message`)
			VALUES('', '1', 'System', '1', now(), '$subject', '$text2send_db', 'ok', 'no')";
		
		$connection = Yii::app()->db;
		foreach ($upd as $sql)
		{
			$command = $connection->createCommand($sql);
			$command->execute();
			unset($command);
		}
		
		$this->commandEndSession();
		return true;
	}

	/**
	 * After such ban the user can't communicate with the reader any more
	 */
	public function commandPersonalBan()
	{
		$Client_name = $this->chatContext->client->nickName;
		$Reader_name = $this->chatContext->reader->nickName;

		$connection = Yii::app()->db;
		$sql = "INSERT INTO `userban` (reader_id, user_id, reason) VALUES (".$this->chatContext->reader_id.", ".$this->chatContext->client_id.", '".$this->parm->banReason."')";
		$command=$connection->createCommand($sql);
		$command->execute();
		
		$subject = " ".$Reader_name."(reader) PB:".$Client_name."(client)";
		$email_text = " ".$Reader_name."(reader) PB:".$Client_name."(client) ".date("d-m-Y h:i a", time())."/SID# ".$this->chatContext->session_id." \n";
		PsyMailer::send($this->chatContext->reader->emailaddress, $subject, $email_text);
		PsyMailer::send(ChatHelper::adminEmail(), $subject, $email_text);

		$this->commandAddLostTime();
		return true;
	}

	public function commandTyping()
	{
		$params = array (
			'nickName' => $this->chatContext->user->nickName,
		);
		CommandsQueue::put($this->chatContext, 'showTyping', serialize($params) , $this->chatContext->opponent->user_id);
	}
	
	///Client side functions

	/**
	 * Add more time to current chat session
	 */
	public function commandAddTime()
	{
	    if($this->parm->time > 0) {
	        if($this->chatContext->addTime($this->parm->time)) {
		        $this->addSystemMessage($this->chatContext->reader_id, Yii::t('lang', 'Client_added') . " ".($this->parm->time/60)." ". Yii::t('lang', 'minutes'));
				/// $this->addSystemMessage($this->chatContext->client_id, "You added ".($this->parm->time/60)." minutes");
	        }	        
	    }
	}

	public function processEvent($eventCode)
    {
    	if($eventCode)
    	{
		
	    	switch ($eventCode)
	    	{
	    		case TimeEvents::ONE_MINUT_LEFT:
					$text = "*** ".Yii::t('lang', 'You_have_one_minute_remaining').". ***";
					$this->addSystemMessage($this->chatContext->client_id, $text);

					if ($this->chatContext->clientTotalTimeRemaining() > 90)
					{
						$text = "*** You can add more time  ***";
						$this->addSystemMessage($this->chatContext->client_id, $text);
					}
					
					$text = "*** ".Yii::t('lang', 'One_minute_emaining_for_client').". ***";
			    	$this->addSystemMessage($this->chatContext->reader_id, $text);					
				break;
	    		case TimeEvents::FREE_TIME_1_MIN:
	    			$text = (RemoveFreetime::isDeposited($this->chatContext->client_id)) ?
                                        "*** ". Yii::t('lang', 'Your_free_time_ends_in_1_minute_cut')."  ***"
                                        : "*** ". Yii::t('lang', 'Your_free_time_ends_in_1_minute')."  ***";
			    	$this->addSystemMessage($this->chatContext->client_id, $text);
    			break;
	    		case TimeEvents::PAID_TIME_STARTED:
	    			$text = "*** ".Yii::t('lang', 'Paid_time_has_now_started')." ***";
			    	$this->addSystemMessage($this->chatContext->reader_id, $text);
			    	$this->addSystemMessage($this->chatContext->client_id, $text);
    			break;
				case TimeEvents::PAID_TIME_END :
					$text = "*** ".Yii::t('lang', 'Your_session_is_over').". ***";
			    	$this->addSystemMessage($this->chatContext->reader_id, $text);
			    	$this->addSystemMessage($this->chatContext->client_id, $text);

	    		default: 
	    			$text = "";
	    		break;
	    	}
    	}
    	else
    		return false;
    }

	/**
	 * Function adds info to debug table  about ajax error happen
	 *
	 */
	public function commandAjaxError()
	{
		DebugExtra::addAjaxErrorInfo($this->chatContext->session_id, $this->parm->notes);
	}
	
	/**
     * Add info about user chat add funds from active chatting
     *
     * @return string Reader name to add transaction info (BMT)
     */   
	public function registerBMT($minutes)
	{		
		// Add info about funds in chatting time
		BmtStat::add($this->chatContext->client_id, $minutes);
		
		// Add balance minutes to current chat window
		$this->chatContext->addBMT($minutes);

		/// system messages to client and reader

 		$message = "*** The client purchased ".$minutes." mins ***";
		$this->addSystemMessage($this->chatContext->reader_id, $message, false);

		$message = "*** You purchased ".$minutes." mins ***";
		$this->addSystemMessage($this->chatContext->client_id, $message, true);
	}
	
	/**
     * output startup chat notes
     *
     * @return void
     */   
    public function outputStartupNotes() {
    	/// get notes about client
    	$notes = NotesClients::getNotes($this->chatContext->client_id);
        
    	$extra_notes = '';
    	foreach($notes as $row)
    	{
    		if($row instanceof NotesClients)
    		{
    			$extra_notes .= $row->note.'<br>';

    			NotesClients::updateNotes($this->chatContext->client_id);
    		}
    	}
        
    	if(strlen($extra_notes) > 0)
    	{
    		$extra_notes = preg_replace("/\n|\r/", " ", $extra_notes);

    		$this->addSystemMessage($this->chatContext->reader_id, $extra_notes);
    	}
    }

}
?>