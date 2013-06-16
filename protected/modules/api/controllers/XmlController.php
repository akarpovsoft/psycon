<?php

class XmlController extends Controller
{
    
    public function actions()
    {
        return array( // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array('class' => 'CCaptchaAction', 'backColor' => 0xFFFFFF, ),
        );
    }
    
    public function filters($params = null) {
        return array(
                'postControl + signup, sendEmailReading',
        );        
    }
    
    public function filterPostControl($filterChain)
    {
        if(!$_POST)
            $this->layout = '404';
        else
            $this->layout = 'zero';
        $filterChain->run();
    }
    
    public function actionShowReaders()
    {
        $group_id = $_GET['gr'];
        if (!isset($group_id))
        {
            $group_id = 1;
        }
        $category = $_GET['cat'];
        $online_only = $_GET['online'];
        $main_only = $_GET['fmr'];
        $keyword = $_GET['keyword'];
        
        $readerList =  ReadersOnline::availableReaders($online_only, $category, $main_only, $group_id, $keyword);
        $dom = new DOMDocument('1.0', 'utf-8');
        $root = $dom->createElement('readers');
        if ($readerList)
        {
            foreach ($readerList as $reader)
            {
                $readerDom = $dom->createElement('reader');
                $root->appendChild($readerDom);
                $readerDom->setAttribute('id', $reader['rr_record_id']);
                $readerDom->setAttribute('name', $reader['name']);
                $readerDom->setAttribute('status', $reader['status']);
                $readerDom->setAttribute('photo', $reader['operator_location']);
                $readerDom->setAttribute('wp_enabled', $reader['webphone_enabled']);
                $readerDom->setAttribute('wp_id', $reader['webphone_id']);
                $text = $dom->createElement('area');
                $cont = $dom->createCDATASection($reader['area']);
                $text->appendChild($cont);
                $readerDom->appendChild($text);
            }
        }
        $dom->appendChild($root);
        $this->renderPartial('goXML', array('dom' => $dom));
        //end xml
        die();
    }

    public function actionView()
    {   
        if (isset($_GET['id']))
        {  
            $reader = Readers::getReaderByName($_GET['id']);
            if(!$reader)                
                $reader = Readers::getReader($_GET['id']);
            
            $dom = new DOMDocument('1.0', 'utf-8');
            if ($reader)
            {
                $reader->comments = ereg_replace("\r", "", $reader->comments);
                $reader->comments = ereg_replace("\n", "<BR>", $reader->comments);
                $root = $dom->createElement('reader');
                $root->setAttribute('id', $reader->rr_record_id);
                $root->setAttribute('name', $reader->name);
                $root->setAttribute('status', $reader->getStatus());
                $root->setAttribute('img', $reader->operator_location);
                $text0 = $dom->createElement('area');
                $cont0 = $dom->createCDATASection($reader->area);
                $text0->appendChild($cont0);
                $root->appendChild($text0);
                $text = $dom->createElement('comments');
                $cont = $dom->createCDATASection(iconv("cp1251", "UTF-8", $reader->comments));
                $text->appendChild($cont);
                $root->appendChild($text);
                $text2 = $dom->createElement('testimonials');
                $cont2 = $dom->createCDATASection(iconv("cp1251", "UTF-8", $reader->
                    testimonials));
                $text2->appendChild($cont2);
                $root->appendChild($text2);
                $dom->appendChild($root);
            }
            $this->renderPartial('goXML', array('dom' => $dom));
            //end xml
            die();
        }
    }

    public function actionEmailReading()
    {

        $tariff['reader_spec'] = Tariff::loadEmailReadingPriceList(); // List of Reader and Special price
        $tariff['question'] = Tariff::loadEmailReadingTariffsList(); // List of question prices (1,2,3 etc...)


        $dom = new DOMDocument('1.0', 'utf-8');
        $root = $dom->createElement('tariffs');

        if ($tariff['reader_spec'])
        {
            foreach ($tariff['reader_spec'] as $tariffReader)
            {
                $readerDom = $dom->createElement('tariff_reader_spec');
                $root->appendChild($readerDom);
                $readerDom->setAttribute('rr_record_id', $tariffReader['rr_record_id']);
                $readerDom->setAttribute('name', $tariffReader['name']);
                $readerDom->setAttribute('special', $tariffReader['special']);
            }
        }
        if ($tariff['question'])
        {
            foreach ($tariff['question'] as $key => $val)
            {
                $readerDom2 = $dom->createElement('tariff_question');
                $root->appendChild($readerDom2);
                $readerDom2->setAttribute('key', $key);
                $readerDom2->setAttribute('title', $val['title']);
                $readerDom2->setAttribute('price', $val['price']);
            }
        }
        $dom->appendChild($root);
        $this->renderPartial('goXML', array('dom' => $dom));
        //end xml
        die();
    }

