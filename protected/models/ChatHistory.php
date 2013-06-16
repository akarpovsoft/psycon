<?php

class ChatHistory extends CActiveRecord
{
    const PER_PAGE = 20;
    
    public $total; // To receive total duration time for client
	/**
	 * The followings are the available columns in table 'History':
	 * @var integer $Session_id
	 * @var integer $Client_id
	 * @var string $Client_name
	 * @var integer $Reader_id
	 * @var string $Reader_name
	 * @var string $Subject
	 * @var string $Date
	 * @var integer $Duration
	 * @var string $Free_time
	 * @var string $Paid_time
	 * @var string $Returned_time
	 * @var string $Due_to_reader
	 * @var string $Rate
	 * @var string $Affiliate
	 * @var string $ChatLocation
	 * @var integer $Finished
	 * @var integer $Status
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return PsyStaticDataProvider::getTableName('chat_history');
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Session_id, Client_id, Reader_id, Duration, Finished, Status', 'numerical', 'integerOnly'=>true),
			array('Client_name, Reader_name', 'length', 'max'=>100),
			array('Subject', 'length', 'max'=>150),
			array('Free_time, Paid_time, Returned_time, Due_to_reader, Rate', 'length', 'max'=>10),
			array('Affiliate', 'length', 'max'=>20),
			array('ChatLocation', 'length', 'max'=>13),
			array('Date', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array('users' => array(self::BELONGS_TO, 'users', 'Client_id'),
                             'reader_payment' => array(self::HAS_ONE, 'ReaderPayment', 'Session_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Session_id' => 'Session',
			'Client_id' => 'Client',
			'Client_name' => 'Client Name',
			'Reader_id' => 'Reader',
			'Reader_name' => 'Reader',
			'Subject' => 'Subject',
			'Date' => 'Date',
			'Duration' => 'Duration (mins)',
			'Free_time' => 'Free Time',
			'Paid_time' => 'Paid Time (mins)',
			'Returned_time' => 'Returned Time',
			'Due_to_reader' => 'Due To Reader',
			'Rate' => 'Rate',
			'Affiliate' => 'Affiliate',
			'ChatLocation' => 'Chat Location',
			'Finished' => 'Finished',
			'Status' => 'Status',
		);
	}

        /**
         * Returns a list of client's chat history records
         * Using for users search
         *
         * @param int $reader_id
         * @return object CActiveDataProvider
         */
        public static function getHistoryListForClient($reader_id, $condition = null){
            $criteria = new CDbCriteria;
            if(is_null($condition))
                $criteria->condition = '`Client_id` = :client_id AND ( `del` = 0 OR `del` IS NULL)';
            else {
                $con_str = '`Date` REGEXP "^'.$condition['year'];
                if(isset($condition['month']))
                    $con_str .= '-'.$condition['month'].'.*"';
                else
                    $con_str .= '.*"';
                if($condition['day'] == '1-15')
                    $con_str .= ' AND DAYOFMONTH(`Date`) > 1 AND DAYOFMONTH(`Date`) < 15';
                else if($condition['day'] == '16-31')
                    $con_str .= ' AND DAYOFMONTH(`Date`) > 15 AND DAYOFMONTH(`Date`) < 32';

                $criteria->condition = '`Client_id` = :client_id AND ( `del` = 0 OR `del` IS NULL) AND '.$con_str;
            }
            $criteria->params = array(':client_id' => $reader_id);
            $criteria->order = '`Date` DESC';
            
            $dataProvider=new CActiveDataProvider('ChatHistory', array(
                        'criteria'=>$criteria,
                        'sort' => array('attributes' =>  array('Date', 'Subject', 'Reader_name', 'Duration', 'Paid_time')),
                        'pagination'=> array(
                                'pageSize'=> self::PER_PAGE,
                                'pageVar' => 'history_page',
                                'params' => array(
                                    'paging' => 1,
                                    'year' => $condition['year'],
                                    'month' => $condition['month'],
                                    'day' => $condition['day']
                                )
                        )
            ));
            return $dataProvider;
        }

