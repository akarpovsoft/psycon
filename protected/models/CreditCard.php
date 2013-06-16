<?php

class CreditCard extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'T1_4':
	 * @var integer $rr_record_id
	 * @var integer $rr_account_id
	 * @var integer $rr_emailsent
	 * @var integer $rr_synchronized
	 * @var string $rr_createdate
	 * @var string $rr_lastaccess
	 * @var integer $account_id
	 * @var string $amount
	 * @var integer $operator_id
	 * @var string $firstname
	 * @var string $lastname
	 * @var string $billingaddress
	 * @var string $billingcity
	 * @var string $billingstate
	 * @var string $billingzip
	 * @var string $billingcountry
	 * @var string $card
	 * @var string $cardnumber
	 * @var string $month
	 * @var string $year
	 */
    public $cardnumber;
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
		return PsyStaticDataProvider::getTableName('credit_card');
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rr_record_id, rr_account_id, rr_emailsent, rr_synchronized, account_id, operator_id', 'numerical', 'integerOnly'=>true),
			array('amount', 'length', 'max'=>10),
			array('firstname, lastname', 'length', 'max'=>150),
			array('billingaddress, billingstate, billingcountry, card, month, year', 'length', 'max'=>255),
			array('billingcity', 'length', 'max'=>100),
			array('billingzip', 'length', 'max'=>50),
			array('cardnumber', 'length', 'max'=>20),
			array('rr_createdate', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array('account' => array(self::BELONGS_TO, 'users', 'rr_record_id')
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
			'account_id' => 'Account',
			'amount' => 'Amount',
			'operator_id' => 'Operator',
			'firstname' => 'Firstname',
			'lastname' => 'Lastname',
			'billingaddress' => 'Billingaddress',
			'billingcity' => 'Billingcity',
			'billingstate' => 'Billingstate',
			'billingzip' => 'Billingzip',
			'billingcountry' => 'Billingcountry',
			'card' => 'Card',
			'month' => 'Month',
			'year' => 'Year',
		);
	}
        
        /**
         * Card number checking for current user. Returns true if card is not exists in db
         * 
         * @param string $cardnumber Number of checking card
         * @param integer $user_id
         * @return boolean
         */
        public static function check($cardnumber, $user_id) {
            $hash = md5(PsyConstants::getName(PsyConstants::ADD_MD5_1).$cardnumber.PsyConstants::getName(PsyConstants::ADD_MD5_2));
            $exist = self::model()->findAll(array(
                    'condition' => 'hash1 = :hash AND rr_record_id <> :user_id',
                    'params' => array(':hash' => $hash, ':user_id' => $user_id)
            ));
            
            $hist_exist = CreditCardHistory::cardExisting($hash, $user_id);
            
            if (($exist == true)||($hist_exist == true))
		//return true;
                return false;
            else
                return true;
        }
        /**
         * Credit card checking only by hash card number (for registration).
         *
         * @param string $cardnumber md5 hash of number of checking card
         * @return boolean
         */
        public static function checkNumber($cardnumber) {
            $hash = md5(PsyConstants::getName(PsyConstants::ADD_MD5_1).$cardnumber.PsyConstants::getName(PsyConstants::ADD_MD5_2));
            $exist = self::model()->exists(array(
                    'condition' => 'hash1 = :hash1',
                    'params' => array(':hash1' => $hash)
            ));

            $hist_exist = CreditCardHistory::checkNumber($hash);
            if (($exist == true)||($hist_exist == true))
                return false;
            else
                return true;
        }
        /**
         * Blocks card changing in active transaction period
         * 
         * @param string $cardnumber
         * @param integer $user_id
         * @return boolean 
         */
        public static function transactionCheck($cardnumber, $user_id){
            $tran_exist = Transactions::getTransactionByUserId($user_id);
            $hash = md5(PsyConstants::getName(PsyConstants::ADD_MD5_1).$cardnumber.PsyConstants::getName(PsyConstants::ADD_MD5_2));
            $card_exist = self::model()->exists(array(
                    'condition' => 'hash1 = :hash AND rr_record_id = :user_id',
                    'params' => array(':hash' => $hash, ':user_id' => $user_id)
            ));
            if(($tran_exist == true)&&($card_exist == false))
                return false;
            else
                return true;
        }
        /**
         * If card number is new - update information about user's card number,
         * changing current card number, add to history
         *
         * @param string $cardnumber
         * @param integer $user_id
         * @param string $username User login
         */
        public static function registerNewCard($cardnumber, $user_id, $username, $billingdata = null) {
            $connection = Yii::app()->db;            
            $hash1 = md5(PsyConstants::getName(PsyConstants::ADD_MD5_1).$cardnumber.PsyConstants::getName(PsyConstants::ADD_MD5_2));
            $view1 = $cardnumber[0];
            $view2 = substr($cardnumber, -4);
            $hash2 = substr($cardnumber, 1, strlen($cardnumber)-5);
            $exist = self::model()->exists(array(
                    'condition' => 'hash1 = :hash1 AND rr_record_id = :user_id',
                    'params' => array(':hash1' => $hash1, ':user_id' => $user_id)
            ));
            $hist_exist = CreditCardHistory::model()->exists(array(
                    'condition' => 'hash1 = :hash1 AND user_id = :user_id',
                    'params' => array(':hash1' => $hash1, ':user_id' => $user_id)
            ));
            if(($exist == false)&&($hist_exist == false)) {
                // Update T1_4 table
                $sql = "UPDATE T1_4 
                        SET view1 = '".$view1."',
                            view2 = '".$view2."',
                            hash1 = '".$hash1."',
                            hash2 = AES_ENCRYPT( '".$hash2."', '".PsyConstants::getName(PsyConstants::CC_PASSWORD)."' ) ";
                if($billingdata)
                    $sql .= ", billingaddress = '".$billingdata['address']."',
                        billingcity = '".$billingdata['city']."',
                        billingstate = '".$billingdata['state']."',
                        billingzip = '".$billingdata['zip']."',
                        billingcountry = '".$billingdata['country']."' ";
                $sql .= "WHERE rr_record_id = ".$user_id;
                $command = $connection->createCommand($sql);
                $command->execute();
                //Insert into card history
                $cch = new CreditCardHistory();
                $cch->user_id = $user_id;
                $cch->user_name = $username;
                $cch->view1 = $view1;
                $cch->view2 = $view2;
                $cch->hash1 = $hash1;
                $cch->addCardToHistory();
            }
            else if(($exist == false)&&($hist_exist == true)){
                // Update T1_4 table
                $sql = "UPDATE T1_4
                        SET hash1 = '".$hash1."',
                            view1 = '".$view1."',
                            view2 = '".$view2."',
                            hash2 = AES_ENCRYPT( '".$hash2."', '".PsyConstants::getName(PsyConstants::CC_PASSWORD)."' ) ";
                if($billingdata)
                    $sql .= ", billingaddress = '".$billingdata['address']."',
                        billingcity = '".$billingdata['city']."',
                        billingstate = '".$billingdata['state']."',
                        billingzip = '".$billingdata['zip']."',
                        billingcountry = '".$billingdata['country']."',
                        month = '".$billingdata['exp_month']."',
                        year = '".$billingdata['exp_year']."'";
                $sql .= "WHERE rr_record_id = ".$user_id;
                $command = $connection->createCommand($sql);
                $command->execute();
            }
            else if(($exist == true)&&($hist_exist == false)){
                //Insert into card history
                $cch = new CreditCardHistory();
                $cch->user_id = $user_id;
                $cch->user_name = $username;
                $cch->view1 = $view1;
                $cch->view2 = $view2;
                $cch->hash1 = $hash1;
                $cch->addCardToHistory();
            }
        }
        /**
         * Register new card with new user
         */
        public function registerNewUserCard(){
            $connection = Yii::app()->db;
            $this->rr_createdate = date('Y-m-d');
            $this->rr_lastaccess = date('Y-m-d H:i:s');
            $this->hash1 = md5(PsyConstants::getName(PsyConstants::ADD_MD5_1).$this->cardnumber.PsyConstants::getName(PsyConstants::ADD_MD5_2));
            $this->view1 = $this->cardnumber[0];
            $this->view2 = substr($this->cardnumber, -4);
            $hash2 = substr($this->cardnumber, 1, strlen($this->cardnumber)-5);
            $sql = 'INSERT INTO `T1_4` (
                rr_record_id, 
                rr_account_id, 
                rr_emailsent,
                rr_synchronized, 
                account_id, 
                operator_id, 
                amount, 
                firstname, 
                lastname,
                billingaddress, 
                billingcity, 
                billingstate, 
                billingzip, 
                billingcountry,
                month, 
                year, 
                rr_createdate, 
                rr_lastaccess, 
                hash1, 
                view1, 
                view2, 
                hash2)
                VALUES (
                '.$this->rr_record_id.', 
                    "1", 
                    "0", 
                    "0", 
                    NULL, 
                    NULL, 
                    '.$this->amount.', 
                        "'.$this->firstname.'", 
                            "'.$this->lastname.'",
                    "'.$this->billingaddress.'", 
                        "'.$this->billingcity.'", 
                            "'.$this->billingstate.'", 
                                "'.$this->billingzip.'", 
                                    "'.$this->billingcountry.'", 
                    '.$this->month.', 
                        '.$this->year.', 
                            "'.$this->rr_createdate.'", 
                                "'.$this->rr_lastaccess.'", 
                                    "'.$this->hash1.'", 
                                        "'.$this->view1.'",
                        "'.$this->view2.'", 
                            AES_ENCRYPT( "'.$hash2.'", "'.PsyConstants::getName(PsyConstants::CC_PASSWORD).'" ))';
            $command = $connection->createCommand($sql);
            $command->execute();
            
            $cch = new CreditCardHistory();
            $cch->user_id = $this->rr_record_id;
            $cch->view1 = $this->view1;
            $cch->view2 = $this->view2;
            $cch->hash1 = $this->hash1;
            $cch->addCardToHistory();

        }

        public static function getCard($client_id){
            $sql = "SELECT AES_DECRYPT(`hash2`, '".PsyConstants::getName(PsyConstants::CC_PASSWORD)."') AS `hash`
                        FROM `T1_4`
                        WHERE `rr_record_id` = ".$client_id;
            $connection = Yii::app()->db;
            $command = $connection->createCommand($sql);
            $res = $command->query();
            foreach($res as $r)
                $hash2 = $r['hash'];
            return $hash2;
        }

        public static function getCardInfo($id){
            return self::model()->findByPk($id);
        }
}
