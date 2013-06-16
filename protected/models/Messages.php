<?php

class Messages extends CActiveRecord {
    const PER_PAGE = 20;

    public $user_type;
    public $attach; // Uploaded file name
    public $path2file; // Upload file dir
    
    /**
     * The followings are the available columns in table 'messages':
     * @var integer $ID
     * @var integer $From_user
     * @var string $From_name
     * @var integer $To_user
     * @var string $Date
     * @var string $Subject
     * @var string $Body
     * @var string $Status
     * @var string $Read_message
     * @var string $attachment
     * @var string $parameters
     */

    /**
     * Returns the static model of the specified AR class.
     * @return CActiveRecord the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return PsyStaticDataProvider::getTableName('messages');
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
                array('Subject, Body', 'required'),
                array('Subject, Body', 'inBlackList'),
                array('attachment', 'isAttach'),
                array('attach', 'file', 'types' => 'jpg, gif, png, txt, doc, pdf', 'allowEmpty' => true),
                array('From_user, To_user', 'numerical', 'integerOnly'=>true),
                array('From_name', 'length', 'max'=>100),
                array('Subject', 'length', 'max'=>150),
                array('Status', 'length', 'max'=>7),
                array('Read_message, attachment', 'length', 'max'=>3),
                array('Date, Body, parameters', 'safe'),
        );
    }

    public function inBlackList() {
        if(($this->user_type == 'reader')||($this->user_type == 'client')) {
            if(!$this->Status)
            {
                $sub_check = BlackWords::checkWord($this->Subject);
                $body_check = BlackWords::checkWord($this->Body);
                if(($sub_check == false)||($body_check == false))
                    $this->Status ='pending';
            }
        }
    }

    public function isAttach(){
        if($this->attachment == 'yes'){
            $this->Status = 'pending';
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
                'ID' => 'Id',
                'From_user' => 'From User',
                'From_name' => 'From',
                'To_user' => 'To User',
                'Date' => 'Date',
                'Subject' => 'Subject',
                'Body' => 'Body',
                'Status' => 'Status',
                'Read_message' => 'Read Message',
                'attachment' => 'Attachment',
                'parameters' => 'Parameters',
        );
    }
    /**
     * Get all messages for current client
     *
     * @param int $client_id
     * @return object CActiveDataProvider
     */
    public function getMessagesForClient($client_id) {
        $reader_access = ContactReadersAccess::getContactAccess($client_id);
        if(!empty($reader_access)) {
            $addition_condition = " OR To_user = '1' ";
        }
        $criteria = new CDbCriteria;
        $criteria->condition = '(To_user = :client_id'.$addition_condition.') AND Status = "ok"';
        $criteria->params = array(':client_id' => $client_id);
        $criteria->order = 'Read_message ASC, Date DESC';

        $dataProvider=new CActiveDataProvider('Messages', array(
                        'criteria'=>$criteria,
                        'sort' => array('attributes' =>  array('From_name', 'Subject', 'Date')),
                        'pagination'=> array(
                                'pageSize'=> self::PER_PAGE,
                        )
        ));
        return $dataProvider;
    }
    
    public static function getMessagesList($user_id)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = 'To_user = :client_id AND Status = "ok"';
        $criteria->params = array(':client_id' => $user_id);
        $criteria->order = 'Read_message ASC, Date DESC';
        
        return self::model()->findAll($criteria);
    }

    /**
     * Deletes all checked messages
     *
     * @param array $messages array of message ids
     */
    public function delCheckedMessages($messages) {
        $connection=Yii::app()->db;

        if(is_array($messages)) {
	        $mess = '(';
	        foreach($messages as $id)
	            $mess .= $id.',';
	        $mess = substr($mess, 0, strlen($mess)-1);
	        $mess .= ')';
	
	        $sql = "DELETE FROM `".$this->tableName()."`
	                    WHERE `ID` IN ".$mess;
	
	        $command=$connection->createCommand($sql);
	        $command->execute();
        }
    }

    /**
     * Sends a message
     *
     * @return integer last insert id
     */
    public function send() {
        $this->Date = date('Y-m-d H:i:s');
        if($this->Status != 'pending')
            $this->Status = 'ok';
        $this->Read_message = 'no'; 
        if(!is_null($this->attach)){
            $this->attach->saveAs($this->path2file);
        }
        $this->save();
    }
	
    /**
     * Return one message object
     *
     * @return object Messages
     */
    public static function getMessage($id) {
        return self::model()->findByPk($id);
    }
    
     public static function delMessage($id)
    {
        self::model()->deleteByPk($id);
    }
}