<?php

class DevAccess extends CFilter 
{
    public $guest;
    public $reader;
    public $admin;
    public $allowedUser;
    
    protected function preFilter($filterChain) 
    {
//        $allowedUsers = Yii::app()->params['dev_allowed_users'];
//
//        $this->guest = Yii::app()->user->isGuest;
//        if(!$this->guest)
//        {            
//            $this->reader = (Yii::app()->user->type == 'reader') ? true : false;
//            $this->admin = (Yii::app()->user->type == 'Administrator') ? true : false;
//            $this->allowedUser = in_array(Yii::app()->user->id, $allowedUsers);
//        }
        return true;
    }

    protected function postFilter($filterChain) 
    {  
//        if($this->guest || (!$this->reader && !$this->admin && !$this->allowedUser))
//        {
//            $filterChain->run();
//            CController::redirect(Yii::app()->params['site_domain'].'/advanced');
//        }
    }
    
}
?>
