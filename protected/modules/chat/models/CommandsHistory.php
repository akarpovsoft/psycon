<?php

class CommandsHistory extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'commands_history':
	 * @var integer $id
	 * @var string $command
	 * @var string $params
	 * @var integer $to_userid
	 * @var integer $session_id
	 * @var string $ts
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
		return 'commands_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('params, session_id, ts', 'required'),
			array('to_userid, session_id', 'numerical', 'integerOnly'=>true),
			array('command', 'length', 'max'=>50),
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
			'id' => 'Id',
			'command' => 'Command',
			'params' => 'Params',
			'to_userid' => 'To Userid',
			'session_id' => 'Session',
			'ts' => 'Ts',
		);
	}
        
        public static function getTranscripts($session_id)
        {
            return self::model()->findAll(array(
                'condition' => 'session_id = :session_id AND command = "text"',
                'params' => array(':session_id' => $session_id)
            ));
        }
        
        public static function getInterlocutors($session_id)
        {
            $c = new CDbCriteria();
            $c->condition = 'session_id = :session_id';
            $c->params = array(':session_id' => $session_id);
            $c->group = 'to_userid';
            
            return self::model()->findAll($c);
        }
}