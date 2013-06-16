<?php

/**
 * This is the model class for table "nrr_requests".
 *
 * The followings are the available columns in table 'nrr_requests':
 * @property integer $id
 * @property integer $reader_id
 * @property integer $client_id
 * @property string $date
 * @property string $nrr_notes
 */
class NrrRequests extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return NrrRequests the static model class
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
		return 'nrr_requests';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('reader_id, client_id', 'numerical', 'integerOnly'=>true),
			array('date, nrr_notes', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, reader_id, client_id, date, nrr_notes', 'safe', 'on'=>'search'),
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
			'reader_id' => 'Reader',
			'client_id' => 'Client',
			'date' => 'Date',
			'nrr_notes' => 'Nrr Notes',
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

		$criteria->compare('reader_id',$this->reader_id);

		$criteria->compare('client_id',$this->client_id);

		$criteria->compare('date',$this->date,true);

		$criteria->compare('nrr_notes',$this->nrr_notes,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

        public function addNewRequest(){
            $this->date = date('Y-m-d H:i:s');
            $this->save();
        }

        public static function getByReaderId($reader_id){
            return self::model()->findAll(array(
                'condition' => 'reader_id = :reader_id',
                'params' => array(':reader_id' => $reader_id),
                'order' => 'id ASC'
            ));
        }
        
        public static function getRequest($id){
            return self::model()->findByPk($id);
        }
        
        public function delRequest()
        {
            self::model()->deleteByPk($this->id);
        }
}