        /**
         * Returns a list of reader's chat history records
         * Using for users search
         *
         * @param <int> $reader_id
         * @param <array> $condition
         * @return <CActiveDataProvider>
         */
        public static function getHistoryListForReader($reader_id, $condition = null){
            $criteria = new CDbCriteria;
            if(is_null($condition))
                $criteria->condition = '`Reader_id` = :reader_id';
            else {
                $con_str = '`Date` REGEXP "^'.$condition['year'];
                if(isset($condition['month']))
                    $con_str .= '-'.$condition['month'].'.*"';
                else
                    $con_str .= '.*"';
                if($condition['day'] == '1-15')
                    $con_str .= ' AND DAYOFMONTH(`Date`) > 1 AND DAYOFMONTH(`Date`) < 15';
                else if($condition['day'] == '16-31')
                    $con_str .= ' AND DAYOFMONTH(`Date`) > 15 AND DAYOFMONTH(`Date`) < 32';

                $criteria->condition = '`Reader_id` = :reader_id AND ( `del` = 0 OR `del` IS NULL) AND '.$con_str;
            }
            $criteria->params = array(':reader_id' => $reader_id);
            $criteria->order = '`Date` DESC';
            
            $dataProvider=new CActiveDataProvider('ChatHistory', array(
                        'criteria'=>$criteria,
                        'sort' => array('attributes' =>  array('Date', 'Subject', 'Client_name', 'Duration', 'Paid_time', 'Session_id', 'Reader_name', 'Free_time', 'Due_to_reader')),
                        'pagination'=> array(
                                'pageSize'=> self::PER_PAGE,
                                'pageVar' => 'history_page',
                                'params' => array(
                                    'paging' => 1,
                                    'year' => $condition['year'],
                                    'month' => $condition['month'],
                                    'day' => $condition['day']
                                )
                        )
            ));
            return $dataProvider;
        }

        /**
         * Return a total time of client's chat sessions
         *
         * @param integer $client_id
         * @return float
         */
        public static function getTotalTimeDuration($client_id, $condition = null){
            $sql = 'SELECT SUM(Duration) as `Total` FROM History WHERE `Client_id` = '.$client_id.' AND ( `del` = 0 OR `del` IS NULL )';
            if(isset($condition)){
                $sql .= ' AND `Date` REGEXP "^'.$condition['year'];
                if(isset($condition['month']))
                    $sql .= '-'.$condition['month'].'.*"';
                else
                    $sql .= '.*"';
                if($condition['day'] == '1-15')
                    $sql .= ' AND DAYOFMONTH(`Date`) > 1 AND DAYOFMONTH(`Date`) < 15';
                else if($condition['day'] == '16-31')
                    $sql .= ' AND DAYOFMONTH(`Date`) > 15 AND DAYOFMONTH(`Date`) < 32';
            }
            $connection=Yii::app()->db;
            $command=$connection->createCommand($sql);
            $res = $command->query();
            return $res;
        }

        public static function getReaderTotalTimeDuration($reader_id, $condition = null){
            $sql = 'SELECT SUM(Duration) as Total FROM History WHERE `Reader_id` = '.$reader_id.' AND ( `del` = 0 OR `del` IS NULL )';
            if(isset($condition)){
                $sql .= ' AND Date REGEXP "^'.$condition['year'];
                if(isset($condition['month']))
                    $sql .= '-'.$condition['month'].'.*"';
                else
                    $sql .= '.*"';
                if($condition['day'] == '1-15')
                    $sql .= ' AND DAYOFMONTH(Date) > 1 AND DAYOFMONTH(Date) < 15';
                else if($condition['day'] == '16-31')
                    $sql .= ' AND DAYOFMONTH(Date) > 15 AND DAYOFMONTH(Date) < 32';
            }
            $connection=Yii::app()->db;
            $command=$connection->createCommand($sql);
            $res = $command->query();
            return $res;
        }

