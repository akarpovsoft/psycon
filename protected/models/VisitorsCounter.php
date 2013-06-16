<?php

class VisitorsCounter extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'visitors_counter':
	 * @var string $ip
	 * @var string $ts
	 * @var integer $affiliate
	 * @var string $page_type
	 */
    
        public $sum;
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
		return 'visitors_counter';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ip, affiliate, page_type', 'required'),
			array('affiliate', 'numerical', 'integerOnly'=>true),
			array('ip', 'length', 'max'=>25),
                        array('ip', 'unique'),
			array('page_type', 'length', 'max'=>6),
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
			'ip' => 'Ip',
			'ts' => 'Ts',
			'affiliate' => 'Affiliate',
			'page_type' => 'Page Type',
		);
	}
        
        public function register()
        {
            $this->save();
        }

        public static function report($startDate, $endDate)
        {
            $homepage_report = array();
            $signup_report = array();
            
            $criteria = new CDbCriteria;
            $criteria->select = 'COUNT(`ip`) as `sum`, affiliate';
            $criteria->condition = '`page_type` = :page_type AND UNIX_TIMESTAMP(`ts`) > UNIX_TIMESTAMP(:start) AND 
                UNIX_TIMESTAMP(`ts`) < UNIX_TIMESTAMP(:end)';
            $criteria->params = array(':start' => $startDate, ':end' => $endDate, ':page_type' => 'home');
            $criteria->group = 'affiliate';
            
            $home_visitors = self::model()->findAll($criteria);
            
            foreach($home_visitors as $group)
            {
                if($group->affiliate == 0)
                {
                    $homepage_report['none'] = $group->sum;
                    continue;
                }
                $homepage_report[$group->affiliate] = $group->sum;                
            }               
            $criteria->params[':page_type'] = 'signup';
            
            $signup_visitors = self::model()->findAll($criteria);
            
            foreach($signup_visitors as $group)
            {
                if($group->affiliate == 0)
                {
                    $signup_report['none'] = $group->sum;
                    continue;
                }
                $signup_report[$group->affiliate] = $group->sum;                
            }
            return array('home' => $homepage_report, 'signup' => $signup_report);            
        }
}