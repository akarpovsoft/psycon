<?php

class ChatRequests extends CActiveRecord
{
    const PER_PAGE = 5;
	/**
	 * The followings are the available columns in table 'T1_7':
	 * @var integer $rr_record_id
	 * @var integer $rr_account_id
	 * @var integer $rr_emailsent
	 * @var integer $rr_synchronized
	 * @var string $rr_createdate
	 * @var string $rr_lastaccess
	 * @var integer $reader_id
	 * @var integer $client_id
	 * @var string $laststatusupdate
	 * @var string $subject
	 * @var integer $chat_type
	 * @var integer $duration
	 * @var integer $session_started
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
		return 'T1_7';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rr_account_id, rr_emailsent, rr_synchronized, reader_id, client_id, chat_type, duration, session_started', 'numerical', 'integerOnly'=>true),
			array('subject', 'length', 'max'=>255),
			array('rr_createdate, laststatusupdate', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array('users' => array(self::BELONGS_TO, 'users', 'client_id'),
                             'credit_card' => array(self::BELONGS_TO, 'CreditCard', 'client_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'rr_record_id' => 'Rr Record',
			'rr_account_id' => 'Rr Account',
			'rr_emailsent' => 'Rr Emailsent',
			'rr_synchronized' => 'Rr Synchronized',
			'rr_createdate' => 'Rr Createdate',
			'rr_lastaccess' => 'Rr Lastaccess',
			'reader_id' => 'Reader',
			'client_id' => 'Client',
			'laststatusupdate' => 'Laststatusupdate',
			'subject' => 'Subject',
			'chat_type' => 'Chat Type',
			'duration' => 'Duration',
			'session_started' => 'Session Started',
		);
	}
	/**
         * Add a new chat request
         *
         * @return integer last insert id
         */
	public function addNewRequest(){
		$this->laststatusupdate = date('Y-m-d H:i:s');
		$this->save();

		return mysql_insert_id();
	}
        
	public static function delRequestByClient($client_id)
	{
		self::model()->deleteAll(array(
		'condition' => 'client_id = :client_id',
		'params' => array(':client_id' => $client_id)
		));
	}

	/**
        * Check: if chat request for current client and reader is avaliable - delete it
        *
        */
	public function checkRequest(){
		$model = self::model()->find(array(
		'condition'  => 'client_id = :client_id AND reader_id = :reader_id',
		'params'     => array(':client_id' => $this->client_id, ':reader_id' => $this->reader_id)
		));
		if(!empty($model))
		$model->delete();
	}
	/**
         * Updates client's request for chat start
         *
         * @param <integer> $request_id
         */
	public static function updateRequest($request_id){
		$sql = "UPDATE T1_7
                    SET laststatusupdate = now(),
                    WHERE rr_record_id='".$request_id."'";
		self::model()->updateByPk($request_id, array(
		'laststatusupdate' => new CDbExpression('NOW()'),)
		);
	}
	/**
         * return list of chat requests for a current reader
         * @param int $reader_id
         * @return oblect CActiveRecord
         */
	public static function getReadersRequest($reader_id){
		$criteria = new CDbCriteria;
		$criteria->select = 'client_id, subject, reader_id, rr_record_id';
		$criteria->condition = 'reader_id = :reader_id
                                    AND (to_days(now())=to_days(t.laststatusupdate)
                                         and (time_to_sec(now()) - time_to_sec(t.laststatusupdate) < 12))';
		$criteria->params = array(':reader_id' => $reader_id);
		$criteria->with = 'credit_card';

		$dataProvider=new CActiveDataProvider('ChatRequests', array(
		'criteria'=>$criteria,
		'sort' => array('attributes' =>  array('subject')),
		'pagination'=> array(
		'pageSize'=> self::PER_PAGE,
		)
		));
		return $dataProvider;
	}

	public static function getReadersRequestNew($reader_id){
		$criteria = new CDbCriteria;
		$criteria->select = 'client_id, subject, reader_id, rr_record_id, chat_type';
		$criteria->condition = 'reader_id = :reader_id
                                    AND (to_days(now())=to_days(t.laststatusupdate)
                                         and (time_to_sec(now()) - time_to_sec(t.laststatusupdate) < 12))';
		$criteria->params = array(':reader_id' => $reader_id);
		return self::model()->findAll($criteria);
	}
	/**
        * Check for update clients grid in reader monitor
        *
        * @param <int> $reader_id
        * @param <array> $clients Array of current clients in monitor page
        * @return <mixed>
        */
	public static function checkNewClient($reader_id, $client_id){
		$criteria = new CDbCriteria;
		$criteria->select = 'client_id, subject, reader_id, rr_record_id';
		$criteria->condition = 'reader_id = :reader_id
                                    AND (to_days(now())=to_days(t.laststatusupdate)
                                         and (time_to_sec(now()) - time_to_sec(t.laststatusupdate) < 12))';

		$criteria->params = array(':reader_id' => $reader_id);

		$cl_models = self::model()->findAll($criteria);

		foreach($cl_models as $cl_model)
		$db_clients[] = $cl_model->client_id;

		if(empty($cl_models))
			return false;
		if(in_array($client_id, $db_clients))
			return false;
		else
		return 'insert';
	}

	public static function getChatRequest($id){
		return self::model()->findByPk($id);
	}

	public static function getByClientId($cl_id)
	{
		return self::model()->find(array(
		'condition' => 'client_id = :client_id',
		'params' => array(':client_id' => $cl_id),
		'order' => 'rr_lastaccess DESC'
		));
	}
       
	public function getId() {
		return $this->rr_record_id;
	}

	public function getExpertId() {
		return $this->reader_id;
	}

	public function getClientId() {
		return $this->client_id;
	}

	public function setExpertId($expertId) {
		$this->reader_id = $expertId;
	}

	public function setClientId($clientId) {
		$this->client_id = $clientId;
	}
	
	public static function readerBusy($readerId) {
		$requesters = ChatRequests::getReadersRequestNew($readerId);
		return (!empty($requesters));

	}
}