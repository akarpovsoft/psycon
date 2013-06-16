<?php

class MessagesController extends PsyController {
    const PAGE_SIZE=50;
    /**
     * @return array action filters
     */
    public function filters($params = null) {
        $a = array(
                'accessControl', // perform access control for CRUD operations
                'readerAccess + create',
                'readersConstClients + messageToConstClient'
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
                array('allow', // allow authenticated user to perform actions
                        'actions'=>array('index','view','create','reply','download','messageToConstClient','successSend'),
                        'users'=>array('@'),
                ),
                array('allow', // allow authenticated user to perform actions
                        'actions'=>array('error'),
                        'users'=>array('*'),
                ),
                array('deny',  // deny all users
                        'users'=>array('*'),
                ),
        );
    }
    public function filterReaderAccess($filterChain){
        if(Yii::app()->user->type == 'reader')
                $this->redirect('error');
        $filterChain->run();
    }

    /*
     * If requested client is not in current reader's constant clients list or not Admin - redirect to error page
     *
     */
    public function filterReadersConstClients($filterChain){
        if($_GET['client_id'] != 'admin'){
            $check = ChatHistory::checkClientAccessorryToReader($_GET['client_id'], Yii::app()->user->id);
            if(!$check)
                $this->redirect('error');
        }
        $filterChain->run();
    }
    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new Messages();
        $client = Clients::getClient(Yii::app()->user->id);

        $data = $model->getMessagesForClient(Yii::app()->user->id);
        if(isset($_POST['del_checked'])) {
            if(isset($_REQUEST['messages'])) {
                $model->delCheckedMessages($_REQUEST['messages']);
            }
        }
		
