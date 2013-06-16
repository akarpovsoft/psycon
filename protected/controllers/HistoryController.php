<?php

class HistoryController extends PsyController
{
    public function filters($params = null) {
        $a = array(
                'accessControl',
        );
        return parent::filters($a);
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
                array('allow', // allow authenticated user to perform 'create' and 'update' actions
                        'actions'=>array('index', 'client', 'reader', 'openLog', 'sessionInfo', 'addTime', 'del', 'download'),
                        'users'=>array('@'),
                ),
                array('deny',  // deny all users
                        'users'=>array('*'),
                ),
        );
    }
    
    public function actionIndex()
    {
        if(Yii::app()->user->type == 'reader')
            $this->redirect('history/reader');
        else
            $this->redirect('history/client');
    }
    
    public function actionClient(){
        $this->layout = $this->without_left_menu;
        $dur = ChatHistory::getTotalTimeDuration(Yii::app()->user->id);
        foreach($dur as $total)
            $total_duration = $total['Total'];

        // If user press search button //
        if(isset($_POST['filter'])){
            $opt = Util::createCriteriaForChatHistory($_POST['filter_year'], $_POST['month'], $_POST['period']);
            
            $dur = ChatHistory::getTotalTimeDuration(Yii::app()->user->id, $opt);
                foreach($dur as $total)
                    $total_duration = $total['Total'];
            $history = ChatHistory::getHistoryListForClient(Yii::app()->user->id, $opt);

        // If some page of data (for Paging) //
        } else if(isset($_GET['paging'])){
            $opt = Util::createCriteriaForChatHistory($_GET['year'], $_GET['month'], $_GET['day']);
            
            $dur = ChatHistory::getTotalTimeDuration(Yii::app()->user->id, $opt);
                foreach($dur as $total)
                    $total_duration = $total['Total'];
            $history = ChatHistory::getHistoryListForClient(Yii::app()->user->id, $opt);

        } else {
            $history = ChatHistory::getHistoryListForClient(Yii::app()->user->id);
        }
        $this->render('client', array('data' => $history, 'total' => $total_duration));
    }

    public function actionReader(){
            $this->layout = $this->without_left_menu;
            $dur = ChatHistory::getReaderTotalTimeDuration(Yii::app()->user->id);
            foreach($dur as $total)
                $total_duration = $total['Total'];

            if(isset($_POST['filter'])){
                $opt = Util::createCriteriaForChatHistory($_POST['filter_year'], $_POST['month'], $_POST['period']);

                $dur = ChatHistory::getReaderTotalTimeDuration(Yii::app()->user->id, $opt);
                    foreach($dur as $total)
                        $total_duration = $total['Total'];
                $history = ChatHistory::getHistoryListForReader(Yii::app()->user->id, $opt);

            } else if(isset($_GET['paging'])){
                $opt = Util::createCriteriaForChatHistory($_GET['year'], $_GET['month'], $_GET['day']);

                $dur = ChatHistory::getTotalTimeDuration(Yii::app()->user->id, $opt);
                    foreach($dur as $total)
                        $total_duration = $total['Total'];
                $history = ChatHistory::getHistoryListForReader(Yii::app()->user->id, $opt);
            } else {
                $history = ChatHistory::getHistoryListForReader(Yii::app()->user->id);
            }
        $this->render('reader', array('data' => $history, 'total' => $total_duration));
    }

    public function actionOpenLog($session_id, $reader_id){
        
        $history = ChatHistory::getSession($session_id);
        $transcript = $history->getTranscript();

        if(empty($transcript))
            $transcript = ChatTranscripts::getTranscript($session_id, $reader_id);
        
        $this->renderPartial('transcripts', array('log' => $transcript));
    }
    
    public function actionSessionInfo()
    {
        $info = DebugExtra::getSessionInfo($_GET['session_id']);

        $client = Clients::getClient($info['client_id'], 'credit_cards');
        $reader = Readers::getReader($info['reader_id']);
        switch($info['chat_type'])
        {
            case 'NULL': 
                $chattype = 'default, by admin control';
                break;
            case 1 : 
                $chattype = 'new chat(debug)';
                break;
            case 2 : 
                $chattype = 'old chat(jchat)';
                break;
            case 3 : 
                $chattype = 'emergency_debug';
                break;
            case 4 : 
                $chattype = 'emergency';
                break;
            case 5 : 
                $chattype = 'debug 2';
                break;
            case 7 : 
                $chattype = 'new chat (advanced)';
                break;
        }
        $paid_time = floor($info['Paid_time']);
        $all_time = floatval($info['Paid_time']);
        
        $this->render('session_info', array(
            'info' => $info, 
            'client_name' => $client->credit_cards->firstname, 
            'reader_name' => $reader->name,
            'chat_type' => $chattype,
            'paid_time' => $paid_time,
            'all_time' => $all_time,
        ));
    }
    
    public function actionAddTime()
    {
        $info = DebugExtra::getSessionInfo($_POST['session_id']);
        
        if(isset($_POST['add_time']) && $_POST['time'] != 0)
        {
            //$client = Clients::getClient($info['client_id']);
            //$reader = Readers::getReader($info['reader_id']); 
            
            //Compute reader balance before reducing the time
            $RTS = new ReturnTimeStat();
            $RTS->reader_id = $info['reader_id'];
            $RTS->client_id = $info['client_id'];
            $RTS->session_id = $_POST['session_id'];
            $RTS->value = $_POST['time'];
            $RTS->saveRetTimeStat();
            unset($RTS);
            
            $RTS = ReturnTimeStat::getRTSBySession($_POST['session_id']);
            $RTS->saveBeforeReaderBalance();
            $RTS->saveAfterReaderBalance();
            
            $RT = new ReturnTime($info, $_POST['time']);
            
            //Check if the session in current period or out of it. Ticket (ADD TIME BACK FUNCTION)
            if($RT->ifSessionOld())
            {
                $period_start = (date("d") > 15)? strtotime(date('Y').'-'.date('m').'-15') : strtotime(date('Y').'-'.date('m').'-01');
                
                //If there is no session with such time return an error
                if(!$RT->reduceCurrentSession()) {
                    $this->render('session_info', array('error' => 1));
                    return;
                }
            }
            else
            {
                $RT->changeHistory();
                $RT->reduceReaderPayment();
                $RT->changeReaderBalance();
                $RT->changeClientBalance(); 
                $RT->notifyUsers();
            }
        }
        if(isset($_POST['add_free_time']) && !empty($_POST['free_time'])) {
            $RT = new ReturnTime($info, intval($_POST['free_time']));
           
            $RT->changeFreeTime();
            $RT->changeClientBalance();
            $RT->notifyUsers('free');
        }
        
        $this->redirect(Yii::app()->params['http_addr'].'users/sessionInfo?session_id='.$_POST['session_id']);
    }

    /*
     * AJAX action for delete chat logs
     */
    public function actionDel(){
        $a = new AjaxResponseJSON();
        ChatHistory::deleteChatHistory($_GET['session_id']);
        $a->send('ok');
    }
    
    public function actionDownload()
    {
        $history = new ChatHistory();
        $log = $history->getFullLog(Yii::app()->user->id);
        
        $path = Yii::app()->params['project_root'].'/data/chat_logs/';
        
        $file = fopen($path.Yii::app()->user->id.'.txt', 'w+');
        fwrite($file, $log);
        fclose($file);
        
        $filename = 'chat_log.txt';
        $filesize = filesize($path.Yii::app()->user->id.'.txt');
        $filetype = 'text/plain';
        $localname = $path.Yii::app()->user->id.'.txt';

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Type: ".$filetype);
        header("Content-Range: bytes ".$filesize);
        $header='Content-Disposition: attachment; filename="'.$filename.'";';
        header($header);
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".$filesize);
        readfile($localname);
        die();
    }
}
?>
