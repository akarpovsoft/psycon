<?php
/**
 * This is the model class for table "time_events".
 *
 * The followings are the available columns in table 'time_events':
 * @property integer $id
 * @property string $session_key
 * @property string $event_flag
 *
 * The followings are the available model relations:
 */
class TimeEvents extends CActiveRecord
{
	public $sessionKey;
	
    const ONE_MINUT_LEFT = 1;
    const FREE_TIME_1_MIN = 3;
    const PAID_TIME_STARTED = 4;
	const PAID_TIME_END = 5;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return TimeEvents the static model class
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
		return 'time_events';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('session_key, event_flag', 'required'),
			array('session_key', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, session_key, event_flag', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'session_key' => 'Session Key',
			'event_flag' => 'Event Flag',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('session_key',$this->session_key,true);
		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Set event flag
	 * @param int $event_flag
	 */
	private function setFlag($event_flag)
	{
		$TimeEvents = $this->getEventFlag($this->sessionKey);
		$flagarr = array();
		if(is_object($TimeEvents))
			$flagarr = unserialize($TimeEvents->event_flag);
		else
			$TimeEvents = new TimeEvents();
		
		$flagarr[$event_flag] = 'checked';
		$flagstr = serialize($flagarr);
		
		$TimeEvents->event_flag = $flagstr;
		$TimeEvents->session_key = $this->sessionKey;
		$TimeEvents->save();
            
        return $TimeEvents = new TimeEvents();
    }
    
    /**
     * Check flag is set
     * @param int $event_flag
     */
    private function isSetFlag($event_flag)
    {
    	$TimeEvents = $this->getEventFlag($this->sessionKey);
		$flagarr = array();
		if(is_object($TimeEvents))
		{
			$flagarr = unserialize($TimeEvents->event_flag);
			return array_key_exists($event_flag, $flagarr);
		}
		return false;
    }
    
    /**
     * Clear all flags in current session
     * @param ChatContext $chatContext
     */
    public function clearFlags($chatContext)
    {
    	$this->sessionKey = $chatContext->sessionKey;
    	self::model()->deleteAll(array(
			'condition' => 'session_key = :sessionKey',
			'params' =>array(':sessionKey' => $this->sessionKey),
		));
    }

    /**
     * Get event flag
     */
	private function getEventFlag()
	{
		$data = self::model()->find(array(
			'condition' => 'session_key = :sessionKey',
			'params' =>array(':sessionKey' => $this->sessionKey),
		));       
		if(is_object($data))
			return $data;
		else
			return false;
    }
    
    /**
     * Check event
     * @param ChatContext $chatContext
     */
    public function check($chatContext) 
    {
		$this->sessionKey = $chatContext->sessionKey;
    	if($chatContext->balance <= 70 && !$this->isSetFlag(self::ONE_MINUT_LEFT)) // +10 seconds for delay
    	{
	    	$this->setFlag(self::ONE_MINUT_LEFT);
    		return self::ONE_MINUT_LEFT;
    	}
    	
	    if($chatContext->freeTimeRemaining() <= 70 && $chatContext->freeTimeOnStart()>0 && !$this->isSetFlag(self::FREE_TIME_1_MIN))
	    {
	    	$this->setFlag(self::FREE_TIME_1_MIN);
	    	return self::FREE_TIME_1_MIN;
	    }
	    
	    if($chatContext->freeTimeRemaining() <= 0 && $chatContext->freeTimeOnStart()>0 && !$this->isSetFlag(self::PAID_TIME_STARTED))
	    {						
	    	$this->setFlag(self::PAID_TIME_STARTED);
	    	return self::PAID_TIME_STARTED;	    
	    }

		if($chatContext->chatTimeRemaining() <= 0 && !$this->isSetFlag(self::PAID_TIME_END))
	    {
	    	$this->setFlag(self::PAID_TIME_END);
	    	return self::PAID_TIME_END;
	    }
    	return false;
    }
}