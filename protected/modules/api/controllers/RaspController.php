<?php

class RaspController extends Controller
{
    public function actions()
    {
        return array( // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array('class' => 'CCaptchaAction', 'backColor' => 0xFFFFFF, ),
        );
    }
    
    public function filters($params = null) {
        return array(
                'postControl + signup, emailreadings',
        );        
    }
    
    public function filterPostControl($filterChain)
    {
        if(!$_POST)
            $this->layout = '404';
        else
        {
            if($_POST['Signup']['site_type'])
                $s_type = $_POST['Signup']['site_type'];
            else
                $s_type = $_POST['site_type'];
            
            switch($s_type)
            {
                case 'MS':
                    $this->layout = 'mysticalsearch';
                    break;
                case 'PN':
                    $this->layout = 'psynet';
                    break;
                case 'MC':
                    $this->layout = 'mysticalchild';
                    break;
                case 'CA':
                    $this->layout = 'canada';
                    break;
            }
        }        
        $filterChain->run();
    }
  
    public function actionAddFunds()
     {
        $ps_type = Yii::app()->request->getParam('ps_type');
        $account_id = Yii::app()->request->getParam('account_id');
        
        $model = new AddFunds();
        $key = Yii::app()->request->getParam('key');
        $return_url = Yii::app()->request->getParam('return_url');
        
        $session = Sessions::getSession($key);         
        $user_id = $session->getUserId();
        
        $cc_info = CreditCard::getCardInfo($user_id);
        
        foreach ($_POST['AddFunds'] as $key => $value)
            $model->$key = $value;
        
        $model->payment_method = $_POST['payment_method'];
        $model->year = $_POST['year'];
        //$model->month = $_POST['month'];
        $model->user_id = $user_id;
        $model->empty_cvv = $_POST['empty_cvv'];
        if ($_POST['cc'] == true)
        {
            $hash2 = CreditCard::getCard($user_id);
            $model->cardnumber = $cc_info->view1 . $hash2 . $cc_info->view2;
            $model->month = $cc_info->month;
            $model->year = $cc_info->year;
        }
        if (!$model->validate())
        {
            $err = $model->getErrors();
            $errstr = urlencode(serialize($err));

            $this->redirect($return_url.'?error='.$errstr);
        } 
        else
        {
            $row = Clients::getClient($model->user_id);
            $model->client_name = $row->name;
            $model->username = $row->login;
            $model->gender = $row->gender;
            $model->email = $row->emailaddress;
            $model->address = $row->address;
            $model->phone = $row->phone;
            $model->affiliate = $row->affiliate;
            $model->signup = $row->rr_createdate;
            $model->tariff = Tariff::loadCurrentChatOptions($model->tariff);
            $model->amount = $model->tariff['amount'];
            $model->client_id = Yii::app()->user->id;
            $model->amount = ($model->cardnumber == '4005550000000019') ? '1.99' : $model->amount;

            // ECHO payment system
            if ($_POST['payment_method'] == "CreditCard")
            {    
                $resp = $model->pay($ps_type, $account_id);
                
                if(is_bool($resp))
                {
                    $this->redirect($return_url.'?success=1');           
                }
                else
                {
                    $errstr = urlencode(serialize($resp));                    
                    $this->redirect($return_url.'?error='.$errstr);
                }
            }
            else
            {
            // Paypal payment system
//                $postdata = "";
//                $postdata .= "cmd=_xclick&";
//                $postdata .= "business=" . urlencode('orders@psychic-contact.com') .
//                    "&";
//                $postdata .= "notify_url=" . urlencode(Yii::app()->params['http_addr'] .
//                    "service/chatAddFundsIpn") . "&";
//                $postdata .= "item_name=" . urlencode("Psychic-Contact Time") . "&";
//                $postdata .= "item_number=1&";
//                $postdata .= "amount=" . $model->amount . "&";
//                $postdata .= "rm=2&";
//                $postdata .= "custom=" . $client_id . "," . $model->session_key . "&";
//                $postdata .= "on0=Affiliate&";
//                $postdata .= "os0=" . $model->affiliate . "&";
//                $postdata .= "first_name=" . $model->firstname . "&";
//                $postdata .= "last_name=" . $model->lastname . "&";
//                if (!empty($model->billingaddress))
//                    $postdata .= "adress_name=" . $model->billingaddress . "&";
//                if (!empty($model->billingcity))
//                    $postdata .= "adress_city=" . $model->billingcity . "&";
//                if (!empty($model->billingstate))
//                    $postdata .= "adress_state=" . $model->billingstate . "&";
//                if (!empty($model->billingzip))
//                    $postdata .= "adress_zip=" . $model->billingzip . "&";
//                if (!empty($model->billingcountry))
//                    $postdata .= "adress_country=" . $model->billingcountry . "&";
//                $postdata .= "return=" . urlencode(Yii::app()->params['http_addr'] .
//                    "site/index") . "&";
//                $postdata .= "currency_code=USD&";
//                $postdata .= "bn=" . urlencode('Psychic-contact');
//                // Link with get parameters for paypal payment system
//                $paypal_link = Yii::app()->params['paypal_url'] . '/cgi-bin/webscr?' . $postdata;
//                $this->redirect($paypal_link);
                $this->actionPaypal($account_id, $model);
            } 
        }
    }
    
