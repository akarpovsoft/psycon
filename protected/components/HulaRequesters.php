<?php
/**
 * class HulaRequesters
 * Class to work with hula.ph DB to check and update reader status, chat requesters
 *
 * @name         HulaRequesters class
 * @author       Den Kazka  <den.smart(at)gmail.com>
 * @since        2010
 * @version      $Id$
 */
class HulaRequesters extends CActiveRecord
{
    public $type; // Hula.ph site sign

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'T1_7';
    }

    public function getDbConnection(){
        return Yii::app()->hula_db;
    }

    public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
                    'credit_card' => array(self::BELONGS_TO, 'CreditCard', 'client_id')
		);
	}
    /**
     * Get array of avaliable clients to render requests grid
     *
     * @param <integer> $reader_id
     * @return <array>
     */
    public static function getQueueClients($reader_id){
        $clients = self::model()->findAll(array(
            'condition' => 'reader_id = :reader_id
                            AND (to_days(now())=to_days(t.laststatusupdate)
                                and (time_to_sec(now()) - time_to_sec(t.laststatusupdate) < 10))',
            'params' => array(':reader_id' => $reader_id)
        ));
        $ret = array();
        foreach($clients as $client)
            $ret[] = $client->client_id;
        return $ret;
    }

    /**
     * Get chat requesters from hula.ph
     *
     * @param <integer> $reader_id
     * @return <self::object>
     */
    public static function getRequesters($reader_id){
        $criteria = new CDbCriteria;
        $criteria->select = 'client_id, subject, reader_id, rr_record_id';
        $criteria->condition = 'reader_id = :reader_id
                                AND (to_days(now())=to_days(t.laststatusupdate)
                                and (time_to_sec(now()) - time_to_sec(t.laststatusupdate) < 10))';
        $criteria->params = array(':reader_id' => $reader_id);
        $cliteria->with = 'credit_card';

        $requesters = self::model()->findAll($criteria);
        foreach ($requesters as $value) {
                $value->type = 'hula';
            }

        return $requesters;
    }
    /**
     * Updates current reader status
     *
     * @param <integer> $id reader id
     * @param <string> $status new status
     */
    public static function updateReaderStatus($id, $status){
        $connect = Yii::app()->hula_db;
        $sql = "UPDATE T1_1
                SET `status` = '".$status."',
                    `laststatusupdate` = NOW()
                WHERE `rr_record_id` = '".$id."'";
        $command=$connect->createCommand($sql);
        $command->execute();
    }

    /**
     * Return current reader status
     * @param <type> $reader_id
     * @return <type>
     */
    public static function getReaderStatus($reader_id){
        $readerIsChatting = self::ifReaderChatting($reader_id) 
        || self::ifReaderReqChatting($reader_id)
        || ChatContext::ifReaderChatting($reader_id)
        || ChatSession::ifReaderChatting($reader_id);
        
        $sql = 'SELECT status FROM T1_1 where rr_record_id = \''.$reader_id.'\' and type = \'reader\'';
        $connect = Yii::app()->hula_db;
        $command = $connect->createCommand($sql);
        $data	= $command->query();
        unset($command);
        while ($reader = $data->read()){
            $status = $reader['status'];

            /// if status is busy in users table but reader ping update was more then 10 sec ago, set status online
            if (($status == 'busy') && !$readerIsChatting){
                $status = 'online';
                self::updateReaderStatus($reader_id, $status);
            }
        }

        if(empty($status)){
            $status = 'offline';
        }
        return $status;
    }


    public static function ifReaderReqChatting($readerId){
        $connection = Yii::app()->hula_db;
        $sql = "SELECT * FROM `chat_context` WHERE `reader_id` = '$readerId' and (DATE_ADD(reader_ping, interval 10 second) > now())";
        $command = $connection->createCommand($sql);
        $data = $command->query();

        foreach($data as $row){
            return true;
        }
        return false;		
    }

    public static function ifReaderChatting($readerId){
        $connection = Yii::app()->hula_db;
        $sql = "SELECT * FROM `T1_12` WHERE `reader_id` = '$readerId' and (DATE_ADD(laststatusupdate, interval 10 second) > now())";
        $command = $connection->createCommand($sql);
        $data = $command->query();
        foreach($data as $row){
            return true;
        }
        return false;
    }

    public static function getChatSessionByReaderId($reader_id){
        $connection = Yii::app()->hula_db;
        $sql = "SELECT * FROM `T1_12` WHERE `reader_id` = '$reader_id'";
        $command = $connection->createCommand($sql);
        $data = $command->execute();
        return $data;
    }

    public static function getChatSessionData($reader_id){
        $connection = Yii::app()->hula_db;
        $sql = "SELECT * FROM `T1_12` WHERE `reader_id` = '$reader_id'";
        $command = $connection->createCommand($sql);
        $data = $command->query();
        return $data;
    }

}

?>
