<?php

class UserBan extends CActiveRecord
{
    const PER_PAGE = 20;
	/**
	 * The followings are the available columns in table 'userban':
	 * @var integer $ban_id
	 * @var integer $reader_id
	 * @var integer $user_id
	 * @var string $reason
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
		return 'userban';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('reader_id, user_id', 'numerical', 'integerOnly'=>true),
			array('reason', 'length', 'max'=>255),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array('users' => array(self::BELONGS_TO, 'users', 'user_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ban_id' => 'Ban',
			'reader_id' => 'Reader',
			'user_id' => 'User',
			'reason' => 'Reason',
		);
	}
        /**
         * Return list of banned clients by current reader
         *
         * @param <integer> $reader_id
         * @return <CActiveDataProvider>
         */
        public static function getReaderBanList($reader_id){
            $criteria = new CDbCriteria;
            $criteria->condition = 'reader_id = :reader_id';
            $criteria->params = array(':reader_id' => $reader_id);
            $criteria->with = 'users';

            $dataProvider = new CActiveDataProvider('UserBan', array(
                'criteria' => $criteria,
                'pagination' => array(
                    'pageSize' => self::PER_PAGE
                )
            ));

            return $dataProvider;
        }

        /**
         * Deletes ban by id
         *
         * @param <integer> $ban_id
         */
        public static function deleteBan($ban_id){
            self::model()->deleteByPk($ban_id);
        }
        
        public static function checkUserBan($client_id, $reader_id)
        {
            return self::model()->count(array(
                'condition' => 'reader_id = :reader_id AND user_id = :client_id',
                'params' => array(':reader_id' => $reader_id, ':client_id' => $client_id)
            ));
        }
}