        public static function getReadersClientList($reader_id){
             $criteria = new CDbCriteria;
             $criteria->select = 't.Client_id, t.Client_name';
             $criteria->condition = 't.Reader_id = :reader AND (`band_list`.`Client_status` = "limited" OR `band_list`.`Client_status` = "preferred")';
             $criteria->params = array(':reader' => $reader_id);
             $criteria->group = 'Client_id';
             $criteria->join = 'INNER JOIN `band_list` ON `band_list`.`UserID` = t.Client_id';

             $real_cnt = 0;
             $records = self::model()->findAll($criteria);
             foreach($records as $rec){
                 if(!empty($rec->Client_id))
                         $real_cnt++;
             }

             $dataProvider=new CActiveDataProvider('ChatHistory', array(
                        'criteria'=>$criteria,
                        'pagination'=> array(
                            'pageSize' => $real_cnt
                        ),
             ));

            return $dataProvider;
        }
        
        public static function getClientSessions($client_id)
        {
            return self::model()->findAll(array(
                'condition' => 'Client_id = :client_id',
                'params' => array(':client_id' => $client_id)
             ));
        }
        
        public static function checkClientAccessorryToReader($client_id, $reader_id){
             $criteria = new CDbCriteria;
             $criteria->select = 't.Client_id, t.Client_name';
             $criteria->condition = 't.Reader_id = :reader AND t.Client_id = :client AND (`band_list`.`Client_status` = "limited" OR `band_list`.`Client_status` = "preferred")';
             $criteria->params = array(':reader' => $reader_id, ':client' => $client_id);
             $criteria->group = 'Client_id';
             $criteria->join = 'INNER JOIN `band_list` ON `band_list`.`UserID` = t.Client_id';

             $records = self::model()->findAll($criteria);
             if(!empty($records))
                 return true;
             else
                 return false;
        }



         /**
          * CHECK IS IT FIRST TIME READER READ FOR THIS USER
          */
        public static function chekFirstTimeReaderRead($client_id, $reader_id)
        {           
            return (self::model()->count("Client_id = :client_id AND Reader_id= :reader_id", array(':client_id' => $client_id,':reader_id' => $reader_id))==0);
        }


        /**
         * Deleting current chat log
         *
         * @param <int> $session_id
         */
        public static function deleteChatHistory($session_id){
            $del_log = self::model()->findByPk($session_id);
            $del_log->del = 1;
            $del_log->save();
        }
        /**
         * Get single chat session data
         *
         * @param <int> $session_id
         * @return <object>
         */
        public static function getSession($session_id){
            return self::model()->findByPk($session_id);
        }

        public function getTranscript($html = true)
        {
            
            Yii::app()->getModule('chat');
            $transcript = '';
            
            $users = CommandsHistory::getInterlocutors($this->Session_id);
            
            $interlocutors = array();
            
            foreach($users as $user)
            {
                $uInfo = users::getUser($user->to_userid);
                if($uInfo->type == 'client')
                    $interlocutors[$user->to_userid] = 'client';
                else
                    $interlocutors[$user->to_userid] = 'reader';
            }
            
            $commands = CommandsHistory::getTranscripts($this->Session_id);
            
            foreach($commands as $comm)
            {
                $t = explode(' ', $comm->ts);
                $time = $t[1];
                $params = unserialize($comm->params);  
                
                if(isset($params['nickName']))
                    $transcript .= ($html) ? '('.$time.') <b>'.$params['nickName'].'</b> '.$params['message'].'<br>' : '('.$time.') '.$params['nickName'].' '.$params['message']."\n";
                else
                {
                    if((isset($params['system'])) && ($interlocutors[$comm->to_userid] == 'client'))
                        $transcript .= ($html) ? '('.$time.') <font color="red">'.$params['message'].'</font><br>' : '('.$time.') '.$params['message']."\n";
                }                
            }            
            return $transcript;
        }  
        
        public function getFullLog($client_id)
        {
            $sessions = self::model()->findAll(array(
                'condition' => 'Client_id = :client_id',
                'params' => array(':client_id' => $client_id)
            ));
            
            $text = "Full chat history\n\n";
            foreach($sessions as $sess)
            {
                $text .= "Subject: ".$sess->Subject."\n";
                $text .= "Date: ".$sess->Date."\n";
                $text .= "Reader: ".$sess->Reader_name."\n\n";
                
                $this->Session_id = $sess->Session_id;
                $log = $this->getTranscript(false);
                
                $text .= $log."\n\n";
            }
            
            return $text;
        }

