<?php

class TestController extends Controller
{
    
    public function actions()
    {
        return array( // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array('class' => 'CCaptchaAction', 'backColor' => 0xFFFFFF, ),
        );
    }
    
    
    public function actionIndex()
    {
    	print_r($_POST['Messages']);
    }
}
