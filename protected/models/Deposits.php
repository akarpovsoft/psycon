<?php

class Deposits extends CActiveRecord
{
    const PER_PAGE = 20;
	/**
	 * The followings are the available columns in table 'deposits':
	 * @var integer $Deposit
	 * @var integer $Client_id
	 * @var string $Amount
	 * @var string $Order_numb
	 * @var string $Date
	 * @var integer $Reader_id
	 * @var string $Bmt
	 * @var string $Notes
	 * @var string $Currency
	 * @var string $Balance_used
	 * @var string $Sessions
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
		return PsyStaticDataProvider::getTableName('deposits');
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Client_id, Reader_id', 'numerical', 'integerOnly'=>true),
			array('Amount, Balance_used, Total_time', 'length', 'max'=>10),
			array('Order_numb, Bmt', 'length', 'max'=>50),
			array('Notes', 'length', 'max'=>100),
			array('Currency', 'length', 'max'=>3),
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
		return array('users' => array(self::BELONGS_TO, 'users', 'rr_record_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Deposit'	=> 'Deposit',
			'Client_id' => 'Client',
			'Amount'	=> 'Amount',
			'Order_numb' => 'Order Numb',
			'Date'		=> 'Date',
			'Reader_id' => 'Reader',
			'Bmt'		=> 'Bmt',
			'Notes'		=> 'Notes',
			'Currency'	=> 'Currency',
			'Balance_used' => 'Balance Used',
			'Sessions'	=> 'Sessions',
			'Total_time' => 'Total Time',
		);
	}
	/**
	 * Save a deposit to table
	 */
	public function addDeposit($bmt = null) {
		$this->Date = date('Y-m-d H:i:s');
		$this->Bmt = $bmt;
		if($this->Currency =="TEST")
			$this->Currency  = "CAD";
		$res = $this->save();
		return mysql_insert_id();
	}

	public function affiliatePay($firstname, $lastname, $login, $txn_id, $affiliate, $grp_id, $mc_gross){
		$aff = "txn_id=".urlencode("PAYPAL:".$txn_id)."&
					fname=".urlencode($firstname)."&
					lname=".urlencode($lastname)."&
					username=".urlencode($login)."&
					aff_id=".$affiliate."&
					group_id=".$grp_id."&
					amount=".$mc_gross;
		$curl = curl_init("http://dev.psychic-contact.com/affpro/callbacks/callback_sample.php");
		curl_setopt ($curl, CURLOPT_HEADER, 0);
		curl_setopt ($curl, CURLOPT_POST, 1);
		curl_setopt ($curl, CURLOPT_POSTFIELDS, $aff);
		curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_exec ($curl);
		curl_close ($curl);
	}
	/**
	 * Returns list of user deposits
	 *
	 * @param integer $user_id
	 * @return object CActiveDataProvider
	 */
	public static function getUsersDeposit($user_id){
		$criteria = new CDbCriteria;
		$criteria->select = 'Date, Amount, Currency, Bmt, Order_numb';
		$criteria->condition = 'Client_ID = :client_id';
                $criteria->order = 'Date DESC';
		$criteria->params = array(':client_id' => $user_id);

		$dataProvider = new CActiveDataProvider('Deposits', array(
					'criteria'	=>	$criteria,
					'pagination'=> array (
						'pageSize'	=> self::PER_PAGE,
					)
		));
		return $dataProvider;
	}
        
        public static function getUserDep($user_id)
        {
            $criteria = new CDbCriteria;
            $criteria->select = 'Date, Amount, Currency, Bmt, Order_numb';
            $criteria->condition = 'Client_ID = :client_id';
            $criteria->params = array(':client_id' => $user_id);
            
            return self::model()->findAll($criteria);
        }

	public static function searchUnmarkedByClientId($client_id){
		$c = new CDbCriteria;
		$c->condition = '`Client_id` = :client_id AND `Balance_used` < `Total_time`';
		$c->params = array(':client_id' => $client_id);
		$c->order = 'Date ASC';

		return self::model()->findAll($c);
	}

	public function getTimePurchased() {
		return Tariff::getDuration($this->Amount);
	}


	/**
	 * Function calc remaining time
	 *
	 * @return <int> seconds remain
	 */
	public function getTime() {
            return ($this->getTimePurchased() - $this->Balance_used);
	}

        public static function checkTxnExist($txn)
        {
            return self::model()->exists(array(
                'condition' => 'Order_numb = :txn',
                'params' => array(':txn' => $txn)
            ));
        }
        
	/**
	 *
	 * @param <int> $sessionId - session id
	 * @param <seconds> $time - time to reduce
	 * 
	 */
	public function acceptSessionTime($sessionId , $time)
	{
		echo $time;
		$this->Balance_used += $time;
		$this->Sessions .= ', '.$sessionId;
		$this->save();
	}
}