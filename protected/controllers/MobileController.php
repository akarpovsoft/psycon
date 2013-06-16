<?php

class MobileController extends Controller
{
    
    const MOBILE_LAYOUT = 3;
    
    public function filters() {
        $a = array(
                'accessControl', // perform access control for CRUD operations
                'layoutControl',                
        );

        return $a;
    }
    
    public function accessRules() {
        return array(
                array('allow', // allow authenticated user to perform actions
                        'actions'=>array('index','login','logout','readers','faq','support','signup','addfunds'),
                        'users'=>array('*'),
                ),
                array('allow', // allow authenticated user to perform actions
                        'actions'=>array('profile','messages','history','chat','openLog','chatStart'),
                        'users'=>array('@'),
                ),
                array('deny',  // deny all users
                        'users'=>array('*'),
                ),
        );
    }
    
        public function filterLayoutControl($filterChain)
        {
            //$this->layout = 'mobile';
            //Yii::app()->theme = 'mobile';
            $this->redirect(Yii::app()->params['http_addr']);
            $filterChain->run();
        }

        public function actionIndex()
        {
                
                $this->render('index');
        }

        public function actionLogin()
        {
            $model = new LoginForm;

            if (isset($_POST['LoginForm']))
            {
                $model->attributes = $_POST['LoginForm'];
                $model->LayoutType = self::MOBILE_LAYOUT;
                if(!$model->validate())
                {
                    $this->render('login', array('errors' => $model->getErrors()));
                    return;
                }
                else
                {
                    $this->redirect('../users/mainmenu');
                }
            }
            $this->render('login');
        }

        public function actionLogout()
        {
            Yii::app()->user->logout();
            $this->redirect('../mobile');
        }

        public function actionReaders()
        {
            $this->render('our_readers');
        }
        
        public function actionProfile()
        {
            if(isset($_POST['save'])){
                $client = Clients::getClient(Yii::app()->user->id, 'credit_cards');
                $client->password = $_POST['password'];
                $client->emailaddress = $_POST['emailaddress'];
                $client->save();
            }            
            $this->render('profile', array('client' => Clients::getClient(Yii::app()->user->id, 'credit_cards')));
        }
        
        public function actionFaq()
        {
            $this->render('faq');
        }
        
