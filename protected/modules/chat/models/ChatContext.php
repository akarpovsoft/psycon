<?php

class ChatContext extends CActiveRecord
{
    /// fields from db table
    public  $sessionKey;
    public  $userType;
	
//@TODO : Remove these values
    public  $paidTimeRemaining;
    public  $freeTimeRemaining;
    ///public  $freeTime;
    public  $pause;
    /// public  $totalTime;

    /// objects from readers and clients models
    public  $reader;
    public  $client;
    public  $user;
    public  $opponent;
    /**
     * This property used if chat ends.
     * When client want to buy more time, this property will be add to current chat duration
     * This basically time of BMT process (while client enter some data in add funds form etc.)
     */
    public $extraBMTTime;
    /**
     *  remove free time submodel instance
     */
	private $_freeTime;
	/**
	 * Debug extra submodel instance
	 */
	private $_history;
	
    const CLIENT = 1;
    const READER = 2;
    
    const MAX_HP_TIME =10;

    public function tableName()
    {
        return 'chat_context';
    }

    public static function model($className=__CLASS__)
    {
		return parent::model($className);
    }

    public function addBMT($minutes) {
    	$this->_history()->addBMT($minutes);
		$this->bal_start += $minutes *60 + $this->getExtraBMTTime();	
		$this->save();
    }
    
    public function getTimer($sessionKey)
    {
        $tmp = $this->findByPk($sessionKey);
        $this->sessionKey           = $tmp['session_key'];
        $this->paidTimeRemaining    = $tmp['balance'];
        $this->freeTimeRemaining    = $tmp['free_time'];
        $this->totalTime            = $tmp['bal_total'];

        return $this;
    }

    public static function convertRequestToChat($request) {
    	$chatContext = new ChatContext();
		$data = self::model()->find(array(
			'condition' => 'session_id = :session_id',
			'params' =>array(':session_id' => $request->getId()),
		));

		if(!is_object($data)) {
	    	$chatContext->add($request->getId(), $request->getClientId(), $request->getExpertId(), $request->duration);
	    	$sessionKey = $chatContext->session_key;
		} else 
	    	$sessionKey = ChatContext::getSessionKey($request->getId());
        return self::getContext($sessionKey, ChatContext::READER);
    }
    
    public function add($session_id, $client_id, $reader_id, $bal_start)
    {
        $this->session_id  = $session_id;
        $this->session_key = md5($session_id.time());
        $this->client_id = $client_id;
        $this->reader_id = $reader_id;
        $this->chat_start = ChatHelper::dbNow();
    	$this->client_ping = ChatHelper::dbNow();
    	$this->reader_ping = ChatHelper::dbNow();
        $this->bal_start = $bal_start;
        $this->balance =  $bal_start;
        $this->free_time = 0;
        
        $this->save();
    }

	public function refreshExpertStatus()
	{
	    $this->reader->setStatus('busy');
	}
    
    /**
     * Ping chat
     */
	public function ping()
	{
		$time = date("Y-m-d H:i:s", ChatHelper::time()); // ChatHelper::dbNow();
		$field = ($this->userType == ChatContext::CLIENT)?'client_ping':'reader_ping';
		$this->$field = $time;
		$res = $this->update(array($field));
		if($this->userType == ChatContext::READER)
			$this->reader->setStatus('busy');
//		ChatHelper::log("user type = ".$this->chatContext->userType." cp = ".$this->chatContext->client_ping." rp = ".$this->chatContext->reader_ping);
		
	    // Update T1_12
	    // @TODO : remove this later when we avoid T1_12 at all
	    $sessionData=ChatSession::model()->findByAttributes(array('rr_record_id'=>$this->session_id ));
	    $sessionData->laststatusupdate= ChatHelper::dbNow();
	    $sessionData->update();

	}
    
