<?php

/**
 * This is the model class for table "balance_log".
 *
 * The followings are the available columns in table 'balance_log':
 * @property integer $id
 * @property integer $client_id
 * @property string $date
 * @property integer $operation
 * @property string $sum
 * @property string $balance
 * @property string $notes
 *
 * The followings are the available model relations:
 */
class BalanceLog extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return BalanceLog the static model class
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
		return 'balance_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('client_id, operation', 'numerical', 'integerOnly'=>true),
			array('sum, balance', 'length', 'max'=>6),
			array('date, notes', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, client_id, date, operation, sum, balance, notes', 'safe', 'on'=>'search'),
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
			'client_id' => 'Client',
			'date' => 'Date',
			'operation' => 'Operation',
			'sum' => 'Sum',
			'balance' => 'Balance',
			'notes' => 'Notes',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('operation',$this->operation);
		$criteria->compare('sum',$this->sum,true);
		$criteria->compare('balance',$this->balance,true);
		$criteria->compare('notes',$this->notes,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}