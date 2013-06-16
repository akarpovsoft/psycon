<?php

class TestController extends CController
{
	static $fixtures = array (
	'addlost' => array(
		"UPDATE `remove_freetime` SET `Seconds` = '600',
`Total_sec` = '600' WHERE `remove_freetime`.`ClientID` =__USERID__",
		"UPDATE `T1_1` SET `balance` = 20 WHERE `rr_record_id` =__USERID__",
		"DELETE FROM `transactions` WHERE  `UserID` = __USERID__",

		"INSERT INTO `transactions` (
`ORDER_NUMBER` , `UserID` ,`Date` , `AUTH_CODE` ,`TRAN_AMOUNT` , `Amount_used` , `Mail_status` ,
`merchant_trace_nbr` , `TypeOfPayment` , `Bmt` ,`ps_type` , `transaction_type` , `Sessions` ,
`balance_used` , `Total_time`
)
VALUES (
'123', '__USERID__', '2011-05-31 10:25:08', NULL , '11.99', '0', 'no', NULL , 'Online', NULL , 'ECHO', '0', '', '0.000', '0.000'
)",

		"INSERT INTO `transactions` (
`ORDER_NUMBER` , `UserID` ,`Date` , `AUTH_CODE` ,`TRAN_AMOUNT` , `Amount_used` , `Mail_status` ,
`merchant_trace_nbr` , `TypeOfPayment` , `Bmt` ,`ps_type` , `transaction_type` , `Sessions` ,
`balance_used` , `Total_time`
)
VALUES (
'234', '__USERID__', '2011-05-31 10:25:08', NULL , '11.99', '0', 'no', NULL , 'Online', NULL , 'ECHO', '0', '', '0.000', '0.000'
)")	
	);

	public static function p() {
		return array('session_id' =>1, 'client_id' => 9615, 'reader_id' => 19310);
	}

	public function runFixtures($sql){

		$p = self::p();
		foreach($sql as $query) {
			$query = str_replace("__USERID__", $p['client_id'], $query);
			Yii::app()->db->createCommand($query)->execute();
		}
	}

	public function addContext($chatDuration, $timePassed){
		$p = self::p();
    	$chatContext = new ChatContext();
		
		$chatContext->add($p['session_id'], $p['client_id'], $p['reader_id'], date("Y-m-d h:i:s", ChatHelper::time()- $timePassed*60));
	}
	
	public function cleanup() {
		$data = ChatContext::model()->find(array(
			'condition' => 'session_id = :sessionKey',
			'params' =>array(':sessionKey' => 1),
		));
		if(!is_null($data))
			$data->delete();
		$data = ChatHistory::model()->find(array(
			'condition' => 'Session_id = :sessionKey',
			'params' =>array(':sessionKey' => 1),
		));
		if(!is_null($data))
			$data->delete();
		$data = DebugExtra::model()->find(array(
			'condition' => 'session_id = :sessionKey',
			'params' =>array(':sessionKey' => 1),
		));
		if(!is_null($data))
			$data->delete();
	}

	public function fixFreeTime($timePassed){
		$p = self::p();
		$ft = RemoveFreetime::getFreeTime($p['client_id']);
		$ft->Seconds -=  $timePassed*60;
		$ft->save();
	}
	
	public function addHistory($chatDuration, $timePassed, $freeTime){
		
		$p = self::p();
		$h = new ChatHistory();
		$h->Session_id = $p['session_id'];
		$h->Client_id = $p['client_id'];
		$h->Reader_id =$p['reader_id'];
		$h->Subject = 'test';
		$h->Date = ChatHelper::dbNow();
		$h->Duration = $timePassed;
		$h->Free_time = $freeTime;
		$h->Paid_time = 0;
		$h->Due_to_reader = 0;
		$h->Rate = ChatBilling::getPaymentRate('New', 0);


		$h->save();
		$h->saveStartupStatistics($chatType, 600);
	}

	public function actionChatAddLostTime(){
		$chatDuration = 20;
		$timePassed = 5;
        $this->cleanup();
        $this->runFixtures(self::$fixtures['addlost']);
        $this->addContext($chatDuration, $timePassed, $freeTime);
        $this->addHistory($chatDuration, $timePassed, $freeTime);
        $this->fixFreeTime($timePassed);
    }	
	
	
    public function actionBilling(){
    	$session_id = 37;
    	$cc = ChatContext::getContext(ChatContext::getSessionKey($session_id), ChatContext::READER);
		$billing = new ChatBilling($cc);
		$nopayment = $billing->countTimeMarkTransDeps();
        
    }

    public function actionDeposit(){
    	$client_id = 9615;
		$deposits = Deposits::searchUnmarkedByClientId($client_id);
//		print_r($deposits[0]);
			$deposits[0]->acceptSessionTime("1213" , 1);
    }

    public function actionTime(){
		echo date("d/m/Y H:i:s", Util::time());    	
		echo "<br>".date("d/m/Y H:i:s", time());    	
    }

    public function actionType(){
    	echo Yii::app()->user->type;
    }
    
    public function actionBuffer(){
    	die('123');
        while (ob_get_level()) {
            ob_end_clean();
        }

        /*                 
        echo ob_get_level()."==|==";
        die();
        ob_clean();
        ob_clean();
        ob_clean();
*/
        echo "[[[[".ob_get_level()."]]]]]";
        while($i<3)
		{
			echo $i.'=========================================================
			===========================================================================================================================
			===========================================================================================================================
			===========================================================================================================================
			===========================================================================================================================
			===========================================================================================================================
			===========================================================================================================================
			===========================================================================================================================
			===========================================================================================================================
			===========================================================================================================================
			===========================================================================================================================
			===========================================================================================================================
			===========================================================================================================================
			===========================================================================================================================
			===========================================================================================================================
			===========================================================================================================================
			===========================================================================================================================
			===========================================================================================================================
			===========================================================================================================================
			===========================================================================================================================
			===========================================================================================================================
			==================================================================<br>||';
			ob_flush();
			flush();
			ob_end_flush();

			sleep(15);
			$i++;
		}
    }
    
    public function actionTestRequest(){
        // Start data
        $reader_id = 19310;
        $client_id = 9615;
        $subject = 'Testing New chat tool (5 mins)';

        $request = new ChatRequests();
        $request->reader_id = $reader_id;
        $request->client_id = $client_id;
        $request->checkRequest(); // Delete record if exists
        $request->subject = $subject;
        $request->chat_type = 1; // todo: change chat type to real value (now only type 1 - debug)
        $request->duration = floor(5*60);

        $request_id = $request->addNewRequest();

        /// insert in chat_context table
                    /*
        $chatContext = new ChatContext();
        $chatContext->add($request_id, $_POST['client_id'], $_POST['reader'], $_POST['time']*60 );
                    */

        $cs=Yii::app()->clientScript;
        $baseUrl=Yii::app()->baseUrl;//$this->module->assetsUrl;
        //$cs->registerScriptFile('http://code.jquery.com/jquery-1.4.2.js');
                    ///$cs->registerScriptFile($baseUrl.'/js/jquery.ajax_queue.js');

                    $cs->registerScriptFile(Yii::app()->params['http_addr']."/js/jquery-1.5.2.js");
                    $cs->registerScriptFile(Yii::app()->params['http_addr']."/js/commandcommand.js");
                    $cs->registerScriptFile(Yii::app()->params['http_addr']."/js/util.js");

        $cs->registerScriptFile($baseUrl.'/js/jquery.json-2.2.js');

        $this->renderPartial('testRequest',
                array('reader_id'   => $reader_id,
                      'client_id'   => $client_id,
                      'subject'     => $subject,
                      'request_id'  => $request_id
        ));
    }

    /**
     * Reader activity checker
     */
    public function actionChatStartChecking()
    {
        $a = array();
        $count = ChatSession::startChatCheck($_GET['request_id']);
        ChatRequests::updateRequest($_GET['request_id']);

        if($count>0){
            $sessionKey = ChatContext::getSessionKey($_GET['request_id']);
            $a['chat'] = 'start';
            $a['sessionKey'] = $sessionKey;
            echo json_encode($a);
        } else {
            $a['chat'] = 'waiting';
            echo json_encode($a);
        }
    }

    public function actionTestMonitor(){

        $reader_id = 19310;

        $requesters = ChatRequests::getReadersRequest($reader_id);
        $client = ChatRequests::getReadersRequestNew($reader_id);

        $this->renderPartial('testMonitor',
                array('active' => $client->client_id,
                      'reader_id' => $reader_id)
                );
    }

    public function actionTestCheck(){
        $reader_id = 19310;

        $criteria = new CDbCriteria;
        $criteria->select = 'client_id, subject, reader_id, rr_record_id';
        $criteria->condition = 'reader_id = :reader_id
                                    AND (to_days(now())=to_days(t.laststatusupdate)
                                         and (time_to_sec(now()) - time_to_sec(t.laststatusupdate) < 10))';
	$criteria->params = array(':reader_id' => $reader_id);

        $cl_models = ChatRequests::model()->findAll($criteria);

        $a = array();

        if(!empty($cl_models)){
            foreach($cl_models as $mod){
                $a['update'] = 'update';
                $a['request_id'] = $mod->rr_record_id;
            }
        }
        else
            $a['update'] = 'continue';

        echo json_encode($a);
    }

    public function actionChatClient(){
    	try {
	        $ChatContext = ChatContext::getContext($_GET['sessionKey'], ChatContext::CLIENT);
    	}catch (Exception $e) {
    		die($e->getMessage());
    	}

        $js = "
	        var sessionKey = '".$_GET['sessionKey']."';
		    var userType = ".ChatContext::CLIENT.";
            var myNick = '".addcslashes($ChatContext->user->nickName, "'")."';
		    var http_addr = '".Yii::app()->params['http_addr']."chat/';//path to server dir
			var ssl_addr = '".Yii::app()->params['ssl_addr']."';
		    var startChatTime = ".($ChatContext->bal_start-$ChatContext->chatDuration()).";
		    var startFreeTime = ".$ChatContext->freeTimeRemaining().";
		    var startTotalTime = ".$ChatContext->clientTotalTimeRemaining().";
            var Are_you_sure_you_want ='".Yii::t('lang', 'Are_you_sure_you_want')."';
            var Connections_to_opponent_lost ='".Yii::t('lang', 'Connections_to_opponent_lost')."';
            var Your_session_is_now_over ='".Yii::t('lang', 'Your_session_is_now_over')."';
            var Are_you_sure_to_add ='".Yii::t('lang', 'Are_you_sure_to_add')."';
            var mins_more_for_the_current_session ='".Yii::t('lang', 'mins_more_for_the_current_session')."';
            var Are_you_sure_you_want_to_purchase_more_time ='".Yii::t('lang', 'Are_you_sure_you_want_to_purchase_more_time')."';
		    ";
		$this->registerChatScripts(ChatContext::CLIENT, $js);

		$timeToAdd = ($ChatContext->clientTotalTimeRemaining()-$ChatContext->chatTimeRemaining())/60;
		$debug=1; // $_GET['debug']

        $this->render('chatclient_start', array('timeToAdd' => $timeToAdd, 'debug' => $debug));
    }

    public function actionChatReader(){
    	try {
        	$ChatContext = ChatContext::getContext($_GET['sessionKey'], ChatContext::READER);
    	}catch (Exception $e) {
    		die($e->getMessage());
    	}

		if(strlen(stristr($_SERVER['HTTP_USER_AGENT'], "MSIE")) > 0){
			$sound =  "<EMBED src=\"".Yii::app()->params['http_addr']."sound/receive.wav\" width=0 height=0 autostart=true loop=false>";
		}
		else
		{
			$sound =
				"<OBJECT classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" WIDTH=\"1\" HEIGHT=\"1\" id=\"ring\" ALIGN=\"\"><PARAM NAME=movie VALUE=\"ring.swf\">	<PARAM NAME=quality VALUE=high><PARAM NAME=bgcolor VALUE=#FFFFFF> <EMBED src=\"".Yii::app()->params['http_addr']."sound/ringin.swf\" quality=high bgcolor=#FFFFFF WIDTH=\"1\" HEIGHT=\"1\" NAME=\"ring\" ALIGN=\"\" TYPE=\"application/x-shockwave-flash\" PLUGINSPAGE=\"http://www.macromedia.com/go/getflashplayer\" autostart=\"true\" loop=\"false\"></EMBED></OBJECT>";
		}

        $js = "
            var sessionKey = '".$_GET['sessionKey']."';
            var userType = ".ChatContext::READER.";
            var myNick = '".addcslashes($ChatContext->user->nickName, "'")."';
		    var http_addr = '".Yii::app()->params['http_addr']."chat/';//path to server dir
			var ssl_addr = '".Yii::app()->params['ssl_addr']."';
		    var startChatTime = ".($ChatContext->bal_start-$ChatContext->chatDuration()).";
		    var startFreeTime = ".$ChatContext->freeTimeRemaining().";
		    var startTotalTime = ".$ChatContext->clientTotalTimeRemaining().";
			var sound = '".$sound."';
            var Are_you_sure_you_want ='".Yii::t('lang', 'Are_you_sure_you_want')."';
            var Connections_to_opponent_lost ='".Yii::t('lang', 'Connections_to_opponent_lost')."';
            var The_client_has_no_paid_time ='".Yii::t('lang', 'The_client_has_no_paid_time')."';
            var End_session ='".Yii::t('lang', 'End_session')."';
            var Chat_is_over ='".Yii::t('lang', 'Chat_is_over')."';
            var Stand_by_is_OFF ='".Yii::t('lang', 'Stand_by_is_OFF')."';
            var Stand_by_is_ON ='".Yii::t('lang', 'Stand_by_is_ON_chat')."';
            var Are_you_sure_you_want_to_end ='".Yii::t('lang', 'Are_you_sure_you_want_to_end')."';
            var Ban_this_client ='".Yii::t('lang', 'Ban_this_client')."';
            var Please_enter_the_reason_of_banning ='".Yii::t('lang', 'Please_enter_the_reason_of_banning')."';
            var Your_session_is_now_over ='".Yii::t('lang', 'Your_session_is_now_over')."';
            var End_session_with_NO_payment ='".Yii::t('lang', 'End_session_with_NO_payment')."';
            var Due_to_various_reasons ='".Yii::t('lang', 'Due_to_various_reasons')."';
            var Are_you_here ='".Yii::t('lang', 'Are_you_here_chat')."';
            var Wake_Up = '".Yii::t('lang', 'Wake_Up')."';
            var Stand_By = '".Yii::t('lang', 'Stand_By')."';
		    ";
		$this->registerChatScripts(ChatContext::READER, $js);

        /// $session_id =  $chatContext->session_id; // ChatContext::model()->findByPk($_GET['sessionKey'])->session_id;
		$h = ChatHistory::getSession($ChatContext->session_id);
		$Subject = $h->Subject;

        $billingcountry = CreditCard::getCardInfo($ChatContext->client_id)->billingcountry;


		$debug=1; // $_GET['debug']

        $this->render('chatreader_start', array(
        	'topic' => $Subject,
        	'clientNick' => $ChatContext->opponent->nickName,//'clientNick',
        	'gender' => $ChatContext->client->gender,
        	'userName' => $ChatContext->client->credit_cards->firstname,
        	'dob' => $ChatContext->client->month." ".$ChatContext->client->day.", ".$ChatContext->client->year,
        	'location' => $billingcountry,
        	'signUpDate' => date("M d, Y", strtotime($ChatContext->client->rr_createdate)),
			'debug' => $debug,	/// debug mode
			'sound' =>$sound,		/// play sound when reader recieve msg
        ));
    }

    private function registerChatScripts($userType, $js) {

        $cs=Yii::app()->clientScript;
        $baseUrl=Yii::app()->baseUrl;//$this->module->assetsUrl;
        $cs->registerScriptFile($baseUrl.'/js/jquery-1.5.2.js');
        $cs->registerScriptFile($baseUrl.'/js/oop.js');
        $cs->registerScriptFile($baseUrl.'/js/chat_queue.js');
		$cs->registerScriptFile($baseUrl.'/js/util.js',CClientScript::POS_HEAD);
		$cs->registerScriptFile($baseUrl.'/js/chat.js',CClientScript::POS_HEAD);
        $cs->registerScriptFile($baseUrl.'/js/command.js');
        $script=($userType==ChatContext::CLIENT?'chat_client':'chat_reader');
		$cs->registerScriptFile($baseUrl.'/js/'.$script.'.js',CClientScript::POS_HEAD);
        $cs->registerScript(1,$js,CClientScript::POS_HEAD);
        $cs->registerCssFile($baseUrl.'/css/chat.css');

        $this->layout = 'application.views.layouts.chat_start';
        return $cs;
    }

    public function actionStartChatting(){
        $sessionKey = ChatSession::startChatting($_GET['request_id']);
        $this->redirect(Yii::app()->params['http_addr']."chat/test/ChatReader?sessionKey=$sessionKey");
    }

    public function actionGetChatMessage(){
        $file = file($_POST['filename']);
        echo json_encode($file);
    }
    
    public function actionTestQueue()
    {
        if(isset($_GET['t']))
        {
            $a = array();
            $a['resp'] = "RECEIVED";
            return json_encode($a);
        }        
        
        $cs=Yii::app()->clientScript;
        $baseUrl=Yii::app()->baseUrl;
        $cs->registerScriptFile($baseUrl.'/js/chat_queue.js');
        
        $this->renderPartial('test_queue');
    }
    
    public function actionTestTrans()
    {
        $session_id = 134519;
        $history = ChatHistory::getSession($session_id);
        $history->getTranscript();
        
    }

    public function actionCmd()
    {
    	$_GET['c'] = 'refresh';
    	$cmd = $_GET['c'];
    	$session_id = $_GET['s'];
    	$p = $_GET['p'];
        $history = ChatHistory::getSession($session_id);
        $history->getTranscript();
        
    }
    
}

?>