	public function updChatContext()
	{
		$history = ChatHistory::getSession($this->session_id);

		if(!$history->sessionFinished()) 
		{
			if($this->freeTimeOnStart() > 0)
			{
				$this->reduceFreeTime();
				$history->setFreeTime($this->freeTimePassed());
			} 

			if($this->hpTimeOnStart() > 0 && $this->freeTimePassed()>= $this->freeTimeOnStart()  && $this->hpTimePassed()<= (ChatContext::MAX_HP_TIME*60))
			{
				$this->reduceHPTime();
				$history->setHPTime($this->hpTimePassed());
			}
			
			///update history
			$history->setDuration($this->chatDuration());
			$history->setPaidTime($this->paidChatDuration());
			$history->setDueToExpert($history->dueToReader());

			$history->save();

			ReaderPayment::saveSum($this->session_id, $history->dueToReader()); // put new sum according to time in chat

			/// update context
			$this->bal_ping    = ChatHelper::dbNow();
			$this->balance     = $this->chatTimeRemaining();
			$this->free_time   = $this->freeTimeRemaining();

			/// update user
			$this->client->setBalance(round($this->clientTotalTimeRemaining()/60, 2));

			$this->save(false, array('bal_ping', 'balance', 'free_time'));
		}
	}

    public static function getSessionKey($sessionId)
    {
        $tmp = self::model()->find("session_id = :session_id", array(':session_id' => $sessionId));
        return $tmp['session_key'];
    }

	 public static function getSessionId($sessionKey)
    {
        $tmp = self::model()->find("session_key = :session_key", array(':session_key' => $sessionKey));
        return $tmp['session_id'];
    }


	public static function getChatContextByReader($readerId)
	{
		return self::model()->find("reader_id = ':reader_id'", array(':reader_id' => $readerId));
	}

        /**
         * Check, if Reader is chatting now
         * If parameter model_return is true = return object model
         * Else return boolean
         * 
         * @param int $readerId
         * @param bool $model_return 
         * @return mixed 
         */
	public static function readerBusy($readerId, $model_return = false)
	{
		$c = new CDbCriteria;
                $c->condition = '`reader_id` = :reader_id and (DATE_ADD(reader_ping, interval 12 second) > now())';
                $c->params = array(':reader_id' => $readerId);
                $model = self::model()->find($c);
		
                if($model_return)
                    return $model;
                else
                    return (!is_null($model));
	}
        
	
    /**
     * Get session data
     * @param unknown_type $sessionKey
     * @param unknown_type $userType
     * @throws Exception
     */
	public static function getContext($sessionKey, $userType)
	{
		$data = self::model()->find(array(
			'condition' => 'session_key = :sessionKey',
			'params' =>array(':sessionKey' => $sessionKey),
		));

		if(!is_object($data))
			throw new Exception("Wrong session id");
		$data->sessionKey = $data->session_key;

		if( $userType == self::CLIENT )
		{
			$data->client = $data->user = ChatHelper::getClient($data->client_id);
			$data->reader = $data->opponent = ChatHelper::getExpert($data->reader_id);
			$data->opponent->nickName = $data->opponent->getScreenName();
			$data->user->nickName = $data->user->getScreenName();
		}
		else if($userType == self::READER)
		{
			$data->reader = $data->user = ChatHelper::getExpert($data->reader_id);
			$data->client = $data->opponent = ChatHelper::getClient($data->client_id);
			$data->opponent->nickName = $data->opponent->getScreenName();
			$data->user->nickName = $data->user->getScreenName();
		}

		$data->userType = $userType;
		return $data;
    }
    
////////////////////////////////////////////////////////    
    /**
     * Free time remaining for current chat session
     *
     */
    public function freeTimeRemaining() {
        $chatDuration=$this->chatDuration();

		$freeTimeRemaining = ($this->_freeTime()->Total_sec - $chatDuration>0 )?$this->_freeTime()->Total_sec - $chatDuration:0;
		return  $freeTimeRemaining;
    }
    
    /**
     * Free time passed for current chat session
     *
     */
    public function freeTimePassed() {
        return $this->_freeTime()->Total_sec-$this->freeTimeRemaining();
    }
    
    public function freeTimeOnStart() {
        return $this->_freeTime()->Total_sec;
    }
    
    public function reduceFreeTime() {
       $this->_freeTime()->Seconds=$this->freeTimeRemaining();
       $this->_freeTime()->save();
    }
    
	public function fixFreeTime()
	{
		$this->_freeTime()->Total_sec = $this->_freeTime()->Seconds;
		$this->_freeTime()->save();
	}

	/**
	 * return free time when nopayment
	 *
	 * @param mixed $clientId client id
	 * @return bool 
	 *
	 */
	public function returnFreeTime()
	{
		$this->_freeTime()->Seconds = $this->freeTimeOnStart();
		$this->_freeTime()->save();
	}
	
//// Half paid time ////////////
   
    public function hpTimeOnStart() {
        return $this->_freeTime()->half_seconds_total;
    }

