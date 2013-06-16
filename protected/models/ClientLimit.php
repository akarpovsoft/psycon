<?php

class ClientLimit extends CActiveRecord
{
	const OK=1;
	const WRONG_DAY=2;
	const WRONG_MONTH=3;
	
	/**
	 * The followings are the available columns in table 'band_list':
	 * @var integer $UserID
	 * @var string $UserName
	 * @var string $FullName
	 * @var string $IP
	 * @var string $Date
	 * @var string $Client_status
	 * @var string $Month_date
	 * @var double $Sum_month
	 * @var string $Day_date
	 * @var double $Sum_day
	 * @var string $Notes
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
		return PsyStaticDataProvider::getTableName('client_limit');
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('UserID', 'numerical', 'integerOnly'=>true),
			array('Sum_month, Sum_day', 'numerical'),
			array('UserName, FullName', 'length', 'max'=>100),
			array('Client_status', 'length', 'max'=>16),
			array('Notes', 'length', 'max'=>150),
			array('IP, Date, Month_date, Day_date', 'safe'),
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
			'UserID' => 'User',
			'UserName' => 'User Name',
			'FullName' => 'Full Name',
			'IP' => 'Ip',
			'Date' => 'Date',
			'Client_status' => 'Client Status',
			'Month_date' => 'Month Date',
			'Sum_month' => 'Sum Month',
			'Day_date' => 'Day Date',
			'Sum_day' => 'Sum Day',
			'Notes' => 'Notes',
		);
	}

        /**
         * Checking money limits for current user
         *
         * @param integer $user_id
         * @param float $money Current payment amount
         * @return boolean
         */
        public static function testLimit($user_id, $money){
            $profile = self::model()->findByPk($user_id);
            
            if(($profile->Client_status == 'banned')||
                    ($profile->Client_status == 'banned_by_reader')) {
                return false;
            }
            $personal = PersonalLimits::getUserLimits($user_id);           
            if($profile->Client_status == 'preferred') {
                if(!empty($personal)) {
                    if($money+$profile->Sum_day > $personal->Day_limit)
                    	return false;
                    if($money+$profile->Sum_month > $personal->Month_limit)
                    	return false;
                }
            }
            else {
            	
                if(empty($personal)) {
                	$dayLimiit=PsyConstants::getName(PsyConstants::DAY_LIMIT);
                	$monthLimit=PsyConstants::getName(PsyConstants::MONTH_LIMIT);
                }
                else {
                	$dayLimiit=$personal->Day_limit;
                	$monthLimit=$personal->Month_limit;
                }
                
				if($money+$profile->Sum_day > $dayLimiit)
                  	return false;

				if($money+$profile->Sum_month > $monthLimit)
                  	return false;
            }
            
			return true;

        }
        /**
         * Add current payment amount to user
         *
         * @param integer $user_id
         * @param float $money Current payment amount
         */
        public static function registerPayment($user_id, $money){
        	
            $profile = self::model()->findByPk($user_id);
            $profile->Sum_day += $money;
            $profile->Sum_month += $money;
            $profile->save();
        }
        /**
         * Add new client info (signup)
         */
        public function registerNew(){
            $this->Date = date('Y-m-d H:i:s');
            $this->Client_status = 'limited';
            $this->Notes = 'The client did not activate the account after signing up';
            $this->save();
        }

 /**
         * Returns a list of user's balances, limits, permissions
         * 
         * @param integer $user_id
         * @return this object
         */
        public static function getUserInfo($user_id){
            return self::model()->findByPk($user_id);
        }

        public static function getInfoByName($client_name){
            return self::model()->find(array(
                    'condition' => 'LOWER(`UserName`) = :username',
                    'params' => array(':username' => $client_name)
                ));
        }
}