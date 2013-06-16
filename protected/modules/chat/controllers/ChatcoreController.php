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
        	$userType = (Yii::app()->user->type=='reader') ? ChatContext::READER : ChatContext::CLIENT;
            $chatContext = ChatContext::getContext($data["sessionKey"], $userType);
        	if(Yii::app()->user->type=='reader') {
		    	ReaderOnlineStatistics::refreshLogin(Yii::app()->user->id); // refresh online time
        	}
        	
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
/*    
    function actionPop ()
    {
        ini_set('output_buffering', 0);
        ini_set('zlib.output_compression', 0);
        set_time_limit(0);
        // @TODO : remove this implied headers and use yii cache control
        //if (! empty($_SERVER['SERVER_SOFTWARE']) && strstr($_SERVER['SERVER_SOFTWARE'], 'Apache/2')) {
            header('Cache-Control: no-cache, pre-check=0, post-check=0, max-age=0');
        //} else {
        //    header('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
        //}
        header('Expires: ' . gmdate('D, d M 2009 H:i:s', time()) . ' GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        $data = $_REQUEST;
        if (empty($data["sessionKey"]))
            die();
        try {
        	$userType = (Yii::app()->user->type=='reader') ? ChatContext::READER : ChatContext::CLIENT;
            $chatContext = ChatContext::getContext($data["sessionKey"], $userType);
            $commander = new ChatCommander($chatContext);
            $cmd = $commander->commandCheck($data["postId"]);
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
*/

}
?>
