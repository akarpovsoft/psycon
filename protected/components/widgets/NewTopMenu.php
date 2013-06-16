<?php 

class NewTopMenu extends CWidget
{
    public $type;
    public function init()
    {
        $this->type = (Yii::app()->user->isGuest) ? 'guest' : 'logged';
    }
    
    public function run()
    {
        if($this->type == 'guest'){
            $links = array(
            'Home' => Yii::app()->params['http_addr'],
            'Register' => Yii::app()->params['ssl_addr'].'site/signup',
            'Our Psychics' => Yii::app()->params['http_addr'].'site/ourreaders',
            'Phone Readings' => Yii::app()->params['http_addr'].'site/phonereaders',
            'Email Readings' => Yii::app()->params['ssl_addr'].'pay/emailreading',   
            'Articles' => Yii::app()->params['http_addr'].'articles',
            'Psychic Blog' => Yii::app()->params['site_domain'].'/blog',
            'Prices' => Yii::app()->params['http_addr'].'site/ourprices',  
            'LogIn' => Yii::app()->params['http_addr'].'site/login', 
            );
        }
        else
        {
            $links = array(
            'Home' => Yii::app()->params['http_addr'],
            'Account' => Yii::app()->params['http_addr'].'users/mainmenu',    
            'Our Psychics' => Yii::app()->params['http_addr'].'site/ourreaders',         
            'Phone Readings' => Yii::app()->params['http_addr'].'site/phonereaders',
            'Email Readings' => Yii::app()->params['ssl_addr'].'pay/emailreading',            
            'Articles' => Yii::app()->params['http_addr'].'articles',            
            'Psychic Blog' => Yii::app()->params['http_addr'].'blog',
            'Prices' => Yii::app()->params['http_addr'].'site/ourprices', 
            'LogOut' => Yii::app()->params['http_addr'].'site/logout', 
            );
        }
        
        $this->render('newTopMenu', array('links' => $links));
    }
}

?>
