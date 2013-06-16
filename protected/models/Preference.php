<?php

class preference extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'preference':
	 * @var integer $preference_id
	 * @var string $user_id
	 * @var string $title
	 * @var string $name
	 * @var string $value
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
		return PsyStaticDataProvider::getTableName('preference');
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('value', 'required'),
			array('user_id, title, name', 'length', 'max'=>32),
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
			'preference_id' => 'Preference',
			'user_id' => 'User',
			'title' => 'Title',
			'name' => 'Name',
			'value' => 'Value',
		);
	}

        public static function getFavouriteReader($client_id){
            return self::model()->find(array(
                'condition' => 'user_id LIKE :client_id AND name = "reader" AND title = "Favorite"',
                'params' => array('client_id' => '%'.$client_id)
            ));
        }
}