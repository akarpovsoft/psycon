<?php
/**
 * class Readers
 *
 * Special subclass to work with users with status 'Reader'
 * 
 * @author Den Kazka den.smart[at]gmail.com
 * @since 2010
 * @version $Id
 */
class Readers extends users
{
    public $reader_id;
    public $photo; // File with reader's photo

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * Get all readers list
     *
     * @return array
     */
    public static function getReadersList(){
        return self::model()->findAll(array(
           'condition' => 'type = "reader"',
           'order' => 'name'
        ));
    }
    
    public static function getGoFishReadersList()
    {
        return self::model()->findAll(array(
           'condition' => '`type` = "reader" OR `login` = "jayson"',
           'order' => 'name'
        ));
    }
    
    public static function getEreadingsReadersList()
    {
        $criteria = new CDbCriteria;
        $criteria->condition = 'type = "reader" and `email_readers`.`reader_id` IS NOT NULL';
        $criteria->order = 'name';
        $criteria->join = 'LEFT JOIN `email_readers` ON `email_readers`.`reader_id` = t.rr_record_id';

        $readers = self::model()->findAll($criteria);
        
        $reader_ids = array();
        $double_readers = array();
        
        $sessions = EmailQuestions::getByClientId(Yii::app()->user->id);

        foreach($sessions as $session)
            $reader_ids[] = $session->reader_id;

        foreach($reader_ids as $id)
        {
            $pair = DoubleReaders::getReaderPair($id);
            if ($pair->id2 && !in_array($pair->id2, $reader_ids)) 
                    $double_readers[] = $pair->id2;
        }

        $stmt = array();
        if(!empty($readers))
        {
            foreach($readers as $reader)
            {
                if(!in_array($reader->rr_record_id, $double_readers))
                        $stmt[] = $reader;
            }
            return $stmt;
        }
        else
            return null;
    }
    /**
     * Return one reader by id
     *
     * @param int $id Reader id
     * @return object users
     */
    public static function getReader($id, $with = null){
        $criteria = new CDbCriteria;
        $criteria->condition = 'type=\'reader\' AND t.rr_record_id=' . $id;
        if(!is_null($with))
            $criteria->with = $with;
        return self::model()->find($criteria);
    }
    
     /**
     * Return one reader by name
     *
     * @param int $id Reader id
     * @return object users
     * @author Andrey
     */
    public static function getReaderByName($name, $with = null){
        $criteria = new CDbCriteria;
        $criteria->condition = 'type=\'reader\' AND LOWER(t.name)=\'' . strtolower($name).'\'';
        if(!is_null($with))
            $criteria->with = $with;
        return self::model()->find($criteria);
    }
    
    /**
     * Check if reader in online
     *
     * @param <integer> $id Reader id
     * @return <obj> stdClass::object
     */
    public static function getReaderOnline($id){
        return self::model()->find(array(
            'condition' => 'rr_record_id = :reader_id 
            				AND status = "online"
                            AND to_days(now())=to_days(laststatusupdate)
                            AND (time_to_sec(now()) - time_to_sec(laststatusupdate) < 12)',
            'params' => array(':reader_id' => $id)            
        ));
    }
    
    public static function getReaderUpdate($id)
    {
        return self::model()->find(array(
            'condition' => 'rr_record_id = :reader_id 
                            AND to_days(now())=to_days(laststatusupdate)
                            AND (time_to_sec(now()) - time_to_sec(laststatusupdate) < 12)',
            'params' => array(':reader_id' => $id)            
        ));
    }

    public function isBusy() {
    	Yii::app()->getModule('chat');
        return (ChatContext::readerBusy($this->rr_record_id) || ChatSession::readerBusy($this->rr_record_id) || ChatRequests::readerBusy($this->rr_record_id)) && !$this->isOnBreak();
    }

    public function isOnline() {
    	return !is_null(self::getReaderOnline($this->rr_record_id));
    }
    
