<?php

class PersonalLimits extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'personal_limits':
	 * @var integer $ID_client
	 * @var double $Day_limit
	 * @var double $Month_limit
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
		return 'personal_limits';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ID_client', 'numerical', 'integerOnly'=>true),
			array('Day_limit, Month_limit', 'numerical'),
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
			'ID_client' => 'Id Client',
			'Day_limit' => 'Day Limit',
			'Month_limit' => 'Month Limit',
		);
	}
        /**
         * Returns info about user's personal payment limits
         *
         * @param integer $user_id
         * @return this object
         */
        public static function getUserLimits($user_id){
            return self::model()->findByPk($user_id);
        }
}