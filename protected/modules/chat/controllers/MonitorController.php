<?php

class MonitorController extends CController
{
    
    public function filters()
    {
        return array( 
            'checkLogged + index, logout, checknewclient, statuschange, startchatting'
        );
    }
    
    public function filterCheckLogged($filterChain)
    {
        if(Yii::app()->user->isGuest || Yii::app()->user->type != 'reader')
                $this->redirect(Yii::app()->params['http_addr'].'chat/monitor/error?id=1');
        $filterChain->run();
    }

    public function actionPre()
    {
    	ReaderOnlineStatistics::login(Yii::app()->user->id);
    	$this->redirect("/advanced/chat/monitor");
    }
    
    public function actionIndex()
    {
        $page_type = 'online';

        $expert = ChatHelper::getExpert(Yii::app()->user->id);        
        
        // Reader break checking
        $mins = $expert->minutesOnBreak();
        if(isset($_GET['force_break'])) {
            $mins = 10;
            $expert->setStatus('break10');
        }
            
         
        if($expert->isBusy()) {
            $page_type = 'busy';
        }
        //// 
        if($mins) {
            $page_type = 'break';
        }
        
        $activeClient = FirstInQueue::getActiveClient();
        
        $setOnline = (!isset($_GET['rld']) && !isset($_GET['force_break'])); // first load and not set break
        if($setOnline){
        	$expert->setOnline();
                $expert->comeBack();
            $page_type = 'online';
        }
        
    	ReaderOnlineStatistics::refreshLogin(Yii::app()->user->id);
        
        //Force offline command check
        // Only if need to reload            
        if($expert->isForcedOffline() && isset($_GET['rld']))
        {
            $this->actionLogout();
            return;
        }
        
        // If reader AFK and do not response more than 2 times
        if($expert->isAway() && isset($_GET['rld']))
        {
            $this->actionLogout('away');
            return;
        }
        
        $this->renderPartial('index', array(
            'reader_id' => Yii::app()->user->id,
            'active' => $activeClient, // $active->id
            'type' => $page_type,
            'mins' => $mins,
            'reload' => $_GET['rld']
        ));
    }
    
    public function actionLogout($type = null){
        $expert = ChatHelper::getExpert(Yii::app()->user->id);
        //send logoff email for admin
        $subject = "PSYCHAT - LOG OFF: ".$expert->getName();
        $text = $expert->getLogin()." Logged Off at ".date("Y-m-d H:i:00");
        PsyMailer::send(ChatHelper::adminEmail(), $subject, $text);
        PsyMailer::send($expert->getEmail(), $subject, $text);
                
        // end sending email
        $expert->setStatus('offline');
        // sets forced offline status
        $expert->setForcedOffline();

        $this->renderPartial('monitor_logout', array(
            'type' => $type
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

        $expert = ChatHelper::getExpert(Yii::app()->user->id);
        
	    $time = new OnlineTime;
        $time->reader_id= Yii::app()->user->id;
        $time->SaveTimeAll();

        $a = array();

        $check = ChatRequests::checkNewClient(Yii::app()->user->id, $_POST['client']);
        $readerStatus = $expert->getStatus();
        if($readerStatus == 'busy' && $expert->status == 'break10')
            $readerStatus = 'break10';
        if(($check != false)&&($check != 'continue')){
            $a['update'] = 'update';
            $a['area'] = 'newClient';
        }
        else
            $a['update'] = 'continue';

        if($readerStatus != $_POST['current_status']) {// check for status change
            $a['update'] = 'update';
            $a['area'] = 'newStatus';
        }
        else
        {
	        $expert->setStatus($_POST['current_status']);
        }
        $a['status11'] = $readerStatus;
        
        if(!$expert->isBusy() && !$expert->isOnBreak()) {
        	$expert->setOnline();
        }
    	ReaderOnlineStatistics::refreshLogin(Yii::app()->user->id);
        
        echo json_encode($a);
    }
    
    public function actionReaderStatusChange(){
        $expert = ChatHelper::getExpert(Yii::app()->user->id);
        $expert->setStatus($_POST['status']);
    }
    
    public function actionStartChatting(){
    	/// get client chat request
    	$request = ChatRequests::getChatRequest($_GET['request_id']);
    	if($request->getExpertId() != Yii::app()->user->id) {
    		die('Hacking attempt');
    	}
        $reader = Readers::getReader($request->getExpertId());
        $reader->comeBack();

        $sessionKey = ChatContext::startChat($request);
        $this->redirect(ChatHelper::moduleUrl()."expert?sessionKey=$sessionKey");
    }
    
    public function actionError($id)
    {
        switch($id) 
        {
            case 1: 
                $err = 'Please login as reader on the main site';
                break;
            default:
                $err = 'Some error occured';
        }        
        $this->renderPartial('error', array('error' => $err));
    }
}
?>