    public function actionPaypal($account_id, $model)
    {
        $account_id = ($account_id) ? $account_id : Yii::app()->request->getParam('account_id');
        
        $buisness_email = RaspConfig::getBuisnessEmail($account_id);
        $currency = RaspConfig::getCurrency($account_id);
        
        // Post data (sends to paypal system)
        $postdata = "";
        $postdata .= "cmd=_xclick&";
        $postdata .= "business=" . urlencode($buisness_email) . "&";
        $postdata .= "notify_url=" . urlencode(Yii::app()->params['http_addr'] . "xml/resp/paypalNotify") . "&";
        $postdata .= "item_name=" . urlencode("Psychic-Contact Time") . "&";
        $postdata .= "item_number=1&";
        $postdata .= "amount=" . $model->amount . "&";
        $postdata .= "rm=2&";
        $postdata .= "custom=" . urlencode(Yii::app()->user->id . "," . $model->session_key . "," . $account_id) . "&";
        $postdata .= "on0=Affiliate&";
        $postdata .= "os0=" . $model->affiliate . "&";
        $postdata .= "first_name=" . $model->firstname . "&";
        $postdata .= "last_name=" . $model->lastname . "&";
        if (!empty($model->billingaddress))
            $postdata .= "adress_name=" . urlencode($model->billingaddress) . "&";
        if (!empty($model->billingcity))
            $postdata .= "adress_city=" . urlencode($model->billingcity) . "&";
        if (!empty($model->billingstate))
            $postdata .= "adress_state=" . urlencode($model->billingstate) . "&";
        if (!empty($model->billingzip))
            $postdata .= "adress_zip=" . urlencode($model->billingzip) . "&";
        if (!empty($model->billingcountry))
            $postdata .= "adress_country=" . urlencode($model->billingcountry) . "&";
        $postdata .= "return=" . urlencode(Yii::app()->params['http_addr'] . "site/index") . "&";
        $postdata .= "currency_code=".$currency."&";
        $postdata .= "bn=" . urlencode('Psychic-contact');
        // Link with get parameters for paypal payment system
        $paypal_link = 'https://sandbox.paypal.com/cgi-bin/webscr?' . $postdata;
        
        $this->redirect($paypal_link);
    }
    