    public function reduceHPTime() {
       $this->_freeTime()->half_seconds=$this->hpTimeRemaining();
       $this->_freeTime()->save();
    }
    
    public function hpTimePassed() {
        return $this->_freeTime()->half_seconds_total-$this->hpTimeRemaining();
    }
    /**
     * Free time remaining for current chat session
     *
     */
    public function hpTimeRemaining() {
        $chatDuration=$this->chatDuration();

		$hpTimeRemaining = ($this->_freeTime()->half_seconds_total - $chatDuration>0 )?$this->_freeTime()->half_seconds_total - $chatDuration:0;
		return  $hpTimeRemaining;
    }
    
	/**
	 * return half paid time when nopayment
	 *
	 * @param mixed $clientId client id
	 * @return bool 
	 *
	 */
	public function returnHPTime()
	{
		$this->_freeTime()->half_seconds = $this->hpTimeOnStart();
		$this->_freeTime()->save();
	}
	
	public function fixHPTime()
	{
		$this->_freeTime()->half_seconds_total = $this->_freeTime()->half_seconds;
		$this->_freeTime()->save();
	}

	
    
///////////////////////////////////////
	
///////////////////////////////////////////////////    
    /**
     * Duration for current chat if chat is going on
     *
     */
    public function chatDuration() {
		$time = ChatHelper::time();
    	
		$startTime = $this->chat_start;
		
        $extraPausedTime=0;
		if($this->pause && $this->pause_start!=null) {
            $pauseStart = strtotime($this->pause_start);
		    $extraPausedTime= $time - $pauseStart;
		}
		
		$chatDuration = $time - strtotime($startTime) - $this->pause_time - $extraPausedTime;	

		if($chatDuration > $this->bal_start)
                {
                    $this->extraBMTTime = $chatDuration - $this->bal_start;
                    $chatDuration = $this->bal_start;
                }
			
		return $chatDuration;
    }


    
    /**
     * Paid duration for current chat
     *
     */
    public function paidChatDuration() {
        $chatDuration=$this->chatDuration();
        $hpTime = ($this->hpTimeOnStart()> (ChatContext::MAX_HP_TIME*60)?(ChatContext::MAX_HP_TIME*60):$this->hpTimeOnStart());
		$paidChatDuration = ($chatDuration - $this->freeTimeOnStart() - $hpTime > 0) ? $chatDuration - $this->freeTimeOnStart() - $hpTime : 0;
		return $paidChatDuration;
    }

  
    /**
     * time in chat remaining
     *
     */
    public function chatTimeRemaining() {
        return $this->bal_start - $this->chatDuration();
    }
    
    /**
     * Return BMT delay time
     * 
     */
    public function getExtraBMTTime() {
        $this->chatDuration();
        return (!$this->extraBMTTime) ? 0 : $this->extraBMTTime;
    }
    /**
     * Client total time
     *
     * @return int total time on balance
     */
    public function clientTotalTimeRemaining() {
		$totalTime = $this->_history()->getBalaceOnStart() + $this->_history()->bmtAmount()*60 - $this->chatDuration();
               
		return $totalTime;
    }
    
    /**
     * Add time to chat
     *
     * @param int $time time in seconds
     */
    public function addTime($time) {
        if($time <= ($this->clientTotalTimeRemaining() -$this->chatTimeRemaining())) {
            $this->bal_start += $time;
            $this->save(false, array('bal_start'));
            return true;
        }
        return false;
    }
    
    
    /**
     * return free time submodel
     *
     * @return RemoveFreetime
     */
    private function _freeTime() {
        if(!is_object($this->_freeTime))
            $this->_freeTime = RemoveFreetime::getFreeTime($this->client_id);
        return $this->_freeTime;
    }
    
    /**
     * return debug_extra submodel
     *
     * @return DebugExtra
     */
    private function _history() {
        if(!is_object($this->_history))
        		$this->_history = ChatHistory::getSession($this->session_id);
        
        return $this->_history;
    }

    /**
     * Delete old sessions( older than 3 hours) 
     */
    public function deleteOldSessions()
    {
    	self::model()->deleteAll('DATE_ADD( chat_start, INTERVAL 3 HOUR ) <  now()', array());
    }
    /**
     * Delete all sessions with $readerId reader
	 *
     */
	public function deleteByReaderId()
	{	
		self::model()->deleteAll("reader_id = :reader_id AND session_id <> :session_id", array(':reader_id' => $this->reader_id, ':session_id' => $this->session_id));
	}
    
