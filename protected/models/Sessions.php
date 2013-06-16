<?php

class Sessions extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'sessions':
	 * @var string $session_key
	 * @var integer $session_expire
	 * @var string $session_value
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
		return 'sessions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('session_value', 'required'),
			array('session_expire', 'numerical', 'integerOnly'=>true),
			array('session_key', 'length', 'max'=>32),
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
			'session_key' => 'Session Key',
			'session_expire' => 'Session Expire',
			'session_value' => 'Session Value',
		);
	}
        
        public static function getSession($key)
        {
            return self::model()->findByPk($key);
        }
        
        public function getUserId()
        {
            $stmt = array();
            preg_match_all('/.*login_operator_id|s:[\d]+:\"(\d+)\"\;/', $this->session_value, $stmt);
         
            return $stmt[1][1];
        }
}