    public function actionPaypalNotify()
    {
        $postdata="";
        foreach ($_POST as $key=>$value) $postdata.=$key."=".urlencode($value)."&";
        $postdata .= "cmd=_notify-validate";
        
        $curl = curl_init("https://sandbox.paypal.com/cgi-bin/webscr?");
        curl_setopt ($curl, CURLOPT_HEADER, 0);
        curl_setopt ($curl, CURLOPT_POST, 1);
        curl_setopt ($curl, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 1);
        $response = curl_exec ($curl);
        curl_close ($curl);
        if ($response != "VERIFIED") {
            die("DOES NOT VIRIFIED");
        }
        $tmp = explode(',', $_POST['custom']);
        $client_id = $tmp[0];
        $session_key = $tmp[1];
        $account_id = $tmp[2];
        
        $buisness_email = RaspConfig::getBuisnessEmail($account_id);
        $currency = RaspConfig::getCurrency($account_id);
        
        $client = Clients::getClient($client_id, 'credit_cards');
        
        if($buisness_email !== $_POST['receiver_email'])
        {
            $subject = "Paypal IPN transaction FAILED";
            //To user
            $body = "Your Paypal System failed due to reason:<br>
                     Receiver email is not equal";
            $emailaddress = $client->emailaddress;
            PsyMailer::send($emailaddress, $subject, $body);

            //To admin
            $body = "Login: ".$client->login."<br>
                     Full name: ".$client->name."<br>
                     Email".$client->emailaddress."<br>
                     Price: \$".$_POST['mc_gross']."<br>
					 BMT: ".$bmt_yes_no.".<br>
                     Paypal System failed due to reason:<br>
                     Receiver email is not equal";
            $emailaddress = Yii::app()->params['adminEmail'];
            PsyMailer::send($emailaddress, $subject, $body);
            die('Invalid email');
        }

        // Txn type checking
        if ($_POST["txn_type"] != "web_accept") {
            // Send failed email
            $subject = "Paypal IPN transaction FAILED";
            //To user
            $body = "Your Paypal System failed due to reason:<br>
                     Txn type is not equal";
            $emailaddress = $client->emailaddress;
            PsyMailer::send($emailaddress, $subject, $body);
            //To admin
            $body = "Login: ".$client->login."<br>
                     Full name: ".$client->name."<br>
                     Email".$client->emailaddress."<br>
                     Price: \$".$_POST['mc_gross']."<br>
					 BMT: ".$bmt_yes_no.".<br>
                     Paypal System failed due to reason:<br>
                     Txn type is not equal";
            $emailaddress = Yii::app()->params['adminEmail'];
            PsyMailer::send($emailaddress, $subject, $body);
            die('Invalid txn_type');
        }

        if($currency != $_POST['mc_currency'])
        {
            // Send failed email
            $subject = "Paypal IPN transaction FAILED";
            //To user
            $body = "Your Paypal System failed due to reason:<br>
                     Currency is not equal";
            $emailaddress = $client->emailaddress;
            PsyMailer::send($emailaddress, $subject, $body);
            //To admin
            $body = "Login: ".$client->login."<br>
                     Full name: ".$client->name."<br>
                     Email".$client->emailaddress."<br>
                     Price: \$".$_POST['mc_gross']."<br>
					 BMT: ".$bmt_yes_no.".<br>
                     Paypal System failed due to reason:<br>
                     Currency is not equal";
            $emailaddress = Yii::app()->params['adminEmail'];
            PsyMailer::send($emailaddress, $subject, $body);
            die("Currency is not equal");
        }

        $order_numb = "PayPal: ".$_POST['txn_id'];
        
        $check_txn = Deposits::checkTxnExist($order_numb);
        if($check_txn)
            die('This TXN is already exists');
        
        // All clear
        $dep_model = new Deposits();
        $dep_model->Client_id = $client_id;
        $dep_model->Amount = $_POST['mc_gross'];
        $dep_model->Currency = $_POST['mc_currency'];
        $dep_model->Order_numb = $order_numb;
        $dep_model->Notes = 'psychat';
        $payment_loc_id = $dep_model->addDeposit($bmt);
		
        $dep_model->affiliatePay($client->credit_cards->firstname,
                                 $client->credit_cards->lastname,
                                 $client->login,
                                 $_POST['txn_id'],
                                 $client->affiliate,
                                 $grp_id,
                                 $_POST['mc_gross']);
       
        // Calculate additional minutes
        $tariffs = Tariff::loadChatOptions();
        foreach($tariffs as $tariff) {
            if($tariff['amount'] == $_POST['mc_gross'])
                $minutes = $tariff['minutes'];
        }
        if($_POST['mc_gross'] > 19.95)
            $minutes += PsyConstants::getName(PsyConstants::CHAT_EXTRA_MINUTES);

        // Add minutes to user's balance
        $client->balance += $minutes;
        $client->save();
        
        // Admin email
        $body = date('h:m:s')."\n
                       Client (Id: ".$client_id.", Username: ".$client->login.")\n".
                       $client->name."\n
                       \$".$_POST['mc_gross']." (".$minutes." minutes)\n".
                       $client->gender."\n
                       Credit Card: PayPal\n\n
                       Order #:".$payment_loc_id."-".$client->affiliate."\n
                       Address: ".$client->address."\n
                       E-mail address: ".$client->emailaddress."\n
                       Phone #: ".$client->phone."\n
					   BMT: ".$bmt_yes_no.".\n
                       Sign up date: ".$client->rr_createdate."\n".
                       $pending_reason;
        $subject = "PSYCHAT - Successful Transaction (".$client->login.")";

        PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $body);

