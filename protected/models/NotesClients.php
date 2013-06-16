<?php

/**
 * This is the model class for table "notes_clients".
 *
 * The followings are the available columns in table 'notes_clients':
 * @property string $client
 * @property string $note
 * @property string $date
 * @property integer $flag
 *
 * The followings are the available model relations:
 */
class NotesClients extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return NotesClients the static model class
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
		return 'notes_clients';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('flag', 'numerical', 'integerOnly'=>true),
			array('client', 'length', 'max'=>10),
			array('note, date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('client, note, date, flag', 'safe', 'on'=>'search'),
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
			'client' => 'Client',
			'note' => 'Note',
			'date' => 'Date',
			'flag' => 'Flag',
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

		$criteria->compare('client',$this->client,true);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('flag',$this->flag);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

	public static function getNotes($clientId)
	{
		return self::model()->findAll('client = :client_id', array(':client_id' => $clientId));
	}

	public static function deleteOldNotes()
	{
		$connect = Yii::app()->db;
		$sql ="DELETE from ".tableName()." where (DATE_ADD(date, interval 3 month) < now())";
        $command=$connect->createCommand($sql);
        $command->execute();
	}

	public static function updateNotes($clientId)
	{
		$connect = Yii::app()->db;
		$sql = 'UPDATE notes_clients SET flag = flag + 1 WHERE client = \'' .$clientId. '\'';
		$command = $connect->createCommand($sql);
		$command->execute();

		return;
	}
}