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
			$params = addslashes(serialize ($params));
		}

		$queue = new CommandsQueue();
		$queue->session_id = $chatContext->session_id;
		$queue->command		= $command;
		$queue->params		= $params;
		$queue->to_userid	= $toUserId;
		$queue->save();

		// insert into history
		ChatCommandsHistory::add($chatContext->session_id, $command, $params, $toUserId);
/*		
		$connect = Yii::app()->db;
		$sql = "INSERT INTO `commands_history` (`command`, `params`, `to_userid`, `session_id`)
		 VALUES (:command, :params, :to_userid, :session_id)";
		$command=$connect->createCommand($sql);
		$command->execute(array(":params" => $params, ":to_userid" => $toUserId, ":session_id" => $chatContext->session_id,
		":command" => $queue->command));
*/
		return true;
	}

	/**
	 * Get commands from commands queue
	 *
	 * @param Chatcontext $chatContext context of the chat
	 * @param int $postId last post id
	 * @return array array of commands
	 */
	public static function get($chatContext, &$postId)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 'session_id = :sessionId and to_userid = :toUserId AND id>:postId';
		$criteria->params = array(':sessionId' => $chatContext->session_id,
		                         ':toUserId' => $chatContext->user->user_id,
		 						':postId' => $postId);
		$criteria->order = "id ASC";
                
        $rows = self::model()->findAll($criteria);                
        
		for($i = 0; $i < count($rows); $i++)
		{
			$rows[$i]->params = stripslashes($rows[$i]->params);
		}
		if(is_array($rows) && count($rows)>0)
		    $postId = $rows[count($rows)-1]->id;

		return $rows;
	}

	public static function clear($chatContext, $userId)
	{
		$connect = Yii::app()->db;
		$sql = "DELETE FROM commands WHERE session_id = '$chatContext->session_id' and to_userid=".$userId;
		$command=$connect->createCommand($sql);
		$command->execute();

	}
}
?>