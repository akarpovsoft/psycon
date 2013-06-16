<?php

/**
 * This is the model class for table "double_readers".
 *
 * The followings are the available columns in table 'double_readers':
 * @property integer $id
 * @property integer $id2
 */
class DoubleReaders extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return DoubleReaders the static model class
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
		return 'double_readers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, id2', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id2', 'safe', 'on'=>'search'),
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
			'id2' => 'Id2',
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
		$criteria->compare('id2',$this->id2);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public static function getReaderPair($reader_id)
        {
            return self::model()->find(array(
                'condition' => 'id = :reader_id',
                'params' => array(':reader_id' => $reader_id)
            ));
        }
        
        public static function _list()
        {
            return self::model()->findAll();
        }
        
        public static function searchPair($r1, $r2)
        {
            return self::model()->find(array(
                'condition' => 'id = :r1 AND id2 = :r2',
                'params' => array(':r1' => $r1, ':r2' => $r2)
            ));
        }
        
        public static function clearPairs()
        {
            self::model()->deleteAll();
        }
}