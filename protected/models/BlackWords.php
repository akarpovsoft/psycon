<?php

class BlackWords extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'black_words':
	 * @var integer $ID
	 * @var string $Word
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
		return PsyStaticDataProvider::getTableName('black_words');
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Word', 'length', 'max'=>150),
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
			'Word' => 'Word',
		);
	}
        /**
         * Check current word black list matching
         *
         * @param string $word Checking word
         * @return boolean
         */
        public static function checkWord($word){
            if(preg_match("/([0-9]{5,})/", $word." ", $matches))
                return false;
            $bws = self::model()->findAll();
            foreach($bws as $bw){
                $bw->Word=addcslashes($bw->Word,"\.\@");
                if (preg_match("/" . $bw->Word . "/i" , $word))
                    return false;
            }
            return true;
        }
}