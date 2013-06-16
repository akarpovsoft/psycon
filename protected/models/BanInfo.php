<?php

class BanInfo extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'ban_info':
	 * @var integer $user_id
	 * @var string $username
	 * @var string $who_banned
	 * @var string $ip_who_banned
	 * @var string $reason
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
		return 'ban_info';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>25),
			array('who_banned', 'length', 'max'=>30),
			array('ip_who_banned', 'length', 'max'=>16),
			array('reason', 'length', 'max'=>255),
			array('date', 'safe'),
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
			'user_id' => 'User',
			'username' => 'Username',
			'who_banned' => 'Who Banned',
			'ip_who_banned' => 'Ip Who Banned',
			'reason' => 'Reason',
			'date' => 'Date',
		);
	}
        /**
         * Returns info about user's bans
         *
         * @param integer $user_id
         * @return this object
         */
        public static function getUserBan($user_id){
            return self::model()->findByPk($user_id);
        }
}