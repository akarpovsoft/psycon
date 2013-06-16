<?php

/**
 * This is the model class for table "total_online_time".
 *
 * The followings are the available columns in table 'total_online_time':
 * @property integer $reader_id
 * @property integer $time_online
 * @property integer $last_update
 * @property string $last_reset
 */
class TotalOnlineTime extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return TotalOnlineTime the static model class
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
		return 'total_online_time';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('reader_id, time_online, last_update', 'numerical', 'integerOnly'=>true),
			array('last_reset', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('reader_id, time_online, last_update, last_reset', 'safe', 'on'=>'search'),
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
			'reader_id' => 'Reader',
			'time_online' => 'Time Online',
			'last_update' => 'Last Update',
			'last_reset' => 'Last Reset',
		);
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

		$criteria->compare('reader_id',$this->reader_id);

		$criteria->compare('time_online',$this->time_online);

		$criteria->compare('last_update',$this->last_update);

		$criteria->compare('last_reset',$this->last_reset,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}