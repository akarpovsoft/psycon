<?php
/**
 * class Signup
 *
 * Chat signup model. Using to validate and save new client.
 *
 * @author  Den Kazka <den.smart[at]gmail.com>
 * @since   2010
 * @version $Id
 */
class Signup extends CFormModel {
    // User's basic info
    public $firstname;
    public $lastname;
    public $login;
    public $password;

    public $email;
    public $email_confirm;

    public $hear;
    public $website;
    public $affiliate;
    public $gift;

    public $dob_month;
    public $dob_year;
    public $dob_day;

    public $gender;

    public $address;
    public $city;
    public $state;
    public $zip;
    public $country;
    public $no_freebie;
    public $site_type;
    public $payment_method;
            
    // User's credit card info
    public $amount;
    public $cardnumber;
    public $exp_month;
    public $exp_year;
    public $cvv;
    public $empty_cvv;
    public $terms;

    private $second_abuser;
    private $body_warn;
    private $subj_warn;

    private $banned;
    
    // Debug mode - do not validate creditcard
    public $debug;
    // Data about RASP sites
    public $admin_email;
    public $site_url;
    public $site_name;
    // Captcha
    public $verifyCode;
    // Instance of Prereg model object
    public $_prereg;
    
    
    public function beforeValidate()
    {
        $captureValid = new CCaptchaValidator();
        $captureValid->attributes = array('verifyCode');
        $captureValid->validate($this);
        $errors = $this->getErrors();
        if(!empty($errors))
        {
            return false;
        }
        else
        {
            $event=new CModelEvent($this);
            $this->onBeforeValidate($event);
            return $event->isValid;
        }
    }
    
    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        $validators = array(
                //array('verifyCode', 'captcha', 'allowEmpty' => $this->site_type == 'SP'),
                array('firstname, lastname, login, password, email, dob_month,
                      dob_day, dob_year, address, city, state, zip, gender, country', 'required'),
                array('payment_method', 'PMCheck'),
                array('login', 'loginCheck'),
                array('email', 'emailCheck'),
                array('email', 'email'),
                array('password', 'passwordCheck'),
                array('dob_day, dob_year', 'type', 'type'=>'integer'),
                array('dob_month, dob_day, dob_year, gender', 'dobCheck'),
                array('dob_day, dob_month, dob_year, address, password', 'secondAbuserCheck'),
                array('site_name, site_url, admin_email', 'setSiteData')                
        );
        
        if($this->payment_method == 'CreditCard' || empty($this->payment_method))
        {
            $cc_val = array(
                array('cvv', 'validateCvv'),
                array('cardnumber', 'cardCheck'),
            );
            $validators = array_merge($validators, $cc_val);
        }

        return $validators;
    }

    public function attributeLabels() {
        return array(
                'firstname' => 'First name',
                'lastname' => 'Last Name',
                'login' => 'Username',
                'exp_month' => 'Card expirence month',
                'exp_year' => 'Card expirence year',
                'cvv' => 'Security code',
                'verifyCode' => 'Verification Code'
            );
    }
    /**
     * Checks login existing
     */
    public function loginCheck() {
        if(empty($this->gift)){
            if(!empty($this->login)) {
                $log = users::getUserByLogin($this->login);
                if(!empty($log))
                    $this->addError('login', 'Sorry, but this login is taken by another user');
                if(preg_match("/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/", $this->login))
                    $this->addError('login', 'Please don\'t use email address as Login');    
            }
        }
    }
    /**
     * Checking email existing and confirmation
     */
    public function emailCheck() {
        if(empty($this->gift)){
            if(!empty($this->email)) {
                $email = users::getUserByEmail($this->email);
                if(!empty($email))
                    $this->addError('email', 'The same email address is registered to another user');
                if($this->email != $this->email_confirm)
                    $this->addError('email_confirm', 'Email confirm failed');
            }
        }
    }
    
    public function PMCheck()
    {
        if(!isset($this->payment_method))
            $this->payment_method = 'CreditCard';
        if(empty($this->payment_method))
            $this->addError('payment_method', 'Payment method cannot be blank');
    }
    
