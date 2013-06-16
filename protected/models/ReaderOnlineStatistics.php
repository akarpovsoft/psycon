<?php

class ReaderOnlineStatistics extends CActiveRecord
{
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
		return 'reader_online_statistics';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('reader_id, login_time', 'required'),
		);
	}


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'login_time' => 'Login time',
			'logout_time' => 'Logout time',
		);
	}
	  
	public static function getListByReaderAndDate($readerId, $startDate)
	{
		return new CActiveDataProvider('ReaderOnlineStatistics', array('criteria' =>new CDBCriteria(array(
			'condition' => 'reader_id = :readerId AND login_time> :startDate AND DATE_SUB(login_time,INTERVAL 1 DAY) < :startDate2',
			'params' => array(':readerId' => $readerId, ':startDate' => $startDate, ':startDate2' => $startDate)
         ))));
	}

	public static function login($readerId)
	{
		$connect = Yii::app()->db;
		$sql = "UPDATE ".self::tableName()." SET last = 0 WHERE reader_id= :id";
		$command = $connect->createCommand($sql);
		$command->bindParam(":id", $readerId, PDO::PARAM_STR);		
		$command->execute();
		unset($command);
		
		$model=new self();
		$model->reader_id = $readerId;
		$model->login_time = new CDbExpression("NOW()");
		$model->save();
	}

	/**
	 * Save fresh logout time
	 * it saves automatically during update because it is timestamp
	 *
	 * @param unknown_type $readerId
	 */
	public static function refreshLogin($readerId)
	{
		$connect = Yii::app()->db;
		$sql = "UPDATE ".self::tableName()." SET cnt = cnt+1 WHERE reader_id= :id AND last = 1";
		$command = $connect->createCommand($sql);
		$command->bindParam(":id", $readerId, PDO::PARAM_STR);		
		$command->execute();
		unset($command);
	}
	
	public static function deleteOld()
	{
		$connect = Yii::app()->db;
		$sql = "DELETE FROM ".self::tableName()." WHERE  login_time< DATE_SUB(NOW(), INTERVAL 1 MONTH)";
		$command = $connect->createCommand($sql);
		$command->execute();
		unset($command);
	}
	
}