        public function actionSupport()
        {            
            $model = new ContactForm;
            if (Yii::app()->user->isGuest)
            {
                if (isset($_POST['send']))
                {
                    $model->verifycode = $_POST['ver'];
                    $model->attributes = $_POST['ContactForm'];
                    if (!$model->validate())
                    {
                        $err = $model->getErrors();
                        $this->render('support', array('model' => $model, 'guest' => 1, 'errors' => $err));
                        return;
                    } else
                    {
                        $subj = "PSYCHAT - Contact us: " . $model->subject . " (" . $model->name . ")";
                        $mess_2_send = "Subject: " . $model->subject . "<br>
                                    Full Name: " . $model->name . "<br>
                                    Username(client wrote it himself/herself): " . $model->
                            login . "<br><br>" . $model->body . "<br>----------------<br>" . date("M j, Y h:i a",
                            time()) . "<br>
                                    E-mail address: " . $model->email . "<br>
                                    IP:" . $_SERVER['REMOTE_ADDR'] . "<br>
                                    System: " . $_SERVER['HTTP_USER_AGENT'];
                        PsyMailer::send(Yii::app()->params['adminEmail'], $subj, $mess_2_send);
                        $this->render('support', array('model' => $model, 'guest' => 1, 'success' =>
                            1));
                        return;
                    }
                }
                $this->render('support', array('model' => $model, 'guest' => 1));
            } else
            {
                $client = Clients::getClient(Yii::app()->user->id, 'credit_cards');
                if (isset($_POST['send']))
                {
                    $model->attributes = $_POST['ContactForm'];
                    if (!$model->validate())
                    {
                        $err = $model->getErrors();
                        $this->render('support', array('model' => $model, 'errors' => $err));
                        return;
                    } else
                    {
                        $full_address = $client->credit_cards->billingaddress . ", " . $client->
                            credit_cards->billingcity . ", ZIP: " . $client->credit_cards->billingzip . ", " .
                            $client->credit_cards->billingstate . ", " . $client->credit_cards->
                            billingcountry;

                        $subj = "PSYCHAT - Contact us: " . $model->subject . " (" . $client->login . ")";
                        $mess_2_send = "Subject: " . $model->subject . "<br>
                                    Full Name: " . $model->name . "<br>
                                    Username(client wrote it himself/herself): " . $model->
                            login . "<br><br>" . $model->body . "<br>----------------<br>" . date("M j, Y h:i a",
                            time()) . "<br>
                                    Client (Id: " . $client->rr_record_id .
                            ", Username: " . $client->login . ")<br>" . $client->name . "<br>" . $client->
                            gender . "<br><br>
                                    Address: " . $full_address . "<br>
                                    E-mail address: " . $client->emailaddress . " (" .
                            $model->email . ")<br>
                                    Phone #: " . $client->phone . "<br>
                                    Sign up date: " . $client->rr_createdate . "<br>
                                    IP:" . $_SERVER['REMOTE_ADDR'] . "<br>
                                    System: " . $_SERVER['HTTP_USER_AGENT'];
                        PsyMailer::send(Yii::app()->params['adminEmail'], $subj, $mess_2_send);
                        $this->render('support', array('model' => $model, 'success' => 1));
                        return;
                    }
                } else
                {
                    $model->name = $client->name;
                    $model->email = $client->emailaddress;
                    $model->login = $client->login;
                    $this->render('support', array('model' => $model));
                }
            }
        }
        
        public function actionMessages()
        {
            $this->redirect(Yii::app()->params['http_addr'].'messages');
        }
        
        public function actionHistory()
        {
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
            $this->render('history', array('data' => $history, 'total' => $total_duration));
        }
        
        public function actionOpenLog()
        {
            $session_id = Yii::app()->request->getParam('session_id');
            $history = ChatHistory::getSession($session_id);
            $transcript = $history->getTranscript();

            if(empty($transcript))
                $transcript = ChatTranscripts::getTranscript($session_id, $reader_id);
        
            $this->render('open_log',array('log' => $transcript));
        }
        
        public function actionChat()
        {
            $this->redirect(Yii::app()->params['http_addr'].'chat/client/chatStart');
        }
        
        public function actionChatStart()
        {
            if(Yii::app()->user->type != 'client')
                $this->redirect(Yii::app()->params['http_addr'].'users/mainmenu');
        
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

            if(is_null($readers_online)){
                $this->render('chat', array('no_one_readers_online' => 1));
                return;
            }

            $favorite_reader = Clients::getFavoriteReader(Yii::app()->user->id);

            if(isset($_GET['chat_with']))
                $chat_with = Readers::getReader($_GET['chat_with']);

            foreach($readers_online as $reader) {
                if($reader['rr_record_id'] == $favorite_reader)
                    $avaliable = $reader['name'];
            }

            $this->render('chat', array('client' => $client,
                    'readers_online' => $readers_online,
                    'avaliable' => $avaliable,
                    'favorite_reader' => $favorite_reader,
                    'with' => $chat_with['rr_record_id'],
                    'main_group_reader' => $main_reader));
        }
        
        public function actionSignup()
        {
            $this->pageTitle = Yii::app()->name . ' - Signup';
            $affiliate = $_COOKIE['Affiliate'];
            $gift = $_GET['gift'];
            $model = new Signup();

            // Gathering visitors statistic
            // Update information about visitors count
            $visitor = new VisitorsCounter();
            $visitor->ip = $_SERVER['REMOTE_ADDR'];
            $visitor->page_type = 'signup';
            $visitor->affiliate = ($_COOKIE['Affiliate']) ? $affiliate : 0;
            $visitor->register();

            if (isset($_POST['Signup']))
            {
                foreach ($_POST['Signup'] as $key => $value)
                {
                    $model->$key = $value;
                }
                
                $model->email_confirm = $model->email;
                
                if (!$model->validate())
                {
                    $err = $model->getErrors();
                    if (isset($_POST['redirect']))
                    {
                        $error_code = serialize($err);
                        $this->redirect('http://' . $_POST['redirect'] . '&error=' . urlencode($error_code));
                    }
                    if (isset($_GET['register']))
                        $this->render('signup', array('mod' => $model, 'affiliate' => $affiliate, 'gift' =>
                            $gift, 'errors' => $err, 'no_freebie' => 1));
                    else
                        $this->render('signup', array('mod' => $model, 'affiliate' => $affiliate, 'gift' =>
                            $gift, 'errors' => $err));
                    return;
                } else
                {
                    $reg = $model->registerUser();
                    if (!is_bool($reg))
                    {
                        if (isset($_POST['redirect']))
                        {
                            $error_code = serialize($reg);
                            $this->redirect('http://' . $_POST['redirect'] . '&error=' . urlencode($error_code));
                        }
                        if ($reg == 'banned')
                        {
                            $this->redirect('moreAccount');
                            return;
                        }
                        if (isset($_GET['register']))
                            $this->render('signup', array('mod' => $model, 'affiliate' => $affiliate, 'gift' =>
                                $gift, 'errors' => $reg, 'no_freebie' => 1));
                        else
                            $this->render('signup', array('mod' => $model, 'errors' => $reg, 'affiliate' =>
                                $affiliate, 'gift' => $gift));
                        return;
                    } else
                    {
                        if (isset($_POST['redirect']))
                        {
                            $this->redirect('http://' . $_POST['redirect'] . '&error=0');
                        }
                        if ($_GET['site_type'] == PsyConstants::SITE_TYPE_CO_UK)
                            $this->render('signup_success');
                        else
                            $this->render('signup', array('mod' => $model, 'success' => 1));
                        return;
                    }
                }
            }
            if (isset($_GET['register']))
                $this->render('signup', array('mod' => $model, 'affiliate' => $affiliate, 'gift' =>
                    $gift, 'no_freebie' => 1));
            else
                $this->render('signup', array('mod' => $model, 'affiliate' => $affiliate, 'gift' =>
                    $gift));
        }
        
        public function actionAddFunds()
        {
            $tariffs['chat_opt'] = Tariff::loadChatOptions();
            $cc_info = CreditCard::getCardInfo(Yii::app()->user->id);
            $model = new AddFunds();
            $model->firstname = $cc_info->firstname;
            $model->lastname = $cc_info->lastname;
            $model->billingaddress = $cc_info->billingaddress;
            $model->billingcity = $cc_info->billingcity;
            $model->billingstate = $cc_info->billingstate;
            $model->billingcountry = $cc_info->billingcountry;
            $model->billingzip = $cc_info->billingzip;
            $encr_card = $cc_info->view1 . '*' . $cc_info->view2;
            $model->tariff = $_POST['tariff'];


            if (isset($_GET['session']))
            {
                $model->session_id = $_GET['session'];
                $model->session_reader = $_GET['reader_id'];
            } else
                if (isset($_GET['session_key']))
                {
                    try
                    {
                        Yii::app()->getModule('chat');
                        $chatContext = ChatContext::getContext($_GET['session_key'], ChatContext::
                            CLIENT);
                        $model->session_key = $_GET['session_key'];
                        $model->session_id = $chatContext->session_id;
                        $model->session_reader = $chatContext->reader_id;
                    }
                    catch (exception $e)
                    {
                        echo $e->getMessage() . "\r\n" . $e->getTraceAsString();
                        die();
                    }
                }

            if ($_POST['pay_begin'])
            {
                foreach ($_POST['AddFunds'] as $key => $value)
                    $model->$key = $value;
                $model->payment_method = $_POST['payment_method'];
                $model->year = $_POST['year'];
                //$model->month = $_POST['month'];
                $model->user_id = $_POST['user_id'];
                $model->empty_cvv = $_POST['empty_cvv'];
                if ($_POST['cc'] == true)
                {
                    $hash2 = CreditCard::getCard($_POST['user_id']);
                    $model->cardnumber = $cc_info->view1 . $hash2 . $cc_info->view2;
                    $model->month = $cc_info->month;
                    $model->year = $cc_info->year;
                }
                if (!$model->validate())
                {
                    $err = $model->getErrors();
                    $model->cardnumber = '';
                    $this->render('add_funds', array('model' => $model, 'tariffs' => $tariffs,
                        'errors' => $err, 'card' => $encr_card));
                } else
                {
                    $row = Clients::getClient($model->user_id);
                    $client_name = $row->name;
                    $model->username = $row->login;
                    $model->gender = $row->gender;
                    $client_email = $row->emailaddress;
                    $client_address = $row->address;
                    $client_phone = $row->phone;
                    $model->affiliate = $row->affiliate;
                    $model->signup = $row->rr_createdate;
                    $tar = Tariff::loadCurrentChatOptions($_POST['tariff']);
                    $model->amount = $tar['amount'];
                    $client_id = Yii::app()->user->id;
                    $pay_amount = ($model->cardnumber == '4005550000000019') ? '1.99' : $model->
                        amount;

                    // ECHO payment system
                    if ($_POST['payment_method'] == "CreditCard")
                    {
                        $PSystem = new EchoPaySystem();
                        $extraClientInfo = ClientLimit::getUserInfo($model->user_id);


                        // If tariff type < 15 min OR special offer 20 min - 19.95  - no extra minutes
                        // Else extra minutes = 10 min
                        if ($_POST['tariff'] < 3)
                            $minutes = $tar['minutes'];
                        else
                            $minutes = $tar['minutes'] + PsyConstants::getName(PsyConstants::
                                CHAT_EXTRA_MINUTES);

                        $model->minutes = $minutes;
                        $merchant_trace_nbr = $client_id . $PSystem->get_random();
                        $init_data = array("remote_address" => $_SERVER['REMOTE_ADDR'],
                            "merchant_trace_num" => $merchant_trace_nbr, );
                        $pay_data = array("first_name" => $model->firstname, "last_name" => $model->
                            lastname, "billing_address" => $model->billingaddress, "billing_city" => $model->
                            billingcity, "billing_state" => $model->billingstate, "billing_zip" => $model->
                            billingzip, "billing_country" => $model->billingcountry, "billing_email" => $client_email,
                            "cc_number" => $model->cardnumber, "grand_total" => $pay_amount,
                            // Payment amount
                            "ccexp_month" => $model->month, "ccexp_year" => $model->year, "cnp_security" =>
                            $model->cvv, "client_id" => $client_id);
                        $ps_type = "ECHO";
                        $resp_info = $PSystem->pay_extra($init_data, $pay_data, $extraClientInfo->
                            Client_status);

                        if ($PSystem->success || $model->user_id == 9615)
                        { /// @todo  remove 9615
                            // Checking and register or update new card in DB
                            CreditCard::registerNewCard($model->cardnumber, $client_id, $client_name);
                            // add payment to limits
                            ClientLimit::registerPayment($model->user_id, $model->amount);

                            $bmt = "";
                            if (!empty($model->session_id))
                            {
                                $model->order_numb = $resp_info['ORDER_NUMBER'];
                                $bmt = $model->processBMT();
                            }

                            //Add new transaction
                            $trans_model = new Transactions();
                            $trans_model->UserID = Yii::app()->user->id;
                            $trans_model->ORDER_NUMBER = $resp_info['ORDER_NUMBER'];
                            $trans_model->AUTH_CODE = $resp_info['AUTH_CODE'];
                            $trans_model->TRAN_AMOUNT = $resp_info['TRAN_AMOUNT'];
                            $trans_model->merchant_trace_nbr = $resp_info['MERCHANT_TRACE_NBR'];
                            $trans_model->transaction_type = 0;
                            $trans_model->Total_time = $minutes * 60;

                            $trans_model->addTransaction($bmt);

                            // Add minutes to user's balance
                            $row->balance += $minutes;
                            $row->save();

                            $date_time = date("M j, Y h:i a");
                            // Admin email
                            $body = $date_time . "\n<br/>
                                                            Client (ID: " . $_POST['user_id'] . "
                                                            Username: " . $model->username . ")\n<br/>
                                                            ".$client_name."\n<br/>
                                                            \$" . $model->amount . " (" . $minutes . " minutes)\n<br/>
                                                            " . $model->gender . "\n<br/>
                                                            Credit Card: ***" . substr($model->cardnumber, -4) . "\n<br/>
                                                            CCV: " . (!empty($model->cvv)?"Yes":"No") . "\n<br/>
                                                            Expiry Date: " . $model->month . "/" . $_POST['year'] . "\n<br/>
                                                            Order #: " . $resp_info[ORDER_NUMBER] . "\n<br/>
                                                            Affiliate: $model->affiliate\n<br/>
                                                            Address: " . $client_address . "\n<br/>
                                                            E-mail address: " . $client_email . "\n<br/>
                                                            Phone #: " . $client_phone . "\n<br/>
                                                            Sign up date: " . $model->signup . "\n<br/>
                                                            IP: " . $_SERVER['REMOTE_ADDR'] . "\n<br/>
                                    Reader(if BMT): $bmt";

                            $subject = "PSYCHAT - Successful Authorization ($model->username) $bmtSubj";
                            PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $body);

                            // Email to all readers
                            $subject = "PSYCHAT - Incoming Chat Order (" . $model->username . ")";
                            $body = $date_time . "\n<br/>
                                                            Client (ID: " . $_POST['user_id'] . "
                                                            Username: " . $model->username . ")\n<br/>
                                                            \$" . $model->amount . " (" . $minutes . " minutes)\n<br/>
                                                            " . $model->gender . "\n<br/>
                                                            Order #: " . $resp_info[ORDER_NUMBER] . "\n<br/>-" . $model->affiliate . "\n<br/>
                                                            Sign up date: " . $model->signup . "\n<br/>
                                                            IP: " . $_SERVER['REMOTE_ADDR'];
                            PsyMailer::sendToAllReaders($subject, $body);

                            // Email to client
                            $subject = "PSYCHAT - Thank you!";
                            $body = $client_name . ",\n<br/>
                                                            Thank you for your order.\n<br/>\n<br/>
                                                            Here are the details of your purchase.\n<br/>" . $date_time . "\n<br/>
                                                            Your (ID: " . $_POST['user_id'] . ", Username: " . $model->username . ")\n<br/>
                                                            " . $client_name . "\n<br/>
                                                            \$" . $model->amount . " (" . $minutes . " minutes)\n<br/>" . $model->gender .
                                "\n<br/>
                                                            Order #: " . $resp_info[ORDER_NUMBER] . "\n<br/>
                                                            Address: " . $client_address . "\n<br/>
                                                            E-mail address: " . $client_email . "\n<br/>
                                                            Phone #: " . $client_phone . "\n<br/>
                                                            Sign up date: " . $model->signup . "\n<br/>
                                                            IP: " . $_SERVER['REMOTE_ADDR'] . "\n<br/>\n<br/>\n<br/>
                                                            Please be sure to disable your POPUP Blockers before attempting to chat. You can read this page for assistance in disabling POPUPS for IE " .
                                Yii::app()->params['site_domain'] . "/chat/iesettings\n<br/>";
                            PsyMailer::send($client_email, $subject, $body);

                            $this->render('add_funds', array('model' => $model, 'tariffs' => $tariffs,
                                'success' => 1, 'card' => $encr_card));
                        } else
                        {
                            $subject = "PSYCHAT - Failed Authorization (" . $model->username . ", ECHO - " .
                                date('h:m:s') . ")";
                            $body = $resp_info["ERROR_MESSAGE"] . "\n<br/>
                                                            Failed Authorization\n<br/>\n<br/>" . $date_time . "\n<br/>
                                                            Client (Id: " . $_POST['user_id'] . ", Username: " . $model->username .
                                ")\n<br/>\$" . $model->amount . " (" . $minutes . " minutes)\n<br/>
                                                            " . $model->gender . "\n<br/>
                                                            Credit Card: ***" . substr($model->cardnumber, -4) . "\n<br/>
                                                            CCV: " . $model->cvv . "\n<br/>
                                                            Expiry Date: " . $model->month . "/" . $_POST['year'] . "\n<br/>
                                                            Order #: " . $resp_info[ORDER_NUMBER] . "\n<br/>
                                                            Affiliate: $model->affiliate\n<br/>
                                                            Address: " . $client_address . "\n<br/>
                                                            E-mail address: " . $client_email . "\n<br/>
                                                            Phone #: " . $client_phone . "\n<br/>
                                                            Sign up date: " . $model->signup . "\n<br/>
                                                            IP: " . $_SERVER['REMOTE_ADDR'] . "\n<br/>
                                                            Error Code: " . $resp_info["DECLINE_CODE"];
                            PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $body);
                            $this->render('add_funds', array('model' => $model, 'tariffs' => $tariffs,
                                'errors' => $resp_info["ERROR_MESSAGE"], 'card' => $encr_card));
                        }
                    }

                    // Paypal payment system
                    if ($_POST['payment_method'] == "PayPal")
                    {
                        // Post data (sends to paypal system)
                        $postdata = "";
                        $postdata .= "cmd=_xclick&";
                        $postdata .= "business=" . urlencode(Yii::app()->params['paypal_buisness_email']) .
                            "&";
                        $postdata .= "notify_url=" . urlencode(Yii::app()->params['http_addr'] .
                            "service/chatAddFundsIpn") . "&";
                        $postdata .= "item_name=" . urlencode("Psychic-Contact Time") . "&";
                        $postdata .= "item_number=1&";
                        $postdata .= "amount=" . $model->amount . "&";
                        $postdata .= "rm=2&";
                        $postdata .= "custom=" . $client_id . "," . $model->session_key . "&";
                        $postdata .= "on0=Affiliate&";
                        $postdata .= "os0=" . $model->affiliate . "&";
                        $postdata .= "first_name=" . $model->firstname . "&";
                        $postdata .= "last_name=" . $model->lastname . "&";
                        if (!empty($model->billingaddress))
                            $postdata .= "adress_name=" . $model->billingaddress . "&";
                        if (!empty($model->billingcity))
                            $postdata .= "adress_city=" . $model->billingcity . "&";
                        if (!empty($model->billingstate))
                            $postdata .= "adress_state=" . $model->billingstate . "&";
                        if (!empty($model->billingzip))
                            $postdata .= "adress_zip=" . $model->billingzip . "&";
                        if (!empty($model->billingcountry))
                            $postdata .= "adress_country=" . $model->billingcountry . "&";
                        $postdata .= "return=" . urlencode(Yii::app()->params['http_addr'] .
                            "site/index") . "&";
                        $postdata .= "currency_code=USD&";
                        $postdata .= "bn=" . urlencode('Psychic-contact');
                        // Link with get parameters for paypal payment system
                        $paypal_link = Yii::app()->params['paypal_url'] . '/cgi-bin/webscr?' . $postdata;
                        $this->redirect($paypal_link);
                    }
                }
            } else
            {
                $this->render('add_funds', array('model' => $model, 'tariffs' => $tariffs,
                    'card' => $encr_card));
            }
        }
}

?>
