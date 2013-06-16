<?php

class BlogDomains extends CActiveRecord
{
    const MD5_KEY = 'djgn5l2nh';
	/**
	 * The followings are the available columns in table 'blog_domains':
	 * @var integer $id
	 * @var string $key
	 * @var string $name
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

        public function getDbConnection(){
            return Yii::app()->blog_db;
        }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'blog_domains';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('key, name', 'length', 'max'=>100),
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
                    'blog_threads' => array(self::HAS_MANY, 'Threads', 'id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'key' => 'Key',
			'name' => 'Name',
		);
	}

        public static function getAllDomainsData(){
            return new CActiveDataProvider('BlogDomains', array(
                        'criteria' => new CDbCriteria
            ));
        }

        public static function getAllDomains(){
            return self::model()->findAll();
        }

        public function addDomain(){
            $this->key = md5($this->name.self::MD5_KEY.$this->name);
            $this->save();
        }

        public static function getDomain($id){
            return self::model()->findByPk($id);
        }

        public static function getDomainByKey($key){
            return self::model()->find(array(
                'condition' => '`key` = :key',
                'params' => array(':key' => $key)
            ));
        }
}