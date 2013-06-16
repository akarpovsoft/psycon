<?php

class ReturnTimeStat extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'return_time_stat':
	 * @var integer $id
	 * @var integer $reader_id
	 * @var integer $client_id
	 * @var integer $session_id
	 * @var double $value
	 * @var string $balance_before
	 * @var string $balance_after
	 * @var string $chattype
	 * @var string $date
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
		return 'return_time_stat';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('reader_id, client_id, session_id', 'numerical', 'integerOnly'=>true),
			array('value', 'numerical'),
			array('balance_before, balance_after', 'length', 'max'=>40),
			array('chattype', 'length', 'max'=>255),
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
			'id' => 'Id',
			'reader_id' => 'Reader',
			'client_id' => 'Client',
			'session_id' => 'Session',
			'value' => 'Value',
			'balance_before' => 'Balance Before',
			'balance_after' => 'Balance After',
			'chattype' => 'Chattype',
			'date' => 'Date',
		);
	}
        
        public function saveRetTimeStat()
        {
            $this->date = date('Y-m-d H:i:s');
            $this->save();
        }
        
        public static function getRTSBySession($session)
        {
            return self::model()->find(array(
                'condition' => 'session_id = :session',
                'params' => array(':session' => $session)
            ));
        }   
        
        public function saveBeforeReaderBalance()
        {
            //FORM THE PERIOD
            $monthPart = (date("d") >= 15) ? "1" : "2";
            $daysInMonth = date("t");
            $curMonth = date("m");
            $curYear  = date("Y");
            if ($monthPart == 1) {
                $dayStart = "01"; 
                $dayEnd ="15";
            }
            else  {
                $dayStart = "16"; 
                $dayEnd = $daysInMonth;
            }
            $firstDate = $curYear."-".$curMonth."-".$dayStart;
            $secDate = $curYear."-".$curMonth."-".$dayEnd;
            
            $connection=Yii::app()->db;
            $sql = "SELECT SUM(Sum) as balance, ChatLocation location
                   FROM `reader_payment` 
                   WHERE ReaderID = (SELECT `Reader_id` FROM `History` WHERE `Session_id` = '".$this->session_id."') AND 
                         TO_DAYS( Date ) >= TO_DAYS( '$firstDate' )  AND   
                         TO_DAYS( Date ) <= TO_DAYS( '$secDate' )    AND 
                         (`Notes` IS NULL OR 
                           (`Notes` NOT LIKE '%webphone%' AND `Notes` NOT LIKE '%email%')
                         ) AND
                         `ChatLocation` = (SELECT `ChatLocation` FROM `History` WHERE `Session_id` = '".$this->session_id."')
                   GROUP BY `ChatLocation` ";
            $command=$connection->createCommand($sql);
            $res = $command->query();
            
            foreach($res as $row)
            {   
                $this->balance_before = $row['balance'];
                $this->chattype = $row['location'];
                $this->save();
            }
        }
        
        public function saveAfterReaderBalance()
        {
           $reduced = 0;
           
           $history = ChatHistory::getSession($this->session_id);
           $reduced = $this->value * $history->getRate();
           $this->balance_after = $this->balance_before - $reduced;
           $this->save();
           
        }
        
}