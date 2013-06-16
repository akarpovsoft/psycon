<?php

/**
 * This is the model class for table "site_question_users".
 *
 * The followings are the available columns in table 'site_question_users':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $type
 * @property string $email
 * @property string $profile
 *
 * The followings are the available model relations:
 */
class SiteQuestionUsers extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return SiteQuestionUsers the static model class
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
		return 'site_question_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, email', 'required'),
			array('username, password, type, email', 'length', 'max'=>128),
			array('profile', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, password, type, email, profile', 'safe', 'on'=>'search'),
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
			'username' => 'Username',
			'password' => 'Password',
			'type' => 'Type',
			'email' => 'Email',
			'profile' => 'Profile',
            'DOB' => 'DOB',
		);
	}
    
        protected function beforeSave()
		{
		    if(parent::beforeSave())
		    {
		        if($this->isNewRecord)
		        {
		            $this->type='client';
		        }
		        return true;
		    }
		    else
		        return false;
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('profile',$this->profile,true);
        $criteria->compare('DOB',$this->DOB,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}