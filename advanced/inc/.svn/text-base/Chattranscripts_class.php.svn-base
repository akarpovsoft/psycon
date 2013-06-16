<?php

if(file_exists('config/config.php'))
{
    require_once ('config/config.php');
}
class Chattranscripts
{
    private $readerId, $clientId, $sessionId,
        $localConnection;
    public $sharedConnection;
    function __construct()
    {
    }
    function __destruct()
   {
       if(isset($this->localConnection))
        {
            @mysql_close($this->localConnection);
        }
    }
    public function setReaderId($id)
    {
        $this->readerId = $id;
    }
    public function setClientId($id)
    {
        $this->clientId = $id;
    }
    public function setSessionId($id)
    {
        $this->sessionId = $id;
    }
    public function setGlobalDbConnection(&$conn)
    {
        $this->sharedConnection = $conn;
    }
    public function insertInToDb()
    {
        if(!isset($this->sharedConnection))
        {
            $this->connectToDb();
        }
        $mysql = 'INSERT into chattranscripts (SessionId, ReadedId, ClientId, Date, Transcripts)
                  VALUES(\''.$this->sessionId.'\', \''.$this->readerId.'\', \''.$this->clientId.'\', now(), \'\')';
        mysql_query($mysql, $this->sharedConnection);
        //echo mysql_error();
        if (mysql_error()) {
	
			$message = "INSERT ERROR <br> ".mysql_error()." <br> $sql <br> ";
			$mails = array("karpovsoft@yandex.ru", 'linko.ivan@gmail.com' );
			foreach ($mails as $email)
				mail($email ,"HISTORY INSERT ERROR", $message, $headers);
		}
    }

    public function addMessage($message)
    {
        if(!isset($this->sharedConnection))
        {
            $this->connectToDb();
        }

        $strsql = 'SELECT Transcripts from chattranscripts  where SessionId = \''.$this->sessionId.'\'';
        $rs = mysql_query($strsql, $this->sharedConnection) or die(mysql_error());
        $row = mysql_fetch_assoc($rs);
        $transcripts = $row['Transcripts']."\n".$message;
        mysql_free_result($rs);
       $transcripts = addslashes($transcripts);
        $mysql = 'UPDATE chattranscripts set Transcripts =\''.$transcripts.'\' where SessionId = \''.$this->sessionId.'\'';
        mysql_query($mysql, $this->sharedConnection);
    }

    private function connectToDb()
    {
        if(isset($this->sharedConnection))
        {
            return;
        }

        global $dbhost, $dbusername, $dbuserpassword, $default_dbname;
        $this->localConnection = mysql_connect($dbhost, $dbusername, $dbuserpassword);
        mysql_select_db($default_dbname);
        $this->sharedConnection = &$this->localConnection;
    }

    public function saveTotxt($file)
    {
        //echo"tr: $transcripts ($file)<br>";
        if(!isset($this->sharedConnection))
        {
            $this->connectToDb();
        }

        $strsql = 'SELECT Transcripts from chattranscripts  where SessionId = \''.$this->sessionId.'\'';
        $rs = mysql_query($strsql, $this->sharedConnection) or die(mysql_error());
        $row = mysql_fetch_assoc($rs);
        $transcripts = $row['Transcripts'];

        mysql_free_result($rs);

        $txtTranscripts = '';
        $fp = fopen($file, "r+");
        if (flock($fp, LOCK_EX))
        {
            while(!feof($fp))
            {                $txtTranscripts .= fgets($fp, 2048);
            }

            if((strlen($transcripts) - strlen($txtTranscripts)) > 100)
            {
                //$fp = fopen($file, "w");
                fputs($fp, $transcripts);
                //fclose($fp);
            }
            flock($fp, LOCK_UN);
        }
        fclose($fp);
    }

    public function removeOldRecords()
    {
        if(!isset($this->sharedConnection))
        {
            $this->connectToDb();
        }

        $mysql = 'DELETE FROM chattranscripts WHERE DATE_ADD(Date, interval 30 day) < now()';
        mysql_query($mysql, $this->sharedConnection);
    }

}
?>