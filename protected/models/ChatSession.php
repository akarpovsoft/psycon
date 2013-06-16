<?php

class ChatSession extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'T1_12':
	 * @var integer $rr_record_id
	 * @var integer $rr_account_id
	 * @var integer $rr_emailsent
	 * @var integer $rr_synchronized
	 * @var string $rr_createdate
	 * @var string $rr_lastaccess
	 * @var integer $reader_id
	 * @var integer $client_id
	 * @var string $type
	 * @var string $laststatusupdate
	 * @var string $duration
	 * @var string $reader_add_lost_time
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
		return 'T1_12';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rr_lastaccess', 'required'),
			array('rr_account_id, rr_emailsent, rr_synchronized, reader_id, client_id', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>50),
			array('duration', 'length', 'max'=>11),
			array('reader_add_lost_time', 'length', 'max'=>4),
			array('rr_createdate, laststatusupdate', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'rr_record_id' => 'Rr Record',
			'rr_account_id' => 'Rr Account',
			'rr_emailsent' => 'Rr Emailsent',
			'rr_synchronized' => 'Rr Synchronized',
			'rr_createdate' => 'Rr Createdate',
			'rr_lastaccess' => 'Rr Lastaccess',
			'reader_id' => 'Reader',
			'client_id' => 'Client',
			'type' => 'Type',
			'laststatusupdate' => 'Laststatusupdate',
			'duration' => 'Duration',
			'reader_add_lost_time' => 'Reader Add Lost Time',
		);
	}
        /**
         * Checking reader's response for chat start
         *
         * @param <integer> $client_id
         * @param <integer> $reader_id
         * @return <integer> count rows (if > 1 then response exists)
         */
        public static function startChatCheck($client_id, $reader_id){
            $connect = Yii::app()->db;
            $sql = "SELECT rr_record_id
                    FROM T1_12
                    WHERE type='reader'
                        AND reader_id='".$reader_id."'
                        AND client_id='".$client_id."'
                        AND (to_days(now())=to_days(laststatusupdate) and (time_to_sec(now()) - time_to_sec(laststatusupdate) < 10))";
            $command = $connect->createCommand($sql);
            Yii::log( 'T1_12 | '. $sql, 'info');
            return $command->execute();
        }
        /**
         * Starts chat
         *
         * @param <integer> $client_id
         * @param <integer> $reader_id
         */
        public static function startChatting($session_id, $client_id, $reader_id)
        {
			/// Delete old chat context records
			ChatContext::deleteOldSessions();
			self::deleteOldSessions();

			/// Delete all records from chat context with this reader
			ChatContext::deleteByReaderId($reader_id);
			self::deleteByReaderId($reader_id);

			/// get client chat request
			$request = ChatRequests::getChatRequest($session_id);
            
			/// insert new chat context record
			$chatContext = new ChatContext();
            $chatContext->add($session_id, $client_id, $reader_id, $request->duration);

			/// get shat context
            $chatContext = ChatContext::getContext(ChatContext::getSessionKey($session_id), ChatContext::READER);

            /// put free seconds to chat contex record
            $free_time = RemoveFreetime::getFreeTime($client_id);
            $chatContext->free_time = $free_time['Seconds'];

            /// CHECK IS IT FIRST TIME READER READ FOR THIS USER 
            $fr = ChatHistory::chekFirstTimeReaderRead($client_id, $reader_id);

            $ChatCommander = new ChatCommander($chatContext);
			
            if($fr)
            {
                $ChatCommander->addSystemMessage($reader_id, 
                    "NEW CLIENT: CHECK NAME/DOB- ban if necessary!"
                 );
            }
            
            if(RemoveFreetime::userHasFreeTime($chatContext->client_id))
            {
            	$freeTimeData = RemoveFreetime::getFreeTime($chatContext->client_id);
				$ChatCommander->addSystemMessage($reader_id,
					"FREEBIE CLIENT: NO MORE THAN ".round($freeTimeData->Total_sec/60, 1)." mins- UNLESS THEY BMT!"
				);
				$ChatCommander->addSystemMessage($client_id,
					"You have ".round($freeTimeData->Total_sec/60, 1)." mins of FREE CHAT TIME - No obligation to purchase more time- unless you want to..."
				);
            }

			/// get notes about client
			$notes = NotesClients::getNotes($chatContext->client_id);
						
			$extra_notes = '';
			foreach($notes as $row)
			{
				if($row instanceof NotesClients)
				{
					$extra_notes .= $row->note.'<br>';

					NotesClients::updateNotes($chatContext->client_id);
				}
			}

			if(strlen($extra_notes) > 0)
			{
				$extra_notes = preg_replace("/\n|\r/", " ", $extra_notes);

				$ChatCommander->addSystemMessage($chatContext->reader_id, $extra_notes);
			}
            
            /// insert into history
            $userData = $chatContext->client;
            $readerData = $chatContext->reader;
           
            $request = ChatRequests::getChatRequest($session_id);
			$subject = urldecode($request['subject']);            
            $chatHistory = new ChatHistory;
            $chatHistory->Session_id = $session_id;
            $chatHistory->Client_id = $client_id;
            $chatHistory->Client_name = $userData['login'];
            $chatHistory->Reader_id =$reader_id;
            $chatHistory->Reader_name = $readerData['login'];
            $chatHistory->Subject = $subject;
            $chatHistory->Date = date('Y-m-d H:i:s');
            $chatHistory->Duration = 0;
            $chatHistory->Free_time = 0;
            $chatHistory->Paid_time = 0;
            $chatHistory->Due_to_reader = 0;
            $chatHistory->Affiliate = $userData["affiliate"];
            $chatHistory->ChatLocation = (($userData["affiliate"] == 'canada' || $userData['site_type'] =='CA') ?'Canada':'International');
           
			if(!$chatHistory->save())
            {
				throw new Exception("chat history: ".$chatHistory->getErrors());
            }

            //Remove Previous BMT
            $bmt = BmtStat::deleteByClientId($client_id);
            
            // Save extra session tracking info            
            $extraData = new DebugExtra();
            $extraData->session_id = $session_id;
            $extraData->client_id = $client_id;
            $extraData->reader_id = $reader_id;
            $extraData->old_balance = $userData['balance'];
            $extraData->free_time_before = round($chatContext->free_time/60, 2);
            $extraData->notes = '';

            if( !$extraData->save())
            {
				throw new Exception("Extra data: ".$extraData->getErrors());
            }

			/// if new client
			if($chatContext->client->first_reading != 'No')
			{
				$ChatCommander->addSystemMessage($chatContext->reader_id, "*** New Client! Never Chatted Before ***");
				$chatContext->client->first_reading = 'No';
				$chatContext->client->save();
				
			}
						
            /// start
            $chatContext->chat_start = Util::dbNow();
            $chatContext->client_ping = Util::dbNow();
            $chatContext->reader_ping = Util::dbNow();
            $chatContext->save();

            $connect = Yii::app()->db;
            $sql = "REPLACE `T1_12`
                    (`rr_record_id`, `type` , `reader_id` , `client_id` , `laststatusupdate`)
                    VALUES
                    ($session_id, 'reader' , '".$reader_id."' , '".$client_id."' , NOW())";
            $command=$connect->createCommand($sql);
            $command->execute();			

            return $chatContext->sessionKey;
        }

		 /**
		 * Delete all sessions with $readerId reader
		 *
		 */
		public static function deleteByReaderId($readerId)
		{
			self::model()->deleteAll("reader_id = :reader_id", array(':reader_id' => $readerId));
		}

                public static function getByReaderId($reader_id){
                    return self::model()->findAll(array(
                               'condition' => 'reader_id = :reader_id',
                               'params' => array(':reader_id' => $reader_id)
                           ));
                }

		 /**
		 * Delete old sessions( older 1 hour)
		 */
		public static function deleteOldSessions()
		{
			self::model()->deleteAll('DATE_ADD( rr_lastaccess, INTERVAL 1 HOUR ) <  now()', array());
		}

		public static function ifReaderChatting($readerId)
		{
			$connection = Yii::app()->db;
			$sql = "SELECT * FROM `T1_12` WHERE `reader_id` = '$readerId' and (DATE_ADD(laststatusupdate, interval 17 second) > now())";
			$command = $connection->createCommand($sql);
			$data = $command->query();

			foreach($data as $row)
			{
				return true;
			}

                        $requesters = ChatRequests::getReadersRequestNew($readerId);
                        if(!empty($requesters))
                            return true;
                        
			return false;
		}
}