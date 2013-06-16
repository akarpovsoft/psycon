<?php

/**
 * PsyUserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class PsyUserIdentity extends CUserIdentity {
    private $_id;
    private $type;

    public function authenticate($layout = null, $lang = null, $site_type = null) {
        $username = strtolower($this->username);
        $user = users::model()->find('LOWER(`login`)=?',array($username));
        // New design for all clients
        if(($user->type == 'client')&&($layout != 3))
        {
            if(($user->design_theme == 1))
                $layout = 2;
            else
                $layout = 1;
        }     
        if(($user->type == 'reader')&&($layout != 3))
                $layout = 2;
        // Checking user status
        // No access for banned, banned_by_reader or unactivated users
        $band_list = ClientLimit::getInfoByName($username);
        // if user is banned by admin or reader
        if(preg_match("/ban+/", $band_list->Client_status))
            $this->errorCode=PsyConstants::ACCOUNT_BANNED;
        // if user have an unactivated status
        if($band_list->Client_status == 'unactivated')
            $this->errorCode=PsyConstants::ACCOUNT_UNACTIVATED;
        // if not registered
        else if($user===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else if(!$user->validatePassword($this->password))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else {
            $this->_id = $user->rr_record_id;
            $this->username = $user->login;
            $this->setState('type', $user->type);
            $this->setState("LoginPassword", $this->password);
            $this->setState("login_account_id", 1);
            if(!is_null($layout))
                $this->setState("layout_type", $layout);
            if(!is_null($lang))
                $this->setState("lang", $lang);
            if(!is_null($site_type))
                $this->setState("site_type", $site_type);
            else
                setcookie('Reader_id', $user->rr_record_id, 0, '/');
            $this->errorCode=self::ERROR_NONE;
        }

        return !$this->errorCode;
    }

    public function getId() {
        return $this->_id;
    }
}