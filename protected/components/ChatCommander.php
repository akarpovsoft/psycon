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
			/// add to chat transcripsts
			ChatTranscripts::addMessage($this->chatContext, "[".date("H:i")."] ".$this->chatContext->user->nickName.": ".$this->parm->message);
			
			/// inc client/reader msg counter
			$this->chatContext->increaseMsgCount();

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
		/// add msg to chat transcripts
		if ($addToTranscripts)
			ChatTranscripts::addMessage($this->chatContext, "[".date("H:i")."] "."System".": ".$message);

		/// put command to queue		
		$params = array('message' => strlen($message) > 0 ? $message : '' , 'style' => 'redLine', 'system' => 1);		     
        CommandsQueue::put($this->chatContext, 'text', serialize($params), $toUserId);
	}
	
	/**
	 * Check for new comands
	 */
	public function commandCheck()
	{
		ob_start();
		
		/// select all from command pull
		$i=0;
		while($i<4)
		{
			$commands = CommandsQueue::get($this->chatContext);
			if (count($commands) > 0)
			{
				break;
			}
			echo ' ';
			ob_flush();
			flush();
			ob_end_flush();
			sleep(5);
			ob_start();
			$i++;
		}
        
		$output = array();
		/// delete all from command pull
		if (count($commands) > 0) {
			CommandsQueue::clear($this->chatContext, $this->chatContext->user->user_id);


			foreach ($commands as $command)
			{
				$output[] = array(
					'command' => $command['command'],
					'params' => unserialize($command['params']),
				); 
			}
		} else {
			$output = array("status" => "ok");
		}
		
		
		return json_encode($output);
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
		    $pauseEnd   = time();
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
		    $this->chatContext->pause_start = Util::dbNow();
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
		$text2send = date("d-m-Y h:i a", time())."/SID# ".$this->chatContext->session_id."<br><b>$time_now<br></b><br><b>User</b>:<b><font color='#FF0000'> $Client_name </font></b> was banned by $Reader_name<br><br>".$banReason;
		$subject = "PSYCHAT - Client ".$Client_name." was banned by". $Reader_name;
		PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $text2send);
		
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
		PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $email_text);

		$this->commandAddLostTime();
		return true;
	}

	/**
	 * End session
	 */
	public function commandEndSession()
	{
		$this->chatContext->updChatContext();
		
		/// set finish initiator
		if($this->chatContext->userType == ChatContext::CLIENT)
			$this->chatContext->setFinishInitiator('client');
		else
			$this->chatContext->setFinishInitiator('reader');
		
		/// add end session command to reader & client	
		$params = array();

		$this->addSystemMessage($this->chatContext->client_id, Yii::t('lang', 'End_session_by')." ".$this->chatContext->user->type);
		CommandsQueue::put($this->chatContext, 'closeSession', serialize($params), $this->chatContext->client_id);

		$params['addLost'] = 0;
		$this->addSystemMessage($this->chatContext->reader_id, Yii::t('lang', 'End_session_by')." ".$this->chatContext->user->type);
		CommandsQueue::put($this->chatContext, 'closeSession', serialize($params), $this->chatContext->reader_id);
	}

	/**
	 * End session and return all time or custom time
	 */
	public function commandAddLostTime()
	{
		$this->chatContext->updChatContext();

		/// set finish initiator
		if($this->chatContext->userType == ChatContext::CLIENT)
			$this->chatContext->setFinishInitiator('client');
		else
			$this->chatContext->setFinishInitiator('reader');
		
		$params = array();
		
		$this->addSystemMessage($this->chatContext->client_id, Yii::t('lang', 'Reader_used_Add_Lost_Time'));
		CommandsQueue::put($this->chatContext, 'closeSession', serialize($params), $this->chatContext->client_id);

		/// for reader
		$params['addLost'] = 1;
		
		$this->addSystemMessage($this->chatContext->reader_id, Yii::t('lang', 'Reader_used_Add_Lost_Time'));
		CommandsQueue::put($this->chatContext, 'closeSession', serialize($params), $this->chatContext->reader_id);
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


	/**
	 * Performs "tick" operation to check time spent in chat
	 * @return <array>
	 */
	public function commandTick()
	{
		// write to log
		$sessionLog = new ChatEventsLogger();
		$sessionLog->userType = ($this->chatContext->userType == ChatContext::CLIENT)?  'Client' : 'Reader';
		$sessionLog->init(Yii::app()->getRuntimePath().DIRECTORY_SEPARATOR.'chat', $this->chatContext->session_id.".txt");
		$sessionLog->addLogMsg($command, '', 'tick, begin');


		$allowUpdate = ($this->chatContext->userType == ChatContext::READER) && !($this->chatContext->pause);

		///update user ping
		if($this->chatContext->userType == ChatContext::CLIENT)
		{
			$this->chatContext->client_ping = Util::dbNow();
		}
		elseif($this->chatContext->userType == ChatContext::READER)
		{
		    // Update T1_12
            $sessionData=ChatSession::model()->findByAttributes(array('rr_record_id'=>$this->chatContext->session_id ));
            $sessionData->laststatusupdate= Util::dbNow();
            $sessionData->update();
             
		    $this->chatContext->reader_ping = Util::dbNow();
		}
		$this->chatContext->save();

		$sessionLog->addLogMsg($command, '', 'tick, [1]');
		///check userping
		//client idle time out
		if(strtotime($this->chatContext->client_ping) + Yii::app()->params['chatIdleTime'] < time())
		{
			//show alert to opponent(Connections to opponent lost)
			$cmdQueue = new CommandsQueue();
			$cmdQueue->put($this->chatContext, 'connectionLost', NULL, $this->chatContext->reader_id);
			$this->commandEndSession();
		}
		//reader idle time out
		else if(strtotime($this->chatContext->reader_ping) + Yii::app()->params['chatIdleTime'] < time())
		{
			//show alert to opponent(Connections to opponent lost)
			$cmdQueue = new CommandsQueue();
			$cmdQueue->put($this->chatContext, 'connectionLost', NULL, $this->chatContext->client_id);
			
			/// set finish initiator
			if($this->chatContext->userType == ChatContext::CLIENT)
				$this->chatContext->setFinishInitiator('client');
			else
				$this->chatContext->setFinishInitiator('reader');
		}
		
		if($allowUpdate)
		{
			$this->chatContext->updChatContext();
		}
        
		///time events
		$TimeEvent = new TimeEvents();
		$eventCode = $TimeEvent->check($this->chatContext);
		$this->processEvent($eventCode);

		$output = array(
			'chat_time' => $this->chatContext->chatTimeRemaining(),
			'free_time' => $this->chatContext->freeTimeRemaining(),
			'total_time' => $this->chatContext->clientTotalTimeRemaining(),
			'eventCode' => $eventCode,
		);


		$sessionLog->addLogMsg($command, json_encode($output), 'tick, end');
		$sessionLog->save();
		return json_encode($output);
	}
	
	public function processEvent($eventCode)
    {
    	if($eventCode)
    	{
			// write to log
			$sessionLog = new ChatEventsLogger();
			$sessionLog->userType = ($this->chatContext->userType == ChatContext::CLIENT)?  'Client' : 'Reader';
			$sessionLog->init(Yii::app()->getRuntimePath().DIRECTORY_SEPARATOR.'chat', $this->chatContext->session_id.".txt");
		
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
	    			$text = "*** ". Yii::t('lang', 'Your_free_time_ends_in_1_minute')."  ***";
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

			$sessionLog->addLogMsg("Event message: " . $text, '', 'put, to user '.$this->chatContext->reader_id);
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

}
?>