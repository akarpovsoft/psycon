<?php

class ChatSession extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'T1_12':
	 * @var integer $rr_record_id
	 * @var integer $rr_account_id
	 * @var integer $rr_emailsent
	 * @var integer $rr_synchronized
	 * @var string $rr_createdate
	 * @var string $rr_lastaccess
	 * @var integer $reader_id
	 * @var integer $client_id
	 * @var string $type
	 * @var string $laststatusupdate
	 * @var string $duration
	 * @var string $reader_add_lost_time
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
		return 'T1_12';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		array('rr_lastaccess', 'required'),
		array('rr_account_id, rr_emailsent, rr_synchronized, reader_id, client_id', 'numerical', 'integerOnly'=>true),
		array('type', 'length', 'max'=>50),
		array('duration', 'length', 'max'=>11),
		array('reader_add_lost_time', 'length', 'max'=>4),
		array('rr_createdate, laststatusupdate', 'safe'),
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
		'rr_account_id' => 'Rr Account',
		'rr_emailsent' => 'Rr Emailsent',
		'rr_synchronized' => 'Rr Synchronized',
		'rr_createdate' => 'Rr Createdate',
		'rr_lastaccess' => 'Rr Lastaccess',
		'reader_id' => 'Reader',
		'client_id' => 'Client',
		'type' => 'Type',
		'laststatusupdate' => 'Laststatusupdate',
		'duration' => 'Duration',
		'reader_add_lost_time' => 'Reader Add Lost Time',
		);
	}

	/**
		 * Delete all sessions with $readerId reader
		 *
		 */
	public static function deleteByReaderId($readerId)
	{
		self::model()->deleteAll("reader_id = :reader_id", array(':reader_id' => $readerId));
	}

	public static function getByReaderId($reader_id){
		return self::model()->findAll(array(
		'condition' => 'reader_id = :reader_id',
		'params' => array(':reader_id' => $reader_id)
		));
	}

	/**
		 * Delete old sessions( older 1 hour)
		 */
	public static function deleteOldSessions()
	{
		self::model()->deleteAll('DATE_ADD( rr_lastaccess, INTERVAL 1 HOUR ) <  now()', array());
	}

	public static function readerBusy($readerId)
	{
		$c = new CDbCriteria;
		$c->condition = '`reader_id` = :reader_id and (DATE_ADD(laststatusupdate, interval 17 second) > now())';
		$c->params = array(':reader_id' => $readerId);
		$model = self::model()->find($c);

		return (!is_null($model));
	}
}