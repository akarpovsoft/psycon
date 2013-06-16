<?php
/**
 * Controller of PayPal IPN scripts
 *
 * @author  Den Kazka <den.smart[at]gmail.com>
 * @since   2010
 * @version $Id
 */
class ServiceController extends PsyController {
    /**
     * PayPal IPN action for email readings
     *
     */
    public function actionPaypalIpn() {
        /**
         * Received data verification
         */
        $postdata="";
        foreach ($_POST as $key=>$value) $postdata.=$key."=".urlencode($value)."&";
        $postdata .= "cmd=_notify-validate";
        $curl = curl_init(Yii::app()->params['paypal_url']."/cgi-bin/webscr");
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
        
        // Duplicate transaction checking
        $dep = new Deposits();
        $res = $dep->findAll(array(
                'condition'  => 'Order_numb LIKE :order_numb',
                'params'     => array(':order_numb' => '%'.$_POST['txn_id'].'%')
        ));
        if(!empty($res)) {
            die("Already registered");
        }

        $model = new EmailQuestions();
        $question = $model->findByPk($_POST['custom']);

        $u_model = new users();
        $reader = $u_model->findByPk($question->reader_id);
        
        $client = Clients::getClient($question->client_id, 'credit_cards');

        // Buisness email checking
        if ($_POST['receiver_email'] != Yii::app()->params['paypal_buisness_email']) {
            // Send failed email
            $subject = "Paypal IPN transaction FAILED";
            //To user
            $body = "Your Paypal System failed due to reason:<br>
                     Receiver email is not equal";
            $emailaddress = $question->z_contact_email_address;
            PsyMailer::send($emailaddress, $subject, $body);
            //To admin
            $body = "First name: ".$question->first_name."<br>
                     Last name: ".$question->first_name."<br>
                     Email".$question->z_contact_email_address."<br>
                     Topic: ".$question->l_topic."<br>
                     Price: \$".$question->spec."<br>
                     Reader: ".$reader->name."<br>
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
            $emailaddress = $question->z_contact_email_address;
            PsyMailer::send($emailaddress, $subject, $body);
            //To admin
            $body = "First name: ".$question->first_name."<br>
                     Last name: ".$question->last_name."<br>
                     Email".$question->z_contact_email_address."<br>
                     Topic: ".$question->l_topic."<br>
                     Price: \$".$question->spec."<br>
                     Reader: ".$reader->name."<br>
                     Paypal System failed due to reason:<br>
                     Txn type is not equal";
            $emailaddress = Yii::app()->params['adminEmail'];
            PsyMailer::send($emailaddress, $subject, $body);
            die('Invalid txn_type');
        }

        // Pay currency checking
        if($_POST['mc_currency'] != 'USD') {
            // Send failed email
            $subject = "Paypal IPN transaction FAILED";
            //To user
            $body = "Your Paypal System failed due to reason:<br>
                     Currency is not equal";
            $emailaddress = $question->z_contact_email_address;
            PsyMailer::send($emailaddress, $subject, $body);
            //To admin
            $body = "First name: ".$question->first_name."<br>
                     Last name: ".$question->last_name."<br>
                     Email".$question->z_contact_email_address."<br>
                     Topic: ".$question->l_topic."<br>
                     Price: \$".$question->spec."<br>
                     Reader: ".$reader->name."<br>
                     Paypal System failed due to reason:<br>
                     Currency is not equal";
            $emailaddress = Yii::app()->params['adminEmail'];
            PsyMailer::send($emailaddress, $subject, $body);
            die("CURRENCY IS NOT EQUAL");
        }

        //Pay amount checking
        if($question->b_reading_type !== $_POST['mc_gross']) {
            // Send failed email
            $subject = "Paypal IPN transaction FAILED";
            //To user
            $body = "Your Paypal System failed due to reason:<br>
                     Price is not equal";
            $emailaddress = $question->z_contact_email_address;
            PsyMailer::send($emailaddress, $subject, $body);
            //To admin
            $body = "First name: ".$question->first_name."<br>
                     Last name: ".$question->last_name."<br>
                     Email".$question->z_contact_email_address."<br>
                     Topic: ".$question->l_topic."<br>
                     Price: \$".$question->spec."<br>
                     Reader: ".$reader->name."<br>
                     Paypal System failed due to reason:<br>
                     Price is not equal";
            $emailaddress = Yii::app()->params['adminEmail'];
            PsyMailer::send($emailaddress, $subject, $body);
            die("AMOUNT IS NOT EQUAL");
        }

        // If all clear add deposit and changing question status to ACTIVE
        $order_numb = "PayPal: ".$_POST['txn_id'];

        // Deposit add
        $grp_id=($_POST['mc_gross'] < 29.95)?1:2;
        $dep_model = new Deposits();
        $dep_model->Client_id = $question->client_id;
        $dep_model->Amount = $_POST['mc_gross'];
        $dep_model->Currency = $_POST['mc_currency'];
        $dep_model->Order_numb = $order_numb;
        $dep_model->Reader_id = $question->reader_id;
        $dep_model->Notes = 'Email Readings';
        $dep_model->addDeposit();
        $dep_model->affiliatePay($client->credit_cards->firstname,
                               $client->credit_cards->lastname,
                               $client->login,
                               $_POST['txn_id'],
                               $client->affiliate,
                               $grp_id,
                               $_POST['mc_gross']);

        //Changing questions status to$order_numb ACTIVE
        $question->status = 'active';
        $question->save();

        //Successfull email sending
        $subject = "PSYCHAT - Email Reading Order";

        //To admin
        $body = "First Name: ".$question->first_name."<br>
                 Last Name: ".$question->last_name."<br>
                 Email".$question->z_contact_email_address."<br>
                 Topic: ".$question->l_topic."<br>
                 Price: \$".$_POST['mc_gross']."<br>
                 Reader id: ".$reader->name."<br>";
        $emailaddress = Yii::app()->params['adminEmail'];
        PsyMailer::send($emailaddress, $subject, $body);

        //To reader
        $body = "First Name: ".$question->first_name."<br>
                ".$question->z_contact_email_address."<br>
                Topic: ".$question->l_topic."<br>
                Price: \$".$_POST['mc_gross']."<br>
                Reader id: ".$reader->name."<br>
                Please, enter your Control Panel to see the details.";
        $emailaddress = $reader->emailaddress;
        PsyMailer::send($emailaddress, $subject, $body);
    }
    /**
     * PayPal IPN action for chat add funds
     *
     */
    public function actionChatAddFundsIpn() {
        /**
         * Received data verification
         */
        
        $postdata="";
        foreach ($_POST as $key=>$value) $postdata.=$key."=".urlencode($value)."&";
        $postdata .= "cmd=_notify-validate";
        $file = fopen('/usr/home/psychi/domains/psychic-contact.com/public_html/chat/data/passwords.txt', 'w+');
        fwrite($file, print_r($postdata, true));
        fclose($file);
        $curl = curl_init(Yii::app()->params['paypal_url']."/cgi-bin/webscr");
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
        
        /// get session id & client id from custom field
		$tmp = explode(',', $_POST['custom']);
		$client_id = $tmp[0];
		$session_key = $tmp[1];
                
                $bmt_yes_no = 'no';
                if($session_key){
                Yii::app()->getModule('chat');
                $session = ChatContext::getContext($session_key, 2);
		
                $bmt = (strlen($session_key) > 0) ? $session->reader->name : 0;
                $bmt_yes_no = 'yes';
                }

        // Duplicate transaction checking
        $dep = new Deposits();
        $res = $dep->findAll(array(
                'condition'  => 'Order_numb LIKE :order_numb',
                'params'     => array(':order_numb' => '%'.$_POST['txn_id'].'%')
        ));
        if(!empty($res)) {
            die("Already registered");
        }
        
        $client = Clients::getClient($client_id, 'credit_cards');
        // Buisness email checking
        if ($_POST['receiver_email'] != Yii::app()->params['paypal_buisness_email']) {
            // Send failed email
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
        // Pay currency checking
        if($_POST['mc_currency'] != 'USD') {
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
        // Deposit add
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
            if($tariff['amount'] == $_POST['mc_gross'] && !isset($tariff['signupOnly'])) {
                $minutes = $tariff['minutes'];
                $freeMinutes = $tariff['freeTime'];
            }
        }
        
        if(!empty($session)) {
        	Yii::app()->getModule('chat');
        	$chatCommander = new ChatCommander($session);
        	$chatCommander->registerBMT($minutes);
        }
        // Add minutes to user's balance
        $client->balance += $minutes;
        $client->save();
        // Add free time if present
        if($freeMinutes>0) {
        	$freeTime = RemoveFreetime::getFreeTime($client_id);
        	$freeTime->addHalfPaidTime($freeMinutes*60);

        	$body="Client id : ".$client_id."\r\n";
        	$body.="Minutes : ".$minutes."\r\n";
        	$body.="Free minutes : ".$freeMinutes."\r\n";
            PsyMailer::send('karpovsoft@yandex.ru', "PSY DEBUG: ADV, REPEATER, PAYPAL, FREE TIME(2)", $body);        	
        }

        
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
                       Client (Id: ".$client_id.", Username: ".$client->login.")\n
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

    public function actionSignupCertIpn(){        
        $postdata="";
        foreach ($_POST as $key=>$value) $postdata.=$key."=".urlencode($value)."&";
        $postdata .= "cmd=_notify-validate";
        $curl = curl_init(Yii::app()->params['paypal_url']."/cgi-bin/webscr");
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
        
        $client = Clients::getClient($_POST['custom']);

        if ($_POST['receiver_email'] != Yii::app()->params['paypal_buisness_email']) {
            // Send failed email
            $subject = "Paypal IPN transaction FAILED";
            //To user
            $body = "Your Paypal System failed due to reason:<br>
                     Receiver email is not equal";
            $client->emailaddress = $client->emailaddress;
            PsyMailer::send($client->emailaddress, $subject, $body);
            //To admin
            $body = "Login: ".$client->login."<br>
                     Full name: ".$client->name."<br>
                     Email".$client->emailaddress."<br>
                     Price: \$".$_POST['mc_gross']."<br>
                     Paypal System failed due to reason:<br>
                     Receiver email is not equal";
            $client->emailaddress = Yii::app()->params['adminEmail'];
            PsyMailer::send($client->emailaddress, $subject, $body);
            die('Invalid email');
        }
        if ($_POST["txn_type"] != "web_accept") {
            // Send failed email
            $subject = "Paypal IPN transaction FAILED";
            //To user
            $body = "Your Paypal System failed due to reason:<br>
                     Txn type is not equal";
            $client->emailaddress = $client->emailaddress;
            PsyMailer::send($client->emailaddress, $subject, $body);
            //To admin
            $body = "Login: ".$client->login."<br>
                     Full name: ".$client->name."<br>
                     Email".$client->emailaddress."<br>
                     Price: \$".$_POST['mc_gross']."<br>
                     Paypal System failed due to reason:<br>
                     Txn type is not equal";
            $client->emailaddress = Yii::app()->params['adminEmail'];
            PsyMailer::send($client->emailaddress, $subject, $body);
            die('Invalid txn_type');
        }
        // Pay currency checking
        if($_POST['mc_currency'] != 'USD') {
            // Send failed email
            $subject = "Paypal IPN transaction FAILED";
            //To user
            $body = "Your Paypal System failed due to reason:<br>
                     Currency is not equal";
            $client->emailaddress = $client->emailaddress;
            PsyMailer::send($client->emailaddress, $subject, $body);
            //To admin
            $body = "Login: ".$client->login."<br>
                     Full name: ".$client->name."<br>
                     Email".$client->emailaddress."<br>
                     Price: \$".$_POST['mc_gross']."<br>
                     Paypal System failed due to reason:<br>
                     Currency is not equal";
            $client->emailaddress = Yii::app()->params['adminEmail'];
            PsyMailer::send($client->emailaddress, $subject, $body);
            die("Currency is not equal");
        }
        if($_POST['mc_gross'] != 19.95) {
            // Send failed email
            $subject = "Paypal IPN transaction FAILED";
            //To user
            $body = "Your Paypal System failed due to reason:<br>
                     Amount is not equal";
            $client->emailaddress = $client->emailaddress;
            PsyMailer::send($client->emailaddress, $subject, $body);
            //To admin
            $body = "Login: ".$client->login."<br>
                     Full name: ".$client->name."<br>
                     Email".$client->emailaddress."<br>
                     Price: \$".$_POST['mc_gross']."<br>
                     Paypal System failed due to reason:<br>
                     Amount is not equal";
            $client->emailaddress = Yii::app()->params['adminEmail'];
            PsyMailer::send($client->emailaddress, $subject, $body);
            die("Amount is not equal");
        }
        if($client->type == 'gift_chat_pending'){
            $client->type = 'gift_chat';
            $client->balance = 20;
            $client->free_mins = 5;
            $client->save();

            $freetime = new RemoveFreetime();
            $freetime->ClientID = $client->rr_record_id;
            $freetime->Seconds = 300;
            $freetime->Total_sec = 300;
            $freetime->insert();

        }
        if($client->type == 'gift_email_pending'){
            $client->type = 'gift_email';
            $client->save();
            $quest = EmailQuestions::getByClientId($client->rr_record_id);
            $quest->status = 'pending2';
            $quest->save();
        }
        //Successfull email
        $message = "Dear $client->name,<br>
	Welcome to ".Yii::app()->params['siteName']."!
<br><br>
	You may redeem your gift certificate at any time - up to 1 year from today's date: ".date("Y.m.D")."<br>
For a chat reading: Log on to your new account and then select any available reader and press \"chat\" (please follow all instructions for chatting on our site)<br>
For email readings- please fill out the request form that appears after you log on<br>
<br>
Your login: ".$client->login."  <br>
Password: ".$client->password."<br>
<br>
If you wish to change your mind about getting the type of reading you have previously selected (chat or email)- please contact customer support: javachat@psychic-contact.com<br>
<br>
<br>
Thank you for visiting ".Yii::app()->params['siteName']."<br>
We hope you enjoy your Reading- Happy Holidays!<br>";
        PsyMailer::send($client->emailaddress, 'CERT', $message);

    }    
    
    public function actionSignupIpn()
    {
        $postdata="";
        foreach ($_POST as $key=>$value) 
            $postdata.=$key."=".urlencode($value)."&";
        $postdata .= "cmd=_notify-validate";
        $curl = curl_init(Yii::app()->params['paypal_url']."/cgi-bin/webscr");
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
         
        $preregged = Prereg::getPrereggedUser($_POST['custom']);
        
        if ($_POST['receiver_email'] != Yii::app()->params['paypal_buisness_email']) {
            // Send failed email
            $subject = "Paypal IPN transaction FAILED";
            //To user
            $body = "Your Paypal System failed due to reason:<br>
                     Receiver email is not equal";
            $preregged->email = $preregged->email;
            PsyMailer::send($preregged->email, $subject, $body);
            //To admin
            $body = "Login: ".$preregged->login."<br>
                     Full name: ".$preregged->firstname." ".$preregged->lastname."<br>
                     Email".$preregged->email."<br>
                     Price: \$".$_POST['mc_gross']."<br>
                     Paypal System failed due to reason:<br>
                     Receiver email is not equal";
            PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $body);
            die('Invalid email');
        }
        
        if ($_POST["txn_type"] != "web_accept") {
            // Send failed email
            $subject = "Paypal IPN transaction FAILED";
            //To user
            $body = "Your Paypal System failed due to reason:<br>
                     Txn type is not equal";
            PsyMailer::send($preregged->email, $subject, $body);
            //To admin
            $body = "Login: ".$preregged->login."<br>
                     Full name: ".$preregged->firstname." ".$preregged->lastname."<br>
                     Email".$preregged->email."<br>
                     Price: \$".$_POST['mc_gross']."<br>
                     Paypal System failed due to reason:<br>
                     Txn type is not equal";
            PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $body);
            die('Invalid txn_type');
        }
        
        $order_numb = "PayPal: ".$_POST['txn_id'];
        
        $check_txn = Deposits::checkTxnExist($order_numb);
        if($check_txn)
            die('This TXN is already exists');        
        
        $site_type = '';
        if($preregged->country == 'Canada')
            $site_type = 'CA';
        
        $new_client = new Clients();
        $new_client->name = $preregged->firstname.' '.$preregged->lastname;
        $new_client->login = $preregged->login;
        $new_client->password = $preregged->password;
        $new_client->emailaddress = $preregged->email;
        $new_client->hear = $preregged->hear;
        $new_client->month = $preregged->month;
        $new_client->day = $preregged->day;
        $new_client->year = $preregged->year;
        $new_client->gender = $preregged->gender;
        $new_client->address = $preregged->address."\n".$preregged->city."\n".$preregged->state."\n".$preregged->zip."\n".$preregged->country;
        $new_client->balance = $preregged->balance;
        $new_client->affiliate = $preregged->affiliate;
        $new_client->site_type = $site_type;
        
        $new_client_id = $new_client->clientRegister();
        
        // Deposit add
        $dep_model = new Deposits();
        $dep_model->Client_id = $new_client_id;
        $dep_model->Amount = $_POST['mc_gross'];
        $dep_model->Currency = $_POST['mc_currency'];
        $dep_model->Order_numb = $order_numb;
        $dep_model->ps_type = 'paypal';
        $dep_model->Notes = 'psychat';
        $payment_loc_id = $dep_model->addDeposit();
		
        $dep_model->affiliatePay($preregged->firstname,
                                 $preregged->lastname,
                                 $preregged->login,
                                 $_POST['txn_id'],
                                 $preregged->affiliate,
                                 $grp_id,
                                 $_POST['mc_gross']);
        
        // Regiter to CC table info
        $cc = new CreditCard();
        $cc->rr_record_id = $new_client_id;
        $cc->amount = $_POST['mc_gross'];
        $cc->firstname = $preregged->firstname;
        $cc->lastname = $preregged->lastname;
        $cc->billingaddress = $preregged->address;
        $cc->billingcity = $preregged->city;
        $cc->billingstate = $preregged->state;
        $cc->billingzip = $preregged->zip;
        $cc->billingcountry = $preregged->country;
        $cc->save();
        
        // Adding info about user's limits to db
        $limits = new ClientLimit();
        $limits->UserID = $new_client_id;
        $limits->UserName = $preregged->login;
        $limits->FullName = $preregged->firstname.' '.$preregged->lastname;
        $limits->IP = $preregged->ip;
        $limits->registerNew();

        // Calculate additional minutes
        $tariffs = Tariff::loadChatOptions();
        foreach($tariffs as $tariff) {
            if($tariff['minutes'] == $new_client->balance && !isset($tariff['repeaterOnly'])) {
                $freeMinutes = $tariff['freeTime'];
            }
        }
        
        // Add free time if present
        if($freeMinutes>0) {
        	
        	$freeTime = RemoveFreetime::getFreeTime($new_client_id);
        	$freeTime->addFreeTime($freeMinutes*60);

        	$body="Client id : ".$new_client_id."\r\n";
        	$body.="Balance : ".$new_client->balance."\r\n";
        	$body.="Free minutes : ".$freeMinutes."\r\n";
            PsyMailer::send('karpovsoft@yandex.ru', "PSY DEBUG: ADV, SIGNUP, PAYPAL, FREE TIME(2)", $body);
       }
        
        // Adding into freetime remove table
/*        
        $freetime = new RemoveFreetime();
        $freetime->ClientID = $new_client_id;
        $freetime->Seconds = 600;
        $freetime->Total_sec = 600;
        $freetime->deposited = true;
        $freetime->save();
*/
        
        // Sending successfull registration email
        //if(!$site_type)
        $site_type = 'PSYCHAT';
        $subject = (empty($preregged->no_freebie)) ? $site_type." - New sign up(".$preregged->balance." mins FS)" : $site_type.' - New sign ('.$preregged->balance.' mins PAID)';
        $body = date("M j, Y h:i a", time())."\n<br>
                Username: ".$preregged->login."\n<br>
                DOB: ".$preregged->month."/".$preregged->day."/".$preregged->year."\n<br>
                First Name: ".$preregged->firstname."\n<br>
                Last Name: ".$preregged->lastname."\n<br>
                Gender: ".$preregged->gender."\n<br>
                Address: ".$preregged->address.", ".$preregged->city.", ".$preregged->state.", ".$preregged->country."\n<br>
                E-mail address: ".$preregged->email."\n<br>                
                IP: ".$preregged->ip."\n<br>
                ".$_SERVER['HTTP_USER_AGENT']."\n<br>
                Affiliate ID: ".$preregged->affiliate."\n<br>
                Referrer: ".$preregged->hear."\n<br>
                Referrer2: ".$preregged->website."<br>
                PayPal Order #: ".$order_numb."\n<br>
                Amount : ".$_POST['mc_gross'];
        // To PSYCON admin
        PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $body);
        // If client from RASP site - to RASP admin
        //if($this->admin_email)
        //    PsyMailer::send($this->admin_email, $subject, $body);

