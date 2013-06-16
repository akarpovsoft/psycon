<?php

class LoginBox extends CWidget
{
    public function run()
    {
        $this->render('loginBox', array('isGuest' => Yii::app()->user->isGuest));
    }
}
?>
