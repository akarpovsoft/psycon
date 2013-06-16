<?php

class RemoveFreetime extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'remove_freetime':
	 * @var integer $ClientID
	 * @var integer $Seconds
	 * @var string $Timestamp
	 * @var integer $Total_sec
	 * @var integer $Count
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
		return 'remove_freetime';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		array('ClientID, Seconds, Total_sec, Count', 'numerical', 'integerOnly'=>true),
		array('Timestamp', 'length', 'max'=>20),
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
		'ClientID' => 'Client',
		'Seconds' => 'Seconds',
		'Timestamp' => 'Timestamp',
		'Total_sec' => 'Total Sec',
		'Count' => 'Count',
		);
	}

	public static function fixFreeTime($clientId)
	{
		$free_time = self::model()->findByPk($clientId);
		if(!$free_time)
		{
			$ft = new self;
			$ft->ClientID = $clientId;
			$ft->Total_sec = 0;
			$ft->Seconds = 0;
			$ft->Timestamp = time();
			$ft->save();
		}
		else
		{
			$free_time->Total_sec = $free_time->Seconds;
			$free_time->Timestamp = time();
			$free_time->save();
		}

		return true;
	}

	public static function userHasFreeTime($clientId)
	{
		$data = self::model()->findByPk($clientId);
		return ($data['Total_sec'] > 5) ? true : false;
	}

	public static function getFreeTime($clientId)
	{
		$ft = self::model()->findByPk($clientId);
		if(!$ft)
		{
			$ft = new self;
			$ft->ClientID = $clientId;
			$ft->Total_sec = 0;
			$ft->Seconds = 0;
			$ft->Timestamp = time();
			$ft->save();
		}

		return $ft;
	}

	/**
	 * Add extra seconds to free time (during payment)
	 *
	 * @param int $seconds
	 * @param int $deposited set free time as bound to deposited payment
	 * @param boolean $halfPaid is it half paid time? (to reader)
	 */
	public function addFreeTime($seconds, $deposited = 1)
	{
		$this->Total_sec += $seconds;
		$this->Seconds += $seconds;
		$this->deposited = $deposited;
		
		$this->save();
	}

	/**
	 * Add extra seconds to half paid time (during payment)
	 *
	 * @param int $seconds
	 */
	public function addHalfPaidTime($seconds)
	{
		$this->half_seconds += $seconds;
		$this->half_seconds_total += $seconds;
		$this->deposited = 1;
		
		$this->save();
	}
	
	public static function isDeposited($id)
	{
		return self::model()->find(array(
		'condition' => 'ClientID = :client_id',
		'params' => array(':client_id' => $id)
		))->deposited;
	}
}