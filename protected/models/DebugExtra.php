<?php

class DebugExtra extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'debug_extra':
	 * @var integer $client_id
	 * @var integer $reader_id
	 * @var double $old_balance
	 * @var integer $session_id
	 * @var double $new_balance
	 * @var string $notes
	 * @var integer $payment
	 * @var integer $session_finished
	 * @var integer $bmt_time
	 * @var string $deposited_payments
	 * @var integer $add_lost_time
	 * @var string $free_time_before
	 * @var string $free_time_after
	 * @var integer $fixed_by_cron
	 * @var integer $client_finished
	 * @var integer $reader_msg_count
	 * @var integer $client_msg_count
	 * @var string $finish_initiator
	 * @var integer $no_response_from_reader
	 * @var integer $session_failed
	 * @var integer $chat_type
	 * @var integer $hire_me_used
	 * @var string $hireme_time
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
		return 'debug_extra';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			
			array('client_id, reader_id, session_id, payment, session_finished, bmt_time, add_lost_time, fixed_by_cron, client_finished, reader_msg_count, client_msg_count, no_response_from_reader, session_failed, chat_type, hire_me_used', 'numerical', 'integerOnly'=>true),
			array('old_balance, new_balance', 'numerical'),
			array('free_time_before, free_time_after', 'length', 'max'=>6),
			array('finish_initiator', 'length', 'max'=>7),
			array('hireme_time', 'length', 'max'=>10),
			array('deposited_payments', 'safe'),
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
			'client_id' => 'Client',
			'reader_id' => 'Reader',
			'old_balance' => 'Old Balance',
			'session_id' => 'Session',
			'new_balance' => 'New Balance',
			'notes' => 'Notes',
			'payment' => 'Payment',
			'session_finished' => 'Session Finished',
			'bmt_time' => 'Bmt Time',
			'deposited_payments' => 'Deposited Payments',
			'add_lost_time' => 'Add Lost Time',
			'free_time_before' => 'Free Time Before',
			'free_time_after' => 'Free Time After',
			'fixed_by_cron' => 'Fixed By Cron',
			'client_finished' => 'Client Finished',
			'reader_msg_count' => 'Reader Msg Count',
			'client_msg_count' => 'Client Msg Count',
			'finish_initiator' => 'Finish Initiator',
			'no_response_from_reader' => 'No Response From Reader',
			'session_failed' => 'Session Failed',
			'chat_type' => 'Chat Type',
			'hire_me_used' => 'Hire Me Used',
			'hireme_time' => 'Hireme Time',
		);
	}

	/**
	 * Add info to debug table about ajax error happen
	 *
	 */
	public static function addAjaxErrorInfo($sessionId, $notes)
	{
		$data = self::model()->findByPk($sessionId);
		if($data instanceof DebugExtra)
		{
			$data->ajax_error += 1;
			$data->ajax_error_notes .= '==='. $notes;
			$data->save();
		}
	}

        public static function getGebugExtra($id){
            return self::model()->findByPk($id);
        }
        
        public static function getSessionInfo($session_id)
        {   
            $connection=Yii::app()->db;
            $sql = "SELECT debug_extra.*, History.Duration, History.Free_time, History.Paid_time, History.Date 
                    FROM debug_extra 
                    LEFT JOIN History 
                        ON debug_extra.session_id = History.Session_id 
                    WHERE debug_extra.session_id = ".$session_id;            
            $command=$connection->createCommand($sql);
            $res = $command->query();
            
            $info = array();
            foreach($res as $key=>$val)
            {
                $info[$key] = $val;
            }
            return $info[0];            
        }
}