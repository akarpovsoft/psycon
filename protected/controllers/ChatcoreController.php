<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of ChatCoreController
 *
 * @author Ivan
 */
class ChatcoreController extends CController
{
    function actionPush ()
    {
		set_time_limit(0);
        $data = $_REQUEST;
        try {
            $chatContext = ChatContext::getContext($data["sessionKey"], $data["userType"]);
            $commander = new ChatCommander($chatContext);
            $method = "command" . ucfirst($data['command']);
            if (method_exists($commander, $method)) {
                if (is_array($data['params']))
                    $commander->registerParams($data['params']);
                $result = $commander->$method();
                if (is_string($result))
                    echo $result;
                else
                    echo json_encode(array("status" => "ok"));
            }
        } catch (Exception $e) {
            echo json_encode(array("error" => $e->getMessage()."\r\n".$e->getTraceAsString()));
        }
		
    }
    function actionPop ()
    {
        ini_set('output_buffering', 0);
        ini_set('zlib.output_compression', 0);
        set_time_limit(0);
        // @TODO : remove this implied headers and use yii cache control
        if (! empty($_SERVER['SERVER_SOFTWARE']) && strstr($_SERVER['SERVER_SOFTWARE'], 'Apache/2')) {
            header('Cache-Control: no-cache, pre-check=0, post-check=0, max-age=0');
        } else {
            header('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
        }
        header('Expires: ' . gmdate('D, d M 2009 H:i:s', time()) . ' GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        $data = $_REQUEST;
        if (empty($data["sessionKey"]) || empty($data["userType"]))
            die();
        try {
            $chatContext = ChatContext::getContext($data["sessionKey"], $data["userType"]);
            $commander = new ChatCommander($chatContext);
            $cmd = $commander->commandCheck();
            echo $cmd;
        } catch (Exception $e) {
            echo json_encode(array("error" => $e->getMessage()."\r\n".$e->getTraceAsString()));
            die();
        }

	
    }


	function actionAjaxError()
	{
		DebugExtra::addAjaxErrorInfo(ChatContext::getSessionId($_REQUEST['sessionKey']), $_REQUEST['notes']);
	}

    /**
     * Test chat context action
     *
     */
    function actionTest ()
    {
        //	    die();
        $sid = "03a482cca29f217f76cb0f47a9b44083";
        $chatContext = ChatContext::getContext($sid, ChatContext::READER);
        $duration = $_GET['d'] ? $_GET['d'] : 60;
        $start = 1200;
        $chatContext->chat_start = date('Y-m-d H:i:s', time() - $duration);
        $chatContext->bal_start = $start;
        echo "<br/>Chat time remaining: " . $chatContext->chatTimeRemaining();
        /*	    
		$timeToAdd = ($chatContext->clientTotalTimeRemaining()-$chatContext->bal_start+$chatContext->chatDuration())/60;	    
		echo "=".$timeToAdd;
	    echo "Start =====";
	    echo "<br>Duration : ".$duration;
	    
	    echo "<br>Results =====<br>";
	    echo "<br/>Free time remaining: ".$chatContext->freeTimeRemaining();
	    echo "<br/>Free time passed: ".$chatContext->freeTimePassed();
	    echo "<br/>chat duration: ".$chatContext->chatDuration();
	    echo "<br/>paid chat duration: ".$chatContext->paidChatDuration();
	    echo "<br/>Client total time: ".$chatContext->clientTotalTimeRemaining();
	    	    
	    $chatContext->reduceFreeTime();
*/
    //        $this->redirect(Yii::app()->params['http_addr']."chatcore/pop?sessionKey=$sid&userType=".ChatContext::READER);
    }

	function actionTestTick($sid = false, $cmd = false)
	{
		if(!isset($cmd)) $cmd = 'Tick';
		 $chatContext = ChatContext::getContext($sid, ChatContext::READER);

		 $commander = new ChatCommander($chatContext);

            $method = "command".$cmd;
            if (method_exists($commander, $method)) {
                if (is_array($data['params']))
                    $commander->registerParams($data['params']);
                $result = $commander->$method();
                if (is_string($result))
                    echo $result;
                else
                    echo json_encode(array("status" => "ok"));
			}
	}

	function actionTestCmd($sid, $cmd)
	{
		Yii::beginProfile('blockID');
		///if(!isset($cmd)) $cmd = 'Check';
		 $chatContext = ChatContext::getContext($sid, ChatContext::READER);

		 $commander = new ChatCommander($chatContext);

		$method = "command".$cmd;
		if (method_exists($commander, $method)) {
			if (is_array($data['params']))
				$commander->registerParams($data['params']);
			$result = $commander->$method();
			if (is_string($result))
				echo $result;
			else
				echo json_encode(array("status" => "ok"));
		}

		print_r(Yii::app()->db->getStats());
		Yii::endProfile('blockID');
	}
}
?>