    public function validateCvv(){
        if(($this->cvv == '')&&(!isset($this->empty_cvv))){
            $this->addError('cvv', 'CVV is empty. Pay attention to the checkbox below');
        }
    }

    /**
     * Checking banned user by DOB and too young users
     */
    public function dobCheck() {
//    	if((!is_int($this->dob_year))||(!is_int($this->dob_month))||(!is_int($this->dob_day))) {
//			$this->banned = 1;
//    		return;
//    	}
        if((!empty($this->dob_year))&&(!empty($this->dob_month))&&(!empty($this->dob_day))&&(!empty($this->gender))) {
            if((date('Y') - $this->dob_year) < 17)
                $this->addError('dob_year', 'This user is too young. DOB'.$this->dob_month.'/'.$this->dob_day.'/'.$this->dob_year);
            $sql = 'SELECT * FROM banned_dob
                  WHERE year = '.$this->dob_year.'
                        AND month = "'.$this->dob_month.'"
                        AND day = '.$this->dob_day.'
                        AND (gender = "'.$this->gender.'"
                            OR gender = "Both")';
            $connection = Yii::app()->db;
            $command = $connection->createCommand($sql);
            $res = $command->query();

            foreach($res as $r)
                if(isset($r['ID']))
                    $this->banned = 1;
        }
    }
    /**
     * Checking banned pwd
     */
    public function passwordCheck() {
        if(!empty($this->password)) {
            $sql = "SELECT `ID` FROM `banned_password`
                    WHERE  `password` = '".mysql_real_escape_string($this->password)."'";
            $connection = Yii::app()->db;
            $command = $connection->createCommand($sql);
            $res = $command->query();
            foreach($res as $r)
                if(isset($r['ID']))
                    $this->banned = 1;
        }
    }
    /**
     * Checking credit card exists
     */
    public function cardCheck() {
        if(empty($this->exp_month))
            $this->addError('exp_month', 'Card expirence month cannot be blank');
        if(empty($this->exp_year))
            $this->addError('exp_year','Card expirence year cannot be blank');
        if(empty($this->cardnumber))
            $this->addError('cardnumber', 'Cardnumber cannot be blank');
        else
        {
            $checker = CreditCard::checkNumber($this->cardnumber);
            if($checker == false)
                $this->addError('cardnumber', 'The Credit Card #**** **** **** '.substr($this->cardnumber, -4).' is already in the database');
        }
    }

    /**
     * Checking second abuser.
     * Check matches with existing users DOB, address or password.
     * This filter do not stop registration, but sends emails to admin and all users with warning message
     */
    public function secondAbuserCheck() {
        $sql = "SELECT rr_record_id, name, login, day, month, year, password FROM T1_1
                WHERE (((year = '".$this->dob_year."' and month = '".$this->dob_month."' and day = '".$this->dob_day."')
                        OR (address like '".mysql_real_escape_string($this->address)."%')
                        OR (password = '".mysql_real_escape_string($this->password)."'))
                    AND (name LIKE '%".mysql_real_escape_string($this->lastname)."%'))
                AND gender = '".mysql_real_escape_string($this->gender)."'
                LIMIT 0, 1";
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $res = $command->query();
        foreach($res as $r) {
            if(isset($r['rr_record_id'])) {
                $this->subj_warn = 'PSYCHAT - WARNING!  POS. 2nd account abuser!';
                if(($this->dob_month == $r['month'])&&($this->dob_day == $r['day'])&&($this->dob_year == $r['year'])) {
                    $this->body_warn = 'Reason: DOB '.$r['month'].' '.$r['day'].', '.$r['year'].'<br>
NEW Username: '.$this->login.' Real name: '.$this->firstname.' '.$this->lastname.'<br>
Matches: '.$r['login'].' Real name: '.$r['name'];
                } else {
                    $this->body_warn = 'Reason: Address or password '.$this->address.'<br>
NEW Username: '.$this->login.' Real name: '.$this->firstname.' '.$this->lastname.'<br>
Matches: '.$r['login'].' Real name: '.$r['name'];
                }
                $this->second_abuser = 1;                
            }
        }
    }
   
