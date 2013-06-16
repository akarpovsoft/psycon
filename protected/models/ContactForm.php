<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class ContactForm extends CFormModel
{
	public $name;
        public $login;
	public $email;
	public $subject;
	public $body;
	public $verifycode;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		$rules = array(
			// name, email, subject and body are required
			array('name, email, subject, body', 'required'),
                        array('name, login, email, subject, body', 'filtration'),
			// email has to be a valid email address
			array('email', 'email'),
			// verifyCode needs to be entered correctly
                        array('name', 'length', 'max'=>80),
                        array('email', 'length', 'max'=>100),
                        array('login', 'length', 'max'=>50),
                        array('subject', 'length', 'max'=>150),
                        array('body', 'length', 'max'=>1500),
                        );
                if(!is_null($this->verifycode)) {
                        array_push($rules, array('verifycode', 'required'));
                        array_push($rules, array('verifycode', 'captcha', 'allowEmpty'=>!extension_loaded('gd')));
                }
                return $rules;
	}       
            

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'verifycode'=>'Verification Code',
		);
	}
        /**
         * Filter to replace some symbols
         */
        public function filtration(){
            $this->email    = preg_replace("/\s+|\t+|\n+|\r+|;|:|,/", "", $this->email);
            $this->name     = preg_replace("/\s+|\t+|\n+|\r+|;|:/", "", $this->name);
            $this->login = preg_replace("/\s+|\t+|\n+|\r+|;|:/", "", $this->login);
            $this->subject  = preg_replace("/\s+|\t+|\n+|\r+|;/", " ", $this->subject);
            $this->body  = preg_replace("/\s+|\t+|\n+|\r+|;/", " ", $this->body);

            $this->name     = preg_replace("/@/", "(at)", $this->name);
            $this->login = preg_replace("/@/", "(at)", $this->login);
            $this->subject  = preg_replace("/@/", "(at)", $this->subject);
            $this->body  = preg_replace("/@/", "(at)", $this->body);

            $this->email    = preg_replace("/\"/", "'", $this->email);
            $this->name     = preg_replace("/\"/", "'", $this->name);
            $this->login = preg_replace("/\"/", "'", $this->login);
            $this->subject  = preg_replace("/\"/", "'", $this->subject);
            $this->body  = preg_replace("/\"/", "'", $this->body);
        }
}