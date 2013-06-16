<?php
class ChatHelper
{
    public static function getExpert($id) {
		return Readers::getReader($id);    	
    }

    public static function getClient($id) {
		return Clients::getClient($id);    	
    }
    
    
    public static function registerChatJS($userType, $js) {
        $cs=Yii::app()->clientScript;
        $baseUrl=Yii::app()->baseUrl;//$this->module->assetsUrl;
        $cs->registerScriptFile($baseUrl.'/js/jquery-1.5.2.js');
		$cs->registerScriptFile($baseUrl.'/js/jquery.json-2.2.js');
        $cs->registerScriptFile($baseUrl.'/js/oop.js');
        $cs->registerScriptFile($baseUrl.'/js/chat/command.js');
        $cs->registerScriptFile($baseUrl.'/js/chat/ajax_queue.js');
		$cs->registerScriptFile($baseUrl.'/js/chat/util.js',CClientScript::POS_HEAD);
		$cs->registerScriptFile($baseUrl.'/js/chat/chat.js',CClientScript::POS_HEAD);
        $script=($userType==ChatContext::CLIENT?'client':'expert');
		$cs->registerScriptFile($baseUrl.'/js/chat/'.$script.'.js',CClientScript::POS_HEAD);
        $cs->registerScript(1,$js,CClientScript::POS_HEAD);
        $cs->registerCssFile($baseUrl.'/css/chat.css');
        return $cs;
    }

    //////////////////////// URL helpers //////////////////////////////

    public static function baseUrl() {
		return  Yii::app()->params['http_addr'];	
    }

    public static function moduleUrl() {
		return  self::baseUrl()."chat/";	
    }
    
    public static function paymentURL() {
		return  Yii::app()->params['ssl_addr']."pay/chat";	
    }
    
    public static function startChatUrl() {
		return self::baseUrl().PsyConstants::getName(PsyConstants::PAGE_CHATSTART);
    }
    
    public static function makeEndChatLinks($rasp = null)
    {
        if($rasp)
        {
            $domain = Yii::app()->params['netrestrict'][$rasp];
            $links = array(
                'chatStart' => $domain.'/chat',
                'addFunds' => $domain.'/account/addFunds',
                'contactUs' => $domain.'/site/contact',
                'chatHistory' => $domain.'/history',
                'mainMenu' => $domain.'/account',
            );    
        }
        else
        {
            $domain = self::baseUrl();
            $links = array(
                'chatStart' => $domain.'chat/client/chatStart',
                'addFunds' => self::paymentURL(),
                'contactUs' => $domain.'site/contact',
                'chatHistory' => $domain.'users/clientchathistory',
                'mainMenu' => $domain.'users/mainmenu',
            );
        }
        return $links;
    }
    
    /////////////////////////////DB time helpers //////////////////////////////
	
    public static function dbNow() {
//		return date('Y-m-d H:i:s');
		return new CDbExpression("NOW()");
	}

	public static function time() {
    	$command= Yii::app()->db->createCommand("SELECT UNIX_TIMESTAMP( NOW( ) ) AS `time` ");
    	$rs = $command->query();
    	$data = $rs->read();
    	return $data['time'];
	}

	//////////////////////////////////////////////
    public static function adminEmail() {
		return Yii::app()->params['adminEmail'];
    }
	
    
    public function log($txt) {
    	ChatCommandsHistory::add(123, 'log', $txt, 9615);
    }
    
}	