        public function dueToReader()
        {
        	$halfPaidtime = !empty($this->half_paid_time)?$this->half_paid_time:0;
            return round($this->Rate * $this->Paid_time, 2)+round(($this->Rate * $halfPaidtime)/2, 2);
        }
             
		 /**
         * Save data to history table on chat start
         */
        public static function startChat($sessionId, $subject, $client, $reader, $chatLocation, $chatType, $freeTime) {

        	$chatHistory = ChatHistory::getSession($sessionId);
			if(!is_object($chatHistory)) 
		    	$chatHistory = new ChatHistory();
        	
	    	$chatHistory->Session_id = $sessionId;
	    	$chatHistory->Client_id = $client->getId();
	    	$chatHistory->Client_name = $client->login;
	    	$chatHistory->Reader_id =$reader->getId();
	    	$chatHistory->Reader_name = $reader->login;
	    	$chatHistory->Subject = $subject;
	    	$chatHistory->Date = Util::dbNow();
	    	$chatHistory->Duration = 0;
	    	$chatHistory->Free_time = 0;
	    	$chatHistory->Paid_time = 0;
	    	$chatHistory->Due_to_reader = 0;
	    	$chatHistory->Affiliate = $client->affiliate;
	    	$chatHistory->ChatLocation = $chatLocation;
	    	$chatHistory->Rate = ChatBilling::getPaymentRate($reader->seniority, 0);

	
	    	if(!$chatHistory->save())
	    	{
	    		throw new Exception("chat history: ".$chatHistory->getErrors());
	    	}
	        	
			$chatHistory->saveStartupStatistics($chatType, $freeTime);	    	
        }

		///setters and getters
        

        public function setPaidTime($paidTime) {
			$this->Paid_time = round($paidTime/60, 2);
        	
        }

        /**
         * Half paid time
         *
         * @param unknown_type $halfTime
         */
        public function setHPTime($halfTime) {
        	if($halfTime>0)
				$this->half_paid_time = round($halfTime/60, 2);
        	
        }
        
        public function setFreeTime($freeTime) {
			$this->Free_time = round($freeTime/60, 2);
        	
        }

        public function setReturnedTime($returnedTime) {
			$this->Returned_time = round($returnedTime/60, 2);
        	
        }
        
        public function setDueToExpert($sum) {
			$this->Due_to_reader = $sum;
        }

        public function setDuration($duration) {
			$this->Duration = $duration;
        }

        public function setRate($rate) {
			$this->Rate = $rate;
        }
        
        public function getDuration() {
			return $this->Duration;
        }

        public function getPaidTimeInMinutes() {
			return $this->Paid_time;
        	
        }

        public function getFreeTimeInMinutes() {
			return $this->Free_time;
        	
        }
        public function getReturnedTimeInMinutes() {
			return $this->Returned_time;
        	
        }

        public function getRate() {
			return $this->Rate;
        }
        
        public function getSubject() {
			return $this->Subject;
        }
        /**
         * Return chat session duration in mins
         * @return <decimal> Session duration in minutes
         */
        public function getDurationMins()
		{
            return round($this->Duration / 60, 2);
        }

        public function getDurationSec()
        {
            return $this->Duration;
        }
        /**
         * Returns client id of current session
         *
         * @return <int> Client id
         */
        public function getClientId(){
            return $this->Client_id;
        }
        
        public function getExpertId(){
            return $this->Reader_id;
        }

        /**
         * Returns id of current session
         * 
         * @return <type>
         */
        public function getSessionId(){
            return $this->Session_id;
        }

		public function getDueToExpert() {
			return $this->Due_to_reader;
        }        
        
   /////// debug extra  /////
   
	/**
	 * Debug extra submodel instance
	 */
	private $_debugExtra;

    /**
     * return debug_extra submodel
     *
     * @return DebugExtra
     */
    private function _debugExtra() {
        if(!is_object($this->_debugExtra))
        		$this->_debugExtra = DebugExtra::getGebugExtra($this->getSessionId());
        
        return $this->_debugExtra;
    }
    
    public function getBalaceOnStart() {
    	return $this->_debugExtra()->old_balance*60;
    }
	