        // To client
        $asterisk_pass = '';
        if(strlen($preregged->password)<=5)
            $asterisk_pass = '***** (Please contact Admin for info)';
        else
            $asterisk_pass = substr($preregged->password, 0, -4).'****';
        $body = "Dear ".$preregged->firstname." ".$preregged->lastname.",\r\n<br/>".
                " \r\n<br/>".
                "*** SAVE THIS MESSAGE *** \r\n\r\nIt is my pleasure to welcome you to Psychic-contact \r\n<br/>".
                " \r\nBelow you will find your login information:\r\n<br/>".
                "\r\n<br/>".
                "Your UserID: ".$preregged->login." \r\n<br/>".
                "   Password: ".$asterisk_pass." \r\n<br/>".
                " \r\n<br/>".
                "Login now and chat with our psychic readers, see what your future holds! \r\n<br/>".
                " \r\n<br/>".
                "Make it a great day! \r\n<br/>\r\n<br/>
                     \r\n<br/>
                    Support Team \r\n<br/>
                    mailto:support@psychic-contact.com";
        //$body .= ($this->admin_email) ? $this->admin_email : 'support@psychic-contact.com';
        //$subject = 'Welcome to '.$this->site_name;
        $subject = 'Welcome to Psychic-contact';
        PsyMailer::send($preregged->email, $subject, $body);
        // IF DEBUG TEST REGISTRATION MODE
        // send this email to additional addresses
//        if($this->debug)
//        {
//            PsyMailer::send('mysticladydi@yahoo.com', $subject, $body);
//            PsyMailer::send('rosie6807@gmail.com', $subject, $body);
//            PsyMailer::send('karpovsoft@yandex.ru', $subject, $body);
//        }
//        die();
    }
}

?>