        $this->render('index', array('data' => $data, 'client' => $client));
    }

    public function actionError(){
        $this->render('error');
    }
    /**
     * Displays a particular model.
     */
    public function actionView() {
        $message_id = $_GET['id'];

        $client = Clients::getClient(Yii::app()->user->id);
        $message = Messages::getMessage($message_id);
        $message->Read_message = 'yes';
        $message->update();
        
        // check personal ban
        $checkUserBan = UserBan::checkUserBan(Yii::app()->user->id, $message->From_user);
        if(!empty($checkUserBan))
            $banned = 1;
        
        if(isset($_POST['del_message'])) {
            $model = new Messages();
            $data = $model->getMessagesForClient(Yii::app()->user->id);
            $message->delete();
            $this->redirect('index', array('data' => $data, 'client' => $client));
        } else
            $this->render('view', array('client' => $client, 'message' => $message, 'download' => $message->attachment, 'banned' => $banned));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $message=new Messages;
        $client = Clients::getClient(Yii::app()->user->id, 'credit_cards');
        $readers_list = Readers::getReadersList();
        // check bans
        $readers = array();
        foreach($readers_list as $reader)
        {
            $checkUserBan = UserBan::checkUserBan(Yii::app()->user->id, $reader->rr_record_id);
            if(empty($checkUserBan))
                $readers[] = $reader;
        }

        if(isset($_POST['send'])) {
            $message->user_type = Yii::app()->user->type;
            $message->From_user = $_POST['from_user'];
            $message->From_name = $_POST['from_name'];
            $message->To_user = $_POST['to'];
            $message->Subject = $_POST['subject'];
            $message->Body = $_POST['text'];
/*            
            foreach($_POST['Messages'] as $key=>$value){
                $message->$key = $value;
            }
*/            
            $message->attach = CUploadedFile::getInstance($message, 'attach');
            
            if(!is_null($message->attach)){
                $message->attachment = 'yes';
                $sql = 'SELECT MAX( `ID` ) AS `max` FROM  `messages` WHERE 1';
                $connection=Yii::app()->db;
                $command=$connection->createCommand($sql);
                $res = $command->query();
                foreach($res as $r)
                    $file_id = $r['max'];
                $message->path2file = $_SERVER['DOCUMENT_ROOT'].'/chat/email_files/'.$file_id;
                $file_name = $message->attach->getName();
                $file_type = $message->attach->getType();
                $file_size = $message->attach->getsize();
                $message->parameters = $file_name.",".$file_type.",".$file_size.",".$message->path2file;
            }            
            else
                $message->attachment = 'no';

            if(!$message->validate()) {
                $err = $message->getErrors();
                $this->render('create',array('message'=>$message,
                                             'client' => $client,
                                             'readers' => $readers_list,
                                             'errors' => $err
                                       ));
                return;
            } else {
                $message->send();
                $this->redirect('successSend');
            }
        }
        $this->render('create',array('message'=>$message,
                      'client' => $client,
                      'readers' => $readers));

    }

    public function actionReply() {
        $message_id = $_GET['id'];
        
        $message = Messages::getMessage($message_id);
        $sender_id = $message->To_user;
        $sender = users::model()->with('credit_cards')->findByPk($sender_id);
        $receiver = users::model()->with('credit_cards')->findByPk($message->From_user);
        // check personal ban
        $checkUserBan = UserBan::checkUserBan($sender_id, $receiver->rr_record_id);
        if(!empty($checkUserBan))
            $this->redirect(Yii::app()->params['http_addr'].'messages/view?id='.$message_id);
        
        $new_message = new Messages();

        if($receiver->type == 'client')
            $receiver_name = $receiver->credit_cards->firstname;
        else
            $receiver_name = $receiver->name;
        if($sender->type == 'client')
            $new_message->From_name = $sender->credit_cards->firstname;
        else
            $new_message->From_name = $sender->name;

        $new_message->Subject = 'Re:'.$message->Subject;
        $new_message->Body = "\n\n-------------------------------------------\n".stripslashes($message->Body);
        if(isset($_POST['send'])) {            
            $new_message->From_user = $sender_id;
            $new_message->To_user = $receiver->rr_record_id;
            $new_message->Subject = $_POST['subject'];
            $new_message->Body = $_POST['text'];
            $new_message->user_type = $sender->type;
/*            
            foreach($_POST['Messages'] as $key=>$value){
                $new_message->$key = $value;
            }
*/            
            $new_message->attach = CUploadedFile::getInstance($new_message, 'attach');
            if(!is_null($new_message->attach)){
                $new_message->attachment = 'yes';
                $sql = 'SELECT MAX( `ID` ) AS `max` FROM  `messages` WHERE 1';
                $connection=Yii::app()->db;
                $command=$connection->createCommand($sql);
                $res = $command->query();
                foreach($res as $r)
                    $file_id = $r['max'];
                $new_message->path2file = $_SERVER['DOCUMENT_ROOT'].'/chat/email_files/'.$file_id;                
                $file_name = $new_message->attach->getName();
                $file_type = $new_message->attach->getType();
                $file_size = $new_message->attach->getsize();
                $new_message->parameters = $file_name.",".$file_type.",".$file_size.",".$new_message->path2file;
            }
            else
                $new_message->attachment = 'no';

            if(!$new_message->validate()) {
                $err = $new_message->getErrors();
                $this->render('reply',array(
                                            'message'=> $new_message,
                                            'receiver_name' => $receiver_name,
                                            'receiver_login' => $receiver->login,
                                            'sender' => $sender,
                                            'errors' => $err
                                      ));
                return;
            } else {
                $new_message->send();
                // Notify client about new message
                if($receiver->type == 'client')
                {
                    $subject = "PSYCHAT - You have received a message";
                    $body = "Hello ".$receiver->name.", 
                    You have received a letter in your message center at ".Yii::app()->params['http_addr']." 
                    From: ".$sender->name."<br> 
                    --------- <br>
                    ".$message->Body."<br> 
                    Visit Our Chat Site: (".Yii::app()->params['http_addr'].") 
                    Free Tarot Readings (www.taroflash.com) 
                    Contact Cust. Support (".Yii::app()->params['adminEmail'].")";
                    
                    PsyMailer::send($receiver->emailaddress, $subject, $body);
                }
                
                $model = new Messages();
                $data = $model->getMessagesForClient($sender_id);
                $this->redirect('index', array(
                                'client' => $sender,
                                'data' => $data));
            }
        }
        $this->render('reply',array(
                      'message'=> $new_message,
                      'receiver_name' => $receiver_name,
                      'receiver_login' => $receiver->login,
                      'sender' => $sender
        ));
    }
    
    // Download atteched file
    public function actionDownload() {
        $mess_id = $_GET['id'];
        $message = Messages::getMessage($mess_id);
        $temp = explode(',', $message->parameters);

        $filename = $temp[0];
        $filesize = $temp[2];
        $filetype = $temp[1];
        $localname = $temp[3];
        
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
    /*
     * This action give reader ability to send messages for constant clients
     */
    public function actionMessageToConstClient($client_id, $sma = null){
        if($client_id == 'admin') $client_id = 1;
        $sender = Readers::getReader(Yii::app()->user->id, 'credit_cards'); // Reader
        $receiver = Clients::getClient($client_id, 'credit_cards'); // to Client
        $message=new Messages;
        if(isset($sma))
            $message->Subject = 'sma';
        if(isset($_POST['send'])) {
            $message->user_type = Yii::app()->user->type;
            $message->From_user = $_POST['from_user'];
            $message->From_name = $_POST['from_name'];
            $message->To_user = $_POST['to'];
            $message->Subject = $_POST['subject'];
            $message->Body = $_POST['text'];
/*            
            foreach($_POST['Messages'] as $key=>$value){
                $message->$key = $value;
            }
*/
            $message->attach = CUploadedFile::getInstance($message, 'attach');

            if(!is_null($message->attach)){
                $message->attachment = 'yes';
                $sql = 'SELECT MAX( `ID` ) AS `max` FROM  `messages` WHERE 1';
                $connection=Yii::app()->db;
                $command=$connection->createCommand($sql);
                $res = $command->query();
                foreach($res as $r)
                    $file_id = $r['max'];
                $message->path2file = $_SERVER['DOCUMENT_ROOT'].'/chat/email_files/'.$file_id;
                $file_name = $message->attach->getName();
                $file_type = $message->attach->getType();
                $file_size = $message->attach->getsize();
                $message->parameters = $file_name.",".$file_type.",".$file_size.",".$message->path2file;
            }
            else
                $message->attachment = 'no';

            if(!$message->validate()) {
                $err = $message->getErrors();
                $this->render('const_client', array(
                              'message' => $message,
                              'receiver' => $receiver,
                              'sender' => $sender,
                              'errors' => $err
                ));
            } else {
                $message->send();
                // Client notice about new message
                $subject = "PSYCHAT - You have received a message";
                $body = "Hello ".$receiver->name.", 
                    You have received a letter in your message center at ".Yii::app()->params['http_addr']." 
                    From: ".$sender->name."<br> 
                    --------- <br>
                    ".$message->Body."<br> 
                    Visit Our Chat Site: (".Yii::app()->params['http_addr'].") 
                    Free Tarot Readings (www.taroflash.com) 
                    Contact Cust. Support (".Yii::app()->params['adminEmail'].")";
                
                PsyMailer::send($receiver->emailaddress, $subject, $body);
                
                $this->render('const_client', array(
                              'message' => $message,
                              'receiver' => $receiver,
                              'sender' => $sender,
                              'success' => 1
                ));
            }
        }
        $this->render('const_client', array(
            'message' => $message,
            'receiver' => $receiver,
            'sender' => $sender,
        ));
    }
    
    public function actionSuccessSend()
    {
        $this->render('success_send');
    }
}
