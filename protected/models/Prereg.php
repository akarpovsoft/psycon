<?php

/**
 * This is the model class for table "prereg".
 *
 * The followings are the available columns in table 'prereg':
 * @property integer $id
 * @property string $firstname
 * @property string $lastname
 * @property string $login
 * @property string $password
 * @property string $email
 * @property string $hear
 * @property string $month
 * @property string $day
 * @property string $year
 * @property string $address
 * @property string $balance
 * @property string $affiliate
 * @property string $ip
 * @property integer $freebie
 */
class Prereg extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Prereg the static model class
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
		return 'prereg';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('firstname, lastname, login, password, email, month, day, year, balance, ip', 'required'),
			array('freebie', 'numerical', 'integerOnly'=>true),
			array('firstname, lastname, login, password, hear', 'length', 'max'=>100),
			array('email, ip', 'length', 'max'=>70),
			array('month, day, year', 'length', 'max'=>50),
			array('address', 'length', 'max'=>255),
			array('balance', 'length', 'max'=>10),
			array('affiliate', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, firstname, lastname, login, password, email, hear, month, day, year, address, balance, affiliate, ip, freebie', 'safe', 'on'=>'search'),
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
			'firstname' => 'Firstname',
			'lastname' => 'Lastname',
			'login' => 'Login',
			'password' => 'Password',
			'email' => 'Email',
			'hear' => 'Hear',
			'month' => 'Month',
			'day' => 'Day',
			'year' => 'Year',
			'address' => 'Address',
			'balance' => 'Balance',
			'affiliate' => 'Affiliate',
			'ip' => 'Ip',
			'freebie' => 'Freebie',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('firstname',$this->firstname,true);
		$criteria->compare('lastname',$this->lastname,true);
		$criteria->compare('login',$this->login,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('hear',$this->hear,true);
		$criteria->compare('month',$this->month,true);
		$criteria->compare('day',$this->day,true);
		$criteria->compare('year',$this->year,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('balance',$this->balance,true);
		$criteria->compare('affiliate',$this->affiliate,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('freebie',$this->freebie);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public static function getPrereggedUser($id)
        {
            return self::model()->findByPk($id);
        }
}