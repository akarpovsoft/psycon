<?php

class BlogUserIdentity extends CUserIdentity
{
    private $_id;

        public function authenticate()
	{
            $username = strtolower($this->username);
            $user = BlogUsers::model()->find('LOWER(`login`)=?',array($username));
            if($user===null)
                $this->errorCode=self::ERROR_USERNAME_INVALID;
            else if(!$user->validatePassword($this->password))
                $this->errorCode=self::ERROR_PASSWORD_INVALID;
            else {
                $this->_id = $user->id;
                $this->username = $user->login;
                    $this->setState("password", $this->password);
                $this->errorCode=self::ERROR_NONE;
            }
            return !$this->errorCode;
	}

	public function getId() {
	    return $this->_id;
	}
}


?>
