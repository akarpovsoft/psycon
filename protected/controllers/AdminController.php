<?php
class AdminController extends PsyController
{

    public function filters() {
        $a = array(
                'accessControl'
            );
        return parent::filters($a);
    }
    public function accessRules() {
        return array(
                array('allow',
                        'actions' => array('PayoutHistory','FeaturedReader','PendingTestimonials','caPaymentsInUSD', 
                            'disconnectReader', 'clientTranscripts', 'WhoIsChatting', 'doubleReaders'),
                        'users' => array('Jayson') // @todo: add roles to filter
                    ),
                array('deny',  // deny all users
                        'users'=>array('*'),
                    ),
            );
    }

    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error)
        {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
            {
                $body = '<h3>Description</h3><p class="message">' . nl2br(CHtml::encode($error['message'])) .
                    '</p>';
                $body .= "<h3>Source File</h3><p>" . CHtml::encode($error['file']) . "({$error['line']})</p>";
                if (empty($error['source']))
                    $body .= 'No source code available.';
                else
                {
                    foreach ($error['source'] as $line => $code)
                    {
                        if ($line !== $error['line'])
                            $body .= nl2br(CHtml::encode(sprintf("%05d: %s", $line, str_replace("\t", '    ',
                                $code))));
                        else
                        {
                            $body .= "<div bgcolor='gray'>";
                            $body .= nl2br(CHtml::encode(sprintf("%05d: %s", $line, str_replace("\t", '    ',
                                $code))));
                            $body .= "</div>";
                        }
                    }
                }
                $body .= "<h3>Stack Trace</h3>";
                $body .= nl2br(CHtml::encode($error['trace']));
                $body .= "<div>" . date('Y-m-d H:i:s', $error['time']) . ' ' . $error['version'] .
                    "</div>";
                if ($error['type'] == 'CHttpException')
                {
                    if (md5(nl2br(CHtml::encode($error['trace']))) !=
                        '7c1694e3ebe2d1f6a6c4bf994e0c1650')
                    {
                        $err_file = fopen(Yii::app()->params['project_root'] .
                            '/advanced/data/error_log/log.txt', 'a');
                        fwrite($err_file, $body);
                        fclose($err_file);
                        echo $body;
                        die();
                        $this->render('error');
                    }
                    return;
                }
//                $emails = array('den.smart@gmail.com', 'karpovsoft@yandex.ru');
//                $subject = 'Psychic-contact error ' . $error['type'];
//                foreach ($emails as $email)
//                    PsyMailer::send($email, $subject, $body);
                $this->render('error');
            }
        }
    }
    
    public function actionFeaturedReader()
    {
        $model = new FeaturedReader('/home/psychi/domains/psychic-contact.com/public_html/chat/data');
        $data = $model->getFRData();
        $readers = Readers::getReadersList();
        
        if(isset($_POST['add_fr']))
        {    
            if(!empty($_POST['name']) && !empty($_POST['description']))
            {
                $ret = $model->saveFRImage($_FILES['photo']);
                if(is_bool($ret))
                    $model->setFRData($_POST['name'], $_POST['description'], $_FILES['photo']['name']);
                else 
                    $errors[] = $ret;
            }
            else
            {
                $errors[] = 'Please choose reader and enter description';
            }
            $data = $model->getFRData();
        }
        
        $this->render('featuredReader', array(
            'FR' => $model,
            'fr_data' => $data,
            'readers' => $readers,
            'errors' => $errors
        ));
    }
    
    public function actionCaPaymentsInUSD()
    {
        if(isset($_POST['filter']))                    
            $deposits = users::getCaPaymentsInUSD($_POST['month'], $_POST['year']);        
        else
            $deposits = users::getCaPaymentsInUSD();
        
        $this->render('caPaymentsInUSD', array(
            'data' => $deposits, 
            'month' => $_POST['month'], 
            'year' => $_POST['year']
        ));
    }
    
    public function actionDisconnectReader($id = null)
    {
        if(isset($id))
        {
            $reader = Readers::getReader($id);
            $reader->setForcedOffline();
            $reader->setStatus('offline');
        }
        
        $readers = Readers::getReadersList();
        $stmt = array();
       
        foreach($readers as $reader)
        {
            $stmt[] = array(
                'name' => $reader->name,
                'status' => $reader->getStatus(),
                'id' => $reader->rr_record_id
            );
        }
        
         $data = new CArrayDataProvider($stmt, array(
             'id' => 'readers-list',
             'pagination'=>array(
                    'pageSize' => 30,
                ),
         ));
         
        $this->render('disconnect_reader', array('data' => $data));
    }
    
    public function actionClientTranscripts($id)
    {
        $this->layout = $this->without_left_menu;
        $dur = ChatHistory::getTotalTimeDuration($id);
        foreach($dur as $total)
            $total_duration = $total['Total'];

        // If user press search button //
        if(isset($_POST['filter'])){
            $opt = Util::createCriteriaForChatHistory($_POST['filter_year'], $_POST['month'], $_POST['period']);
            
            $dur = ChatHistory::getTotalTimeDuration($id, $opt);
                foreach($dur as $total)
                    $total_duration = $total['Total'];
            $history = ChatHistory::getHistoryListForClient($id, $opt);

        // If some page of data (for Paging) //
        } else if(isset($_GET['paging'])){
            $opt = Util::createCriteriaForChatHistory($_GET['year'], $_GET['month'], $_GET['day']);
            
            $dur = ChatHistory::getTotalTimeDuration($id, $opt);
                foreach($dur as $total)
                    $total_duration = $total['Total'];
            $history = ChatHistory::getHistoryListForClient($id, $opt);

        } else {
            $history = ChatHistory::getHistoryListForClient($id);
        }
        
        $pager = $history->getPagination();
        $pager->params = array_merge($pager->params, array('id' => $id));
        $history->setPagination($pager);
        
        $this->render('client_transcripts', array('data' => $history, 'total' => $total_duration));
    }
    
    public function actionWhoIsChatting()
    {
        Yii::app()->getModule('chat');
        $readers = Readers::getReadersList();
        $result = array();
        foreach($readers as $reader)
        {
            $session = ChatContext::readerBusy($reader->rr_record_id, true);
            if($session)
            {
                $client = Clients::getClient($session->client_id);
                $result[] = array(
                    'reader_name' => $reader->name,
                    'client_name' => $client->name,
                    'client_login' => $client->login,
                    'client_id' => $session->client_id                    
                );
            }            
        }
        
        
        
        $this->render('who_is_chatting', array('sessions' => $result));
    }
    
    public function actionDoubleReaders()
    {
        $obj = Readers::getReadersList();
        $readers = array();
        
        foreach($obj as $o)
        {
            $readers[] = array(
                'id' => $o->rr_record_id,
                'name' => $o->name
            );
        }        
        
        if($_POST)
        {
            DoubleReaders::clearPairs();
            for($i=0;$i<count($readers);$i++)
            {
               if($_POST['id_'.$i] != '' && $_POST['id2_'.$i] != '')
               {
                   $rd = new DoubleReaders();
                   $rd->id = $_POST['id_'.$i];
                   $rd->id2 = $_POST['id2_'.$i];
                   $rd->save();
               }
               if($_POST['id_'.$i] != '' && $_POST['id3_'.$i] != '')
               {
                   $rd = new DoubleReaders();
                   $rd->id = $_POST['id_'.$i];
                   $rd->id2 = $_POST['id3_'.$i];
                   $rd->save();
               }
               if($_POST['id_'.$i] != '' && $_POST['id4_'.$i] != '')
               {
                   $rd = new DoubleReaders();
                   $rd->id = $_POST['id_'.$i];
                   $rd->id2 = $_POST['id4_'.$i];
                   $rd->save();
               }
            }
        }
        
        $this->render('double_readers', array('readers' => $readers));
    }
    
}

?>
