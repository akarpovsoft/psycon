<?php

class Testimonials extends CActiveRecord
{
    const PER_PAGE = 20;
    
    public static $messages = array(
        1 => 'Your testimonial is sent to admin to approve'
    );
	/**
	 * The followings are the available columns in table 'testimonials':
	 * @var integer $id
	 * @var string $ts
	 * @var string $tm_date
         * @var string $tm_member
	 * @var string $tm_text
	 * @var string $tm_status
	 * @var integer $reader_id
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
		return 'testimonials';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tm_date, tm_member, tm_text, tm_status, reader_id', 'required'),
			array('reader_id', 'numerical', 'integerOnly'=>true),
			array('tm_date', 'length', 'max'=>50),
			array('tm_status', 'length', 'max'=>8),
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
                    'account' => array(self::BELONGS_TO, 'users', 'reader_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'ts' => 'Ts',
			'tm_date' => 'Date',
			'tm_text' => 'Text',
			'tm_status' => 'Status',
                        'tm_member' => 'Member name',
			'reader_id' => 'Reader',
		);
	}
        
        /**
         * Get testimonials by reader id
         * Grid param for different results: array of model or CActiveDataProvider (for grid)
         * 
         * @param integer $reader_id
         * @param boolean $grid
         * @return mixed 
         */
        public static function getByReaderId($reader_id, $grid = false)
        {
            $c = new CDbCriteria();
            $c->condition = 'reader_id = :reader_id AND tm_status = "approved"';
            $c->params = array(':reader_id' => $reader_id);
            $c->order = 'id DESC';
            
            if($grid)
                return new CActiveDataProvider('Testimonials', array(
                    'criteria' => $c,
                    'pagination'=> array(
                        'pageSize'=> self::PER_PAGE,
                    )
                ));
            else
                return self::model()->findAll($c);
        }
        
        public static function getById($id)
        {
            return self::model()->findByPk($id);
        }
        
        public static function getPending()
        {
            $c = new CDbCriteria();
            $c->condition = 'tm_status = "pending"'; 
            $c->with = 'account';
            $c->order = 'id DESC';
            return new CActiveDataProvider('Testimonials', array(
                    'criteria' => $c,
                    'pagination'=> array(
                        'pageSize'=> self::PER_PAGE,
                    )
                ));
        }
        
        public static function getCount($reader_id)
        {
            $c = new CDbCriteria();
            $c->condition = 'reader_id = :reader_id AND tm_status = "approved"';
            $c->params = array(':reader_id' => $reader_id);
            
            return self::model()->count($c);
        }

        public function isMine()
        {
            if($this->reader_id == Yii::app()->user->id || Yii::app()->user->isAdmin())
                return true;
            else
                return false;
        }
        
        public function addTestimonial()
        {
            $this->tm_status = 'pending';
            $this->save();
        }
        
        public function approve()
        {
            $this->tm_status = 'approved';
            $this->save();
        }
}