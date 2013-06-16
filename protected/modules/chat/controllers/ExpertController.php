<?php
class ExpertController extends CController
{
    /**
     * Special layout for different actions
     */
    const WITHOUT_LEFT_MENU = 'main_without_left_menu';

    
     public function filters()
     {
        return array(
                'accessControl', // perform access control for CRUD operations                
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
                        'actions'=>array('endSession', 'index', 'changeSoundMode', 'getSoundMode'),
                        'users'=>array('@'),
                ),
                array('deny',  // deny all users
                        'users'=>array('*'),
                ),
        );
    }

    /**
     * End session for reader
     * Calculate received money, using billing system
     */
    public function actionEndSession(){

		$sessionKey	= $_GET['session_key'];
		try {
			$sessionId = ChatContext::getSessionId($sessionKey);
    	}catch (Exception $e) {
    		die("Wrong session key");
    	}

        $this->layout = self::WITHOUT_LEFT_MENU;
       
        $history = ChatHistory::getSession($sessionId);
        $client = ChatHelper::getClient($history->getClientId());
        
        $message = new Messages();
        $message->From_user = Yii::app()->user->id;
        $message->From_name = 'Psychat';
        $message->To_user = Yii::app()->user->id;
        $message->Subject = "SESSION ENDED - ".$history->Reader_name."(reader)/".$client->getLogin()."(client)";
        $message->Body = "ESSION ENDED - ".$history->Reader_name."(reader)/".$client->getLogin()."(client) 
           ".date('Y-m-d H:i a')."/SID# ".$sessionId." 
               Client Username: ".$client->getLogin()." 
               Affiliate: ".$client->affiliate." 
               Paid time used: ".$history->getPaidTimeInMinutes()." mins. 
               Free Time: ".$history->getFreeTimeInMinutes()." mins. 
               BMT: ".$bmt."
               no Rate: ".$history->getRate()." per minute 
               Total Due Reader: ".$history->getDueToExpert();
        $message->send();
        
        $bmt = $history->BMT() ? 'yes' : 'no';
        $this->render('end_chat', array(
			'nopayment' => $history->noPayment(),
			'dueToReader' => $history->getDueToExpert(),
			'paidTime' => $history->getPaidTimeInMinutes(),
			'halfPaidtime' => $history->half_paid_time,
			'rate' => $history->getRate(),
			'bmt' => $bmt,
			'freeTime' => $history->getFreeTimeInMinutes(),
			'clientName' => $client->getLogin(),
			'affiliate' => $client->affiliate,
			'readerCouldMake_mins' => $history->getReturnedTimeInMinutes(),
			'readerCouldMake_money' => round($history->getReturnedTimeInMinutes() * $history->getRate(), 2),
                        'session_key' => $sessionKey
			)
		);
    }

    public function actionIndex(){
    	try {
        	$chatContext = ChatContext::getContext($_GET['sessionKey'], ChatContext::READER);
    	}catch (Exception $e) {
    		die($e->getMessage());
    	}

        if(strlen(stristr($_SERVER['HTTP_USER_AGENT'], "MSIE")) > 0){
                $sound =  "<EMBED src=\"".ChatHelper::baseUrl()."sound/receive.wav\" width=0 height=0 autostart=true loop=false>";
        }
        else
        {
                $sound = "<OBJECT classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" WIDTH=\"1\" HEIGHT=\"1\" id=\"ring\" ALIGN=\"\"\><PARAM NAME=movie VALUE=\"ring.swf\"\><PARAM NAME=quality VALUE=high\><PARAM NAME=bgcolor VALUE=#FFFFFF\><EMBED src=\"".Yii::app()->params['site_domain']."/chat/receive.swf\" quality=high bgcolor=#FFFFFF WIDTH=\"1\" HEIGHT=\"1\" NAME=\"ring\" ALIGN=\"\" TYPE=\"application/x-shockwave-flash\" PLUGINSPAGE=\"http://www.macromedia.com/go/getflashplayer\" autostart=\"true\" loop=\"false\"\>\<\/EMBED\></OBJECT\>";
        }
        $sound_mode = ($_COOKIE['sound_status']) ? $_COOKIE['sound_status'] : 'off';

        $js = "
            var sessionKey = '".$_GET['sessionKey']."';
            var userType = ".ChatContext::READER.";
            var myNick = '".addcslashes($chatContext->user->nickName, "'")."';
		    var baseChatPath = '".ChatHelper::moduleUrl()."';//path to root of module
		    var startChatTime = ".($chatContext->bal_start-$chatContext->chatDuration()).";
		    var startFreeTime = ".$chatContext->freeTimeRemaining().";
		    var startTotalTime = ".$chatContext->clientTotalTimeRemaining().";
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
		$this->layout = 'chat';
		$cs = ChatHelper::registerChatJS(ChatContext::READER, $js);               
                $sound_js = '
                function changeSoundMode(mode)
                {
                    var url = "'.Yii::app()->params['http_addr'].'chat/expert/changeSoundMode?mode="+mode;

                    $.ajax({    
                    url: url,  
                    cache: false,  
                    success: function(html){  
                        $("#sound").html(html);  
                    }  
                    });    
                }
                ';
                $cs->registerScript(2,$sound_js,CClientScript::POS_HEAD);
                
		$history = ChatHistory::getSession($chatContext->session_id);
                $history->saveReaderUserAgent();
                
		$debug=1; // $_GET['debug']
                
        $this->render('index', array(
        	'topic' => $history->getSubject(),
        	'clientNick' => $chatContext->client->getLogin(),//'clientNick',
        	'gender' => $chatContext->client->gender,
        	'userName' => $chatContext->client->getScreenName(),
        	'dob' => date("M d, Y", $chatContext->client->getDOBTimestamp()),
        	'location' => $chatContext->client->getBillingCountry(),
        	'signUpDate' => date("M d, Y", strtotime($chatContext->client->getSignupDate())),
			'debug' => $debug,	/// debug mode
			'sound' =>$sound,		/// play sound when reader recieve msg
                        'sound_mode' => $sound_mode
        ));
    }
    
    public function actionChangeSoundMode()
    {
        if($_GET['mode'] == 'on')
        {
            setcookie('sound_status', 'on');           
        }
        else
        {
            setcookie('sound_status', 'off'); 
        }
        die();
    }
    
    public function actionGetSoundMode()
    {
        echo $_COOKIE['sound_status'];
        die();
    }
}
?>