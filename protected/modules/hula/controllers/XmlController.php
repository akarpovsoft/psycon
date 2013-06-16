<?php

class XmlController extends Controller
{
    const HULA_ADDR = 'http://hula.ph/';
    
    public function actionAddFunds()
    {
        $model = new AddFunds();
        $cc_info = CreditCard::getCardInfo($_POST['user_id']);
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
                $errstr = urlencode(serialize($err));
                
                $this->redirect(self::HULA_ADDR.'site/addfunds?error='.$errstr);
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
                $pay_amount = ($model->cardnumber == '4005550000000019') ? '1.99' : $model->amount;

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

                        $bmt = null;
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

                        // Admin email
                        $text_2_send = date('h:m:s') . "\n
							Client (ID: " . $_POST['user_id'] . "
							Username: " . $model->username . ")\n
							\$" . $model->amount . " (" . $minutes . " minutes)\n
							" . $model->gender . "\n
							Credit Card: ***" . substr($model->cardnumber, -4) . "\n
							CCV: " . $model->cvv . "\n
							Expiry Date: " . $model->month . "/" . $_POST['year'] . "\n
							Order #: " . $resp_info[ORDER_NUMBER] . "\n
							Affiliate: $model->affiliate\n
							Address: " . $client_address . "\n
							E-mail address: " . $client_email . "\n
							Phone #: " . $client_phone . "\n
							Sign up date: " . $model->signup . "\n
							IP: " . $_SERVER['REMOTE_ADDR'];

                        $subject = "PSYCHAT - Successful Authorization ($model->username) $bmtSubj";
                        PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $body);

                        // Email to all readers
                        $subject = "PSYCHAT - Incoming Chat Order (" . $model->username . ")";
                        $body = date('h:m:s') . "\n
							Client (ID: " . $_POST['user_id'] . "
							Username: " . $model->username . ")\n
							\$" . $model->amount . " (" . $minutes . " minutes)\n
							" . $model->gender . "\n
							Order #: " . $resp_info[ORDER_NUMBER] . "\n-" . $model->affiliate . "\n
							Sign up date: " . $model->signup . "\n
							IP: " . $_SERVER['REMOTE_ADDR'];
                        PsyMailer::sendToAllReaders($subject, $body);

                        // Email to client
                        $subject = "PSYCHAT - Thank you!";
                        $body = $client_name . ",\n
							Thank you for your order.\n\n
							Here are the details of your purchase.\n" . date('h:m:s') . "\n
							Your (ID: " . $_POST['user_id'] . ", Username: " . $model->username . ")\n
							" . $client_name . "\n
							\$" . $model->amount . " (" . $minutes . " minutes)\n" . $model->gender .
                            "\n
							Order #: " . $resp_info[ORDER_NUMBER] . "\n
							Address: " . $client_address . "\n
							E-mail address: " . $client_email . "\n
							Phone #: " . $client_phone . "\n
							Sign up date: " . $model->signup . "\n
							IP: " . $_SERVER['REMOTE_ADDR'] . "\n\n\n
							Please be sure to disable your POPUP Blockers before attempting to chat. You can read this page for assistance in disabling POPUPS for IE " .
                            Yii::app()->params['site_domain'] . "/chat/iesettings\n";
                        PsyMailer::send($client_email, $subject, $body);

                        $this->redirect(self::HULA_ADDR.'site/addFunds?success=1');
                        
                    } else
                    {
                        $subject = "PSYCHAT - Failed Authorization (" . $model->username . ", ECHO - " .
                            date('h:m:s') . ")";
                        $body = $resp_info["ERROR_MESSAGE"] . "\n
							Failed Authorization\n\n" . date('h:m:s') . "\n
							Client (Id: " . $_POST['user_id'] . ", Username: " . $model->username .
                            ")\n\$" . $model->amount . " (" . $minutes . " minutes)\n
							" . $model->gender . "\n
							Credit Card: ***" . substr($model->cardnumber, -4) . "\n
							CCV: " . $model->cvv . "\n
							Expiry Date: " . $model->month . "/" . $_POST['year'] . "\n
							Order #: " . $resp_info[ORDER_NUMBER] . "\n
							Affiliate: $model->affiliate\n
							Address: " . $client_address . "\n
							E-mail address: " . $client_email . "\n
							Phone #: " . $client_phone . "\n
							Sign up date: " . $model->signup . "\n
							IP: " . $_SERVER['REMOTE_ADDR'] . "\n
							Error Code: " . $resp_info["DECLINE_CODE"];
                        PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $body);
                        
                        $errors = array($resp_info["ERROR_MESSAGE"]);
                        $errstr = urlencode(serialize($errors));
                        
                        $this->redirect(self::HULA_ADDR.'site/addFunds?error='.$errstr);
                    }
                }

                // Paypal payment system
                if ($_POST['payment_method'] == "PayPal")
                {
                    // Post data (sends to paypal system)
                    $postdata = "";
                    $postdata .= "cmd=_xclick&";
                    $postdata .= "business=" . urlencode('orders@hula.com') .
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
    }
}