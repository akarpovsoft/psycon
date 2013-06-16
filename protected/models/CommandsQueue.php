<?php
class CommandsQueue extends CActiveRecord
{
 	/**
     * Returns the static model of the specified AR class.
     * @return CActiveRecord the static model class
     */
	public static function model($className=__CLASS__)
    {
		return parent::model($className);
    }

    public function tableName()
    {
        return 'commands';
    }

	public static function put($chatContext, $command, $params, $toUserId)
	{
		if (is_array($params))
		{
			$params = serialize ($params);		
		}
		/*
		$queue = new CommandsQueue();
		$queue->session_key = $chatContext->sessionKey;
		$queue->command		= $command;
		$queue->params		= $params;
		$queue->to_userid	= $toUserId;
		$queue->save();
		*/
		$connect = Yii::app()->db;
		$sql = "INSERT INTO `commands` (`to_userid`, `session_key`, `command`, `params`) VALUES (".$toUserId.", '".$chatContext->sessionKey."', '".$command."', '".$params."')";
		$sql_command=$connect->createCommand($sql);
		$sql_command->execute();


		// write to log		
		$sessionLog = new ChatEventsLogger();
		$sessionLog->userType = ($chatContext->userType == ChatContext::CLIENT)?  'Client' : 'Reader';
		$sessionLog->init(Yii::app()->getRuntimePath().DIRECTORY_SEPARATOR.'chat', $chatContext->session_id.".txt");
		$sessionLog->addLogMsg($command, json_encode(unserialize($params)), 'put, to user '.$toUserId);
		$sessionLog->save();
		
		return true;
		/// return $queue->getDbConnection()->lastInsertID;
	}

	public static function get($chatContext)
	{
		$rows = self::model()->findAll(array(
			'condition' => 'session_key = :sessionKey and to_userid = :toUserId',
			'params' => array(':sessionKey' => $chatContext->sessionKey, ':toUserId' => $chatContext->user->user_id)
		));

		// write to log
		$sessionLog = new ChatEventsLogger();
		$sessionLog->userType = ($chatContext->userType == ChatContext::CLIENT)?  'Client' : 'Reader';
		$sessionLog->init(Yii::app()->getRuntimePath().DIRECTORY_SEPARATOR.'chat', $chatContext->session_id.".txt");

		foreach($rows as $row)
			$sessionLog->addLogMsg($row['command'], json_encode(unserialize($row['params'])), 'get, for user '.$chatContext->user->user_id);

		$sessionLog->save();
		return $rows;
	}

	public static function clear($chatContext, $userId)
	{
		$connect = Yii::app()->db;
		$sql = "DELETE FROM commands WHERE session_key = '$chatContext->sessionKey' and to_userid=".$userId;		
		$command=$connect->createCommand($sql);
		$command->execute();

		// write to log
		$sessionLog = new ChatEventsLogger();
		$sessionLog->userType = ($chatContext->userType == ChatContext::CLIENT)?  'Client' : 'Reader';
		$sessionLog->init(Yii::app()->getRuntimePath().DIRECTORY_SEPARATOR.'chat', $chatContext->session_id.".txt");
		$sessionLog->addLogMsg('clear', '', 'get, for user '.$chatContext->user->user_id);

	}
}
?>