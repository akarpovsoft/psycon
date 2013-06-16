<?php
class ChatController extends PsyController
{
     public function filters()
     {
        $a = array(
                'accessControl', // perform access control for CRUD operations
                array('application.components.PsyBanController + chatstart'),
            );
        return parent::filters($a);
     }
     
    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
                array('allow', // allow authenticated user
                        'actions'=>array('chatstart','ChatStartChecking','ReaderMonitor','ReaderStatusChange','ReaderStatusChangeNew','StartChatting',
                            'ClientEndSession', 'ReaderEndSession', 'ChatClient', 'ChatReader', 'Test', 'NewReaderMonitor', 'checkNewClient','CheckNewStatus'),
                        'users'=>array('@'),
                ),
                array('deny',  // deny all users
                        'users'=>array('*'),
                ),
        );
    }
    
    public function actionError()
    {
        if($error=Yii::app()->errorHandler->error) {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays page with chat request form
     */
    public function actionChatStart()
    {
        if(Yii::app()->user->type == 'reader')
                $this->redirect(Yii::app()->params['http_addr'].'users/mainmenu');
        if(isset($_GET['waiting_fail'])){
            $this->layout = $this->without_left_menu;
            $reader = Readers::getReader($_GET['reader_id']);
            $client = Clients::getClient($_GET['client_id']);
            //send email to reader
            // byt how we know reader language ? by ashumilo
            $date = date("d/m/Y");
            $time = date("H:i:s");
            $subject = "".$client->login." tried to chat w/you!";
            $text = 'DATE: '.$date."<br>\r\n".
                    'TIME: '.$time."<br>\r\n".
                    $reader->name.' : Please do the following now:'."<br>\r\n".
                    'Send this client an email telling them to reboot, try the Eroom or switch to FireFox'."<br>\r\n".
                    'And that you are also rebooting now as well.'."<br>\r\n".
                    'Let them know when to expect you to be back onling & available to chat.'."<br>\r\n"."<br>\r\n".
                    'If they do not appear on your client list - then please only reboot'."<br>\r\n".
                    'We will inform them of the other things to do via email (automatically)'."<br>\r\n"."<br>\r\n".
                    'Remeber- you should also reboot now as quickly as possible!'."<br>\r\n";
            PsyMailer::send($reader->emailaddress, $subject, $text);

            //send email to admin
            $date = date("d/m/Y");
            $time = date("H:i:s");
            $subject = "".$client->login." aborted chat w/".$reader->name."!";

            $text = 'DATE: '.$date."<br>\r\n".
                    'TIME: '.$time."<br>\r\n".
            (!empty($_SERVER['HTTP_USER_AGENT'])? 'Client browser: '.$_SERVER['HTTP_USER_AGENT'] : '')."<br>\r\n";
            PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $text);

            //send email to client
            $subject = "Your chat attempt failed";
            $text = 'Reader was: '.$reader->name."<br>\r\n".
                    'DATE: '.$date."<br>\r\n".
                    'TIME: '.$time."<br>\r\n".
                    'Dear '.$client->login."<br>\r\n".
                    'We\'re sorry your recent attempt to chat with: '.$reader->name.' has failed:'."<br>\r\n".
                    'Please do the following:'."<br>\r\n".
                    '1) reboot your computer'."<br>\r\n".
                    '2) try again but use FireFox or Google Chrome- if your previous attempt was with IE'."<br>\r\n".
                    '3) you might want to try our Emergency Chatroom (link is found on the chat log on page)'."<br>\r\n".
                    'Please also note:'."<br>\r\n".
                    'Your reader is rebooting now as well- so if you see them off-line: please be patient and wait for them to return'."<br>\r\n".
                    '4) You might want to also try another Reader'."<br>\r\n".
                    '5) Please check your email now for a message from the Reader'."<br>\r\n".
                    'If you need further assistance please contact support: javachat@psychic-contact.com'."<br>\r\n".
                    'Thank you'."<br>\r\n".
                    Yii::app()->params['siteName'].' Admin';
            PsyMailer::send($client->emailaddress, $subject, $text);

            $this->render('chat_waiting', array('waiting_fail' => 1));
            return;
        }
        if(isset($_POST['start'])&&($_POST['reader']<>$_POST['client_id']))
        {
            $request = new ChatRequests();
            $request->reader_id = $_POST['reader'];
            $request->client_id = $_POST['client_id'];
            $request->checkRequest(); // Delete record if exists
            $request->subject = $_POST['subject'].' ('.$_POST['time'].' mins)';
            $request->chat_type = 1; // todo: change chat type to real value (now only type 1 - debug)
            $request->duration = floor($_POST['time']*60);
			
            $request_id = $request->addNewRequest();

            /// insert in chat_context table
			/*
            $chatContext = new ChatContext();
            $chatContext->add($request_id, $_POST['client_id'], $_POST['reader'], $_POST['time']*60 );
			*/

            $this->layout = $this->without_left_menu;
            $cs=Yii::app()->clientScript;
            $baseUrl=Yii::app()->baseUrl;//$this->module->assetsUrl;
            //$cs->registerScriptFile('http://code.jquery.com/jquery-1.4.2.js');
			///$cs->registerScriptFile($baseUrl.'/js/jquery.ajax_queue.js');

			$cs->registerScriptFile(Yii::app()->params['http_addr']."/js/jquery-1.4.4-tuned.js");
			$cs->registerScriptFile(Yii::app()->params['http_addr']."/js/command.js");
			$cs->registerScriptFile(Yii::app()->params['http_addr']."/js/util.js");
         
            $cs->registerScriptFile($baseUrl.'/js/jquery.json-2.2.js');

            $this->render('chat_waiting',
                    array('reader_id'   => $_POST['reader'],
                          'client_id'   => $_POST['client_id'],
                          'subject'     => $_POST['subject'].' ('.$_POST['time'].'  mins)',
                          'request_id'  => $request_id
            ));            
            return;
        }
        
        $client = Clients::getClient(Yii::app()->user->id);
		
        if(isset($_GET['gr']))
            $group_id = $_GET['gr'];
        else $group_id = 1; // Default group

        // Check if main group reader is online - show only this reader
        // If main group reader is online - select it in list and disable message of favourite reader
        /// @todo: this doesn't work if main reader is set
        $main_group_reader = ReadersVisibility::getMainGroupReader($group_id);

        if(!empty($main_group_reader))
            $main_online = Readers::getReaderOnline($main_group_reader);
       
        if(!empty($main_online))
            $main_reader = Readers::getReader($main_group_reader);
       
        ///var_export($main_reader['rr_record_id']);
        // All another readers of current groups
        $readers_online = Readers::getOnlineReadersList($group_id);
       
        $aval = Readers::getOnlineReadersList($group_id);
        if(is_null($readers_online)){
            $this->render('chat_start', array('no_one_readers_online' => 1));
            return;
        }
        $tmp = Yii::app()->user->id;
        $favorite_reader = Clients::getFavoriteReader($tmp);
       
        if(isset($_GET['chat_with']))
            $chat_with = Readers::getReader($_GET['chat_with']);

        foreach($aval as $reader) {
            if($reader['rr_record_id'] == $favorite_reader)
                $avaliable = $reader['name'];
        }

        $this->render('chat_start', array('client' => $client,
                'readers_online' => $readers_online,
                'avaliable' => $avaliable,
                'favorite_reader' => $favorite_reader,
                'with' => $chat_with['rr_record_id'],
                'main_group_reader' => $main_reader));
    }
    
    /**
     * Reader activity checker
     */
    public function actionChatStartChecking()
    {
        $a = array();
        $count = ChatSession::startChatCheck($_GET['client_id'], $_GET['reader_id']);
        ChatRequests::updateRequest($_GET['reader_id'], $_GET['subject'], $_GET['request_id']);
       
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

    /**
     * Reader monitor
     */
    public function actionReaderMonitor()
    {
         $requesters = ChatRequests::getReadersRequest(Yii::app()->user->id);
        $clients = ChatRequests::getReadersRequestNew(Yii::app()->user->id);
        $page_type = 'online';

        // calc emailPendings & nrrQuests
        $emailPendings = count(EmailQuestions::getByReaderId(Yii::app()->user->id));
        $nrrQuests = count(NrrRequests::getByReaderId(Yii::app()->user->id));
        // end calc
        $reader = Readers::getReader(Yii::app()->user->id);
        if(isset($_GET['logout'])){
            //send logoff email for admin            
            $subject = "PSYCHAT - LOG OFF: ".$reader->name;
            $text = $reader->login." Logged Off at ".date("Y-m-d H:i:00");
            PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $text);
            // end sending email
            Readers::updateReaderStatus(Yii::app()->user->id, 'offline');
            $page_type = 'logout';
        }

        // Reader busy checking
        if (Count(ChatSession::getByReaderId(Yii::app()->user->id)) && !substr_count($reader->status , 'break')) {
            $sessionsReaderData = ChatSession::getByReaderId(Yii::app()->user->id);
            foreach ($sessionsReaderData as $sessionReaderData ) {
                if (strtotime($sessionReaderData->laststatusupdate) + 10 > time())
                    $page_type = 'busy';
            }
        }

        // Reader break checking
        if(substr_count($reader->status , 'break')) {
            $mins = preg_replace('/break(.*)/', '\\1', $reader->status);
            $page_type = 'break';
        }

        $active_clients = array();
        $sound = "";
        foreach($clients as $cl){
            $active_clients[] = $cl->client_id;
        }
        // @todo: this crutch stopping users queue after first user.
        // It means, that reader going to busy mod, if at least one client requests him for a chat
        // Another changes to do this: select from T1_7 in "ifReaderchatting" methods of ChatRequests & ChatContext models
        if(count($active_clients) >= 1)
            $page_type = 'busy';
        // Then if maybe only one requester, take his chat type (old or new)
        $chat_type = Clients::getClient($active_clients[0])->chat_type;

        if($_GET['sound'] == 1)
            $sound = "<OBJECT classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" WIDTH=\"1\" HEIGHT=\"1\" id=\"ring\" ALIGN=\"\"> <PARAM NAME=movie VALUE=\"".Yii::app()->params['site_domain']."/chat/ring.swf\"> <PARAM NAME=quality VALUE=high> <PARAM NAME=bgcolor VALUE=#FFFFFF> <EMBED src=\"".Yii::app()->params['site_domain']."/chat/ring.swf\" quality=high bgcolor=#FFFFFF  WIDTH=\"1\" HEIGHT=\"1\" NAME=\"ring\" ALIGN=\"\" TYPE=\"application/x-shockwave-flash\" PLUGINSPAGE=\"http://www.macromedia.com/go/getflashplayer\"></EMBED></OBJECT>";
        if($_GET['sound'] == 2)
            $sound = "<OBJECT classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" WIDTH=\"1\" HEIGHT=\"1\" id=\"admin_ring\" ALIGN=\"\"> <PARAM NAME=movie VALUE=\"".Yii::app()->params['site_domain']."/chat/sounds/ringin.swf\"> <PARAM NAME=quality VALUE=high> <PARAM NAME=bgcolor VALUE=#FFFFFF> <EMBED src=\"".Yii::app()->params['site_domain']."/chat/sounds/ringin.swf\" quality=high bgcolor=#FFFFFF  WIDTH=\"1\" HEIGHT=\"1\" NAME=\"ring\" ALIGN=\"\" TYPE=\"application/x-shockwave-flash\" PLUGINSPAGE=\"http://www.macromedia.com/go/getflashplayer\"></EMBED></OBJECT>";

        $cs=Yii::app()->clientScript;
        $baseUrl=Yii::app()->baseUrl;//$this->module->assetsUrl;
        $this->renderPartial('reader_monitor', array(
            'reader_id' => Yii::app()->user->id,
            'req' => $requesters,
            'active' => $active_clients,
            'type' => $page_type,
            'mins' => $mins,
            'sound' => $sound,
            'emailPendings' => $emailPendings,
            'nrr' => $nrrQuests,
            'chatType' => $chat_type
        ));
    }
    /**
     * Change reader status through AJAX
     */
    public function actionReaderStatusChange(){
        Readers::updateReaderStatus($_POST['reader_id'], $_POST['status']);
    }

    public function actionStartChatting(){
        $sessionKey = ChatSession::startChatting($_GET['request_id'],$_GET['client_id'], $_GET['reader_id']);
        $this->redirect(Yii::app()->params['http_addr']."chat/ChatReader?sessionKey=$sessionKey");
    }

    public function actionClientEndSession(){
		$sessionKey	= $_GET['session_key'];
		try {	
			$chatContext = ChatContext::getContext($sessionKey, ChatContext::CLIENT);
    	}catch (Exception $e) {
    		die($e->getMessage());
    	}

		$debugExtra = DebugExtra::getGebugExtra($chatContext->session_id);
		$debugExtra->client_finished = 1;
		$debugExtra->save();

        $this->layout = $this->without_left_menu;
        $this->render('client_end_chat', array('session_key' => $sessionKey));
    }
    
    /**
     * End session for reader
     * Calculate received money, using billing system
     */
    public function actionReaderEndSession(){

		$sessionKey	= $_GET['session_key'];
		$addLost	= $_GET['addLost'];
		try {
			$chatContext = ChatContext::getContext($sessionKey, ChatContext::READER);
    	}catch (Exception $e) {
    		die($e->getMessage());
    	}

        $billing = new PsyChatBilling($chatContext->session_id);
        $nopayment = $billing->endSession($addLost, 0);
	
        $this->layout = $this->without_left_menu;

        $session = ChatHistory::getSession($chatContext->session_id);
        $client = $chatContext->client;
        $bmt_time = BmtStat::processBmt($chatContext->client_id);

        $bmt = ($bmt_time > 0) ? 'yes' : 'no';
        $this->render('reader_end_chat', array(
			'nopayment' => $nopayment,
			'dueToReader' => $session->Due_to_reader,
			'paidTime' => $session->Paid_time,
			'rate' => $session->Rate,
			'bmt' => $bmt,
			'freeTime' => round($session->Free_time/60, 2),
			'clientName' => $session->Client_name,
			'affiliate' => $client->affiliate,
			'readerCouldMake_mins' => round($session->Returned_time/60, 2),
			'readerCouldMake_money' => round(round($session->Returned_time/60, 2) * $session->Rate, 2)
			)
		);
    }        

    /**
     * Registers chat javascripts
     */
    private function registerChatScripts($userType, $js) {
    	
        $cs=Yii::app()->clientScript;
        $baseUrl=Yii::app()->baseUrl;//$this->module->assetsUrl;
        $cs->registerScriptFile($baseUrl.'/js/jquery-1.4.4-tuned.js');
		$cs->registerScriptFile($baseUrl.'/js/jquery.json-2.2.js');
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
		    var http_addr = '".Yii::app()->params['http_addr']."';//path to server dir
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
		    var http_addr = '".Yii::app()->params['http_addr']."';//path to server dir
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
		$ChatHistory = ChatHistory::getSession($ChatContext->session_id);
		$Subject = $ChatHistory->Subject;

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
    
    public function actionCheckNewClient(){

		 // @TODO : remove this implied headers and use yii cache control
        if (! empty($_SERVER['SERVER_SOFTWARE']) && strstr($_SERVER['SERVER_SOFTWARE'], 'Apache/2')) {
            header('Cache-Control: no-cache, pre-check=0, post-check=0, max-age=0');
        } else {
            header('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
        }
        header('Expires: ' . gmdate('D, d M 2009 H:i:s', time()) . ' GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

        $time = new OnlineTime;
        $time->reader_id= Yii::app()->user->id;
        $time->SaveTimeAll();

        $a = array();

        if(empty($_POST['clients']))
            $_POST['clients'] = array();

        $check = ChatRequests::checkNewClient(Yii::app()->user->id, $_POST['clients']);
		
        $readerStatus = Readers::getReaderChatStatus(Yii::app()->user->id);
		
        if($check != false){
            $a['update'] = 'update';
            $a['type'] = $check;
        }
        else 
            $a['update'] = 'continue';
		
        if($readerStatus != $_POST['current_status']) // check for status change
            $a['update'] = 'update';

		$a['status11'] = $readerStatus;

		
        echo json_encode($a);
    }
}
?>