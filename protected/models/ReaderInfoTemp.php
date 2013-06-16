<?php

class ReaderInfoTemp extends CActiveRecord
{
    public $photo;
    
	/**
	 * The followings are the available columns in table 'Reader_Info_Temp':
	 * @var integer $rr_record_id
	 * @var string $operator_location
	 * @var string $name
	 * @var string $password
	 * @var string $astrologers
	 * @var string $tarotreaders
	 * @var string $clairvoyants
	 * @var string $area
	 * @var string $comments
	 * @var string $login
	 * @var string $emailaddress
	 * @var string $address
	 * @var string $phone
	 * @var string $reader_real_name
	 * @var string $lang
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
		return 'Reader_Info_Temp';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rr_record_id', 'numerical', 'integerOnly'=>true),
			array('operator_location, area, address', 'length', 'max'=>255),
			array('name, password, login', 'length', 'max'=>100),
			array('astrologers, tarotreaders, clairvoyants', 'length', 'max'=>18),
			array('emailaddress', 'length', 'max'=>70),
			array('phone', 'length', 'max'=>60),
			array('reader_real_name', 'length', 'max'=>150),
			array('comments', 'safe'),
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
			'rr_record_id' => 'Rr Record',
			'operator_location' => 'Operator Location',
			'name' => 'Name',
			'password' => 'Password',
			'astrologers' => 'Astrologers',
			'tarotreaders' => 'Tarotreaders',
			'clairvoyants' => 'Clairvoyants',
			'area' => 'Area',
			'comments' => 'Comments',
			'login' => 'Login',
			'emailaddress' => 'Emailaddress',
			'address' => 'Address',
			'phone' => 'Phone',
			'reader_real_name' => 'Reader Real Name',
			'lang' => 'Lang',
		);
	}
        
        public static function getInfoByReaderId($reader_id)
        {
            return self::model()->find(array(
                'condition' => 'rr_record_id = :reader_id',
                'params' => array(':reader_id' => $reader_id)
            ));
        }
}