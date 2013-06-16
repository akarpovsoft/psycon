<?php

class BmtStat extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'bmt_stat':
	 * @var integer $ID_bmt
	 * @var integer $ID
	 * @var double $Time
	 * @var string $Date
	 */
	public $total;

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
		return 'BMT_stat';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ID', 'numerical', 'integerOnly'=>true),
			array('Time', 'numerical'),
			array('Date', 'safe'),
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
			'ID_bmt' => 'Id Bmt',
			'ID' => 'Id',
			'Time' => 'Time',
			'Date' => 'Date',
		);
	}

        public static function processBmt($client_id){
            $c = new CDbcriteria;
            $c->condition = 'ID = :id';
            $c->params = array(':id' => $client_id);

            $bmt_stat = self::model()->findAll($c);
            foreach($bmt_stat as $stat){
                $time_bmt += $stat->Time;
            }
            if($time_bmt > 0){
                self::model()->deleteAll($c);
            }
            return $time_bmt;
        }

		public static function add($clientId, $minutes)
		{
			$connect = Yii::app()->db;
			$sql = "INSERT INTO ".self::tableName()."
                VALUES ('',
                        '".$clientId."',
                        '".$minutes."',
                        now())";
			$command = $connect->createCommand($sql);
			$command->execute();
			unset($command);
		}

		public static function getClientBmt($clientId)
		{
			$crit = new CDbCriteria;			
			$crit->select = new CDbExpression('SUM(Time) as total');
			$crit->condition = 'ID = :ID';
			$crit->params = array(':ID' => $clientId);
			$data = self::model()->find($crit);
		
			return ($data->total > 0) ? $data->total : 0;


		}

                public static function deleteByClientId($client_id){
                    self::model()->deleteAll('ID = :client_id', array(':client_id' =>$client_id));
                }
}