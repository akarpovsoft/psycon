<?php
class ClientController extends CController
{
    /**
     * Special layout for different actions
     */
    const WITHOUT_LEFT_MENU = 'main_without_left_menu';
    const ZERO = 'application.views.layouts.zero';
    
     public function filters()
     {
        return array(
                'accessControl', // perform access control for CRUD operations
                array('application.components.PsyBanController + chatStart'),
            );
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
                        'actions'=>array('index', 'chatStart', 'waitingFail', 'chatRequest', 'chatStartChecking', 'endSession', 'removeRequest'),
                        'users'=>array('@'),
                ),
                array('deny',  // deny all users
                        'users'=>array('*'),
                ),
        );
    }

    public function actionWaitingFail(){
        
        
        $client = ChatHelper::getClient(Yii::app()->user->id);
        $request = ChatRequests::getByClientId(Yii::app()->user->id);
        $reader = ChatHelper::getExpert($request->getExpertId());
        $this->layout = ($client->site_type == 'US' || !$client->site_type) ? self::WITHOUT_LEFT_MENU : self::ZERO;
        //send email to reader
        // byt how we know reader language ? by ashumilo
        $date = date("d/m/Y");
        $time = date("H:i:s");
        $subject = "".$client->getLogin()." tried to chat w/you!";
        $text = 'DATE: '.$date."<br>\r\n".
                'TIME: '.$time."<br>\r\n".
                $reader->getName().' : Please do the following now:'."<br>\r\n".
                'Send this client an email telling them to reboot, try the Eroom or switch to FireFox'."<br>\r\n".
                'And that you are also rebooting now as well.'."<br>\r\n".
                'Let them know when to expect you to be back onling & available to chat.'."<br>\r\n"."<br>\r\n".
                'If they do not appear on your client list - then please only reboot'."<br>\r\n".
                'We will inform them of the other things to do via email (automatically)'."<br>\r\n"."<br>\r\n".
                'Remeber- you should also reboot now as quickly as possible!'."<br>\r\n";
        PsyMailer::send($reader->getEmail(), $subject, $text);

        //send email to admin
        $date = date("d/m/Y");
        $time = date("H:i:s");
        $subject = "".$reader->getName()." wasn't response to chat request by ".$reader->getLogin()."!";

        $text = 'DATE: '.$date."<br>\r\n".
                'TIME: '.$time."<br>\r\n".
        (!empty($_SERVER['HTTP_USER_AGENT'])? 'Client browser: '.$_SERVER['HTTP_USER_AGENT'] : '')."<br>\r\n";
        PsyMailer::send(ChatHelper::adminEmail(), $subject, $text);

        //send email to client
        $subject = "Your chat attempt failed";
        $text = 'Reader was: '.$reader->getName()."<br>\r\n".
                'DATE: '.$date."<br>\r\n".
                'TIME: '.$time."<br>\r\n".
                'Dear '.$client->getLogin()."<br>\r\n".
                'We\'re sorry your recent attempt to chat with: '.$reader->getName().' has failed:'."<br>\r\n".
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
        PsyMailer::send($client->getEmail(), $subject, $text);
        
        $reader->setChatAbort();        
        
        $this->render('chat_waiting_fail');
    }
    
    public function actionRemoveRequest()
    {
        $request = ChatRequests::getByClientId(Yii::app()->user->id);
        $reader = ChatHelper::getExpert($request->getExpertId());
        $client = Clients::getClient(Yii::app()->user->id);

        ChatRequests::delRequestByClient(Yii::app()->user->id);

        if($this->layout != 'application.views.layouts.mobile')
            $this->layout = ($client->site_type == 'US' || !$client->site_type) ? self::WITHOUT_LEFT_MENU : self::ZERO;
        //send email to admin
        $date = date("d/m/Y");
        $time = date("H:i:s");
        $subject = "".$client->getLogin()." aborted chat w/".$reader->getName()."!";

        $text = 'DATE: '.$date."<br>\r\n".
                'TIME: '.$time."<br>\r\n".
        (!empty($_SERVER['HTTP_USER_AGENT'])? 'Client browser: '.$_SERVER['HTTP_USER_AGENT'] : '')."<br>\r\n";
        PsyMailer::send(ChatHelper::adminEmail(), $subject, $text);
        
        $this->render('chat_waiting_fail');
    }    
    
    public function actionChatRequest()
    { 
    	$expertId = $_POST['reader'];
    	$time = $_POST['time'];
        $subject = $_POST['subject'].' ('.$time.' mins)';
    	
        if(is_null($expertId))
        {
            $this->render('chat_waiting_fail');
            return;
        }
        
    	$reader = ChatHelper::getExpert($expertId);
    	$client = ChatHelper::getClient(Yii::app()->user->id);
    	
        if($reader->type=='reader' && $client->type=='client')
        {
            if($reader->getStatus() != 'online')
            {
                 $this->render('chat_waiting_fail');
                 return;
            }
            if($client->balance <= 0)
            {
                $this->render('zero_balance');
                return;
            }
            $request = new ChatRequests();
            $request->setExpertId($expertId);
            $request->setClientId(Yii::app()->user->id);
            $request->checkRequest(); // Delete record if exists
            $request->subject = $subject;
            $request->chat_type = 7; // todo: change chat type to real value. 7 is only for NEW CHAT
            $request->duration = floor($time*60);

            $request_id = $request->addNewRequest();

            if($this->layout != 'application.views.layouts.mobile')
                $this->layout = ($client->site_type == 'US' || !$client->site_type) ? self::WITHOUT_LEFT_MENU : self::ZERO;
            $cs=Yii::app()->clientScript;
            
            $cs->registerScriptFile(ChatHelper::baseUrl()."js/jquery-1.5.2.js");
            $cs->registerScriptFile(ChatHelper::baseUrl().'js/jquery.json-2.2.js');

            $this->render('chat_waiting', array());
        }        
    }

    /**
     * Displays page with chat request form
     */
    public function actionChatStart()
    {
        if(Yii::app()->user->type != 'client')
                $this->redirect(ChatHelper::baseUrl().'users/mainmenu');
        
        $client = ChatHelper::getClient(Yii::app()->user->id);
        
        if($client->balance <= 0)
        {
            $this->render('zero_balance');
            return;
        }
        
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
            $main_reader = ChatHelper::getExpert($main_group_reader);

        ///var_export($main_reader['rr_record_id']);
        // All another readers of current groups
        $readers_online = Readers::getOnlineReadersList($group_id);
        if(is_null($readers_online)){
            $this->render('chat_start', array('no_one_readers_online' => 1));
            return;
        }

        $favorite_reader = Clients::getFavoriteReader(Yii::app()->user->id);
        
        if(isset($_GET['chat_with']))
            $chat_with = ChatHelper::getExpert($_GET['chat_with']);

        foreach($readers_online as $reader) {
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
        $request = ChatRequests::getByClientId(Yii::app()->user->id);
        if(!is_object($request)) {
            $a['chat'] = 'offline';
            echo json_encode($a);
            return;
        }

        ChatRequests::updateRequest($request->getId());
        $sessionKey = ChatContext::startChatCheck($request->getId());
        if($sessionKey) {
            $a['chat'] = 'start';
            $a['sessionKey'] = $sessionKey;
            echo json_encode($a);
        } else {
            $reader = ChatHelper::getExpert($request->getExpertId()); 
            // It's not important which status is... LAST STATUS UPDATE Checking only.
            // If `laststatusupdate` not updating - reader close monitor window (or lost internet connection)
            if($reader->isBusy() && !$reader->isUpdate()) {
                $a['chat'] = 'offline';
            } else
                $a['chat'] = 'waiting';
            echo json_encode($a);                
        }
    }

    public function actionEndSession(){
		$sessionKey	= $_GET['session_key'];
		try {
			$chatContext = ChatContext::getContext($sessionKey, ChatContext::CLIENT);
    	}catch (Exception $e) {
    		$sessionKey = "";
    	}
         
        $site_type = $chatContext->client->site_type;
         if($this->layout != 'application.views.layouts.mobile')
                $this->layout = ($site_type == 'US' || !$site_type) ? self::WITHOUT_LEFT_MENU : self::ZERO;

        $links = (!is_null($site_type) && $site_type != 'US') 
            ? ChatHelper::makeEndChatLinks($site_type) : ChatHelper::makeEndChatLinks();
         
        $this->render('end_chat', array('session_key' => $sessionKey, 'links' => $links, 'reader' => $chatContext->reader->name));
    }

    /**
     * opens chat
     */
    public function actionIndex(){
        RemoveFreetime::fixFreeTime(Yii::app()->user->id);
    	try {
	        $ChatContext = ChatContext::getContext($_GET['sessionKey'], ChatContext::CLIENT);
    	}catch (Exception $e) {
    		die($e->getMessage());
    	}

        $js = "
	        var sessionKey = '".$_GET['sessionKey']."';
		    var userType = ".ChatContext::CLIENT.";
            var myNick = '".addcslashes($ChatContext->user->nickName, "'")."';
		    var baseChatPath = '".ChatHelper::moduleUrl()."';//path to root of module
			var paymentUrl = '".ChatHelper::paymentURL()."';
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
            ChatHelper::registerChatJS(ChatContext::CLIENT, $js);
		
            $timeToAdd = ($ChatContext->clientTotalTimeRemaining()-$ChatContext->chatTimeRemaining())/60;
            
	    	$debug=1; // $_GET['debug'] 
	                
            $history	= ChatHistory::getSession($ChatContext->session_id);
            $history->saveUserAgent();
            
            $this->layout = 'chat';
            $this->render('index', array('timeToAdd' => $timeToAdd, 'debug' => $debug));
            
    }

}
?>