    /**
     * Set info about site
     * If this site is RASP like - this data is coming from registration form
     * Else - standart data for psycon site
     */
    public function setSiteData()
    {
        if(!$this->site_url) $this->site_url = 'www.psychic-contact.com';
        if(!$this->site_name) $this->site_name = 'Psychic-Contact';
    }
    /**
     * New client registration.
     */
    public function registerUser($ps_type = null, $account_id = null, $currency='USD') {
        if($this->banned == 1) {
            return 'banned';
        }
        
        if($this->payment_method == 'PayPal')
        {
            $this->_prereg->save();

            if($this->_prereg->id <= 0)
            {
                throw new Exception('Error prereg user');
            }
            $tar = Tariff::loadCurrentChatOptions($this->amount);
            $amount = $tar['amount'];
            
            $postdata = "";
            $postdata .= "cmd=_xclick&";
            $postdata .= "business=" . urlencode(Yii::app()->params['paypal_buisness_email']) .
                "&";
            $postdata .= "notify_url=" . urlencode(Yii::app()->params['http_addr'] .
                "service/signupIpn") . "&";
            $postdata .= "item_name=" . urlencode("Psychic-Contact Signup") . "&";
            $postdata .= "item_number=1&";
            $postdata .= "amount=" . $amount . "&";
            $postdata .= "rm=2&";
            $postdata .= "custom=" . $this->_prereg->id . "&";
            $postdata .= "on0=Affiliate&";
            $postdata .= "os0=" . $this->affiliate . "&";
            $postdata .= "first_name=" . $this->firstname . "&";
            $postdata .= "last_name=" . $this->lastname . "&";
            if (!empty($this->address))
                $postdata .= "adress_name=" . $this->address . "&";
            if (!empty($this->city))
                $postdata .= "adress_city=" . $this->city . "&";
            if (!empty($this->state))
                $postdata .= "adress_state=" . $this->state . "&";
            if (!empty($this->zip))
                $postdata .= "adress_zip=" . $this->zip . "&";
            if (!empty($this->country))
                $postdata .= "adress_country=" . $this->country . "&";
            $postdata .= "return=" . urlencode(Yii::app()->params['http_addr'] .
                "site/successPaypalSignup") . "&";
            $postdata .= "currency_code=USD&";
            $postdata .= "bn=" . urlencode('Psychic-contact');
            // Link with get parameters for paypal payment system
            $paypal_link = Yii::app()->params['paypal_url'] . '/cgi-bin/webscr?' . $postdata;
            //var_dump($paypal_link);
            //die();
            CController::redirect($paypal_link);
        }
        // Client's first payment
        if(empty($ps_type))
            $PSystem = PayAccount::getDefaultPS();
        else
            $PSystem = PayAccount::loadPaymentSystem($ps_type, $account_id, $currency);
        
        $ps_type = $PSystem->getType();
        $authOnly = $PSystem->supportsAuthOnly();
        
        // Loading info about payment tariff
        $tar = Tariff::loadCurrentChatOptions($this->amount);
        $amount = $tar['amount'];
        if($this->cardnumber == '4005550000000019')
            $amount = '1.99';
            
        if(!$authOnly) // no freebie for deposited payments
        	$this->no_freebie = false;
        	
        if(empty($this->no_freebie)) // if this is freebie tariff - we must add 10 minutes
            $minutes = $tar['minutes']+PsyConstants::getName(PsyConstants::CHAT_EXTRA_MINUTES);
        else $minutes = $tar['minutes'];
        $freeMinutes = (isset($tar['freeTime']))?$tar['freeTime']:0;

        // In debug mode balance = 0.00 always
        if($this->debug)
                $minutes = 0;
        
        // Check for canadians
        if(strtolower($this->country) == 'canada')
        {
            if(empty($this->affiliate))
                   $this->affiliate = 8;
            $this->site_type = 'CA';
        }
        $pay_data=array(
                "first_name" => $this->firstname,
                "last_name" => $this->lastname,
                "billing_address" =>$this->address,
                "billing_city" => $this->city,
                "billing_state" => $this->state,
                "billing_zip" => $this->zip,
                "billing_country" => $this->country,
                "billing_email" => $this->email,
                "cc_number" => $this->cardnumber,
                "grand_total" => $amount,
                "ccexp_month" => $this->exp_month,
                "ccexp_year" => $this->exp_year,
                "cnp_security" => $this->cvv,
                "client_id" => ''
        );
        
//        die('here');
//        $resp_info = $PSystem->authorize($pay_data);
        $resp_info = ($authOnly) ? $PSystem->authorize($pay_data) : $PSystem->authorizeAndCapture($pay_data);
        if ($PSystem->success || $this->debug) {
            // Register a new client
            $new_client = new Clients();
            $new_client->name = $this->firstname.' '.$this->lastname;
            $new_client->login = $this->login;
            $new_client->password = $this->password;
            $new_client->emailaddress = $this->email;
            $new_client->hear = $this->hear;
            $new_client->month = $this->dob_month;
            $new_client->day = $this->dob_day;
            $new_client->year = $this->dob_year;
            $new_client->gender = $this->gender;
            $new_client->address = $this->address.'\n'.$this->city.'\n'.$this->state.'\n'.$this->zip.'\n'.$this->country.'\n';
            $new_client->balance = $minutes;
            $new_client->affiliate = $this->affiliate;
            $new_client->site_type = $this->site_type;
            if(!empty($this->gift)) {
                $new_client->rr_record_id = $this->gift;
                $new_client->clientRegister();
                $new_client_id = $this->gift;
            } else
                $new_client_id = $new_client->clientRegister();

            //Add new transaction
            // In debug mode this addition is missing
//        	echo "debug=".strval($this->debug);
            if(!$this->debug)
            {
            	if($authOnly) {
//            		echo "1";
	                $trans_model = new Transactions();
	                $trans_model->UserID = $new_client_id;
	                $trans_model->ORDER_NUMBER = $resp_info['ORDER_NUMBER'];
	                $trans_model->AUTH_CODE = $resp_info['AUTH_CODE'];
	                $trans_model->TRAN_AMOUNT = $resp_info['TRAN_AMOUNT'];
	                $trans_model->merchant_trace_nbr = $resp_info['MERCHANT_TRACE_NBR'];
	                $trans_model->transaction_type = 0;
	                $trans_model->ps_type = $ps_type;
	                $trans_model->addTransaction();
            	} else {
//            		echo "2";
            		$dep_model = new Deposits();
            		$dep_model->Client_id = $new_client_id;
            		$dep_model->Amount = $resp_info['TRAN_AMOUNT'];
            		$dep_model->Order_numb = $ps_type.': '.$resp_info['ORDER_NUMBER'];
            		$dep_model->Notes = "psychat";
            		$dep_model->Currency = $PSystem->currency;
            		$dep_model->ps_type = $ps_type;
//            		echo "3";
            		$dep_model->addDeposit();
            	}
            }
//            echo "4";
//            die;
            // Checking and register or update new card in DB
            $cc = new CreditCard();
            $cc->rr_record_id = $new_client_id;
            $cc->amount = $amount;
            $cc->firstname = $this->firstname;
            $cc->lastname = $this->lastname;
            $cc->billingaddress = $this->address;
            $cc->billingcity = $this->city;
            $cc->billingstate = $this->state;
            $cc->billingzip = $this->zip;
            $cc->billingcountry = $this->country;
            $cc->month = $this->exp_month;
            $cc->year = $this->exp_year;  
            $cc->cardnumber = $this->cardnumber;
            $cc->registerNewUserCard();

            // Adding info about user's limits to db
            $limits = new ClientLimit();
            $limits->UserID = $new_client_id;
            $limits->UserName = $this->login;
            $limits->FullName = $this->firstname.' '.$this->lastname;
            $limits->IP = $_SERVER['REMOTE_ADDR'];
            $limits->registerNew();
/*
            if(empty($this->no_freebie)){
                // Adding into freetime remove table
                $freetime = new RemoveFreetime();
                $freetime->ClientID = $new_client_id;
                $freetime->Seconds = 600;
                $freetime->Total_sec = 600;
                $freetime->save();
            }
*/
            // Add free time if present
            if($freeMinutes>0) {
	            $freeTime = RemoveFreetime::getFreeTime($new_client_id);
    	        $freeTime->addFreeTime($freeMinutes*60);

	        	$body="Client id : ".$new_client_id."\r\n";
	        	$body.="Minutes : ".$minutes."\r\n";
	        	$body.="Free minutes : ".$freeMinutes."\r\n";
	            PsyMailer::send('karpovsoft@yandex.ru', "PSY DEBUG: ADV, SIGNUP, CCARD, FREE TIME(2)", $body);        	
            }

            // If second abuser warning defined
            if($this->second_abuser == 1) {
                $sql = "INSERT into notes_clients
                        (client, note, date, flag)
                        values('".$new_client_id."',
                               '".$this->subj_warn."<br>".$this->body_warn."',
                               now(),
                               '0')";
                $connection = Yii::app()->db;
                $command = $connection->createCommand($sql);
                $command->execute();
                // Email warning to client, admin and readers
                PsyMailer::sendToAllReaders($this->subj_warn, $this->body_warn);
                PsyMailer::send(Yii::app()->params['adminEmail'], $this->subj_warn, $this->body_warn);
            }
            // If not from RASP
            if(!$this->admin_email)
            $this->admin_email = 'support@psychic-contact.com';

            // Sending successfull registration emails to PSYCON admin
            $subject = (empty($this->no_freebie)) ? "PSYCHAT - New signup (FS)" : 'PSYCHAT - New signup (PAID)';
            $body = date("M j, Y h:i a", time())."\n<br>
Username: ".$this->login."\n<br>
DOB: ".$this->dob_month."/".$this->dob_day."/".$this->dob_year."\n<br>
First Name: ".$this->firstname."\n<br>
----------------------\n<br>
Affiliate: ".$this->affiliate."\n<br>
IP: ".$_SERVER['REMOTE_ADDR']."\n<br>";
            PsyMailer::sendToAllReaders($subject, $body);


            // Sending successfull registration email
            if(!$this->site_type)
                    $this->site_type = 'PSYCHAT';
            $subject = (empty($this->no_freebie)) ? $this->site_type." - New sign up(".$minutes." mins FS)" : $this->site_type.' - New sign ('.$minutes.' mins PAID)';
            $body = date("M j, Y h:i a", time())."\n<br>
                    Username: ".$this->login."\n<br>
                    DOB: ".$this->dob_month."/".$this->dob_day."/".$this->dob_year."\n<br>
                    First Name: ".$this->firstname."\n<br>
                    Last Name: ".$this->lastname."\n<br>
                    Gender: ".$this->gender."\n<br>
                    Address: ".$this->country.", ".$this->state.", ".$this->city.", ".$this->address."\n<br>
                    E-mail address: ".$this->email."\n<br>
                    Last 4 digits of the credit card: ".substr($this->cardnumber, -4)."\n<br>
                    IP: ".$_SERVER['REMOTE_ADDR']."\n<br>
                    ".$_SERVER['HTTP_USER_AGENT']."\n<br>
                    Affiliate ID: ".$this->affiliate."\n<br>
                    Referrer: ".$this->hear."\n<br>
                    Referrer2: ".$this->website."<br>
                    ECHO Order #: ".$resp_info['ORDER_NUMBER']."\n<br>
                    Amount : ".$resp_info['TRAN_AMOUNT'];
            // To PSYCON admin
            PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $body);
            // If client from RASP site - to RASP admin
            if($this->admin_email)
            PsyMailer::send($this->admin_email, $subject, $body);

            // To client
            $asterisk_pass = '';
            if(strlen($this->password)<=5)
                $asterisk_pass = '***** (Please contact Admin for info)';
            else
                $asterisk_pass = substr($this->password, 0, -4).'****';
            $body = "Dear ".$this->firstname." ".$this->lastname.",\r\n<br/>".
                    " \r\n<br/>".
                    "*** SAVE THIS MESSAGE *** \r\n\r\nIt is my pleasure to welcome you to ".$this->site_name." \r\n<br/>".
                    " \r\nBelow you will find your login information:\r\n<br/>".
                    "\r\n<br/>".
                    "Your UserID: ".$this->login." \r\n<br/>".
                    "   Password: ".$asterisk_pass." \r\n<br/>".
                    " \r\n<br/>".
                    "Login now and chat with our psychic readers, see what your future holds! \r\n<br/>".
                    " \r\n<br/>".
                    "Make it a great day! \r\n<br/>\r\n<br/>
                        ".$this->site_url." \r\n<br/>
                        Support Team \r\n<br/>
                        mailto:";
            $body .= ($this->admin_email) ? $this->admin_email : 'support@psychic-contact.com';
            $subject = 'Welcome to '.$this->site_name;
            PsyMailer::send($this->email, $subject, $body);
            // IF DEBUG TEST REGISTRATION MODE
            // send this email to additional addresses
            if($this->debug)
            {
                PsyMailer::send('mysticladydi@yahoo.com', $subject, $body);
                PsyMailer::send('rosie6807@gmail.com', $subject, $body);
            }
            return true;
        } else {
            // Sending fail email
            $subject = $this->site_type." - Failed Sign Up  (".$ps_type.")";
            $body = date("M j, Y h:i a", time())."<br>
                    DOB: ".$this->dob_month."/".$this->dob_day."/".$this->dob_year."<br>
                    First Name: ".$this->firstname."<br>
                    Last Name: ".$this->lastname."<br> 
                    Gender: ".$this->gender."<br>
                    Reason blocked: ".strip_tags($resp_info["ERROR_MESSAGE"])."<br>
                    ----------------------<br>
                    New Information Entered:<br>
                    name: ".$this->firstname." ".$this->lastname."<br>
                    login: ".$this->login."<br>
                    emailaddress: ".$this->email."<br>
                    hear: ".$this->hear."<br>
                    day: ".$this->dob_day."<br>
                    month: ".$this->dob_month."<br>
                    year: ".$this->dob_year."<br>
                    type: client<br>
                    Site: ".$this->site_type."<br>
                    address: ".$this->address." ".$this->state." ".$this->city." ".$this->zip." ".$this->country."<br>
                    password: ".$this->password."<br>
                    gender: ".$this->gender."<br>
                    affiliate: ".$this->affiliate."<br>
                    Last 4 digits of the credit card: ".substr($this->cardnumber, -4)."<br>
                    Expiry Date: ".$this->exp_month."/".$this->exp_year."<br>
                    IP: ".$_SERVER['REMOTE_ADDR']."<br>\n
                    Error Code: ".$resp_info["DECLINE_CODE"].'<br>\n
                    Site type: '.$this->site_type.'<br>';
            PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $body);
            if($this->admin_email)
                PsyMailer::send($this->admin_email, $subject, $body);
            
