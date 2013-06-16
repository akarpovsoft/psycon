<?php

class EmailReaders extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'email_readers':
	 * @var integer $reader_id
	 * @var string $q1
	 * @var string $q2
	 * @var string $q3
	 * @var string $q4
	 * @var string $q5
	 * @var string $special
	 * @var string $status
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
		return 'email_readers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('reader_id', 'numerical', 'integerOnly'=>true),
			array('q1, q2, q3, q4, q5, special', 'length', 'max'=>10),
			array('status', 'length', 'max'=>8),
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
			'q1' => 'Q1',
			'q2' => 'Q2',
			'q3' => 'Q3',
			'q4' => 'Q4',
			'q5' => 'Q5',
			'special' => 'Special',
			'status' => 'Status',
		);
	}

        public static function getReaderRates($id){
            return self::model()->findByPk($id);
        }
        
        public static function getCurrentRate($reader_id, $type)
        {
            $rates = self::model()->findByPk($reader_id);
            
            switch($type)
            {
                case 1:
                    $rate = $rates->q1;
                    break;
                case 2:
                    $rate = $rates->q2;
                    break;
                case 3:
                    $rate = $rates->q3;
                    break;
                case 4:
                    $rate = $rates->q4;
                    break;
                case 5:
                    $rate = $rates->q5;
                    break;
                case 'SPECIAL':
                    $rate = $rates->special/2;
                    break;
            }
            
            return $rate;
        }
}