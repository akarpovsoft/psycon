<?php

class PsyNetRestrictor extends CFilter
{
    protected function preFilter($filterChain) {
        return true;
    }

    protected function postFilter($filterChain) {     
        $user_id = Yii::app()->session['login_operator_id'];
        $client = Clients::getClient($user_id);
        
        $restricts = Yii::app()->params['netrestrict'];
        
        foreach($restricts as $key => $val)
        {
            if($client->site_type == $key)
                CController::redirect($val);
        }        
    }
}

?>
