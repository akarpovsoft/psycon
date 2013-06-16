<?php
/**
 * class SignupCert
 *
 * Validates and create an account through paypal certificate program.
 * Using to validate and save new client with status "gift"
 *
 * @author  Den Kazka <den.smart[at]gmail.com>
 * @since   2010
 * @version $Id
 */
class SignupCert extends CFormModel {
    // User's basic info
    public $firstname;
    public $lastname;
    public $login;
    public $password;
    public $email;
    public $reader;
    public $subject;
    public $signup_type;

    public function rules() {
        return array(
                // payment_method are required
                array('firstname, lastname, login, password, email', 'required'),
                array('login', 'loginCheck'),
                array('email', 'emailCheck'),
                array('signup_type', 'typeValidate'),
        );
    }

    /**
     * Checks login existing
     */
    public function loginCheck() {
        if(!empty($this->login)) {
            $log = users::getUserByLogin($this->login);
            if(!empty($log))
                $this->addError('login', 'Sorry, but this login is taken by another user');
        }
    }
    /**
     * Checking email existing and confirmation
     */
    public function emailCheck() {
        if(!empty($this->email)) {
            $email = users::getUserByEmail($this->email);
            if(!empty($email))
                $this->addError('email', 'The same email address is registered to another user');
        }
    }
    
    public function typeValidate(){
        if(($this->signup_type == 'email') && ($this->reader == ""))
            $this->addError('signup_type', 'You must choose your reader');
        if(($this->signup_type == 'email') && ($this->subject == ""))
            $this->addError('signup_type', 'You must choose your subject');
        if(($this->signup_type == 'email') && ($this->reader == "") && ($this->subject == ""))
            $this->addError('signup_type', 'You must choose your reader and subject');
    }

    public function createGiftClient(){
        $gift_client = new Clients();
        $gift_client->name = $this->firstname.' '.$this->lastname;
        $gift_client->password = $this->password;
        $gift_client->login = $this->login;
        $gift_client->emailaddress = $this->email;
        $gift_client->type = 'gift_'.$this->signup_type.'_pending';
        $gift_client->insert();
        $gift_client_id = mysql_insert_id();
        
        if($this->signup_type == 'email'){
            $gift_email = new EmailQuestions();
            $gift_email->client_id = $gift_client_id;
            $gift_email->first_name = $this->firstname;
            $gift_email->last_name = $this->lastname;
            $gift_email->z_contact_email_address = $this->email;
            $gift_email->b_reading_type = 19.95;
            $gift_email->reader_id = $this->reader;
            $gift_email->m_special_instructions = 'Gift client.';
            $gift_email->l_topic = $this->subject;
            $gift_email->status = 'pending';
            $gift_email->spec = 19.95;
            $gift_email->payment_method = 2;
            $gift_email->insert();
        }
        return $gift_client_id;
    }
}


?>
