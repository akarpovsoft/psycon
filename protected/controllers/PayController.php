<?php

class PayController extends PsyController
{
    
    public function filters($params = null)
    {
        $a = array( 
            'pageControlAccess + chat, emailreading, package'
        );
        return parent::filters($a);
    }

    /**
     * Checking user type and redirecting into different pages
     *
     */
    public function filterPageControlAccess($filterChain)
    {
        if (Yii::app()->user->isGuest)
        {
            $site = Yii::app()->session['site_type'];
            $this->redirect(Yii::app()->params['http_addr'] . 'site/login');
        }
        if (!isset(Yii::app()->user->type))
            $this->redirect(Yii::app()->params['http_addr'] . 'site/index');
        if (Yii::app()->user->type != 'client')
            $this->redirect(Yii::app()->params['http_addr'] . 'site/index');
        $filterChain->run();
    }
    
    
    public function actionChat()
    {
        if(Yii::app()->theme->getName() == 'newtheme')
            $this->layout = 'application.views.layouts.ssl.new_ssl_main';
        else
            $this->layout = 'ssl_main';
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
        if(!empty($cc_info->view1) && !empty($cc_info->view2))
            $encr_card = $cc_info->view1 . '*' . $cc_info->view2;
        else
            $encr_card = '';
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
                $this->render('chat', array('model' => $model, 'tariffs' => $tariffs,
                    'errors' => $err, 'card' => $encr_card));
            } else
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
                $model->tariff = Tariff::loadCurrentChatOptions($_POST['tariff']);
                $model->amount = $model->tariff['amount'];
                $model->client_id = Yii::app()->user->id;                
                $model->amount = ($model->cardnumber == '4005550000000019') ? '1.99' : $model->amount;

                // ECHO payment system
                if ($_POST['payment_method'] == "CreditCard")
                {
                    $resp_info = $model->pay();
                    
                    if(is_bool($resp_info))
                    {
                        $this->render('chat', array('model' => $model, 'tariffs' => $tariffs,
                            'success' => 1, 'card' => $encr_card));           
                    }
                    else
                    {
                        $model->cardnumber = "";
                        $this->render('chat', array('model' => $model, 'tariffs' => $tariffs,
                            'errors' => $resp_info, 'card' => $encr_card));
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
                    $postdata .= "custom=" . $model->user_id . "," . $model->session_key . "&";
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
            $this->render('chat', array('model' => $model, 'tariffs' => $tariffs,
                'card' => $encr_card));
        }
    }
    