    public function actionChat()
    {
        // stevel id = 9615
        //$client_id = $_GET['i'];
        if (isset($_GET['i']))
        {
            $client_id = $_GET['i'];
        } else
        {
            return;
        }
        Yii::app()->user->id = $client_id;

        $client = Clients::getClient($client_id);

        if (isset($_GET['gr']))
            $group_id = $_GET['gr'];
        else
            $group_id = 1; // Default group

        // Check if main group reader is online - show only this reader
        // If main group reader is online - select it in list and disable message of favourite reader
        /// @todo: this doesn't work if main reader is set
        $main_group_reader = ReadersVisibility::getMainGroupReader($group_id);

        if (!empty($main_group_reader))
            $main_online = Readers::getReaderOnline($main_group_reader);

        if (!empty($main_online))
            $main_reader = Readers::getReader($main_group_reader);

        ///var_export($main_reader['rr_record_id']);
        // All another readers of current groups
        $readers_online = ReadersOnline::availableReaders('for_chat', false, false, $group_id);
        $aval = ReadersOnline::availableReaders('for_chat', false, false, $group_id);

        if (is_null($readers_online))
        {
            $dom = new DOMDocument('1.0', 'utf-8');
            $root = $dom->createElement('chat');
            $readerDom = $dom->createElement('error');
            $root->appendChild($readerDom);
            $readerDom->setAttribute('because', 'no_one_readers_online');

            $readerDom = $dom->createElement('client');
            $root->appendChild($readerDom);
            $readerDom->setAttribute('rr_record_id', $client->rr_record_id);
            $readerDom->setAttribute('balance', $client->balance);
            $readerDom->setAttribute('name', $client->name);

            $dom->appendChild($root);
            $this->renderPartial('goXML', array('dom' => $dom));
            die();
        }

        $favorite_reader = Clients::getFavoriteReader($client_id);

        if (isset($_GET['chat_with']))
            $chat_with = Readers::getReader($_GET['chat_with']);

        foreach ($aval as $reader)
        {
            if ($reader['rr_record_id'] == $favorite_reader)
                $avaliable = $reader['name'];
        }


        $dom = new DOMDocument('1.0', 'utf-8');
        $root = $dom->createElement('chat');
        if ($client)
        {
            $readerDom = $dom->createElement('client');
            $root->appendChild($readerDom);
            $readerDom->setAttribute('rr_record_id', $client->rr_record_id);
            $readerDom->setAttribute('balance', $client->balance);
            $readerDom->setAttribute('name', $client->name);
            if ($avaliable)
            {
                $readerDom->setAttribute('favorite', $avaliable);
            } else
            {
                $readerDom->setAttribute('favorite', 'no');
            }

        }
        if ($main_reader)
        {
            $readerDom = $dom->createElement('main_reader');
            $root->appendChild($readerDom);
            $readerDom->setAttribute('rr_record_id', $main_reader->rr_record_id);
            $readerDom->setAttribute('name', $main_reader->name);
        } else
        {
            $readerDom = $dom->createElement('main_reader');
            $root->appendChild($readerDom);
            $readerDom->setAttribute('rr_record_id', '0');
            $readerDom->setAttribute('name', '0');
        }
        foreach ($readers_online as $reader)
        {
            $readerDom = $dom->createElement('reader');
            $root->appendChild($readerDom);
            $readerDom->setAttribute('rr_record_id', $reader['rr_record_id']);
            $readerDom->setAttribute('name', $reader['name']);
            if ($reader['rr_record_id'] == $chat_with['rr_record_id'])
            {
                $readerDom->setAttribute('select', 'yes');
            } else
            {
                if ($reader['rr_record_id'] == $favorite_reader)
                {
                    $readerDom->setAttribute('select', 'yes');
                } else
                {
                    $readerDom->setAttribute('select', 'no');
                }
            }
        }

        $dom->appendChild($root);
        $this->renderPartial('goXML', array('dom' => $dom));
        //end xml
        die();
    }
    
