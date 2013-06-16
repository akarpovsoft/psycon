<?php

class users extends CActiveRecord
{
    const PER_PAGE = 20;
	/**
	 * The followings are the available columns in table 'users':
	 * @var integer $rr_record_id
	 * @var string $operator_location
	 * @var integer $rr_account_id
	 * @var integer $rr_emailsent
	 * @var integer $rr_synchronized
	 * @var string $rr_createdate
	 * @var string $rr_lastaccess
	 * @var string $name
	 * @var string $password
	 * @var string $password1
	 * @var string $password_orig
	 * @var string $astrologers
	 * @var string $tarotreaders
	 * @var string $clairvoyants
	 * @var string $area
	 * @var string $comments
	 * @var string $login
	 * @var string $emailaddress
	 * @var string $hear
	 * @var string $day
	 * @var string $month
	 * @var string $year
	 * @var string $address
	 * @var string $phone
	 * @var string $seniority
	 * @var string $type
	 * @var string $frames
	 * @var string $status
	 * @var string $laststatusupdate
	 * @var string $balance
	 * @var integer $requestid
	 * @var string $banlist
	 * @var string $gender
	 * @var string $affiliate
	 * @var string $first_reading
	 * @var string $user_type
	 * @var integer $free_mins
	 * @var string $reader_real_name
	 * @var string $lang
	 * @var integer $chat_type
	 */

