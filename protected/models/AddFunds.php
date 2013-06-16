<?php
/**
 * AddFunds class
 *
 * This model includes methods to checking and adding funds to user.
 *
 */
class AddFunds extends CFormModel {

    public $payment_method;

    public $tariff;
    public $package;
    public $minutes;

    public $username;   // Client login
    public $firstname;
    public $lastname;
    public $gender;     // Client gender
    public $affiliate;  // Client affiliate
    public $signup;     // Client create date
    public $cardnumber;
    public $month;
    public $year;
    
    public $email;
    public $address;
    public $phone;
    public $client_id;
    public $client_name;

    public $billingaddress;
    public $billingcity;
    public $billingstate;
    public $billingcountry;
    public $billingzip;
    public $cvv;
    public $empty_cvv;
    public $amount;
    public $order_numb;     // Transation order number

    public $user_id;

    public $session_id;     // Id of chat session
    public $session_reader; // Reader id fron chat session
	public $session_key;	// md5 hash of chat session
    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
                // payment_method are required
                array('payment_method,', 'required'),
                // cvv validation
                array('cvv', 'validateCvv'),
                // card number is only numerical value
                array('cardnumber', 'numerical', 'integerOnly'=>true),
                // form fields required logic
                array('firstname, lastname, cardnumber, month, year', 'validatePayment'),                
                // checking card register on another user
                array('cardnumber', 'validateCardExists'),
                // checking user's payment limit
                array('amount', 'validatePaymentLimit')
        );
    }

    public function validateCvv(){
        if(($this->cvv == '')&&(!isset($this->empty_cvv))&&($this->payment_method == 'CreditCard')){
            $this->addError('cvv', 'CVV is empty. Pay attention to the checkbox below');
        }
    }
    /**
     * Validates empty fields in dependent from payment method
     */
    public function validatePayment() {
        if($this->payment_method == 'CreditCard') {
            if(empty($this->firstname))
                $this->addError('firstname', Yii::t('lang', 'email_readings_error_2'));
            if(empty($this->lastname))
                $this->addError('lastname', Yii::t('lang', 'email_readings_error_3'));
            if(empty($this->cardnumber))
                $this->addError('cardnumber', Yii::t('lang', 'addfunds_7'));
            if((empty($this->month))||(empty($this->year)))
                $this->addError('month', Yii::t('lang', 'email_readings_error_5'));
            if(($this->cardnumber == '4005550000000019')&&($this->user_id != '9615'))
                $this->addError('month', 'Invalid card number');
        }
        if($this->payment_method == 'PayPal') {
            if(empty($this->firstname))
                $this->addError('firstname', Yii::t('lang', 'email_readings_error_2'));
            if(empty($this->lastname))
                $this->addError('lastname', Yii::t('lang', 'email_readings_error_3'));
        }
    }
    /**
     * Validates credit card registered to another user
     */
    public function validateCardExists() {
        if(!empty($this->cardnumber)) {
            $card_check = CreditCard::check($this->cardnumber, $this->user_id);
            if($card_check == false)
                $this->addError('cardnumber', 'This card is already registered to another user');
            $card_check = CreditCard::transactionCheck($this->cardnumber, $this->user_id);
            if($card_check == false)
                $this->addError('cardnumber', 'Current transaction registered to another card number');
        }
    }
    /**
     * Validates user payments limits
     */
    public function validatePaymentLimit() {
		if(!empty($this->tariff) && !$this->package){
        	$tar = Tariff::loadCurrentChatOptions($this->tariff);
        	$amount = $tar['amount'];
        	$limit_check = ClientLimit::testLimit($this->user_id, $amount);
        	if($limit_check == false)
        	$this->addError('amount', 'Transaction Declined: You\'ve reached your Psychic Contact limit!<br><br>
                    Please Try Again or contact us at:
                    <a href="mailto:'.Yii::app()->params['adminEmail'].'" class=LinkMedium>'.Yii::app()->params['adminEmail'].'</a>');
        }
    }

    public function pay($ps_type = null, $account_id = null, $currency='USD')
    {
        if(is_null($ps_type))
            $PSystem = PayAccount::getDefaultPS();
        else
            $PSystem = PayAccount::loadPaymentSystem($ps_type, $account_id, $currency);
        
        $ps_type = $PSystem->getType();
        $authOnly = $PSystem->supportsAuthOnly();
        
        $extraClientInfo = ClientLimit::getUserInfo($this->user_id);
        $row = Clients::getClient($this->client_id);
        
        $this->minutes = $this->tariff['minutes'];
        $freeMinutes = (isset($this->tariff['freeTime']))?$this->tariff['freeTime']:0;

//        if($this->client_id==9615)
//        	die("=".$freeMinutes);
        	
        $pay_data = array("first_name" => $this->firstname, 
            "last_name" => $this->lastname, 
            "billing_address" => $this->billingaddress, 
            "billing_city" => $this->billingcity, 
            "billing_state" => $this->billingstate, 
            "billing_zip" => $this->billingzip, 
            "billing_country" => $this->billingcountry, 
            "billing_email" => $this->email,
            "cc_number" => $this->cardnumber, 
            "grand_total" => $this->amount,
            // Payment amount
            "ccexp_month" => $this->month, "ccexp_year" => $this->year, 
            "cnp_security" =>$this->cvv, 
            "client_id" => $this->client_id
        );
        
        $resp_info = ($authOnly) ? $PSystem->authorize($pay_data) : $PSystem->authorizeAndCapture($pay_data);
        $date_time = date("M j, Y h:i a");
        if ($PSystem->success)
        {
            $billingdata = array();
            $billingdata['address'] = $this->billingaddress;
            $billingdata['city'] = $this->billingcity;
            $billingdata['state'] = $this->billingstate;
            $billingdata['zip'] = $this->billingzip;
            $billingdata['country'] = $this->billingcountry;
            $billingdata['exp_month'] = $this->month;
            $billingdata['exp_year'] = $this->year;
            // Checking and register or update new card in DB
            CreditCard::registerNewCard($this->cardnumber, $this->client_id, $client_name, $billingdata);
            // add payment to limits
            ClientLimit::registerPayment($this->user_id, $this->amount);

            $bmt = "";
            if (!empty($this->session_id))
            {
                $this->order_numb = $resp_info['ORDER_NUMBER'];
                try {
                    $bmt = $this->processBMT();
                } catch (Exception $e) {}
                
            }
            
            if($authOnly){
                //Add new transaction
                $trans_model = new Transactions();
                $trans_model->UserID = Yii::app()->user->id;
                $trans_model->ORDER_NUMBER = $resp_info['ORDER_NUMBER'];
                $trans_model->AUTH_CODE = $resp_info['AUTH_CODE'];
                $trans_model->TRAN_AMOUNT = $resp_info['TRAN_AMOUNT'];
                $trans_model->merchant_trace_nbr = $resp_info['MERCHANT_TRACE_NBR'];
                $trans_model->transaction_type = 0;
                $trans_model->ps_type = $ps_type;
                $trans_model->Total_time = $this->minutes * 60;
                
                $trans_model->addTransaction($bmt);
            } 
            else
            {
                $dep_model = new Deposits();
                $dep_model->Client_id = Yii::app()->user->id;
                $dep_model->Amount = $resp_info['TRAN_AMOUNT'];
                $dep_model->Order_numb = $ps_type.': '.$resp_info['ORDER_NUMBER'];
                $dep_model->Notes = "psychat";
                $dep_model->Currency = $PSystem->currency;;
                $dep_model->Ps_type = $ps_type;
                $dep_model->addDeposit();
            }
            // Add minutes to user's balance
            $row->balance += $this->minutes;            
            if($this->package)
            {
                // Insert info about package purchase
                $package = new Packages();
                $package->client_id = Yii::app()->user->id;
                $package->package_id = $this->package;
                $package->save();
                
                $row->emailreadings += $this->tariff['readings'];
                $row->astrology += $this->tariff['astrology'];
            }
            $row->save();
            // Add free time if present
            if($freeMinutes>0) {
	            $freeTime = RemoveFreetime::getFreeTime($this->client_id);
    	        $freeTime->addHalfPaidTime($freeMinutes*60);

	        	$body="Client id : ".$this->client_id."\r\n";
	        	$body.="Minutes : ".$this->minutes."\r\n";
	        	$body.="Free minutes : ".$freeMinutes."\r\n";
	            PsyMailer::send('karpovsoft@yandex.ru', "PSY DEBUG: ADV, REPEATER, CCARD, FREE TIME(2)", $body);        	
            }
            
            // Admin email
            $body = $date_time . "\n<br/>
                        Client (ID: " . $this->client_id . "
                        Username: " . $this->username . ")\n<br/>
                        ".$client_name."\n<br/>
                        \$" . $this->amount . " (" . $this->minutes . " minutes)\n<br/>
                        " . $this->gender . "\n<br/>
                        Credit Card: ***" . substr($this->cardnumber, -4) . "\n<br/>
                        CCV: " . $this->cvv . "\n<br/>
                        Expiry Date: " . $this->month . "/" . $this->year . "\n<br/>
                        Order #: " . $resp_info['ORDER_NUMBER'] . "\n<br/>
                        Affiliate: $this->affiliate\n<br/>
                        Address: " . $this->address . "\n<br/>
                        E-mail address: " . $this->email . "\n<br/>
                        Phone #: " . $this->phone . "\n<br/>
                        Sign up date: " . $this->signup . "\n<br/>
                        IP: " . $_SERVER['REMOTE_ADDR']."\n<br/>
                        Reader (if BMT): ".$bmt;

            $subject = "PSYCHAT - Successful Authorization ($this->username)";
            PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $body);

            // Email to all readers
            $subject = "PSYCHAT - Incoming Chat Order (" . $this->username . ")";
            $body = $date_time . "\n<br/>
                        Client (ID: " . $this->client_id . "
                        Username: " . $this->username . ")\n<br/>
                        \$" . $this->amount . " (" . $this->minutes . " minutes)\n<br/>
                        " . $this->gender . "\n<br/>
                        Order #: " . $resp_info['ORDER_NUMBER'] . "\n-" . $this->affiliate . "\n<br/>
                        Sign up date: " . $this->signup . "\n
                        IP: " . $_SERVER['REMOTE_ADDR'];
            PsyMailer::sendToAllReaders($subject, $body);

            // Email to client
            $subject = "PSYCHAT - Thank you!";
            $body = $client_name . ",\n<br/>
                        Thank you for your order.\n<br/>\n<br/>
                        Here are the details of your purchase.\n" . $date_time . "\n<br/>
                        Your (ID: " . $this->client_id . ", Username: " . $this->username . ")\n<br/>
                        " . $client_name . "\n<br/>
                        \$" . $this->amount . " (" . $this->minutes . " minutes)\n<br/>" . $this->gender .
                        "\n<br/>
                        Order #: " . $resp_info['ORDER_NUMBER'] . "\n<br/>
                        Address: " . $this->address . "\n<br/>
                        E-mail address: " . $this->email . "\n<br/>
                        Phone #: " . $this->phone . "\n<br/>
                        Sign up date: " . $this->signup . "\n<br/>\n<br/>\n<br/>
                        Please be sure to disable your POPUP Blockers before attempting to chat. You can read this page for assistance in disabling POPUPS for IE " .
                Yii::app()->params['site_domain'] . "/chat/iesettings\n<br/>";
            PsyMailer::send($client_email, $subject, $body);
            
            return true;
        }
        else
        {
            $subject = "PSYCHAT - Failed Authorization (" . $this->username . ", ".$ps_type." - " . $date_time . ")";
            $body = $resp_info["ERROR_MESSAGE"] . "\n<br/>
                        Failed Authorization\n\n" . $date_time . "\n<br/>
                        Client (Id: " . $this->client_id . ", Username: " . $this->username .
                        ")\n<br/>\$" . $this->amount . " (" . $this->minutes . " minutes)\n<br/>
                        " . $this->gender . "\n<br/>
                        Credit Card: ***" . substr($this->cardnumber, -4) . "\n<br/>
                        CCV: " . $this->cvv . "\n<br/>
                        Expiry Date: " . $this->month . "/" . $_POST['year'] . "\n<br/>
                        Order #: " . $resp_info['ORDER_NUMBER'] . "\n<br/>
                        Affiliate: $this->affiliate\n<br/>
                        Address: " . $this->address . "\n<br/>
                        E-mail address: " . $this->email . "\n<br/>
                        Phone #: " . $this->phone . "\n<br/>
                        Sign up date: " . $this->signup . "\n<br/>
                        IP: " . $_SERVER['REMOTE_ADDR'] . "\n<br/>
                        Error Code: " . $resp_info["DECLINE_CODE"];
            PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $body);

            return $resp_info["ERROR_MESSAGE"];            
        }
    }
    
	/**
     * Add info about user chat add funds from active chatting
     *
     * @return string Reader name to add transaction info (BMT)
     */
    public function processBMT()
	{
		Yii::app()->getModule('chat');
		
		$chatCommander = new ChatCommander(ChatContext::getContext($this->session_key, ChatContext::CLIENT));
		$chatCommander->registerBMT($this->minutes);

        $reader = Readers::getReader($this->session_reader);
        //send email to reader
        $body =
			"date('h:m:s')\n\n<br>
			Order Received for Client (ID: ".$this->user_id.", Username: ".$this->username.")\n<br>
			$".$this->amount." (".$this->minutes." minutes)\n<br>".
			$this->gender."\n<br>
			Order #: ".$this->order_numb."-".$this->affiliate."\n<br>
			Sign up date: ".$this->signup."\n<br>
			IP: ".$_SERVER['REMOTE_ADDR']."\n<br>
			Reader(if BMT): ".$reader->login;
        $subject = "PSYCHAT - Incoming Chat Order (".$this->username.")";
        PsyMailer::send($reader->emailaddress, $subject, $text_2_send);
        return $reader->name;
    }
    
}
?>