    /*
     * Action to get list of readers, avliable for current site
     * Use json encoding
     */
    public function actionGetSiteReaders($group_id){
        $main_reader = ReadersVisibility::getMainGroupReader($group_id);
        if(!empty($main_reader)){
            $reader = Readers::getReader($main_reader);
            echo json_encode(array($main_reader => $reader->name));
            die();
        } else {
            $readers = ReadersVisibility::getAvaliableReaders($group_id);
            echo json_encode($readers);
            die();
       }
    }

    /**
     * This action return information about user name (fname lname) and type (client, reader, admin)
     * Information gets by user id
     */
     public function actionGetUserInfo(){
         if(isset($_GET['id'])){
             $user = users::getUser($_GET['id']);

             $dom = new DOMDocument('1.0', 'utf-8');
             $root = $dom->createElement('user');
             $root->setAttribute('Name', $user->name);
             $root->setAttribute('Type', $user->type);
             $root->setAttribute('Login', $user->login);
             $root->setAttribute('Id', $user->rr_record_id);
             $root->setAttribute('SiteType', $user->site_type);
             $root->setAttribute('Loc', $user->operator_location);
             $dom->appendChild($root);

             $this->renderPartial('goXML', array('dom' => $dom));
             //end xml
             die();
         }
     }
     
     public function actionGetBillingInfo()
     {
         $key = Yii::app()->request->getParam('key');
         
         $session = Sessions::getSession($key);         
         $user_id = $session->getUserId();
         
         $cc = CreditCard::getCardInfo($user_id);

         $dom = new DOMDocument('1.0', 'utf-8');
         $root = $dom->createElement('user');
         $root->setAttribute('firstname', $cc->firstname);
         $root->setAttribute('lastname', $cc->lastname);
         $root->setAttribute('billingaddress', $cc->billingaddress);
         $root->setAttribute('billingcity', $cc->billingcity);
         $root->setAttribute('billingstate', $cc->billingstate);
         $root->setAttribute('billingzip', $cc->billingzip);
         $root->setAttribute('billingcountry', $cc->billingcountry);
         $root->setAttribute('view1', $cc->view1);
         $root->setAttribute('view2', $cc->view2);
         $dom->appendChild($root);

         $this->renderPartial('goXML', array('dom' => $dom));
         //end xml
         die();
     }
     
     /*
      * This action check user logging into main site (by cookie value)
      */
     public function actionLoggedCheck($session_id){
         $guest = PsyDbHttpSession::readSession($session_id);

         $dom = new DOMDocument('1.0', 'utf-8');
         $root = $dom->createElement('user');
         if(!$guest){
             $root->setAttribute('logged', 'guest');
         } else {
             $root->setAttribute('logged', 'logged');
         }
         $dom->appendChild($root);
         
         $this->renderPartial('goXML', array('dom' => $dom));
         //end xml
         die();
     }

     /*
      * This action resize big readers avatars to width = 86px
      */
     public function actionConvertImage(){
         $q = 100;
         if(isset($_GET['id'])){
             $loc = Readers::getReader($_GET['id'])->operator_location;
             //echo Yii::app()->params['site_domain'].'/chat/'.$loc;
             //die();
             $file = Yii::app()->params['project_root'].'/chat/'.$loc;             
             $e = explode('.', $loc);
             $ext = strtolower($e[1]);
             if(($ext == 'jpeg')||($ext == 'jpg')){
                 $src = imagecreatefromjpeg($file);
             }
             if($ext == 'png'){
                 $src = imagecreatefrompng($file);
             }
             if($ext == 'gif'){
                 $src = imagecreatefromgif($file);
             }
             $w_src = imagesx($src);
             $h_src = imagesy($src);

             header('Content-type: image/png');

             if($w_src < $_GET['width']){
                $h_dest = $h_src;
             } else {
                 $h_dest = $_GET['width'];
             }

             $dest = imagecreatetruecolor($_GET['width'], $h_dest);

             imagecopyresized($dest, $src, 0, 0, 0, 0, $_GET['width'], $h_dest, $w_src, $h_src);
             
             if(($ext == 'jpeg')||($ext == 'jpg')){
                 imagejpeg($dest, '', $q);
             }
             if($ext == 'png'){
                 imagepng($dest);
             }
             if($ext == 'gif'){
                 imagegif($dest, '');
             }

             imagedestroy($src);
             imagedestroy($dest);
         }
     }
     