    /**
     * Increases count of messages posted
     */
    public function increaseMsgCount($userType) {
			
		if($userType == ChatContext::CLIENT)
			$this->_debugExtra()->client_msg_count += 1;
		else
			$this->_debugExtra()->reader_msg_count += 1;

		$this->_debugExtra()->save();

    }
    
    /**
     * return true if chat successful
     */
    public function isSuccessful() {
			
    	return ($this->_debugExtra()->client_msg_count>=2 && $this->_debugExtra()->reader_msg_count>=6 && $this->getDuration()>120);
    }
    
    /**
     * return debug_extra submodel
     *
     * @return DebugExtra
     */
    public function noPayment() {
    	return !$this->_debugExtra()->payment;
    }

    /**
     * return bmt
     *
     * @return int
     */
    public function BMT() {
    	return ($this->bmtAmount()>0);
    }

    /**
     * return bmt time amount
     *
     * @return int
     */
    public function bmtAmount() {
    	return $this->_debugExtra()->bmt_time;
    }
    
	/**
	 * Get finish initiator
	 *
	 * @return finish initiator id
	 */
	public function getFinishInitiator()
	{
		return $this->_debugExtra()->finish_initiator;
	}

	/**
	 * Set finish initiator
	 *
	 * @return finish initiator id
	 */
	public function setFinishInitiator($userType)
	{
		$this->_debugExtra()->finish_initiator = $userType;
		if($userType == 'client')
			$this->_debugExtra()->client_finished = 1;
		$this->_debugExtra()->session_finished = 1;
		$this->_debugExtra()->save();

		$this->Finished = 1;
		$this->save(false, array('Finished'));
	}
    
    /**
     * Save statistics on startup
     */
	public function saveStartupStatistics($chatType, $freeTime) {
		
    	// Save extra session tracking info
		$stat = $this->_debugExtra();
		if(!is_object($stat))
    		$stat = new DebugExtra();
    	$client = ChatHelper::getClient($this->getClientId());
    	$stat->session_id = $this->getSessionId();
    	$stat->client_id = $this->getClientId();
    	$stat->reader_id = $this->getExpertId();
    	$stat->old_balance = $client->getBalance();
    	$stat->free_time_before = $freeTime;
		$stat->chat_type = $chatType;
    	$stat->notes = '';
    	$stat->save();

		//@TODO : remove this when we abuse T1_12 at all
    	$connect = Yii::app()->db;
    	$sql = "REPLACE `T1_12`
                    (`rr_record_id`, `type` , `reader_id` , `client_id` , `laststatusupdate`)
                    VALUES
                    (".$this->getSessionId().", 'reader' , '".$this->getExpertId()."' , '".$this->getClientId()."' , NOW())";
    	$command=$connect->createCommand($sql);
    	$command->execute();

		
	}

	public function saveFinalStatistics($additional) {
		$this->_debugExtra()->free_time_after = $additional['free_time_remaining'];
		$this->_debugExtra()->new_balance = $additional['balance'];
//		$this->_debugExtra()->bmt_time = BmtStat::processBmt($this->getClientId());
		$this->_debugExtra()->add_lost_time = $additional['add_lost_time'];
		$this->_debugExtra()->payment = $additional['payment'];		
		$this->_debugExtra()->save();
		
                if($additional['add_lost_time'] == 1)
                {
                    $this->Due_to_reader = 0;                    
                    $this->save();
                }
                    
	}

	public function addBMT($minutes) {
		$this->_debugExtra()->bmt_time += $minutes;
		$this->_debugExtra()->update(array('bmt_time'));
	}
	
    public function sessionFinished() {
		return $this->Finished;
    }
	
    public function saveUserAgent() {
    	$userAgent = $_SERVER['HTTP_USER_AGENT'];
    	$data = Util::getBrowserData($userAgent);
    	$this->_debugExtra()->client_browser = $data['browser'];
    	$this->_debugExtra()->browser_version = $data['version'];
    	$this->_debugExtra()->user_agent = $userAgent;
		
		$this->_debugExtra()->save();
    }
    
    public function saveReaderUserAgent()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $data = Util::getBrowserData($userAgent);
    	$this->_debugExtra()->reader_browser = $data['browser'];
    	$this->_debugExtra()->reader_useragent = $userAgent;
        $this->_debugExtra()->save();
    }
    
    
}