            return $resp_info['ERROR_MESSAGE'];
        }
    }
    
    /**
     * Initiate pre-registration information about client
     */
    public function preregInit()
    {
        $tar = Tariff::loadCurrentChatOptions($this->amount);
        if(empty($this->no_freebie)) // if this is freebie tariff - we must add 10 minutes
            $minutes = $tar['minutes']+PsyConstants::getName(PsyConstants::CHAT_EXTRA_MINUTES);
        else 
            $minutes = $tar['minutes'];
        
        $this->_prereg->firstname = $this->firstname;
        $this->_prereg->lastname = $this->lastname;
        $this->_prereg->login = $this->login;
        $this->_prereg->password  = $this->password;
        $this->_prereg->email = $this->email;
        $this->_prereg->hear = $this->hear;
        $this->_prereg->website = $this->website;
        $this->_prereg->month = $this->dob_month;
        $this->_prereg->day = $this->dob_day;
        $this->_prereg->year = $this->dob_year;
        $this->_prereg->gender = $this->gender;
        $this->_prereg->address = $this->address;
        $this->_prereg->city = $this->city;
        $this->_prereg->state = $this->state;
        $this->_prereg->zip = $this->zip;
        $this->_prereg->country = $this->country;
        $this->_prereg->balance = $minutes;
        $this->_prereg->affiliate = $this->affiliate;
        $this->_prereg->ip = $_SERVER['REMOTE_ADDR'];
        $this->_prereg->freebie = ($this->no_freebie) ? 0 : 1;
    }
}

?>