     public function actionGetPurchaseHistory()
     {
         $key = Yii::app()->request->getParam('key');
         
         $session = Sessions::getSession($key);         
         $user_id = $session->getUserId();

         $deposits = Deposits::getUserDep($user_id);
         
         $dom = new DOMDocument('1.0', 'utf-8');
         $root = $dom->createElement('deposits');
         
         foreach($deposits as $dep)
         {
             $purchaseDom = $dom->createElement('purchase');
             $root->appendChild($purchaseDom);
             $purchaseDom->setAttribute('Date', $dep->Date);
             $purchaseDom->setAttribute('Amount', $dep->Amount);
             $purchaseDom->setAttribute('Currency', $dep->Currency);
             $purchaseDom->setAttribute('Bmt', $dep->Bmt);
             $purchaseDom->setAttribute('Order_numb', $dep->Order_numb);
         }
         
         $dom->appendChild($root);
         $this->renderPartial('goXML', array('dom' => $dom));
     }
     
     public function actionGetMessagesList()
     {
         $key = Yii::app()->request->getParam('key');
         
         $session = Sessions::getSession($key);         
         $user_id = $session->getUserId();
         
         $messages = Messages::getMessagesList($user_id);
         
         $dom = new DOMDocument('1.0', 'utf-8');
         $root = $dom->createElement('messages');
         
         foreach($messages as $msg)
         {
             $msgDom = $dom->createElement('message');
             $root->appendChild($msgDom);
             $msgDom->setAttribute('Id', $msg->ID);
             $msgDom->setAttribute('From', $msg->From_name);
             $msgDom->setAttribute('Subject', $msg->Subject);
             $msgDom->setAttribute('Date', $msg->Date);
             $msgDom->setAttribute('Read_message', $msg->Read_message);
         }
         
         $dom->appendChild($root);
         $this->renderPartial('goXML', array('dom' => $dom));
     }
     
     public function actionGetMessage()
     {
         $message_id = Yii::app()->request->getParam('id');
         
         $message = Messages::getMessage($message_id);
         
         $dom = new DOMDocument('1.0', 'utf-8');
         $msgDom = $dom->createElement('message');
         
         $msgDom->setAttribute('Id', $message->ID);
         $msgDom->setAttribute('From', $message->From_name);
         $msgDom->setAttribute('Subject', $message->Subject);
         $msgDom->setAttribute('Date', $message->Date);
         $text = $dom->createElement('text');
         $content = $dom->createCDATASection($message->Body);         
         $text->appendChild($content);
         $msgDom->appendChild($text);
         
         $dom->appendChild($msgDom);
         $this->renderPartial('goXML', array('dom' => $dom));         
     }
  
     public function actionDelMessage()
     {
         $message_id = Yii::app()->request->getParam('id');
         Messages::delMessage($message_id);
     }
     
     public function actionGetReadersList()
     {
         $group_id = Yii::app()->request->getParam('group_id');
         $online = Yii::app()->request->getParam('online');
         
         $c = new CDbCriteria();
	 $c->join = "LEFT OUTER JOIN site_forbidden_readers fr on t.rr_record_id=fr.reader_id AND group_id = :group_id";
	 $c->condition = 'fr.reader_id IS NULL AND type = :type ';
	 $c->params = array(':type' => 'reader', ':group_id' => $group_id);
	 $c->order = "login ASC";
         $readers = users::model()->findAll($c);
                  
         if($online)
         {
             $r_a = array();
             foreach($readers as $reader)
             {
                 $r = Readers::getReader($reader->rr_record_id);
                 if($r->getStatus() == 'online')
                         $r_a[] = $reader;
             }
             $readers = $r_a;
         }
         
         $group = SiteGroups::getGroup($group_id);
         if($group->main_reader_id != 0)
         {
             $r = Readers::getReader($group->main_reader_id);
             if($r->getStatus() == 'online')
                 array_push ($readers, $r);
         }
         
         $dom = new DOMDocument('1.0', 'utf-8');
         $root = $dom->createElement('readers');
         
         foreach($readers as $reader)
         {
             $readerDom = $dom->createElement('reader');
             $root->appendChild($readerDom);
             $readerDom->setAttribute('Id', $reader->rr_record_id);
             $readerDom->setAttribute('Name', $reader->name);
         }   
         
         $dom->appendChild($root);
         $this->renderPartial('goXML', array('dom' => $dom));
      }
      
      public function actionGetUserBasicInfo()
      {
          $key = Yii::app()->request->getParam('key');
          
          $session = Sessions::getSession($key);         
          $user_id = $session->getUserId();

          $user = users::getUser($user_id);
          
          $dom = new DOMDocument('1.0', 'utf-8');
          $root = $dom->createElement('user');
          
          $root->setAttribute('Id', $user->rr_record_id);
          $root->setAttribute('Name', $user->name);
          $root->setAttribute('Type', $user->type);
          $root->setAttribute('Emailaddress', $user->emailaddress);
          $root->setAttribute('Balance', $user->balance);
          $root->setAttribute('Login', $user->login);
          $root->setAttribute('Password', $user->password);
          $root->setAttribute('SiteType', $user->site_type);
          
          $dom->appendChild($root);
          $this->renderPartial('goXML', array('dom' => $dom));          
      }
      
