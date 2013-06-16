<?php

/**
 * This is the model class for table "commands_history".
 *
 * The followings are the available model relations:
 */
class ChatCommandsHistory extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ChatCommandsHistory the static model class
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
	 * Add command to history
	 */
	public static function add($sessionId, $command, $params, $toUserId)
	{
			$ch = new ChatCommandsHistory();
			$ch->session_id = $sessionId;
			$ch->command		= $command;
			$ch->params		= $params;
			$ch->to_userid	= $toUserId;
			$ch->save();
	}

}

// ChatCommandsHistory::add(123, 'debug', 'client_ping', 9615);