    /**
     * cleanup old chats data
     */
    public function cleanup()
    {
    	//@TODO : remove this when we abuse T1_12 at all
    	ChatSession::deleteOldSessions();
    	ChatSession::deleteByReaderId($this->reader_id);

    	/// Delete old chat context records
    	$this->deleteOldSessions();
    	
    	/// Delete all records from chat context with this reader
    	$this->deleteByReaderId();

    	//Remove Previous BMT
    	$bmt = BmtStat::deleteByClientId($this->client_id);
    	
    }
    
    /**
         * Starts chat
         *
         * @param <integer> $session_id
    */
    public static function startChat($request)
    {
    	/// insert new chat context record
    	$chatContext = ChatContext::convertRequestToChat($request);
    	/// Delete old chat context records
    	$chatContext->cleanup();
    	$ChatCommander = new ChatCommander($chatContext);

    	/// if new client
    	if($chatContext->client->first_reading != 'No')
    	{
    		$ChatCommander->addSystemMessage($chatContext->reader_id, "*** New Client! Never Chatted Before ***");
    		$chatContext->client->first_reading = 'No';
    		$chatContext->client->save(false, array('first_reading'));

    	}
    	/// CHECK IS IT FIRST TIME READER READ FOR THIS USER
    	if(ChatHistory::chekFirstTimeReaderRead($chatContext->client_id, $chatContext->reader_id))
    	{
    		$ChatCommander->addSystemMessage($chatContext->reader_id,
    		"NEW CLIENT: CHECK NAME/DOB- ban if necessary!"
    		);
    	}

    	if(RemoveFreetime::userHasFreeTime($chatContext->client_id))
    	{
    		if(RemoveFreetime::isDeposited($chatContext->client_id)) { // payment already deposited
	    		$ChatCommander->addSystemMessage($chatContext->reader_id,
	    		"CLIENT HAS ".round($chatContext->freeTimeOnStart()/60, 1)." mins of FREE CHAT TIME"
	    		);
	    		$ChatCommander->addSystemMessage($chatContext->client_id,
	    		"You have ".round($chatContext->freeTimeOnStart()/60, 1)." mins of FREE CHAT TIME"
	    		);
    			
    		} else {
	    		$ChatCommander->addSystemMessage($chatContext->reader_id,
	    		"FREEBIE CLIENT: NO MORE THAN ".round($chatContext->freeTimeOnStart()/60, 1)." mins- UNLESS THEY BMT!"
	    		);
	    		$ChatCommander->addSystemMessage($chatContext->client_id,
	    		"You have ".round($chatContext->freeTimeOnStart()/60, 1)." mins of FREE CHAT TIME - No obligation to purchase more time- unless you want to..."
	    		);
    		}
    	}
    	if($chatContext->hpTimeOnStart()>5) { // Client has half paid time
	    		$ChatCommander->addSystemMessage($chatContext->reader_id,
	    		"CLIENT HAS ".round($chatContext->hpTimeOnStart()/60, 1)." mins of TIME PAID IN HALF!"
	    		);
    		
    	}
    	
    	$ChatCommander->outputStartupNotes();

		$chatLocation = ($chatContext->client->site_type =='CA') ?'Canada':'International';    	
    	/// insert into history
	  	$subject = urldecode($request->subject);

    	ChatHistory::startChat($chatContext->session_id, $subject, $chatContext->client, $chatContext->reader, $chatLocation, $request->chat_type, $chatContext->freeTimeOnStart());
		ReaderPayment::startChat($chatContext->reader_id, $chatContext->session_id, $chatLocation);

        // Insert to debug table
                $debChat = new ChatDebug();
                $debChat->session_id = $chatContext->session_id;
                $debChat->bal_start = $chatContext->bal_start;
                $debChat->old_balance = $chatContext->client->getBalance();
                $debChat->save();

    	return $chatContext->sessionKey;
    }


	public static function startChatCheck($requestId){
		$c = new CDbCriteria;
		$c->condition = '`session_id` = :request_id and (DATE_ADD(reader_ping, interval 10 second) > now())';
		$c->params = array(':request_id' => $requestId);
		$model = self::model()->find($c);
		return is_object($model) ? $model->session_key : false;
	}
   
}
?>