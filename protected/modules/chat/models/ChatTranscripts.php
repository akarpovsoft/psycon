<?php

/**
 * This is the model class for table "chattranscripts".
 *
 * The followings are the available columns in table 'chattranscripts':
 * @property string $SessionId
 * @property string $ReadedId
 * @property string $ClientId
 * @property string $Date
 * @property string $Transcripts
 *
 * The followings are the available model relations:
 */
class ChatTranscripts extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ChatTranscripts the static model class
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
		return 'chattranscripts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('SessionId, ReadedId, ClientId', 'length', 'max'=>10),
			array('Date, Transcripts', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('SessionId, ReadedId, ClientId, Date, Transcripts', 'safe', 'on'=>'search'),
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
			'SessionId' => 'Session',
			'ReadedId' => 'Readed',
			'ClientId' => 'Client',
			'Date' => 'Date',
			'Transcripts' => 'Transcripts',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('SessionId',$this->SessionId,true);
		$criteria->compare('ReadedId',$this->ReadedId,true);
		$criteria->compare('ClientId',$this->ClientId,true);
		$criteria->compare('Date',$this->Date,true);
		$criteria->compare('Transcripts',$this->Transcripts,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

	public static function addMessage($chatContext, $message)
	{
		$count = self::model()->count('SessionId = :session_id', array(':session_id' => $chatContext->session_id));
		if($count > 0)
		{
			$log = self::model()->find('SessionId = :session_id', array(':session_id' => $chatContext->session_id));
		}
		else
		{
			$log = new ChatTranscripts();
			$log->SessionId = $chatContext->session_id;
			$log->ReadedId = $chatContext->reader_id;
			$log->ClientId = $chatContext->client_id;
			$log->Date = date('Y-m-d H:i:s');
		}

		$log->Transcripts .= $message."\r\n";
		$log->save();

	}

        public static function getTranscript($session_id, $reader_id){
            $chat = self::model()->findByPk($session_id);
            // Search in database at first
            if(!empty($chat)){
                $line = $chat->Transcripts;
            } else { // And then in txt log files
                $file = $reader_id."/".$session_id.".txt";
                if(file_exists($_SERVER['DOCUMENT_ROOT']."/chat/logs/".$file)){
                    $file_log = file($_SERVER['DOCUMENT_ROOT']."/chat/logs/".$file);
                    foreach($file_log as $log){
                        $line.= $log;
                    }
                }
            }
            return nl2br($line);
        }

        public static function getChatLog($session_id){
            return self::model()->findByPk($session_id)->Transcripts;
        }
}