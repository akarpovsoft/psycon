<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $LoginName;
	public $LoginPassword;
        public $LayoutType;
        public $lang;
        public $site_type;
	public $rememberMe;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('LoginName, LoginPassword', 'required'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('LoginPassword', 'authenticate'),
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
			$identity=new PsyUserIdentity($this->LoginName,$this->LoginPassword);
			$identity->authenticate($this->LayoutType, $this->lang, $this->site_type);
			switch($identity->errorCode)
			{
				case PsyUserIdentity::ERROR_NONE:
					$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
					Yii::app()->user->login($identity,$duration);
					break;
				case PsyUserIdentity::ERROR_USERNAME_INVALID:
					$this->addError('LoginName','Username is incorrect.');
					break;
                                case PsyConstants::ACCOUNT_BANNED: // Banned by admin
                                        $this->addError('Account', PsyConstants::getName(PsyConstants::ACCOUNT_BANNED));
                                        break;
                                case PsyConstants::ACCOUNT_UNACTIVATED: // Unactivated
                                        $this->addError('Account', PsyConstants::getName(PsyConstants::ACCOUNT_UNACTIVATED));
                                        break;
				default: // UserIdentity::ERROR_PASSWORD_INVALID
					$this->addError('LoginPassword','Password is incorrect.');
					break;
			}
		}
	}
}