    public function actionEmailreading()
    {
        if(Yii::app()->theme->getName() == 'newtheme')
            $this->layout = 'application.views.layouts.ssl.new_ssl_without_left_menu';
        else
            $this->layout = 'ssl_without_left_menu';
        
        if (isset($_GET['referal_link']))
        {
            $refer = 'http://'.$_GET['referal_link'];
        }
        
        // Loading reading tariffs
        $tariff['reader_spec'] = Tariff::loadEmailReadingPriceList(); // List of Reader and Special price
        $tariff['question'] = Tariff::loadEmailReadingTariffsList(); // List of question prices (1,2,3 etc...)
        $affiliate = $_COOKIE['Affiliate'];
        
        $client = Clients::getClient(Yii::app()->user->id, 'credit_cards');
        $encr_card = $client->credit_cards->view1.'*'.$client->credit_cards->view2;
        
        $readers = Readers::getEreadingsReadersList();
        
        $model = new EmailQuestions();
        
        $model->first_name = $model->billingfirstname = $client->credit_cards->firstname;
        $model->last_name = $model->billinglastname = $client->credit_cards->lastname;
        $model->r_contact_street_address_1 = $model->billingaddress = $client->credit_cards->billingaddress;
        $model->t_contact_city = $model->billingcity = $client->credit_cards->billingcity;
        $model->u_contact_state_or_province = $model->billingstate = $client->credit_cards->billingstate;
        $model->v_contact_country = $model->billingcountry = $client->credit_cards->billingcountry;
        $model->z_contact_email_address = $client->emailaddress;
        $model->c_sex = $client->gender;
        $model->billingzip = $client->credit_cards->billingzip;
        $model->d_date_of_birth_Month1 = "MM";
        $model->e_date_of_birth_Date2 = "DD";
        $model->f_date_of_birth_YR3 = "YYYY";
        $model->j_numerology_name = "Your Numerology Name";
        $model->k_numerology_date = "Date of Birth";
        
        $packages = Packages::getPackageByClientId(Yii::app()->user->id);
        if($packages && $client->emailreadings > 0)
        {
            $model->payment_method = 'Package';
        }
        
        if (isset($_POST['EmailQuestions']))
        {
            foreach ($_POST['EmailQuestions'] as $key => $value)
            $model->$key = $value;           
            
            $current_tariff = new Tariff();
            $price = $current_tariff->loadEmailReadingPrice($_POST['reader_id'], $_POST['b_reading_type']);
            $model->price = $price;
            $model->reader_id = $_POST['reader_id'];
            $model->b_reading_type = $_POST['b_reading_type'];
            if ($_POST['b_reading_type'] == "SPECIAL")
            {
                $model->b_reading_type = $price;
                $model->spec = $price;
            } else
            {
                $model->b_reading_type = $price;
                $model->spec = '0.00';
            }
            if ($_POST['cc'] == true)
            {
                $hash2 = CreditCard::getCard(Yii::app()->user->id);
                $model->cardnumber = $client->credit_cards->view1 . $hash2 . $client->credit_cards->view2;
                $model->month = $client->credit_cards->month;
                $model->year = $client->credit_cards->year;
            }
            
            // If some errors
            if (!$model->validate())
            {
                $err = $model->getErrors();
                //for ref sites
                if ($refer)
                {
                    if (is_array($err))
                    {
                        foreach ($err as $error)
                        {
                            $errorText .= $error[0] . ',,';
                        }
                    } else
                    {
                        $errorText = $err;
                    }

                    $this->redirect($refer . '?errors=' . $errorText);
                }
                $model->cardnumber = "";
                $this->render('emailreadings', array(
                    'model' => $model, 
                    'tariffs' => $tariff,
                    'affiliate' => $affiliate, 
                    'client' => $client, 
                    'card' => $encr_card, 
                    'readers' => $readers,
                    'errors' => $err,
                    'r_type' => $_POST['b_reading_type']));
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
                        $user_name = $row->name; // User full name
                    } else
                    {
                        $reader_email = $row->emailaddress; // Reader email address
                        $model->reader_name = $row->name; // Reader full name
                    }
                }
                // Using ECHO payment system
                if ($model->payment_method == "CreditCard")
                {
                    $resp_info = $model->sendReadingRequest();
                    
                    if (!is_bool($resp_info))
                    {
                        $model->cardnumber = "";
                        $this->render('emailreadings', array(
                            'model' => $model, 
                            'tariffs' => $tariff,
                            'affiliate' => $affiliate, 
                            'client' => $client, 
                            'card' => $encr_card,
                            'readers' => $readers,
                            'errors' => $resp_info));
                        return;
                    }                                        
                    else
                    {   
                        $readings = ($_POST['b_reading_type'] == 'SPECIAL') ? 1 : $_POST['b_reading_type'];
                        $client->emailreadings += $readings;
                        $client->save();
                        $this->render('emailreadings', array('model' => $model, 'tariffs' => $tariff,
                            'success' => 1));
                        return;
                    }
                }
                if($model->payment_method == 'Package')
                {
                    $model->registerPackageReading();
                    $readings = ($_POST['b_reading_type'] == 'SPECIAL') ? 1 : $_POST['b_reading_type']; 
                    $client->emailreadings -= $readings;
                    $client->save();
                    
                    $this->render('emailreadings', array('model' => $model, 'tariffs' => $tariff,
                            'success' => 1));
                    return;
                }
                // Using PayPal payment system
                if ($model->payment_method == "PayPal")
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
            $this->render('emailreadings', array(
                'model' => $model, 
                'tariffs' => $tariff,
                'affiliate' => $affiliate, 
                'client' => $client, 
                'card' => $encr_card,
                'readers' => $readers,
                'email_to' => $_GET['email_to'],
                ));
    }
    
    public function actionPackage()
    {        
        if(Yii::app()->theme->getName() == 'newtheme')
            $this->layout = 'application.views.layouts.ssl.new_ssl_main';
        else
            $this->layout = 'ssl_main';
        $tariffs = Tariff::loadPackagesList();
        
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
                $this->render('package', array(
                    'model' => $model, 
                    'tariffs' => $tariffs, 
                    'errors' => $err, 
                    'card' => $encr_card
                ));
            } else
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
                $model->tariff = Tariff::loadCurrentPackage($_POST['package_id']);
                $model->package = $_POST['package_id'];
                $model->amount = $model->tariff['price'];
                $model->client_id = Yii::app()->user->id;                
                $model->amount = ($model->cardnumber == '4005550000000019') ? '1.99' : $model->amount;

                // ECHO payment system
                if ($_POST['payment_method'] == "CreditCard")
                {
                    $resp_info = $model->pay();
                    
                    if(is_bool($resp_info))
                    {
                        $this->render('package', array('model' => $model, 'success' => 1, 'card' => $encr_card));           
                    }
                    else
                    {
                        $model->cardnumber = "";
                        $this->render('package', array(
                            'model' => $model, 
                            'errors' => $resp_info,
                            'tariffs' => $tariffs, 
                            'card' => $encr_card
                        ));
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
                    $postdata .= "custom=" . $model->user_id . "," . $model->session_key . "&";
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
            $this->render('package', array(
                'model' => $model, 
                'tariffs' => $tariffs, 
                'card' => $encr_card));
        }
    }
}
?>
