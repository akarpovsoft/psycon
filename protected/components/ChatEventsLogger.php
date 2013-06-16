<?php

class ChatEventsLogger extends CFileLogRoute
{
	public	$logs = array();
	public  $userType;
	const LOGGING = true;


	public function init($logPath, $logFile)
	{
		$this->setLogPath($logPath);
		$this->setLogFile($logFile);		
	}

	public function addLogMsg($command, $params, $info)
	{
		$this->logs[] = array($command, $params, $info);		
	}

	public function save()
	{
		if(LOGGING)
		{
			$logFile = $this->getLogPath().DIRECTORY_SEPARATOR.$this->getLogFile();

			if(@filesize($logFile) > $this->getMaxFileSize() * 1024)
				$this->rotateFiles();

			foreach($this->logs as $log)
				error_log($this->formatLogMessage($log[0], $log[1], $log[2]),3,$logFile);
		}
		
		$this->logs = array();
	}

	function formatLogMessage($command, $params, $info)
	{
		return @"[".date('Y/m/d H:i:s')."] $this->userType\t [$info]\t cmd:$command\t $params \n";
	}


}
?>