    public function isForcedOffline() 
    {
        $timeModel = TodayOnlineTime::getOnlineTimeInfo($this->rr_record_id);
        return !is_null($timeModel->isForcedOffline());
    }
    
    public function setForcedOffline()
    {
        $timeModel = TodayOnlineTime::getOnlineTimeInfo($this->rr_record_id);
        $timeModel->forced_offline = 1;
        $timeModel->forced_offline_time = new CDbExpression('NOW()');
        $timeModel->save();
    }
    
    public function setChatAbort()
    {
        $timeModel = TodayOnlineTime::getOnlineTimeInfo($this->rr_record_id);
        if(!is_null($timeModel->isChatAbort()))
            $timeModel->chat_abort_count += 1; 
        else
            $timeModel->chat_abort_count = 1; 
            $timeModel->chat_abort_time = new CDbExpression('NOW()');
        $timeModel->save();
    }
    
    /**
     * If reader do not respond more than 5 times
     * Close reader monitor
     */
    public function isAway()
    {
        $timeModel = TodayOnlineTime::getOnlineTimeInfo($this->rr_record_id);
        return !is_null($timeModel->isChatAbort('abort'));
    }
    
    /**
     * Reader came back after away (not break) mode.
     */
    public function comeBack()
    {
        $timeModel = TodayOnlineTime::getOnlineTimeInfo($this->rr_record_id);
        $timeModel->chat_abort_count = 0;
        $timeModel->forced_offline = 0;
        $timeModel->save();
    }
    
    public function isUpdate()
    {
        return !is_null(self::getReaderUpdate($this->rr_record_id));
    }

    public function minutesOnBreak() {
    	if($this->isOnBreak()) 
    	{
            return preg_replace('/break(.*)/', '\\1', $this->status);
        } 
        return false;
    }

    public function isOnBreak() {
    	
    	return (substr_count($this->status , 'break')>0);
    }
    
    public function setOnline() {
    	self::updateReaderStatus($this->rr_record_id, "online");
    }
    
	/**
	 * Get reader online status
	 *  - online
	 *  - busy 
	 *  - break 
	 *  - offline
	 *
	 * @param <type> $reader_id
	 * @return reader status
	 */

	public function getStatus()
	{
		/// only this func can set status busy
		if($this->isBusy())
		{                    
			$status = 'busy';
		}
		else {
			$status = $this->status;
			if(!$this->isOnBreak()) {
				$status = $this->isOnline()?"online":"offline";
				/// if status is busy in users table but reader ping update was more then 10 sec ago, set status online
				if ($this->status == 'busy')
				{
					self::updateReaderStatus($this->rr_record_id, $status);
				}
			}
		}	
                
		if(empty($status))
		{
			$status = 'offline';
		}
		return $status;
	}

