<?php

class Transactions extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'transactions':
	 * @var string $ORDER_NUMBER
	 * @var string $UserID
	 * @var string $Date
	 * @var string $AUTH_CODE
	 * @var double $TRAN_AMOUNT
	 * @var double $Amount_used
	 * @var string $Mail_status
	 * @var string $merchant_trace_nbr
	 * @var string $TypeOfPayment
	 * @var string $Bmt
	 * @var string $ps_type
	 * @var integer $transaction_type
	 * @var string $Sessions
	 * @var string $balance_used
	 * @var string $Total_time
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
		return PsyStaticDataProvider::getTableName('transactions');
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('transaction_type', 'numerical', 'integerOnly'=>true),
			array('TRAN_AMOUNT, Amount_used', 'numerical'),
			array('ORDER_NUMBER, AUTH_CODE, Bmt', 'length', 'max'=>50),
			array('UserID', 'length', 'max'=>20),
			array('Mail_status', 'length', 'max'=>13),
			array('merchant_trace_nbr', 'length', 'max'=>100),
			array('TypeOfPayment', 'length', 'max'=>8),
			array('ps_type, balance_used, Total_time', 'length', 'max'=>10),
			array('Sessions', 'length', 'max'=>255),
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
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ORDER_NUMBER' => 'Order Number',
			'UserID' => 'User',
			'Date' => 'Date',
			'AUTH_CODE' => 'Auth Code',
			'TRAN_AMOUNT' => 'Tran Amount',
			'Amount_used' => 'Amount Used',
			'Mail_status' => 'Mail Status',
			'merchant_trace_nbr' => 'Merchant Trace Nbr',
			'TypeOfPayment' => 'Type Of Payment',
			'Bmt' => 'Bmt',
			'ps_type' => 'Ps Type',
			'transaction_type' => 'Transaction Type',
			'Sessions' => 'Sessions',
			'balance_used' => 'Balance Used',
			'Total_time' => 'Total Time',
		);
	}
        /**
         * Save transaction info into table
         */
        public function addTransaction($bmt = null) {
            $this->Date = date("Y-m-d H:i:s");
            $this->Mail_status = 'no';
            $this->TypeOfPayment = 'Online';
            $this->Bmt = $bmt;
            $this->balance_used = 0;

            $this->save();

        }

        public function getTimePurchased()
		{
            return Tariff::getDuration($this->TRAN_AMOUNT);
		}

	public function getTime() 
	{
		return ($this->getTimePurchased() - $this->balance_used);
	}
	

	public static function searchUnmarkedByClientId($client_id)
	{
		$c = new CDbCriteria;
		/// $c->condition = '`UserID` = :client_id AND `balance_used` < `Total_time`';
		$c->condition = '`UserID` = :client_id AND transaction_type = 0'; // only chat transactions
		$c->params = array(':client_id' => $client_id);
		$c->order = 'Date DESC';

		return self::model()->findAll($c);
	}

	public function acceptSessionTime($sessionId , $time)
	{
		$this->balance_used += $time;
		$this->Sessions .= ", ".$sessionId;

		if($this->balance_used >0 && $this->Amount_used==0)
				$this->Amount_used = $this->TRAN_AMOUNT;

		$this->save();
	}

	public static function deposit($chatContext)
	{
		/// get free time data
		$freeTime = RemoveFreetime::getFreeTime($chatContext->client_id);

		/// deposit transaction
		$c = new CDbCriteria;
        $c->condition = "`UserID` = :client_id AND `Amount_used` ='0'";
        $c->params = array(':client_id' => $chatContext->client_id );
		$data = self::model()->find($c);

		if($data instanceof Transactions)
		{
			$data->Amount_used = $data->TRAN_AMOUNT;
			$data->save();			
		
			/// Email to the Admin
			$subject   = 'PSYCHAT - Continued chat order';
			$text2send =
				"Client ".$chatContext->client->login." continued chat order # $chatContext->session_id with: ".$chatContext->reader->name."<br>
				DATE: TIME: ".date("h:i a M, j")."<br>
				USED: ".round($freeTime->Total_sec/60,2)." free minutes";

			PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $text2send);
		}

	}

        public static function getTransactionByUserId($user_id){
            return self::model()->exists(array(
                    'condition' => 'UserID = :user_id',
                    'params' => array(':user_id' => $user_id)
            ));
        }
        
        public static function getTransaction($id)
        {
            return self::model()->findByPk($id);
        }

}