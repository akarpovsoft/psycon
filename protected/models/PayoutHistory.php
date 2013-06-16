<?php

class PayoutHistory extends CActiveRecord
{
    const PER_PAGE = 20;
	/**
	 * The followings are the available columns in table 'payout_history':
	 * @var integer $ID
	 * @var integer $Reader_id
	 * @var string $Reader_login
	 * @var string $Date_submited
	 * @var string $Date_paid
	 * @var string $Usd
	 * @var string $Cad
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
		return 'payout_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Reader_id', 'numerical', 'integerOnly'=>true),
			array('Reader_login', 'length', 'max'=>100),
			array('Usd, Cad', 'length', 'max'=>10),
			array('Date_submited, Date_paid', 'safe'),
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
			'ID' => 'Id',
			'Reader_id' => 'Reader',
			'Reader_login' => 'Reader Login',
			'Date_submited' => 'Date Submited',
			'Date_paid' => 'Date Paid',
			'Usd' => 'Usd',
			'Cad' => 'Cad',
		);
	}

        public static function getHistoryList(){
            return new CActiveDataProvider('PayoutHistory', array(
                'criteria' => new CDbCriteria,
                'pagination'=> array(
                          'pageSize'=> self::PER_PAGE,
                        )
            ));
        }
}