	public $first_name;
	public $last_name;
	public $nickName;
	public $user_id;
        public $order_numb;
        public $dep_amount;
        public $dep_date;
	
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
		return PsyStaticDataProvider::getTableName('user');
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
			array('rr_account_id, rr_emailsent, rr_synchronized, requestid, free_mins, chat_type', 'numerical', 'integerOnly'=>true),
			array('operator_location, area, address', 'length', 'max'=>255),
			array('name, password, password1, login, hear, type, status', 'length', 'max'=>100),
			array('password_orig, astrologers, tarotreaders, clairvoyants, frames', 'length', 'max'=>18),
			array('emailaddress', 'length', 'max'=>70),
			array('day, month, year', 'length', 'max'=>50),
			array('phone', 'length', 'max'=>60),
			array('seniority, first_reading, user_type', 'length', 'max'=>3),
			array('balance', 'length', 'max'=>10),
			array('banlist', 'length', 'max'=>1),
			array('gender', 'length', 'max'=>19),
			array('affiliate', 'length', 'max'=>20),
			array('reader_real_name', 'length', 'max'=>150),
			array('lang', 'length', 'max'=>15),
			array('rr_createdate, comments, laststatusupdate', 'safe'),
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
                    'credit_cards' => array(self::HAS_ONE, 'CreditCard', 'rr_record_id'),
                    'chat_requests' => array(self::HAS_ONE, 'ChatRequests', 'rr_record_id'),
                    'chat_history' => array(self::HAS_MANY, 'ChatHistory', 'rr_record_id'),
                    'userban' => array(self::HAS_ONE, 'UserBan', 'rr_record_id'),
                    'testimonials' => array(self::HAS_ONE, 'Testimonials', 'rr_record_id'),
                    'deposits' => array(self::HAS_ONE, 'Deposits', 'Client_id'),
                    'email_readings' => array(self::HAS_ONE, 'EmailQuestions', 'rr_record_id'),
		);
	}

	public function validatePassword($password)
	{
        return ($password===$this->password);
	}
	
	/**
	 * Return deposits array for this user ordered by Date
	 *
	 */
	public function getUserDeposits() {
        $sql = 'SELECT * FROM deposits WHERE Client_id = :clientID ORDER BY Date DESC LIMIT 0,200';
	    $command = Yii::app()->db->createCommand($sql);
	    $command->bindParam(':clientID',Yii::app()->user->id,PDO::PARAM_INT);
	    $rows = $command->queryAll();
        return $rows;
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'rr_record_id' => 'Rr Record',
			'operator_location' => 'Operator Location',
			'rr_account_id' => 'Rr Account',
			'rr_emailsent' => 'Rr Emailsent',
			'rr_synchronized' => 'Rr Synchronized',
			'rr_createdate' => 'Rr Createdate',
			'rr_lastaccess' => 'Rr Lastaccess',
			'name' => 'Name',
			'password' => 'Password',
			'password1' => 'Password1',
			'password_orig' => 'Password Orig',
			'astrologers' => 'Astrologers',
			'tarotreaders' => 'Tarotreaders',
			'clairvoyants' => 'Clairvoyants',
			'area' => 'Area',
			'comments' => 'Comments',
			'login' => 'Login',
			'emailaddress' => 'Emailaddress',
			'hear' => 'Hear',
			'day' => 'Day',
			'month' => 'Month',
			'year' => 'Year',
			'address' => 'Address',
			'phone' => 'Phone',
			'seniority' => 'Seniority',
			'type' => 'Type',
			'frames' => 'Frames',
			'status' => 'Status',
			'laststatusupdate' => 'Laststatusupdate',
			'balance' => 'Balance',
			'requestid' => 'Requestid',
			'banlist' => 'Banlist',
			'gender' => 'Gender',
			'affiliate' => 'Affiliate',
			'first_reading' => 'First Reading',
			'user_type' => 'User Type',
			'free_mins' => 'Free Mins',
			'reader_real_name' => 'Reader Real Name',
			'lang' => 'Lang',
			'chat_type' => 'Chat Type',
		);
	}


	public static function getUser($id){
		return self::model()->findByPk($id);
	}

	public static function getUserByLogin($login){
		return self::model()->exists(array(
		'condition' => 'login = :login',
		'params' => array(':login' => $login)
		));
	}

	public static function getUserByEmail($email){
		return self::model()->find(array(
		'condition' => 'emailaddress = :email',
		'params' => array(':email' => $email)
		));
	}

	protected function afterFind() {
	    $tmp = preg_split('/[\s]+/i',$this->name);
	    $this->first_name = $tmp[0];
	    $this->last_name = $tmp[1];
            $this->user_id = $this->rr_record_id;
	}

	////////////////////// functions

	public function getLogin()
	{
		return $this->login;
	}
	
	public function getScreenName()
	{
		return $this->getLogin();
	}

	public function getId(){
		return $this->rr_record_id;
	}

	public function getName()
	{
		return $this->name;
	}
	
	
	public function getEmail()
	{
		return $this->emailaddress;
	}
	
    public function getDOB()
    {
        return $this->year."-".$this->month."-".$this->day;
    }
    
    public function getDOBTimestamp()
    {
        return mktime(0, 0, 0, $this->MonthToDate($this->month), (int)$this->day, (int)$this->year);
    }
	
    public function getSignupDate()
    {
        return $this->rr_createdate;
    }
	
    private function MonthToDate($month)
    {
        switch($month)
        {
            case 'Jan':
                $date = 1;
                break;
            case 'Feb':
                $date = 2;
                break;
            case 'Mar':
                $date = 3;
                break;
            case 'Apr':
                $date = 4;
                break;
            case 'May':
                $date = 5;
                break;
            case 'Jun':
                $date = 6;
                break;
            case 'Jul':
                $date = 7;
                break;
            case 'Aug':
                $date = 8;
                break;
            case 'Sep':
                $date = 9;
                break;
            case 'Oct':
                $date = 10;
                break;
            case 'Nov':
                $date = 11;
                break;
            case 'Dec':
                $date = 12;
                break;           
        }
        return $date;
    }
    
    public static function getCaPaymentsInUSD($month = false, $year = false)
    {
        $criteria = new CDbCriteria;
        $criteria->select = "t.login, deposits.Order_numb as order_numb, deposits.Date as dep_date, deposits.Amount as dep_amount";
        $criteria->condition = "deposits.Client_id = t.rr_record_id AND credit_cards.rr_record_id= t.rr_record_id AND t.site_type = 'CA' AND deposits.currency='USD'";
        if($month)
            $criteria->condition .= " AND MONTH(deposits.Date) = ".$month;
        if($year)
            $criteria->condition .= " AND YEAR(deposits.Date) = ".$year;
        $criteria->order = 'deposits.Date DESC';
        $criteria->with = array('credit_cards', 'deposits');
        
         $dataProvider=new CActiveDataProvider('users', array(
                        'criteria'=>$criteria,
                        'pagination'=> array(
                                'pageSize'=> self::PER_PAGE, 
                                'params' => array(
                                    'paging' => 1,
                                    'month' => $month,
                                    'year' => $year
                                ),
                        )
         ));
         return $dataProvider;
    }

}