      public function actionSendMessage()
      {
            $message= new Messages;
            
            $key = Yii::app()->request->getParam('key');
            $return_url = Yii::app()->request->getParam('return_url');
            
            $dom = new DOMDocument('1.0', 'utf-8');
            
            $session = Sessions::getSession($key);         
            $user_id = $session->getUserId();

            $client = Clients::getClient($user_id, 'credit_cards');
            
            $readers_list = Readers::getReadersList();

            $message->user_type = $client->type;
            $message->From_user = $user_id;
            $message->From_name = $client->credit_cards->firstname;
            $message->To_user = $_POST['to'];
            $message->Subject = $_POST['subject'];
            $message->Body = $_POST['text'];

            foreach($_POST['Messages'] as $key=>$value)
            {
                $message->$key = $value;
            }        
            $message->attach = CUploadedFile::getInstance($message, 'attach');

            if(!is_null($message->attach))
            {
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
            
            if(!$message->validate()) 
            {
                $err = $message->getErrors();
                $this->redirect($return_url.'?errors='.urlencode(serialize($err)));
            } 
            else 
            {            
                $message->send();                
                $this->redirect($return_url.'?success=1');
            }        
      }
      
      public function actionSignup()
      {
        $model = new Signup();
        $debug = Yii::app()->request->getParam('debug');

        if (isset($_POST))
        {
            foreach ($_POST as $key => $value)
            {
                $model->$key = $value;
            }

            if($debug == 1)
                $model->debug = 1;
            
            $postFields = $_POST['Signup'];
            
            if (!$model->validate())
            {
                $err = $model->getErrors();                
                
                $errors = serialize($err);
                
                $this->render('signup', array(
                    'message' => $errors
                ));                  
                return;
            } 
            else
            {   
                $reg = $model->registerUser();
                
                if (!is_bool($reg))
                {
                    if ($reg == 'banned')
                    {
                        $error_code = 'You are creating more than one account';
                    }
                    else
                    {
                        $error_code = $reg;                        
                    }
                    $errors = serialize($error_code);
                    
                    $this->render('signup', array(
                        'message' => $errors
                    ));
                    return;
                }
                
                $this->render('signup', array(
                        'message' => 'success'
                ));
            }
         }            
      }
      
      public function actionSendEmailReading()
      {
            $tariff['reader_spec'] = Tariff::loadEmailReadingPriceList(); // List of Reader and Special price
            $tariff['question'] = Tariff::loadEmailReadingTariffsList(); // List of question prices (1,2,3 etc...)

            $model = new EmailQuestions();
            
            foreach ($_POST as $key => $value)
                $model->$key = $value;
            
            if (isset($_POST['payment_method']))
            {
                $current_tariff = new Tariff();
                $price = $current_tariff->loadEmailReadingPrice($model->reader_id, $model->b_reading_type);
                $model->price = $price;
                if ($model->b_reading_type == "SPECIAL")
                {
                    $model->b_reading_type = $price;
                    $model->spec = $price;
                } else
                {
                    $model->b_reading_type = $price;
                    $model->spec = '0.00';
                }
                
                // If some errors
                if (!$model->validate())
                {
                    $err = $model->getErrors();
                    $errors = serialize($err);
                    
                    $this->render('signup', array(
                        'message' => $errors
                    ));                  
                    return;
                }
                // Else begin payment
                else
                {
                    $user_model = new users();
                    $rows = $user_model->findAll(array('condition' =>
                        'rr_record_id = :user_id OR rr_record_id = :reader_id', 'params' => array(':user_id' =>
                        Yii::app()->user->id, ':reader_id' => $_POST['reader_id'])));
                    foreach ($rows as $row)
                    {
                        if ($row->rr_record_id == Yii::app()->user->id)
                        {
                            $model->full_user_name = $row->name; // User full name
                        } else
                        {
                            $model->reader_email = $row->emailaddress; // Reader email address
                            $model->reader_name = $row->name; // Reader full name
                        }
                    }
                    // Using ECHO payment system
                    if ($_POST['payment_method'] == "CreditCard")
                    {         
                        $resp = $model->sendReadingRequest();

                        // If error
                        if (!is_bool($resp))
                        {
                            $error_code = $reg;
                            $errors = serialize($error_code);
                    
                            $this->render('emailreadings', array(
                                'emailreadings' => $errors
                            ));
                            return;
                        } 
                        else
                        {
                            $this->render('emailreadings', array(
                                'message' => 'success'
                            ));
                            return;
                        }
                    }

                    // Using PayPal payment system
                    if ($_POST['payment_method'] == "PayPal")
                    {

                        //Register question with status PENDING
                        $quest_id = $model->registerQuestion('pending');
                        // Post data (sends to paypal system)
                        $postdata = "";
                        $postdata .= "cmd=_xclick&";
                        $postdata .= "item_name=" . urlencode("Email Readings") . "&";
                        $postdata .= "business=" . urlencode(Yii::app()->params['paypal_buisness_email']) .
                            "&";
                        $postdata .= "amount=" . $price . "&";
                        $postdata .= "notify_url=" . urlencode(Yii::app()->params['http_addr'] .
                            "service/paypalIpn") . "&";
                        $postdata .= "rm=2&";
                        $postdata .= "custom=" . $quest_id . "&";
                        $postdata .= "first_name=" . urlencode($_POST['billingfirstname']) . "&";
                        $postdata .= "last_name=" . urlencode($_POST['billinglastname']) . "&";
                        if (!empty($_POST['billingaddress']))
                            $postdata .= "adress_name=" . urlencode($_POST['billingaddress']) . "&";
                        if (!empty($_POST['billingcity']))
                            $postdata .= "adress_city=" . urlencode($_POST['billingcity']) . "&";
                        if (!empty($_POST['billingstate']))
                            $postdata .= "adress_state=" . urlencode($_POST['billingstate']) . "&";
                        if (!empty($_POST['billingzip']))
                            $postdata .= "adress_zip=" . urlencode($_POST['billingzip']) . "&";
                        if (!empty($_POST['billingcountry']))
                            $postdata .= "adress_country=" . urlencode($_POST['billingcountry']) . "&";
                        $postdata .= "return=" . urlencode(Yii::app()->params['http_addr'] .
                            "site/index");
                        // Link with get parameters for paypal payment system
                        $paypal_link = Yii::app()->params['paypal_url'] . '/cgi-bin/webscr?' . $postdata;
                        $this->redirect($paypal_link);
                    }
                }
            } 
      }
      
      public function actionGetChatSessionById()
      {
          $id = Yii::app()->request->getParam('id');
          if($id)
          {
            $session = ChatHistory::getSession($id);
            
            $dom = new DOMDocument('1.0', 'utf-8');
            $root = $dom->createElement('history');
            
            $root->setAttribute('Session_id', $session->Session_id);
            $root->setAttribute('Reader_id', $session->Reader_id);
            
            $dom->appendChild($root);
            $this->renderPartial('goXML', array('dom' => $dom));            
          }         
      }
      
      public function actionGetHistoryList()
      {
            $key = Yii::app()->request->getParam('key');
            $con = Yii::app()->request->getParam('con');

            $session = Sessions::getSession($key);         
            $user_id = $session->getUserId();
             
            $condition = (isset($con)) ? unserialize($con) : false;
            
            $criteria = new CDbCriteria;
            if(!$condition || empty($condition))
                $criteria->condition = '`Client_id` = :client_id';
            else {
                $con_str = '`Date` REGEXP "^'.$condition['year'];
                if(isset($condition['month']))
                    $con_str .= '-'.$condition['month'].'.*"';
                else
                    $con_str .= '.*"';
                if($condition['day'] == '1-15')
                    $con_str .= ' AND DAYOFMONTH(`Date`) > 1 AND DAYOFMONTH(`Date`) < 15';
                else if($condition['day'] == '16-31')
                    $con_str .= ' AND DAYOFMONTH(`Date`) > 15 AND DAYOFMONTH(`Date`) < 32';

                $criteria->condition = '`Client_id` = :client_id AND ( `del` = 0 OR `del` IS NULL) AND '.$con_str;
            }                
            $criteria->params = array(':client_id' => $user_id);
            $criteria->order = '`Date` DESC';
          
            $result = ChatHistory::model()->findAll($criteria);
            
            $dom = new DOMDocument('1.0', 'utf-8');
            $root = $dom->createElement('history');
            
            foreach($result as $row)
            {
                $readerDom = $dom->createElement('row');
                $root->appendChild($readerDom);
                $readerDom->setAttribute('Session_id', $row->Session_id);
                $readerDom->setAttribute('Date', $row->Date);
                $readerDom->setAttribute('Subject', $row->Subject);
                $readerDom->setAttribute('Reader_name', $row->Reader_name);
                $readerDom->setAttribute('Duration', $row->Duration);
                $readerDom->setAttribute('Paid_time', $row->Paid_time);
            }
            
            $dom->appendChild($root);
            $this->renderPartial('goXML', array('dom' => $dom));
      }
      
      public function actionGetHistoryTotalDurTime()
      {
            $key = Yii::app()->request->getParam('key');
            $con = Yii::app()->request->getParam('con');

            $session = Sessions::getSession($key);         
            $user_id = $session->getUserId();

            
            $condition = (isset($con)) ? unserialize($con) : false;
            
            $criteria = new CDbCriteria;
            $criteria->select = 'SUM(Duration) as `total`';
            if(!$condition || empty($condition))
                $criteria->condition = '`Client_id` = :client_id AND ( `del` = 0 OR `del` IS NULL )';
            else
            {
                $sql .= ' AND `Date` REGEXP "^'.$condition['year'];
                if(isset($condition['month']))
                    $sql .= '-'.$condition['month'].'.*"';
                else
                    $sql .= '.*"';
                if($condition['day'] == '1-15')
                    $sql .= ' AND DAYOFMONTH(`Date`) > 1 AND DAYOFMONTH(`Date`) < 15';
                else if($condition['day'] == '16-31')
                    $sql .= ' AND DAYOFMONTH(`Date`) > 15 AND DAYOFMONTH(`Date`) < 32';

                $criteria->condition = '`Client_id` = :client_id AND ( `del` = 0 OR `del` IS NULL )'.$sql;
            }
            $criteria->params = array(':client_id' => $user_id);

            $result = ChatHistory::model()->findAll($criteria);
            
            foreach($result as $row)
                $total = $row->total;
            
            $dom = new DOMDocument('1.0', 'utf-8');
            $root = $dom->createElement('total_time');

            $root->setAttribute('Total', $total);
            
            $dom->appendChild($root);
            $this->renderPartial('goXML', array('dom' => $dom));
      }
      
      public function actionGetChatLog()
      {
            $sess_id = Yii::app()->request->getParam('session_id');
            $reader_id = Yii::app()->request->getParam('reader_id');
            
            $history = ChatHistory::getSession($sess_id);
            $transcript = $history->getTranscript();
            
            if(empty($transcript))
            {
                $transcript = ChatTranscripts::getTranscript($sess_id, $reader_id);
            }            
            
            $dom = new DOMDocument('1.0', 'utf-8');
            $root = $dom->createElement('log');
            
            $text = $dom->createElement('text');
            $cont = $dom->createCDATASection($transcript);
            $root->appendChild($cont);
            
            $dom->appendChild($root);
            $this->renderPartial('goXML', array('dom' => $dom));            
      }
      
      public function actionDelChatLog()
      {
          $sess_id = Yii::app()->request->getParam('session_id');
          
          ChatHistory::deleteChatHistory($sess_id);
          
          die();
      }
      
      
      /**
       * Get favourite reader for client
       */
      public function actionGetUserFR()
      {
          $key = Yii::app()->request->getParam('key');          

          $session = Sessions::getSession($key);         
          $user_id = $session->getUserId();
                  
          
          $fr = Preference::getFavouriteReader($user_id);          
          
          $dom = new DOMDocument('1.0', 'utf-8');
          $root = $dom->createElement('FR');
            
          $root->setAttribute('fr_id', $fr->value);
                      
          $dom->appendChild($root);
          $this->renderPartial('goXML', array('dom' => $dom));
      }
      
      public function actionGetUserByLoginOrEmail()
      {
          $key = Yii::app()->request->getParam('key');
          
          $user = Clients::getByLoginOrEmail($key);
          
          $dom = new DOMDocument('1.0', 'utf-8');
          $root = $dom->createElement('user');
          
          $root->setAttribute('Id', $user->rr_record_id);
          $root->setAttribute('Emailaddress', $user->emailaddress);
          $root->setAttribute('Login', $user->login);
          $root->setAttribute('Password', $user->password);
          
          $dom->appendChild($root);
          $this->renderPartial('goXML', array('dom' => $dom));
      }
      
      public function actionLogoutUser()
      {
          $key = Yii::app()->request->getParam('key');
          $sess = Sessions::getSession($key);
          $sess->delete();
      }
      
        public function actionNrrRequest()
        {
            $key = Yii::app()->request->getParam('key');
            $sess = Sessions::getSession($key);
            $client_id = $sess->getUserId();
            
            if(empty($_POST['reader_id']))
                $this->redirect($_POST['return_url'].'account/nrrRequest?error='.urlencode('Reader doesn\'t selected'));
            
            $client = Clients::getClient($client_id, 'credit_cards');
            $reader = Readers::getReader($_POST['reader_id']);
               
            $freetime = RemoveFreetime::getFreeTime($client_id);
            $freetime->Count ++;
            $freetime->save();

            $subject="NRR Request Form(".$client->credit_cards->firstname." / ".$client->login."), Reader: ".$reader->login;

            $message="NRR Request Form<br>";
            $message.="CLIENT :     ".$client->credit_cards->firstname." (".$client->login.")<br>";
            $message.="READER :     ".$reader->login."<br>";
            $message.="SESSION DATE :     ".$_POST['session_date']."<br><br>";
            $message.="TIME BACK :     ".(!empty($_POST['time_back']) ? $_POST['time_back'] :
                    (!empty($_POST['time_back2']) ? $_POST['time_back2'] : $_POST['time_back3']))."<br><br>";

            switch ($_POST['nrr']) {
                case 1 :
                    $message .= "<br>Time put back ".$_POST['time_back']."<br>";
                    break;
                case 2 :
                    break;
                case 3 : {
                    switch ($_POST['nrr3_1']) {
                            case 1 :
                                $selected_option = "I WILL TRY TO RE-ENTER THE CHAT ASAP<br>";
                                break;
                            case 2 :
                                $selected_option = "I WILL SAVE MY TIME FOR ANOTHER SESSION AND/OR READER<br>";
                                break;
                            case 3 :
                                $selected_option = "I AM REQUESTING THAT THE READER ADD TIME BACK TO MY ACCOUNT IN THE AMOUNT OF ".$_POST['time_back2'];
                                break;
                    }
                    $message .= "Unfortunately due to various reasons (most likely internet related)-  your chat with ".$reader['login']." ended abruptly - We apologize for this occurrence.  Please clear your browser cache, <u>RE-BOOT</u> and try again- ALSO LOOK FOR EMAIL FROM YOUR READER!.<br>
    We strongly recommend that you use Mozilla FireFox browser<br>
    Best regards, Psychic-contact Admin<br>";
                        if ($selected_option)
                            $message .= " <br>Customer has chosen : ".$selected_option;
                        break;
                    }
                    case 4 : {
                        $message .= "Reason why client is unhappy :".$_POST['unhappy']."<br><br>";
                        $message .= "WE ARE SORRY THAT YOU ARE NOT SATISFIED WITH THE SESSION THAT JUST ENDED WITH: ".$reader->login."<br>
    OUR POLICY IS THAT WE GUARANTEE SATISFACTION FOR ALL SESSIONS<br>
    IF YOU HONESTLY FEEL THAT THE READER DID NOT LIVE UP TO YOUR EXPECTATIONS- WE WILL GLADLY ADD TIME BACK TO YOUR ACCOUNT<br>
    BUT! IN THE FUTURE YOU MUST TELL THE READER WITHIN THE FIRST 5MINS AFTER HITTING THE 'HIRE ME' BUTTON AND THE READER WILL EITHER GIVE YOU YOUR TIME BACK OR PAUSE THE TIMER SO THAT YOU AND THE READER CAN TRY TO COMMUNICATE BETTER.<br>
    NEXT TIME PLEASE DISCUSS YOUR FEELINGS ABOUT THE SESSION WITH THE READER";
                        break;
                    }
                    default:
                }

            // Email to admin, reader and client
            PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $message);
            PsyMailer::send($reader->emailaddress, $subject, $message);
            PsyMailer::send($client->emailaddress, $subject, $message);

                // Add new nrr request to db
                $nrr = new NrrRequests();
                $nrr->reader_id = $reader->rr_record_id;
                $nrr->client_id = Yii::app()->user->id;
                $nrr->nrr_notes = $message;
                $nrr->addNewRequest();

                if (strlen($_POST['suggest']) > 0){
                    $subject = "NRR : Clients suggestions(".$client->credit_cards->firstname." / ".$client->login."), Reader: ".$reader->login;
                    $email_text = $_POST['suggest'];
                    PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $email_text);
                    PsyMailer::send($reader->emailaddress, $subject, $email_text);
                }

                $this->redirect($_POST['return_url'].'account/nrrRequest?success=1');
                return;            
        
        }
}
