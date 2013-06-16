<?php
class PsyDbHttpSession extends CHttpSession {
    
    public function getUseCustomStorage() {
        return true;
    }
   
    public function openSession($save_path, $session_name) {
    	$sql = "CREATE TABLE IF NOT EXISTS `sessions` (
                  `session_key` varchar(32) NOT NULL default '',
                  `session_expire` int(11) unsigned NOT NULL default '0',
                  `session_value` text NOT NULL,
                  PRIMARY KEY  (`session_key`)
                ) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
    	$command = Yii::app()->db->createCommand($sql);
    	$command->execute();
    	return true;
    }
    
    public function closeSession() {
    	return true;
    }
    
    
    public function readSession($session_key) {
    	$sql = "SELECT session_value FROM sessions WHERE session_key = :session_key";
    	$command = Yii::app()->db->createCommand($sql);
    	$command->bindParam(':session_key',$session_key,PDO::PARAM_STR);
    	$row = $command->queryRow();
    	if (!empty($row)) {
    		return $row['session_value'];
    	}
    	else {
    		return false;
    	}
    }
    
    public function writeSession($session_key, $session_value) {
        $sql = 'SELECT COUNT(*) cnt FROM sessions WHERE session_key = :session_key';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':session_key',$session_key,PDO::PARAM_STR);
        $session = $command->queryRow();
        if($session['cnt'] == 0) {
            $sql = 'INSERT INTO sessions (session_key, session_expire, session_value)
    								VALUES (:session_key, UNIX_TIMESTAMP(NOW()), :session_value)';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(':session_key',$session_key,PDO::PARAM_STR);
            $command->bindParam(':session_value',$session_value,PDO::PARAM_STR);
            $return = $command->execute();
        }
        else {
            $sql = 'UPDATE sessions SET session_value = :session_value, session_expire = UNIX_TIMESTAMP(NOW())
    								WHERE session_key = :session_key ';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(':session_key',$session_key,PDO::PARAM_STR);
            $command->bindParam(':session_value',$session_value,PDO::PARAM_STR);
            $return = $command->execute();
        }
    	return $return;
    }
    
    public function destroySession($session_key) {
        $sql = 'DELETE FROM sessions WHERE session_key = :session_key';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':session_key',$session_key,PDO::PARAM_STR);
        $return = $command->execute();
    	return $return;
    }
    
    public function gcSession ($maxlifetime) {
        $sql = 'DELETE FROM sessions WHERE session_expire < :expiration';
    	$expirationTime = time() - $maxlifetime;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':expiration',$expirationTime,PARAM_INT);
        $return = $command->execute();
    	return $return;
    }


}
?>