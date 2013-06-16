<?php

/**
 * This is the model class for table "gofish_fishes".
 *
 * The followings are the available columns in table 'gofish_fishes':
 * @property integer $id
 * @property string $model
 * @property integer $reader
 * @property string $name
 *
 * The followings are the available model relations:
 */
class GoFishFishes extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return GoFishFishes the static model class
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
		return 'gofish_fishes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('reader', 'required'),
			array('reader', 'numerical', 'integerOnly'=>true),
			array('model', 'length', 'max'=>20),
                        array('name', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, model, reader', 'safe', 'on'=>'search'),
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
			'model' => 'Model',
			'reader' => 'Reader',
                        'name' => 'Name'
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
		$criteria->compare('model',$this->model,true);
		$criteria->compare('reader',$this->reader);
                $criteria->compare('name',$this->name);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
    
        public function getReaderModel($id)  {
          return  self::model()->findByAttributes(array('reader' => $id));
        }

        public function haveModel($id)  {
          return  self::model()->findByAttributes(array('reader' => $id))->model;
        }
        
}