    /**
     * Return array of online readers
     *
     * @return array
     */
    public static function getOnlineReadersList($group_id){
        $sql = "SELECT T1_1.rr_record_id, T1_1.name FROM T1_1
                LEFT JOIN T1_12
                    ON (T1_1.rr_record_id=T1_12.reader_id
                        AND T1_12.type='reader')
                LEFT JOIN T1_7
                    ON T1_1.rr_record_id=T1_7.reader_id
                        AND to_days(now())=to_days(T1_7.laststatusupdate)
                        AND (time_to_sec(now()) - time_to_sec(T1_7.laststatusupdate) < 12)
                LEFT JOIN week_online_time wt
                    ON wt.reader_id = T1_1.rr_record_id
                WHERE ((T1_12.rr_record_id is null)
                    OR NOT ((to_days(now())=to_days(T1_12.laststatusupdate)
                        and (time_to_sec(now()) - time_to_sec(T1_12.laststatusupdate) < 12))))
                AND T1_1.status='online'
                AND (to_days(now())=to_days(T1_1.laststatusupdate)
                AND (time_to_sec(now()) - time_to_sec(T1_1.laststatusupdate) < 12))
                AND T1_7.rr_record_id is null
                GROUP BY T1_1.rr_record_id
                ORDER BY time_online DESC";
        $connect = Yii::app()->db;
        $command=$connect->createCommand($sql);
        $onlineReaders = $command->query();
        unset($command);
        foreach($onlineReaders as $reader){
            $count = ReadersVisibility::forbiddenReaders($reader['rr_record_id'], $group_id);
            $ban_sql = 'SELECT * FROM `userban` WHERE `reader_id` = '.$reader['rr_record_id'].' AND `user_id` = '.Yii::app()->user->id;
            $command=$connect->createCommand($ban_sql);
            $userban = $command->execute();
            if(($count < 1)&&($userban < 1))
                $chat_readers[] = $reader;
        }               
        return self::getReadersListWithoutPair($chat_readers);
    }
    /**
     * Updates reader status (online, offline, busy, etc...)
     * @param integer $id reader id
     * @param string $status
     */
    public static function updateReaderStatus($id, $status){
        $connect = Yii::app()->db;
        $sql = "UPDATE T1_1
                SET `status` = '".$status."',
                    `laststatusupdate` = NOW()
                WHERE `rr_record_id` = '".$id."'";
           $command=$connect->createCommand($sql);
           $command->execute();
    }

    public static function updateReaderInfo($reader_id, $data, $photo){
        $temp = ReaderInfoTemp::getInfoByReaderId($reader_id);
        if(!$temp) $temp = new ReaderInfoTemp();
        
        foreach($data as $key=>$value){
            $temp->$key = $value;
        }
                
        $temp->rr_record_id = $reader_id;
        if(!is_null($photo)){
             $ext = explode('.', $photo->getName());
             $temp->operator_location = 'operator'.$reader_id.'.'.$ext[1];
             
             Util::resizeImage($photo, Yii::app()->params['project_root'].'/chat/temp_photo/'.$temp->operator_location);             
             //$photo->saveAs($_SERVER['DOCUMENT_ROOT'].'/chat/'.$temp->operator_location);
        }
        $temp->save();
        return $temp;
    }
    
    /**
     * Sets reader status (online, offline, busy, etc...)
     * @param string $status
     */
    public function setStatus($status){
    	$this->status = $status;
    	$this->laststatusupdate = Util::dbNow();
    	$this->save();
    }
    
    public static function phoneReadersList()
    {
        $wp_readers = self::model()->findAll(array(
            'condition' => 'webphone_enabled = 1',
            'order' => 'webphone_extension'
        ));
        
        $online = array();
        $offline = array();
        foreach($wp_readers as $wp)
        {
            if(Util::getWPStatus($wp->webphone_id) == 'online')
                $online[] = array('object' => $wp, 'status' => 'online');
            else
                $offline[] = array('object' => $wp, 'status' => 'offline');;
        }
        
        return array_merge($online, $offline);
    }
    
    /**
     * Check if reader has pair and detect a denied readers for chat
     * @param array $readers online readers ready to chat
     * @return array 
     */    
    public static function getReadersListWithoutPair($readers)
    {
        $reader_ids = array();
        $double_readers = array();
        
        $sessions = ChatHistory::getClientSessions(Yii::app()->user->id);
        
        foreach($sessions as $session)
            $reader_ids[] = $session->Reader_id;
        
        foreach($reader_ids as $id)
        {
            $pair = DoubleReaders::getReaderPair($id);
            if ($pair->id2 && !in_array($pair->id2, $reader_ids)) 
                    $double_readers[] = $pair->id2;
        }
        
        $stmt = array();
        if(!empty($readers))
        {
            foreach($readers as $reader)
            {
                if(!in_array($reader['rr_record_id'], $double_readers))
                        $stmt[] = $reader;
            }
            return $stmt;
        }
        else
            return null;
    }
}
?>
