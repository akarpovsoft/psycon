<?php

class LoginForm extends CFormModel
{
	public $login;
	public $password;
        public $key;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('login, password', 'required'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>'Remember me next time',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
            if(!$this->hasErrors())  // we only want to authenticate when no input errors
            {
                $identity=new BlogUserIdentity($this->login,$this->password);
                $identity->authenticate();
                switch($identity->errorCode){
                    case UserIdentity::ERROR_NONE:
                        $session = new BlogSessions();
                        $session->session_key = $this->key;
                        $session->writeSession($identity);
                        break;
                    case UserIdentity::ERROR_USERNAME_INVALID:
                        $this->addError('LoginName','Username is incorrect.');
                        break;
                    default: // UserIdentity::ERROR_PASSWORD_INVALID
                        $this->addError('LoginPassword','Password is incorrect.');
                        break;
                }
            }
	}
}

?>
