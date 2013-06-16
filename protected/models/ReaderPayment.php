<?php

/**
 * This is the model class for table "reader_payment".
 *
 * The followings are the available columns in table 'reader_payment':
 * @property integer $ID
 * @property integer $ReaderID
 * @property string $Date
 * @property string $Sum
 * @property string $ChatLocation
 * @property string $Session_numb
 * @property string $Notes
 *
 * The followings are the available model relations:
 */
class ReaderPayment extends CActiveRecord
{
    const PER_PAGE = 20;
	/**
	 * Returns the static model of the specified AR class.
	 * @return ReaderPayment the static model class
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
		return 'reader_payment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ReaderID', 'numerical', 'integerOnly'=>true),
			array('Sum, Session_numb', 'length', 'max'=>10),
			array('ChatLocation', 'length', 'max'=>13),
			array('Notes', 'length', 'max'=>255),
			array('Date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, ReaderID, Date, Sum, ChatLocation, Session_numb, Notes', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array('chat_history' => array(self::BELONGS_TO, 'ChatHistory', 'Session_numb'),
                             'email_readings' => array(self::BELONGS_TO, 'EmailQuestions', 'Session_numb')
                    );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'ReaderID' => 'Reader',
			'Date' => 'Date',
			'Sum' => 'Sum',
			'ChatLocation' => 'Chat Location',
			'Session_numb' => 'Session Numb',
			'Notes' => 'Notes',
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

		$criteria->compare('ID',$this->ID);
		$criteria->compare('ReaderID',$this->ReaderID);
		$criteria->compare('Date',$this->Date,true);
		$criteria->compare('Sum',$this->Sum,true);
		$criteria->compare('ChatLocation',$this->ChatLocation,true);
		$criteria->compare('Session_numb',$this->Session_numb,true);
		$criteria->compare('Notes',$this->Notes,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
        /**
         * Return all payments for current reader
         * Have ability to filter results
         *
         * @param <integer> $reader_id
         * @param <array> $condition condition filter
         * @return <CActiveDataProvider>
         */
        public static function getReaderPayments($reader_id, $condition =  null){
            $criteria = new CDbCriteria;
            $criteria->condition = 'ReaderID = :reader_id';

            if(!is_null($condition)){
                $con_str = '`t`.`Date` REGEXP "^'.$condition['year']; // Year condition (defaults on current)
                // Month condition
                if(isset($condition['month']))
                    $con_str .= '-'.$condition['month'].'.*"';
                else
                    $con_str .= '.*"';
                // Days condition
                if($condition['day'] == 1)
                    $con_str .= ' AND DAYOFMONTH(`t`.`Date`) >= 1 AND DAYOFMONTH(`t`.`Date`) <= 15';
                else if($condition['day'] == 2)
                    $con_str .= ' AND DAYOFMONTH(`t`.`Date`) >= 16 AND DAYOFMONTH(`t`.`Date`) < 32';
                // Location filter
                if(isset($condition['loc']))
                    $con_str .= ' AND `t`.`ChatLocation` = "'.$condition['loc'].'"';
                
                $criteria->condition .= ' AND '.$con_str;
                
            }

            $criteria->params = array(':reader_id' => $reader_id);
            $criteria->order = 't.`Date` DESC';
            $criteria->with = array('chat_history', 'email_readings');
            
            $select = self::model()->findAll($criteria);
            
            $stmt = array();
            foreach($select as $row)
            {
                $client = ($row->chat_history->Client_id) ? 
                        Clients::getClient($row->chat_history->Client_id) : 
                            Clients::getClient($row->email_readings->client_id);
                $currency = ($row->ChatLocation = 'International') ? "USD" : "CAD";
                
                $stmt[] = array(
                    'client_id' => $client->rr_record_id,
                    'client_login' => $client->login,
                    'date' => date("M j, Y h:i a", strtotime($row->Date)),
                    'session' => $row->Session_numb,
                    'sum' => "$ ".$row->Sum." ".$currency,
                );
            }
            
            $dataProvider = new CArrayDataProvider($stmt, array(
                        'pagination'=> array(
                                'pageSize'=> self::PER_PAGE,
                                'params' => array(
                                    'paging' => 1,
                                    'year' => $condition['year'],
                                    'location' => $condition['loc'],
                                    'month' => $condition['month'],
                                    'day' => $condition['day']
                                )
                        )
            ));

            return $dataProvider;
        }