        // Email to all readers
        $subject = "PSYCHAT - Client Chat Order (".$client->login.")";
        $body = date('h:m:s')."\n
                       Client (Id: ".$client_id.", Username: ".$client->login.")\n".
                       $client->name."\n
                       \$".$_POST['mc_gross']." (".$minutes." minutes)\n".
                       $client->gender."\n
                       Order #:".$payment_loc_id."-".$client->affiliate."\n
                       Sign up date: ".$client->rr_createdate."\n";
        PsyMailer::sendToAllReaders($subject, $body);

        // Email to client
        $subject = "PSYCHAT - Thank you!";
        $body = $client->name.",\n
                       Thank you for your order. Here are the details of your purchase.\n".
                       date('h:m:s')."\n
                       Client (Id: ".$client_id.", Username: ".$client->login.")\n".
                       $client->name."\n
                       \$".$_POST['mc_gross']." (".$minutes." minutes)\n".
                       $client->gender."\n
                       Credit Card: PayPal\n\n
                       Order #:".$payment_loc_id."-".$client->affiliate."\n
                       Address: ".$client->address."\n
                       E-mail address: ".$client->emailaddress."\n
                       Phone #: ".$client->phone."\n
                       Sign up date: ".$client->rr_createdate."\n";
        PsyMailer::send($client->emailaddress, $subject, $body);
    }
    
    public function actionSignup()
    {
        $model = new Signup();
        
        $ps_type = Yii::app()->request->getParam('ps_type');
        $account_id = Yii::app()->request->getParam('account_id');
        $currency = Yii::app()->request->getParam('currency');
        
        if (isset($_POST['Signup']))
        {
            foreach ($_POST['Signup'] as $key => $value)
            {
                $model->$key = $value;
            }
            if (!$model->validate())
            {
                $err = $model->getErrors();                
                $this->render('signup', array(
                    'mod' => $model,
                    'errors' => $err,
                    'return_url' => $_POST['return_url']));
                return;
            } else
            {
                $reg = $model->registerUser($ps_type, $account_id, $currency);
                if (!is_bool($reg))
                {
                    if ($reg == 'banned')
                    {
                        $this->redirect('moreAccount');
                        return;
                    }
                    $this->render('signup', array(
                        'mod' => $model, 
                        'errors' => $reg,
                        'return_url' => $_POST['return_url']
                    ));
                    return;
                } else
                {
                    $this->redirect($model->site_url.'site/registerResult');
                    return;
                }
            }
        }
        else
        {
            $model->login = $_POST['login'];
            $model->email = $_POST['email'];
            $model->email_confirm = $_POST['email'];
            $model->firstname = $_POST['first_name'];
            $model->lastname = $_POST['last_name'];
            $model->gender = $_POST['gender'];
            $model->hear = $_POST['hear'];
            $model->dob_month = $_POST['dob_month'];
            $model->dob_day = $_POST['dob_day'];
            $model->dob_year = $_POST['dob_year'];
            $model->affiliate = $_POST['affiliate'];
            $model->site_type = $_POST['site_type'];
            $model->site_url = $_POST['site_url'];
            $model->site_name = $_POST['site_name'];
            $model->admin_email = $_POST['admin_email'];
            $model->debug = $_POST['debug'];
        }
        
        $postStr = urlencode(serialize($_POST));
        
        if(empty($_POST['login']) || empty($_POST['email']) 
                || empty($_POST['gender']) || empty($_POST['dob_month']) 
                || empty($_POST['dob_day']) || empty($_POST['dob_year']))
            $this->redirect($_POST['return_url'].'?error=empty_data&postData='.$postStr);
        
        $exsist = Clients::getByLoginOrEmail($_POST['login']);        
        if($exsist)
            $this->redirect($_POST['return_url'].'?error=duplicate_login&postData='.$postStr);
        
        $exsist = Clients::getByLoginOrEmail($_POST['email']);
        if($exsist)
            $this->redirect($_POST['return_url'].'?error=duplicate_email&postData='.$postStr);    
        
        // Email to admin about success first step
        $subject = "New Sign Up Step 1: ".$model->affiliate;
        $body = "Real First Name: Affiliate ".$model->firstname."<br>
            Login Username: ".$model->login."<br>
            Email: ".$model->email."<br>
            Hear: ".$model->hear."<br>
            Gender: ".$model->gender."<br>
            DOB: ".$model->dob_month."/".$model->dob_day."/".$model->dob_year."<br><br>
            From ".$model->site_name.", ".$model->site_url."<br>
            Site type: ".$model->site_type;
        PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $body);
        // ---
        $this->pageTitle = 'Signup';
        $this->render('signup', array('postData' => $_POST, 'mod' => $model, 'return_url' => $_POST['return_url']));
    }
    
    public function actionEmailreadings()
    {  
        $tariff['reader_spec'] = Tariff::loadEmailReadingPriceList(); // List of Reader and Special price
        $tariff['question'] = Tariff::loadEmailReadingTariffsList(); // List of question prices (1,2,3 etc...)
        $affiliate = $_COOKIE['Affiliate'];

        $model = new EmailQuestions();
        if($_POST['EmailQuestions'])
        {            
            foreach ($_POST['EmailQuestions'] as $key => $value)
                $model->$key = $value;
        }
        
        if (isset($_POST['payment_method']))
        {
            $model->payment_method = $_POST['payment_method'];
            $model->client_id = Yii::app()->user->id;
            $model->billingfirstname = $_POST['billingfirstname'];
            $model->billinglastname = $_POST['billinglastname'];
            $model->billingaddress = $_POST['billingaddress'];
            $model->billingcity = $_POST['billingcity'];
            $model->billingstate = $_POST['billingstate'];
            $model->billingzip = $_POST['billingzip'];
            $model->billingcountry = $_POST['billingcountry'];
            $model->cardnumber = $_POST['cardnumber'];
            $model->month = $_POST['month'];
            $model->year = $_POST['year'];
            $model->cvv = $_POST['cvv'];
            $model->affiliate = $_POST['affiliate'];

            
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
                $model->b_reading_type = $_POST['EmailQuestions']['b_reading_type'];
                $this->render('emailreadings', array('model' => $model, 'tariffs' => $tariff,
                    'affiliate' => $affiliate, 'return_url' => $_POST['return_url'], 'errors' => $err));
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
                        $model->b_reading_type = $_POST['EmailQuestions']['b_reading_type'];
                        $this->render('emailreadings', array('model' => $model, 'tariffs' => $tariff,
                            'affiliate' => $affiliate, 'return_url' => $_POST['return_url'], 'errors' => $resp));
                    } 
                    else
                    {
                        $this->redirect($_POST['return_url'].'?success=1');
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
        } else
            $this->render('emailreadings', array('model' => $model, 'tariffs' => $tariff,
                'affiliate' => $affiliate, 'return_url' => $_POST['return_url']));
    }    
}
?>