        /**
         * Return total payments for reader sort by chat locations
         * Have ability to filter results
         *
         * @param <integer> $reader_id
         * @return <array>
         */
        public static function getTotalSum($reader_id, $condition = null){
            $sql = 'SELECT sum(Sum) as total, ChatLocation
                    FROM reader_payment
                    WHERE ReaderID = '.$reader_id;
            
            if(!is_null($condition)){
                $sql .= ' AND `Date` REGEXP "^'.$condition['year']; // Year condition (defaults on current)
                // Month condition
                if(isset($condition['month']))
                    $sql .= '-'.$condition['month'].'.*"';
                else
                    $sql .= '.*"';
                // Days condition
                if($condition['day'] == 1)
                    $sql .= ' AND DAYOFMONTH(`Date`) >= 1 AND DAYOFMONTH(`Date`) <= 15';
                else if($condition['day'] == 2)
                    $sql .= ' AND DAYOFMONTH(`Date`) >= 16 AND DAYOFMONTH(`Date`) < 32';
                // Location filter
                if(isset($condition['loc']))
                    $sql .= ' AND `ChatLocation` = "'.$condition['loc'].'"';
            }

            $sql .= ' GROUP BY `ChatLocation`';

            $connection=Yii::app()->db;
            $command=$connection->createCommand($sql);
            $query = $command->query();
            $result = array();
            foreach($query as $row){
                if($row['ChatLocation'] != 'Canada')
                    $result['total_usd'] = $row['total'];
                else
                    $result['total_canada'] = $row['total'];
            }
            return $result;
        }
        
        /**
         * adds payment
         *
         * @param <integer> $reader_id
         * @param <integer> $session_id
         * @param <integer> $sum
         * @param <integer> $chatLocation
         * @return <void>
         */
        public static function add($reader_id, $session_id, $sum, $chatLocation, $notes) {
        	$payment = new ReaderPayment();
        	$payment->ReaderID = $reader_id;
        	$payment->Session_numb = $session_id;
        	$payment->Sum = $sum;
        	$payment->ChatLocation = $chatLocation;
        	$payment->Date = new CDbExpression("NOW()");
        	$payment->Notes = $notes;
        	$payment->save();
        }

        /**
         * adds payment
         *
         * @param <integer> $reader_id
         * @param <integer> $session_id
         * @param <integer> $chatLocation
         * @return <void>
         */
        public static function startChat($reader_id, $session_id, $chatLocation) {
        	$payment = self::findBySessionId($session_id);
        	if(!is_object($payment))
        		self::add($reader_id, $session_id, 0, $chatLocation, null);
        }
        
        /**
         * find payment by session id
         *
         * @param <integer> $session_id
         * @param <integer> $sum
         * @return <array>
         */
        public static function findBySessionId($session_id) {
	        return self::model()->find(array(
	            'condition' => 'Session_numb = :session_id',
	            'params' => array(':session_id' => $session_id)
	        ));
        }
        
        /**
         * puts fresh data to payment
         *
         * @param <integer> $session_id
         * @param <integer> $sum
         * @return <array>
         */
        public static function saveSum($session_id, $sum) {
        	$payment = self::findBySessionId($session_id);
        	if(is_object($payment)) {
	        	$payment->Sum = $sum;
	        	$payment->Date = new CDbExpression("NOW()");
	        	$payment->save();
        	}
        }

        /**
         * delete payment by session id
         *
         * @param <integer> $session_id
         * @return <array>
         */
        public static function deleteBySessionId($session_id) {
        	$payment = self::findBySessionId($session_id);
        	$payment->delete();
        }
        
        /**
         * puts fresh data to payment
         *
         * @param <integer> $session_id
         * @param <integer> $sum
         * @return <array>
         */
        public static function finalize($session_id, $sum) {
        	if($sum>0)
				self::saveSum($session_id, $sum);
			else 
				self::deleteBySessionId($session_id);
        }
        
        
}